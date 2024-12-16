<?php

declare(strict_types=1);

namespace App\Search;

use App\Repository\EventRepository;

final class DatabaseEventSearch
{
    public function __construct(
        private readonly EventRepository $eventRepository,
    ) {
    }

    public function searchByName(string|null $name = null): array
    {
        if (null === $name) {
            return $this->eventRepository->findAll();
        }

        return $this->eventRepository->searchByName($name);
    }
}
