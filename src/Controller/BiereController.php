<?php

namespace App\Controller;

use App\Entity\Biere;
use DateTimeImmutable ;
use App\Repository\BiereRepository;
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


#[Route('/api/biere', name:'app_api_houblon_biere_')]
class BiereController extends AbstractController
{
    private EntityManagerInterface $manager;
    private BiereRepository $repository;
    private SerializerInterface $serializer;
    private UrlGeneratorInterface $urlGenerator;

    public function __construct(
        EntityManagerInterface $manager,
        BiereRepository $repository,
        SerializerInterface $serializer,
        UrlGeneratorInterface $urlGenerator
    ) {
        $this->manager = $manager;
        $this->repository = $repository;
        $this->serializer = $serializer;
        $this->urlGenerator = $urlGenerator;
    }

    #[Route('', name:'create', methods:['POST'])]
    public function createBiere(Request $request, EntityManagerInterface $entityManager): Response
    {
        // Créez une nouvelle instance de l'entité Biere
        $biere = new Biere();
        
        // Récupérez les données envoyées dans la requête POST
        $data = json_decode($request->getContent(), true);
        
        // Vérifiez et définissez les propriétés de l'biere
        if (isset($data['nom'])) {
            $biere->setNom($data['nom']);
        } else {
            return new Response('Nom is required', Response::HTTP_BAD_REQUEST);
        }
        
        if (isset($data['description'])) {
            $biere->setDescription($data['description']);
        }

        if (isset($data['taux_alcool'])) { 
            $biere->setTauxAlcool($data['taux_alcool']);
        }
        
        if (isset($data['image_data'])) {
            $biere->setImageData($data['image_data']);
        }

        
        $entityManager->persist($biere);
        $entityManager->flush();
        
        return new Response('biere créée correctement', Response::HTTP_CREATED);
    }

    #[Route('/get', name:'show', methods:['GET'])]
    public function show(): JsonResponse
    {
        $bieres = $this->repository->findAll();
    
        $bieresArray = [];
        foreach ($bieres as $biere) {
            $bieresArray[] = [
                'id' => $biere->getId(),
                'nom' => $biere->getNom(),
                'description' => $biere->getDescription(),
                'taux_alcool' => $biere->getTauxAlcool(),
                'image_data' => $biere->getImageData(),
                'origine' => $biere->getOrigine() ? $biere->getOrigine()->getLabel() : null,
                'stock' => $biere->getStock() ? $biere->getStock()->getQuantite() : null,
                'categorie' => $biere->getCategorie() ? $biere->getCategorie()->getNom() : null
            ];
        }
    
        return new JsonResponse($bieresArray, Response::HTTP_OK);
    }

    #[Route('/{id}', name:'edit', methods:['PUT'])]
    public function updateBiere(Request $request, EntityManagerInterface $entityManager, $id): Response
    {
        $biere = $entityManager->getRepository(Biere::class)->find($id);
    
        if (!$biere) {
            throw $this->createNotFoundException('Biere not found');
        }
    
        $data = json_decode($request->getContent(), true);
    
        if (isset($data['nom'])) {
            $biere->setNom($data['nom']);
        } else {
            return new Response('Nom is required', Response::HTTP_BAD_REQUEST);
        }
        
        if (isset($data['description'])) {
            $biere->setDescription($data['description']);
        }

        if (isset($data['taux_alcool'])) { 
            $biere->setTauxAlcool($data['taux_alcool']);
        }
        
        if (isset($data['image_data'])) {
            $biere->setImageData($data['image_data']);
        }
    
        $entityManager->persist($biere);
        $entityManager->flush();
    
        return new Response('Biere updated successfully');
    }
    

    #[Route('/{id}', name:'delete', methods:['DELETE'])]
    public function delete(int $id): JsonResponse
    {
        $biere = $this->repository->findOneBy(['id' => $id]);
        if ($biere) {
            $this->manager->remove($biere);
            $this->manager->flush();

            return new JsonResponse(null, Response::HTTP_NO_CONTENT);
        }
        return new JsonResponse(null, Response::HTTP_NOT_FOUND);
    }
}