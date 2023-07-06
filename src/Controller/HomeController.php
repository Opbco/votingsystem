<?php

namespace App\Controller;

use App\Entity\Membre;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Mime\Email;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Address;
use Symfony\Component\Serializer\SerializerInterface;

class HomeController extends AbstractController
{

    #[Route('/', name: 'app_home')]
    public function index(Request $request): Response
    {
        return $this->render(
            'home/index.html.twig',
            ['page' => $request->query->all()]
        );
    }

    #[Route('/adapimin', name: 'app_admin_api')]
    public function adminApi(Request $request): Response
    {
        return $this->render(
            'home/api.html.twig'
        );
    }


    #[Route('/{_locale<%locales%>}/print/card/{id}', name: 'app_id_card')]
    public function card(int $id, Membre $personnel, SerializerInterface $serializer): Response
    {
        $spersonnel = $serializer->serialize($personnel, 'json', ['groups' => 'getMembres']);

        return $this->render('Card/index.html.twig', [
            'personnel' => $spersonnel,
            'id' => $id
        ]);
    }

    #[Route('/contactsending', name: 'app_contact_email_us', methods: ['POST'])]
    public function contact(Request $request, SerializerInterface $serializer, MailerInterface $mailer): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        try {

            $email = (new Email())
                ->from(new Address('brice.mich@gmail.com', $data['name']))
                ->to('brice.mich@gmail.com')
                ->priority(Email::PRIORITY_HIGH)
                ->subject($data['subject'] . ' (' . $data['name'] . ')')
                ->text($data['contacts'])
                ->html($data['email_body']);
            $mailer->send($email);
            return new JsonResponse(null, Response::HTTP_OK);
        } catch (Exception $ex) {
            $jsonDocument = $serializer->serialize(["message" => $ex->getMessage()], 'json');
            return new JsonResponse($jsonDocument, Response::HTTP_BAD_REQUEST);
        }
    }
}
