<?php

namespace App\Controller;

use App\Entity\Evenement;
use DateTimeImmutable ;
use App\Repository\EvenementRepository;
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


#[Route('/api/evenement', name:'app_api_houblon_evenement_')]
class EvenementController extends AbstractController
{
    private EntityManagerInterface $manager;
    private EvenementRepository $repository;
    private SerializerInterface $serializer;
    private UrlGeneratorInterface $urlGenerator;

    public function __construct(
        EntityManagerInterface $manager,
        EvenementRepository $repository,
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
        try {
            $data = json_decode($request->getContent(), true);
    
            // Validation des champs obligatoires
            $nom = $data['nom'] ?? null;
            $description = $data['description'] ?? null;
            $imageDataBase64 = $data['image_data'] ?? null;
    
            if (!$nom || !$description) {
                return new JsonResponse(['error' => 'Nom et description sont obligatoires'], Response::HTTP_BAD_REQUEST);
            }
    
            // Vérifiez si la chaîne base64 est correctement formée
            if ($imageDataBase64 && !preg_match('/^[a-zA-Z0-9+\/=]+\s*$/', $imageDataBase64)) {
                return new JsonResponse(['error' => 'Données d\'image non valides'], Response::HTTP_BAD_REQUEST);
            }
    
            // Création de l'événement
            $evenement = new Evenement();
            $evenement->setNom($nom);
            $evenement->setDescription($description);
            $evenement->setImageData($imageDataBase64);
    
            // Enregistrement en base de données
            $this->manager->persist($evenement);
            $this->manager->flush();
    
            return new JsonResponse(['message' => 'Événement créé avec succès'], Response::HTTP_CREATED);
        } catch (\Exception $e) {
            return new JsonResponse(['error' => $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }



    #[Route('/get', name: 'show', methods: ['GET'])]
    public function show(EntityManagerInterface $entityManager, SerializerInterface $serializer): JsonResponse 
    {
        $queryBuilder = $entityManager->createQueryBuilder();
        $queryBuilder->select('e')
            ->from(Evenement::class, 'e');
    
        $query = $queryBuilder->getQuery();
        $evenements = $query->getArrayResult();
    
        $responseData = $serializer->serialize($evenements, 'json');
    
        return new JsonResponse($responseData, Response::HTTP_OK, [], true);
    }

    #[Route('/{id}', name: 'edit', methods: ['PUT'])]
    public function edit(int $id, Request $request): JsonResponse
    {
        $evenement = $this->repository->find($id);
        if (!$evenement) {
            return new JsonResponse(['error' => 'Événement non trouvé'], Response::HTTP_NOT_FOUND);
        }
    
        $data = json_decode($request->getContent(), true);
    
        if (isset($data['nom'])) {
            $evenement->setNom($data['nom']);
        }
        if (isset($data['description'])) {
            $evenement->setDescription($data['description']);
        }
        if (isset($data['image_data'])) {
            $evenement->setImageData($data['image_data']);
        }
    
        try {
            $this->manager->flush();
            return new JsonResponse(['message' => 'Événement modifié avec succès'], Response::HTTP_OK);
        } catch (\Exception $e) {
            error_log($e->getMessage());
            return new JsonResponse(['error' => 'Erreur interne du serveur'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    #[Route('/{id}', name:'delete', methods:['DELETE'])]
    public function delete(int $id): JsonResponse
    {
        $evenement = $this->repository->findOneBy(['id' => $id]);
        if ($evenement) {
            $this->manager->remove($evenement);
            $this->manager->flush();

            return new JsonResponse(null, Response::HTTP_NO_CONTENT);
        }
        return new JsonResponse(null, Response::HTTP_NOT_FOUND);
    }
}