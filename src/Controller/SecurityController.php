<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\RoleRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\{JsonResponse, Request, Response};
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\CurrentUser;

#[Route('/api', name: 'app_api_')]
class SecurityController extends AbstractController
{
    public function __construct(
        private EntityManagerInterface $manager,
        private UserPasswordHasherInterface $passwordHasher,
        private RoleRepository $roleRepository
    ) {}

    #[Route('/registration', name: 'registration', methods: 'POST')]
    public function register(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        dump($data); // Debugging

        // Validation des données reçues
        if (!isset($data['email']) || !isset($data['password']) || !isset($data['role'])) {
            return new JsonResponse(['error' => 'Email, password, and role are required'], Response::HTTP_BAD_REQUEST);
        }

        // Vérifier si l'utilisateur existe déjà
        $existingUser = $this->manager->getRepository(User::class)->findOneBy(['email' => $data['email']]);
        if ($existingUser) {
            return new JsonResponse(['error' => 'User already exists'], Response::HTTP_CONFLICT);
        }

        // Vérifier le rôle en utilisant l'ID directement
        $roleId = $data['role']; // Assurez-vous que c'est un entier ici

        $role = $this->roleRepository->find($roleId);
        if (!$role) {
            return new JsonResponse(['error' => 'Invalid role'], Response::HTTP_BAD_REQUEST);
        }

        // Créer et enregistrer le nouvel utilisateur
        $user = new User();
        $user->setEmail($data['email']);
        $user->setPassword($this->passwordHasher->hashPassword($user, $data['password']));
        $user->setRole($role);
        $user->setApiToken(bin2hex(random_bytes(20)));

        $this->manager->persist($user);
        $this->manager->flush();

        dump($user); // Debugging

        return new JsonResponse([
            'email' => $user->getEmail(),
            'apiToken' => $user->getApiToken(),
            'roles' => $user->getRoles()
        ], Response::HTTP_CREATED);
    }

    #[Route('/login', name: 'login', methods: 'POST')]
    public function login(#[CurrentUser] ?User $user): JsonResponse
    {
        if (null === $user) {
            return new JsonResponse(['message' => 'Missing credentials'], Response::HTTP_UNAUTHORIZED);
        }

        return new JsonResponse([
            'user' => $user->getUserIdentifier(),
            'apiToken' => $user->getApiToken(),
            'roles' => $user->getRoles(),
        ]);
    }

    #[Route('/admin', name: 'show', methods: 'GET')]
    public function show(#[CurrentUser] ?User $user): JsonResponse
    {
        if ($user === null) {
            return new JsonResponse(['message' => 'User not found'], Response::HTTP_NOT_FOUND);
        }

        return new JsonResponse([
            'email' => $user->getEmail(),
            'roles' => $user->getRoles()
        ]);
    }
}
