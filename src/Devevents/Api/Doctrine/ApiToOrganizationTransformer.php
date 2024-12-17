<?php

declare(strict_types=1);

namespace App\Devevents\Api\Doctrine;

use App\Entity\Event;
use App\Entity\Organization;
use Doctrine\ORM\EntityManagerInterface;

final class ApiToOrganizationTransformer
{
    public function __construct(
        private readonly EntityManagerInterface $manager,
    ) {
    }

    public function get(array $apiOrganization): Organization
    {
        $entity = $this->manager->getRepository(Organization::class)->findOneBy(['name' => $apiOrganization['name']]);

        if (null !== $entity) {
            return $entity;
        }

        $entity = $this->transform($apiOrganization);

        $this->manager->persist($entity);

        return $entity;
    }

    public function transform(array $apiOrganization): Organization
    {
        return (new Organization())
            ->setName($apiOrganization['name'])
            ->setPresentation($apiOrganization['presentation'])
            ->setCreatedAt(new \DateTimeImmutable($apiOrganization['createdAt']))
        ;
    }
}
