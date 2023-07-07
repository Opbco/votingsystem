<?php

namespace App\Controller;

use App\Entity\Document;
use App\Entity\Member;
use App\Entity\Vote;
use App\Repository\CandidatRepository;
use App\Repository\MemberRepository;
use App\Repository\VoteRepository;
use App\Repository\VotingSessionRepository;
use App\Service\Base64FileExtractor;
use App\Service\UploadedBase64File;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class MemberController extends AbstractController
{
    const SERVER_PATH_TO_FILES_FOLDER = __DIR__ . '/../../public/uploads';

    #[Route('/api/open/candidats', name: 'app_get_candidats', methods: ['GET'])]
    public function getCandidats(CandidatRepository $candidatsRepository, VotingSessionRepository $votingSessionRepository, SerializerInterface $serializer): JsonResponse
    {
        $session = $votingSessionRepository->findOneBy(['status' => true]);
        if ($session) {
            $candidats = $candidatsRepository->findBy(['status' => true, 'session' => $session], ['position' => 'ASC']);

            return new JsonResponse($serializer->serialize($candidats, 'json', ['groups' => 'candidat.list']), Response::HTTP_OK, [], true);
        }

        return new JsonResponse(null, Response::HTTP_NOT_FOUND);
    }

    #[Route('/api/open/members/{id}/votes', name: 'app_get_member_votes', methods: ['GET'])]
    public function getMemberVotes(Member $member,  VoteRepository $voteRepository, VotingSessionRepository $votingSessionRepository, SerializerInterface $serializer): JsonResponse
    {
        $session = $votingSessionRepository->findOneBy(['status' => true]);
        if ($session) {
            $votes = $voteRepository->findBy(['member' => $member, 'session' => $session], ['candidat' => 'ASC']);

            return new JsonResponse($serializer->serialize($votes, 'json', ['groups' => 'vote.list']), Response::HTTP_OK, [], true);
        }

        return new JsonResponse(null, Response::HTTP_NOT_FOUND);
    }

    #[Route('/api/members/me', name: 'app_member_connected_details', methods: ['GET'])]
    public function getPersonnelConnected(TokenStorageInterface $tokenStorageInterface, MemberRepository $memberRepository, SerializerInterface $serializer): JsonResponse
    {

        $user = $tokenStorageInterface->getToken()->getUser();

        $member = $memberRepository->findOneBy(['account' => $user]);

        if ($member) {
            return new JsonResponse($serializer->serialize($member, 'json', ['groups' => 'membre.list']), Response::HTTP_OK, [], true);
        }

        return new JsonResponse(null, Response::HTTP_NOT_FOUND);
    }

    #[Route('/api/open/members/{id}/votes', name: 'app_post_member_votes', methods: ['POST'])]
    public function setMemberVote(Request $request, Member $member, CandidatRepository $candidatRepository,  VoteRepository $voteRepository, SerializerInterface $serializer, EntityManagerInterface $em): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        if ($data) {
            $candidat = $candidatRepository->find($data['candidat']);
            if ($candidat) {
                $vote = new Vote();
                $vote->setCandidat($candidat);
                $vote->setMember($member);
                $vote->setDateCreated(new \DateTimeImmutable());
                $em->persist($vote);

                $candidat->IncNumberVoter();

                $em->flush();

                return new JsonResponse($serializer->serialize($vote, 'json', ['groups' => 'vote.list']), Response::HTTP_OK, [], true);
            }
        }

        return new JsonResponse(null, Response::HTTP_BAD_REQUEST);
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
