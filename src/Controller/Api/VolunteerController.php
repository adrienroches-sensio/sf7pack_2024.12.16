<?php

declare(strict_types=1);

namespace App\Controller\Api;

use App\Repository\VolunteerRepository;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\Serializer\SerializerInterface;

#[AsController]
final class VolunteerController
{
    #[IsGranted('IS_AUTHENTICATED')]
    #[Route(path: '/api/volunteers', name: 'api_volunteer_list', methods: ['GET'])]
    public function getVolunteers(VolunteerRepository $volunteerRepository, SerializerInterface $serializer): JsonResponse
    {
        return new JsonResponse(
            $serializer->serialize($volunteerRepository->findAll(), 'json'),
            json: true
        );
    }
}
