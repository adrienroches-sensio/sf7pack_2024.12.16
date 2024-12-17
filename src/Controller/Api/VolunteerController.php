<?php

declare(strict_types=1);

namespace App\Controller\Api;

use App\Repository\VolunteerRepository;
use Symfony\Component\Routing\Attribute\Route;

final class VolunteerController
{
    #[Route(path: '/api/volunteers', name: 'api_volunteer_list', methods: ['GET'])]
    public function getVolunteers(VolunteerRepository $volunteerRepository): array
    {
        return $volunteerRepository->findAll();
    }
}
