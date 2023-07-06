<?php

namespace App\Controller;

use App\Entity\CandidatConcours;
use App\Repository\DepartementRepository;
use App\Repository\ExamCenterRepository;
use App\Repository\SpecialityRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

class ConcoursController extends AbstractController
{
    #[Route('/api/open/specialities', name: 'api_concours_specialities', methods: ['GET'])]
    public function getListSpecialities(SpecialityRepository $specialityRepository, SerializerInterface $serializer): JsonResponse
    {
        $specialities = $specialityRepository->findBy([], ["name" => "ASC"]);

        return new JsonResponse($serializer->serialize($specialities, 'json', ['groups' => 'list.speciality']), Response::HTTP_OK, [], true);
    }

    #[Route('/api/open/departments', name: 'api_concours_departments', methods: ['GET'])]
    public function getListDivisions(DepartementRepository $departementRepository, SerializerInterface $serializer): JsonResponse
    {
        $departments = $departementRepository->findBy([], ["name" => "ASC"]);

        return new JsonResponse($serializer->serialize($departments, 'json', ['groups' => 'divisions']), Response::HTTP_OK, [], true);
    }

    #[Route('/api/open/examcenters', name: 'api_concours_examcenters', methods: ['GET'])]
    public function getListExamCenter(ExamCenterRepository $examCenterRepository, SerializerInterface $serializer): JsonResponse
    {
        $examcenter = $examCenterRepository->findBy([], ["name" => "ASC"]);

        return new JsonResponse($serializer->serialize($examcenter, 'json', ['groups' => 'list.examcenter']), Response::HTTP_OK, [], true);
    }

    #[Route('/api/open/concourscandidats/{id}', name: 'api_concours_candidat', methods: ['GET'])]
    public function getConcoursCandidat(CandidatConcours $candidatConcours, SerializerInterface $serializer): JsonResponse
    {
        if ($candidatConcours) {
            return new JsonResponse($serializer->serialize($candidatConcours, 'json', ['groups' => 'candidatConcour']), Response::HTTP_OK, [], true);
        }

        return new JsonResponse(null, Response::HTTP_NOT_FOUND);
    }
}
