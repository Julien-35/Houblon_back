<?php

namespace App\Controller;

use App\Entity\Origine;
use DateTimeImmutable ;
use App\Repository\OrigineRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\{JsonResponse, Request, Response};
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use OpenApi\Attributes as OA;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Attributes\RequestBody;
use OpenApi\Attributes\Property;
use OpenApi\Attributes\JsonContent;
use OpenApi\Attributes\MediaType;
use OpenApi\Attributes\Schema;


#[Route('/api/origine', name:'app_api_houblon_origine_')]
class OrigineController extends AbstractController
{
    private EntityManagerInterface $manager;
    private OrigineRepository $repository;
    private SerializerInterface $serializer;
    private UrlGeneratorInterface $urlGenerator;

    public function __construct(
        EntityManagerInterface $manager,
        OrigineRepository $repository,
        SerializerInterface $serializer,
        UrlGeneratorInterface $urlGenerator
    ) {
        $this->manager = $manager;
        $this->repository = $repository;
        $this->serializer = $serializer;
        $this->urlGenerator = $urlGenerator;
    }

    #[Route('', name:'create', methods:['POST'])]
    public function new(Request $request): JsonResponse
    {
        $origine = $this->serializer->deserialize($request->getContent(), Origine::class, 'json');
        $this->manager->persist($origine);
        $this->manager->flush();
        
        $responseData = $this->serializer->serialize($origine, 'json');
        
        $location = $this->urlGenerator->generate(
            'app_api_houblon_origine_show',
            ['id' => $origine->getId()],
            UrlGeneratorInterface::ABSOLUTE_URL
        );

        return new JsonResponse($responseData, Response::HTTP_CREATED, ["Location" => $location], true);
    }

    #[Route('/get', name: 'app_api_houblon_origine_show', methods: ['GET'])]
    public function show(): JsonResponse
    {
        $origines = $this->repository->findAll();

        $debugData = $this->serializer->normalize($origines, null, ['groups' => ['origine:read']]);
        file_put_contents('debug_data.json', json_encode($debugData, JSON_PRETTY_PRINT));

        if (empty($origines)) {
            return new JsonResponse(['message' => 'No data found'], Response::HTTP_NOT_FOUND);
        }
        $responseData = $this->serializer->serialize($origines, 'json', ['groups' => ['origine:read']]);

        return new JsonResponse($responseData, Response::HTTP_OK, [], true);
    }

    
    #[Route('/{id}', name:'edit', methods:['PUT'])]
    public function edit(int $id, Request $request): JsonResponse
    {
        $origine = $this->repository->findOneBy(['id' => $id]);
        if ($origine) {
            $origine = $this->serializer->deserialize(
                $request->getContent(),
                Origine::class,
                'json',
                [AbstractNormalizer::OBJECT_TO_POPULATE => $origine]
            );
            $this->manager->flush();

            return new JsonResponse(null, Response::HTTP_NO_CONTENT);
        }
        return new JsonResponse(null, Response::HTTP_NOT_FOUND);
    }

    #[Route('/{id}', name:'delete', methods:['DELETE'])]
    public function delete(int $id): JsonResponse
    {
        $origine = $this->repository->findOneBy(['id' => $id]);
        if ($origine) {
            $this->manager->remove($origine);
            $this->manager->flush();

            return new JsonResponse(null, Response::HTTP_NO_CONTENT);
        }
        return new JsonResponse(null, Response::HTTP_NOT_FOUND);
    }
}