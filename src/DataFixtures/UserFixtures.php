<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserFixtures extends Fixture
{
    public function __construct(
        private readonly UserPasswordHasherInterface $passwordHasher,
    ) {}

    public function load(ObjectManager $manager): void
    {
        $admin = new User();
        $admin
            ->setEmail('admin@fima.it')
            ->setFirstName('Admin')
            ->setLastName('FIMA')
            ->setRoles(['ROLE_ADMIN'])
            ->setPassword($this->passwordHasher->hashPassword($admin, 'password'))
            ->setCreatedAt(new \DateTime())
            ->setIsDeleted(false);

        $manager->persist($admin);
        $manager->flush();
    }
}
