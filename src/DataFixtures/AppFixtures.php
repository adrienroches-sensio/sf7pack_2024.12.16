<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\PasswordHasherFactoryInterface;

class AppFixtures extends Fixture
{
    public function __construct(
        private readonly PasswordHasherFactoryInterface $passwordHasherFactory,
    ) {
    }

    public function load(ObjectManager $manager): void
    {
        $admin = (new User())
            ->setEmail('admin@example.com')
            ->setPassword($this->passwordHasherFactory->getPasswordHasher(User::class)->hash('admin'))
            ->setRoles(['ROLE_ADMIN'])
        ;

        $manager->persist($admin);
        $manager->flush();
    }
}
