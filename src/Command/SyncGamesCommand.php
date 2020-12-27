<?php

namespace App\Command;

use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\ProgressBar;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Game;
use App\Entity\GameImage;
use App\Repository\GameRepository;
use App\Repository\GameImageRepository;

use function Symfony\Component\String\u;

class SyncGamesCommand extends Command
{
    protected static $defaultName = 'app:sync-games';
    private $apiKey;
    private $client;
    private $entityManager;
    private $validator;
    private $parameterBag;
    private $baseUrl;

    public function __construct(
        String $apiKey,
        HttpClientInterface $client,
        EntityManagerInterface $entityManager,
        ValidatorInterface $validator,
        ParameterBagInterface $parameterBag,
        Filesystem $filesystem
    ) {
        $this->apiKey = $apiKey;
        $this->client = $client;
        $this->entityManager = $entityManager;
        $this->validator = $validator;
        $this->parameterBag = $parameterBag;
        $this->filesystem = $filesystem;
        $this->gameRepository = $entityManager->getRepository(Game::class);
        $this->gameImageRepository = $entityManager->getRepository(GameImage::class);
        $this->baseUrl = 'https://www.giantbomb.com/api/games/';

        parent::__construct();
    }

    protected function configure(): void
    {
        $this->setDescription('Sync games from GiantBomb API')
             ->addArgument('offset', InputArgument::OPTIONAL);
    }

    /**
     * @TODO Refactor into smaller bits
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $io->info('We will now query the awesome GiantBomb API for games ..');
        $progressBar = $this->setupProgressBar($io);

        $offset = (int) $input->getArgument('offset') ?? 0;
        $progressBar->advance($offset);
        while ($games = $this->getGames($offset)) {
            $progressBar->setMaxSteps($games['number_of_total_results']);
            foreach ($games['results'] as $entry) {
                $progressBar->setMessage($entry['name']);
                $progressBar->advance();

                $game = $this->gameRepository->findOneBy(['giantbomb_guid' => $entry['guid']]);
                if ($game === null) {
                    $game = new Game();
                }

                $game->setName(trim($entry['name']))
                     ->setAliases($entry['aliases'])
                     ->setDescription(trim($entry['deck']))
                     ->setGiantbombGuid($entry['guid'])
                     ->setOriginalReleaseDate($entry['original_release_date'] !== null ? new \DateTime($entry['original_release_date']) : null);

                $errors = $this->validator->validate($game);
                if ($errors->count() == 0) {
                    $this->entityManager->persist($game);
                    $this->processImages($game, $entry);
                } else {
                    foreach ($errors as $error) {
                        if ($error->getPropertyPath() == 'giantbomb_guid'
                            && $error->getConstraint() instanceof UniqueEntity
                        ) {
                            // Do not warn about duplicate keys
                            continue;
                        }

                        $io->warning((string) $error);
                    }
                }
            }

            $this->entityManager->flush();
            $offset += 100;
        }

        $progressBar->finish();

        return Command::SUCCESS;
    }

    private function setupProgressBar(SymfonyStyle $io): ProgressBar
    {
        ProgressBar::setFormatDefinition('with_message', ' %current%/%max% [%bar%] %percent:3s%% -- %message%');
        $progressBar = new ProgressBar($io);
        $progressBar->setFormat('with_message');
        $progressBar->setMessage('Starting ..');
        $progressBar->start();

        return $progressBar;
    }

    private function getGames(int $offset): ?array
    {
        return $this
            ->client
            ->request('GET', $this->formatUrl(['offset' => $offset]))
            ->toArray();
    }

    private function formatUrl(array $arguments = []): string
    {
        $arguments += [
            'format'     => 'json',
            'api_key'    => $this->apiKey,
            'field_list' => 'guid,name,aliases,deck,image,original_release_date',
            'filter'     => 'platforms:94', // 94 is PC, 17 is MAC
            'sort'       => 'name:asc',
            'limit'      => 100
        ];

        return $this->baseUrl . '?' . http_build_query($arguments);
    }

    /**
     * Download and store images
     */
    private function processImages(Game $game, array $entry): void
    {
        $path = $this->parameterBag->get('kernel.project_dir')
              . '/public/images/game-image/'
              . $game->getGiantbombGuid();
        $this->filesystem->mkdir($path);

        $responses = [];
        foreach ($entry['image'] as $type => $url) {
            if (!u($type)->endswith('_url')) {
                continue;
            }

            // Check if this image is already imported
            $image = $game->getGameImages()->filter(function($child) use ($type) {
                return $child->getType() === $type;
            });

            if ($image->isEmpty()) {
                $image = new GameImage();
                $image->setType($type)
                      ->setUrl($url)
                      ->setIsDownloaded(false);
                $game->addGameImage($image);
            } else {
                $image = $image->first();
            }

            $this->entityManager->persist($image);
            $this->entityManager->flush();

            $urlParts = parse_url($url);
            $remoteFilename = u(basename($urlParts['path']));
            $localFilename = $image->getId() . '.' . $remoteFilename->afterLast('.');
            $image->setFilename($localFilename);

            if (file_exists($path . '/' . $localFilename)) {
                continue;
            }

            $responses[] = $this->client->request('GET', $url, ['user_data' => $image]);
        }

        foreach ($this->client->stream($responses) as $response => $chunk) {
            $image = $response->getInfo('user_data');
            try {
                if ($chunk->isFirst()) {
                    if ($response->getStatusCode() != 200) {
                        $response->cancel();
                    }
                } elseif ($chunk->isLast()) {
                    file_put_contents($path . '/' . $localFilename, $response->getContent());
                    $image->setIsDownloaded(true);
                }
            } catch (TransportExceptionInterface $e) {
                $image
                    ->setIsDownloaded(false)
                    ->setFilename(null);
            }
        }
    }
}
