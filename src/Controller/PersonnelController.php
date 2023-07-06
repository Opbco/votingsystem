<?php

namespace App\Controller;

use App\Entity\Document;
use App\Entity\Membre;
use App\Repository\DocumentRepository;
use App\Repository\MembreRepository;
use App\Service\Base64FileExtractor;
use App\Service\UploadedBase64File;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class PersonnelController extends AbstractController
{
    const SERVER_PATH_TO_FILES_FOLDER = __DIR__ . '/../../public/uploads';

    #[Route('/api/open/personnels', name: 'app_personnel', methods: ['GET'])]
    public function index(Request $request, MembreRepository $personnelRepository, SerializerInterface $serializer): JsonResponse
    {
        $featured = intval($request->query->get('featured'));
        if ($featured) {
            $personnels = $personnelRepository->findBy(['isFeatured' => true], ['name' => 'ASC']);
        } else {
            $personnels = $personnelRepository->findAll();
        }

        return new JsonResponse($serializer->serialize($personnels, 'json', ['groups' => 'getMembres']), Response::HTTP_OK, [], true);
    }

    #[Route('/api/personnels/me', name: 'app_personnel_connected_details', methods: ['GET'])]
    public function getPersonnelConnected(TokenStorageInterface $tokenStorageInterface, MembreRepository $personnelRepository, SerializerInterface $serializer): JsonResponse
    {

        $user = $tokenStorageInterface->getToken()->getUser();

        $personnel = $personnelRepository->findOneBy(['account' => $user]);

        if ($personnel) {
            return new JsonResponse($serializer->serialize($personnel, 'json', ['groups' => 'getMembres']), Response::HTTP_OK, [], true);
        }

        return new JsonResponse(null, Response::HTTP_NOT_FOUND);
    }

    #[Route('/api/personnels/{id}', name: 'app_personnel_details', methods: ['GET'])]
    public function getPersonnel(Membre $personnel, SerializerInterface $serializer): JsonResponse
    {

        if ($personnel) {
            return new JsonResponse($serializer->serialize($personnel, 'json', ['groups' => 'getMembres']), Response::HTTP_OK, [], true);
        }

        return new JsonResponse(null, Response::HTTP_NOT_FOUND);
    }

    #[Route('/api/personnels/{id}', name: 'app_personnel_delete', methods: ['DELETE'])]
    public function deletePersonnel(Membre $personnel, EntityManagerInterface $em): JsonResponse
    {

        $em->remove($personnel);
        $em->flush();

        return new JsonResponse(null, Response::HTTP_NO_CONTENT);
    }

    #[Route('/api/personnels', name: 'app_personnel_create', methods: ['POST'])]
    public function createPersonnel(Request $request, SerializerInterface $serializer, EntityManagerInterface $em, UrlGeneratorInterface $urlGenerator): JsonResponse
    {
        $personnel = $serializer->deserialize($request->getContent(), Membre::class, 'json');

        $em->persist($personnel);
        $em->flush();

        $jsonPersonnel = $serializer->serialize($personnel, 'json', ['groups' => 'getMembres']);

        $location = $urlGenerator->generate('app_personnel_details', ['id' => $personnel->getId()], UrlGeneratorInterface::ABSOLUTE_URL);


        return new JsonResponse($jsonPersonnel, Response::HTTP_CREATED, ['location' => $location], true);
    }

    #[Route('/api/personnels/{id}', name: 'app_personnel_update', methods: ['PUT'])]
    public function updatePersonnel(Request $request, LoggerInterface $logger, DocumentRepository $documentRepository, SerializerInterface $serializer, Membre $personnel, EntityManagerInterface $em): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        $avatar = $documentRepository->find($data['avatar']);
        $updatedPersonnel = $serializer->deserialize($request->getContent(), Membre::class, 'json', [AbstractNormalizer::OBJECT_TO_POPULATE => $personnel]);


        $updatedPersonnel->setAvatar($avatar);

        $em->persist($updatedPersonnel);
        $em->flush();

        return new JsonResponse(null, Response::HTTP_OK);
    }


    #[Route('/api/documents', name: 'app_document_create', methods: ['POST'])]
    public function createDocument(Request $request, Base64FileExtractor $base64FileExtractor, SerializerInterface $serializer, EntityManagerInterface $em): JsonResponse
    {
        $document = new Document();

        $originalName = $request->request->get('fileName');
        $base64Image = $request->request->get('file');
        $base64Image = $base64FileExtractor->extractBase64String($base64Image);
        $imageFile = new UploadedBase64File($base64Image[0], $originalName);

        if ($imageFile->move($this->getParameter('uploads_dir'), $imageFile->getClientOriginalName())) {
            $document->setFileName($originalName);
            $document->setMimeType($base64Image[1]);

            $em->persist($document);
            $em->flush();

            $jsonDocument = $serializer->serialize($document, 'json', ['groups' => "document"]);
            return new JsonResponse($jsonDocument, Response::HTTP_CREATED);
        }

        $jsonDocument = $serializer->serialize(["message" => "Error while uploading the file."], 'json');
        return new JsonResponse($jsonDocument, Response::HTTP_BAD_REQUEST);
    }
}
