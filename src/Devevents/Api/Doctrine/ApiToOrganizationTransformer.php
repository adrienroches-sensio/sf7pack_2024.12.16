<?php

declare(strict_types=1);

namespace App\Devevents\Api\Doctrine;

use App\Entity\Organization;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

final class ApiToOrganizationTransformer
{
    public function __construct(
        private readonly EntityManagerInterface $manager,
        private readonly AuthorizationCheckerInterface $authorizationChecker,
    ) {
    }

    public function get(array $apiOrganization): Organization
    {
        $entity = $this->manager->getRepository(Organization::class)->findOneBy(['name' => $apiOrganization['name']]);

        if (null !== $entity) {
            return $entity;
        }

        $entity = $this->transform($apiOrganization);

        if ($this->isGranted() === true) {
            $this->manager->persist($entity);
        }

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

    private function isGranted(): bool
    {
        return $this->authorizationChecker->isGranted('ROLE_ORGANIZER') || $this->authorizationChecker->isGranted('ROLE_WEBSITE');
    }
}
