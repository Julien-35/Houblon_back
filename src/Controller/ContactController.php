<?php

// src/Controller/ContactController.php
namespace App\Controller;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use App\Repository\ContactRepository;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

#[Route('/api/contact', name: 'app_api_contact')]
class ContactController extends AbstractController
{
    private ContactRepository $repository;
    private SerializerInterface $serializer;
    private UrlGeneratorInterface $urlGenerator;

    public function __construct(
        ContactRepository $repository,
        SerializerInterface $serializer,
        UrlGeneratorInterface $urlGenerator
    ) {
        $this->repository = $repository;
        $this->serializer = $serializer;
        $this->urlGenerator = $urlGenerator;
    }

    #[Route('/post', name: 'contact', methods: ['POST'])]
    public function contact(Request $request, MailerInterface $mailer): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        $email = $data['email'] ?? '';
        $reason = $data['raison'] ?? '';
        $message = $data['message'] ?? '';

        if (empty($email) || empty($message)) {
            return new JsonResponse(['erreur' => 'Input non valide'], 400);
        }

        $emailMessage = (new Email())
            ->from($email)
            ->to('d653d77ca5-888676@inbox.mailtrap.io') 
            ->subject('Nouvelle demande de contact: ' . $reason)
            ->text($message);

        try {
            $mailer->send($emailMessage);
            return new JsonResponse(['status' => 'Email envoyé']);
        } catch (\Exception $e) {
            return new JsonResponse(['erreur' => "Echec de l'envoie:" . $e->getMessage()], 500);
        }
    }
}
