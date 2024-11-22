<?php

namespace App\DataFixtures;

use App\Entity\Score;
use App\Entity\Equipe;
use App\Entity\Joueur;
use App\Entity\User;
use Faker\Factory as FakerFactory;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class TestFixtures extends Fixture implements FixtureGroupInterface
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
        return ['test'];
    }

    public function load(ObjectManager $manager): void
    {
        $this->manager = $manager;

        $this->loadEquipes();
        $this->loadJoueurs();
        $this->loadUsers();
    }

    public function loadEquipes(): void
    {
        for ($i = 0; $i < 5; ++$i) {
            $equipe = new Equipe();
            $equipe->setNom($this->faker->company());
            $equipe->setVille($this->faker->city());
            $this->manager->persist($equipe);

            $score = new Score();
            $score->setVictoire($this->faker->numberBetween(0, 10));
            $score->setNul($this->faker->numberBetween(0, 10));
            $score->setDefaite($this->faker->numberBetween(0, 10));
            $points = $score->getVictoire() * 3 + $score->getNul() * 1;
            $score->setPoints($points);
            $score->setEquipe($equipe);
            $this->manager->persist($score);
        }

        $this->manager->flush();
    }

    public function loadJoueurs(): void
    {
        $equipeRepository = $this->manager->getRepository(Equipe::class);
        $equipes = $equipeRepository->findAll();

        for ($i = 0; $i < 40; ++$i) {
            $joueur = new Joueur();
            $joueur->setNom($this->faker->lastName());
            $joueur->setPrenom($this->faker->firstName());
            $equipe = $this->faker->randomElement($equipes);
            $joueur->setEquipe($equipe);
            $this->manager->persist($joueur);
        }

        $this->manager->flush();
    }

    public function loadUsers(): void
    {
        for ($i = 0; $i < 10; ++$i) {
            $user = new User();
            $user->setEmail($this->faker->email());
            $user->setPassword($this->hasher->hashPassword($user, 'password'));
            $user->setRoles(['ROLE_USER']);
            $this->manager->persist($user);
        }

        $this->manager->flush();
    }
}
