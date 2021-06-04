<?php

namespace App\Controller\Api;

use App\Repository\GameRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

class SearchController extends AbstractController
{
    /**
     * @Route("/api/v1/search", methods={"POST"}, name="search")
     *
     * @param Request $request
     * @param GameRepository $gameRepository
     *
     * @return JsonResponse
     */
    public function search(Request $request, GameRepository $gameRepository, SerializerInterface $serializer): JsonResponse
    {
        return new JsonResponse(
            [
                'games' => $serializer->normalize(
                    $gameRepository->search($request->get('query')),
                    'json',
                    ['groups' => ['api']]
                )
            ]
        );
    }
}
