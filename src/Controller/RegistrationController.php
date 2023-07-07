<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegistrationFormType;
use App\Security\UsersAuthenticator;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\UserAuthenticatorInterface;
use Symfony\Component\Serializer\SerializerInterface;

class RegistrationController extends AbstractController
{
    #[Route('/{_locale<%locales%>}/register', name: 'app_register')]
    public function register(Request $request, UserPasswordHasherInterface $userPasswordHasher, UserAuthenticatorInterface $userAuthenticator, UsersAuthenticator $authenticator, EntityManagerInterface $entityManager): Response
    {
        $user = new User();
        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // encode the plain password
            $user->setPassword(
                $userPasswordHasher->hashPassword(
                    $user,
                    $form->get('plainPassword')->getData()
                )
            );

            $entityManager->persist($user);
            $entityManager->flush();
            // do anything else you need here, like send an email

            return $userAuthenticator->authenticateUser(
                $user,
                $authenticator,
                $request
            );
        }

        return $this->render('registration/register.html.twig', [
            'registrationForm' => $form->createView(),
        ]);
    }

    #[Route('/api/register', name: 'app_api_register', methods: ['POST'])]
    public function api_register(Request $request, UserPasswordHasherInterface $userPasswordHasher, SerializerInterface $serializer, EntityManagerInterface $entityManager): JsonResponse
    {
        try {
            $user = $serializer->deserialize($request->getContent(), User::class, 'json');

            $user->setPassword(
                $userPasswordHasher->hashPassword(
                    $user,
                    $user->getPlainPassword()
                )
            );

            $user->setEnabled(true);
            $entityManager->persist($user);
            $entityManager->flush();

            $jsonUser = $serializer->serialize($user, 'json', ['groups' => 'user:info']);

            return new JsonResponse($jsonUser, Response::HTTP_CREATED);
        } catch (\Throwable $th) {
            $jsonError = $serializer->serialize($th->getMessage(), 'json');
            return new JsonResponse($jsonError, Response::HTTP_BAD_REQUEST);
        }
    }
}
