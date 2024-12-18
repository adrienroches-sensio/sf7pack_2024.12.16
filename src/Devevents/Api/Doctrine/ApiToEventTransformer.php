<?php

declare(strict_types=1);

namespace App\Devevents\Api\Doctrine;

use App\Entity\Event;
use App\Security\Authorization;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

final class ApiToEventTransformer
{
    public function __construct(
        private readonly EntityManagerInterface $manager,
        private readonly AuthorizationCheckerInterface $authorizationChecker,
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

        if ($this->isGranted() === true) {
            $this->manager->persist($event);
        }

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

    private function isGranted(): bool
    {
        return $this->authorizationChecker->isGranted(Authorization::EVENT_CREATE);
    }
}
