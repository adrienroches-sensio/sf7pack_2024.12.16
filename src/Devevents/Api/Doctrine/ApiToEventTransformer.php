<?php

declare(strict_types=1);

namespace App\Devevents\Api\Doctrine;

use App\Entity\Event;
use Doctrine\ORM\EntityManagerInterface;

final class ApiToEventTransformer
{
    public function __construct(
        private readonly EntityManagerInterface $manager,
    ) {
    }

    public function get(array $apiEvent): Event
    {
        $event = $this->manager
            ->getRepository(Event::class)
            ->findOneBy([
                'name' => $apiEvent['name'],
                'startAt' => new \DateTimeImmutable($apiEvent['startDate'])
            ])
        ;

        if (null !== $event) {
            return $event;
        }

        $event = $this->transform($apiEvent);

        $this->manager->persist($event);

        return $event;
    }

    public function transform(array $apiEvent): Event
    {
        return (new Event())
            ->setName($apiEvent['name'])
            ->setStartAt(new \DateTimeImmutable($apiEvent['startDate']))
            ->setEndAt(new \DateTimeImmutable($apiEvent['endDate']))
            ->setDescription($apiEvent['description'])
            ->setAccessible($apiEvent['accessible'])
        ;
    }
}
