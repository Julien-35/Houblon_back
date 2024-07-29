<?php

namespace App\Controller;

use App\Entity\Categorie;
use DateTimeImmutable ;
use App\Repository\CategorieRepository;
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


#[Route('/api/categorie', name:'app_api_houblon_categorie_')]
class CategorieController extends AbstractController
{
    private EntityManagerInterface $manager;
    private CategorieRepository $repository;
    private SerializerInterface $serializer;
    private UrlGeneratorInterface $urlGenerator;

    public function __construct(
        EntityManagerInterface $manager,
        CategorieRepository $repository,
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
        $categorie = $this->serializer->deserialize($request->getContent(), Categorie::class, 'json');
        $this->manager->persist($categorie);
        $this->manager->flush();
        
        $responseData = $this->serializer->serialize($categorie, 'json');
        
        $location = $this->urlGenerator->generate(
            'app_api_houblon_categorie_show',
            ['id' => $categorie->getId()],
            UrlGeneratorInterface::ABSOLUTE_URL
        );

        return new JsonResponse($responseData, Response::HTTP_CREATED, ["Location" => $location], true);
    }

    #[Route('/get', name:'show', methods:['GET'])]
    public function show(): JsonResponse
    {
        $categories = $this->repository->findAll();
        $responseData = $this->serializer->serialize($categories, 'json');

        return new JsonResponse($responseData, Response::HTTP_OK, [], true);
    }

    #[Route('/{id}', name:'edit', methods:['PUT'])]
    public function edit(int $id, Request $request): JsonResponse
    {
        $categorie = $this->repository->findOneBy(['id' => $id]);
        if ($categorie) {
            $categorie = $this->serializer->deserialize(
                $request->getContent(),
                Categorie::class,
                'json',
                [AbstractNormalizer::OBJECT_TO_POPULATE => $categorie]
            );
            $this->manager->flush();

            return new JsonResponse(null, Response::HTTP_NO_CONTENT);
        }
        return new JsonResponse(null, Response::HTTP_NOT_FOUND);
    }

    #[Route('/{id}', name:'delete', methods:['DELETE'])]
    public function delete(int $id): JsonResponse
    {
        $categorie = $this->repository->findOneBy(['id' => $id]);
        if ($categorie) {
            $this->manager->remove($categorie);
            $this->manager->flush();

            return new JsonResponse(null, Response::HTTP_NO_CONTENT);
        }
        return new JsonResponse(null, Response::HTTP_NOT_FOUND);
    }
}