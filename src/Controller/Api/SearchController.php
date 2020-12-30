<?php

namespace App\Controller\Api;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\GameRepository;

use Doctrine\Common\Annotations\AnnotationReader;
use Symfony\Component\Serializer\Mapping\Factory\ClassMetadataFactory;
use Symfony\Component\Serializer\Mapping\Loader\AnnotationLoader;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Encoder\XmlEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;


class SearchController extends AbstractController
{
    /**
     * @Route("/api/v1/search", methods={"POST"}, name="search")
     * @param $request
     * @param GameRepository
     * @return JsonResponse
     */
    public function search(Request $request, GameRepository $gameRepository): JsonResponse
    {
        $games = $gameRepository->search($request->get('query'));

        $classMetadataFactory = new ClassMetadataFactory(new AnnotationLoader(new AnnotationReader()));
        $encoders = [new JsonEncoder()];
        $normalizers = [new ObjectNormalizer($classMetadataFactory)];
        //$normalizer->setCircularReferenceHandler(function ($object) {
        //    return $object->getId();
        //});
        $serializer = new Serializer($normalizers, $encoders);

        return new JsonResponse([
            'games' => $serializer->normalize($games, 'json', ['groups' => ['api']])
        ]);
    }
}
