<?php

declare(strict_types=1);

namespace App\Devevents\Api\Doctrine;

use App\Entity\Event;
use App\Entity\Organization;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;
use Symfony\Component\HttpKernel\Event\ViewEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use function array_map;

final class ApiTransformerListener
{
    private array $newOrganizations = [];

    public function __construct(
        private readonly EntityManagerInterface $manager
    ) {
    }

    #[AsEventListener(event: KernelEvents::VIEW)]
    public function mapToEntities(ViewEvent $event): void
    {
        $request = $event->getRequest();
        if ('app_event_search' !== $request->attributes->get('_route')) {
            return;
        }

        $result = $event->getControllerResult();
        $result['events'] = array_map(function (array $apiEvent) {
            $event = $this->manager
                ->getRepository(Event::class)
                ->findOneBy([
                    'name' => $apiEvent['name'],
                    'startAt' => new \DateTimeImmutable($apiEvent['startDate'])
                ])
            ;

            if (null === $event) {
                $event = $this->createEvent($apiEvent);
            }

            foreach ($apiEvent['organizations'] as $org) {
                $entity = $this->manager->getRepository(Organization::class)->findOneBy(['name' => $org['name']]);

                if (null === $entity) {
                    $entity = $this->createOrganization($org);
                }

                $entity->addEvent($event);
                $event->addOrganization($entity);
            }

            $this->manager->flush();

            return $event;
        }, $result['events']['hydra:member']);

        $event->setControllerResult($result);
    }

    private function createEvent(array $apiEvent): Event
    {
        $event = (new Event())
            ->setName($apiEvent['name'])
            ->setStartAt(new \DateTimeImmutable($apiEvent['startDate']))
            ->setEndAt(new \DateTimeImmutable($apiEvent['endDate']))
            ->setDescription($apiEvent['description'])
            ->setAccessible($apiEvent['accessible'])
        ;

        $this->manager->persist($event);

        return $event;
    }

    private function createOrganization(array $org): Organization
    {
        if (isset($this->newOrganizations[$org['name']])) {
            return $this->newOrganizations[$org['name']];
        }

        $entity = (new Organization())
            ->setName($org['name'])
            ->setPresentation($org['presentation'])
            ->setCreatedAt(new \DateTimeImmutable($org['createdAt']))
        ;

        $this->manager->persist($entity);

        $this->newOrganizations[$org['name']] = $entity;

        return $entity;
    }
}
