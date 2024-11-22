<?php

namespace App\DataFixtures;

use App\Entity\User;
use Faker\Factory as FakerFactory;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture implements FixtureGroupInterface
{

    private $faker;
    private $hasher;
    private $manager;

    public function __construct(UserPasswordHasherInterface $hasher)
    {
        $this->faker = FakerFactory::create('fr_FR');
        $this->hasher = $hasher;
    }

    public static function getGroups(): array
    {
        return ['prod', 'test'];
    }

    public function load(ObjectManager $manager): void
    {
        $this->manager = $manager;
        $this->loadAdmins();
    }

    public function loadAdmins(): void
    {
        $datas = [
            [
                'email' => 'admin@example.com',
                'password' => 'score123!',
                'roles' => ['ROLE_ADMIN'],
            ],
        ];

        foreach ($datas as $data) {
            $user = new User();
            $user->setEmail($data['email']);
            $user->setPassword($this->hasher->hashPassword($user, $data['password']));
            $user->setRoles($data['roles']);
            $this->manager->persist($user);
        }

        $this->manager->flush();

    }

}
