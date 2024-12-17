<?php

declare(strict_types=1);

namespace App\Devevents\Api\Doctrine;

use Doctrine\ORM\EntityManagerInterface;
use function array_map;

final class ApiParser
{
    public function __construct(
        private readonly EntityManagerInterface $manager,
        private readonly ApiToEventTransformer $apiToEventTransformer,
        private readonly ApiToOrganizationTransformer $apiToOrganizationTransformer,
    ) {
    }

    public function parse(array $data): array
    {
        return array_map(function (array $apiEvent) {
            $event = $this->apiToEventTransformer->get($apiEvent);

            foreach ($apiEvent['organizations'] as $org) {
                $entity = $this->apiToOrganizationTransformer->get($org);

                $entity->addEvent($event);
                $event->addOrganization($entity);
            }

            $this->manager->flush();

            return $event;
        }, $data['hydra:member']);
    }
}
