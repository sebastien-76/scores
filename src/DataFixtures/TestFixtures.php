<?php

namespace App\DataFixtures;

use App\Entity\Score;
use App\Entity\Equipe;
use App\Entity\Joueur;
use Faker\Factory as FakerFactory;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;

class TestFixtures extends Fixture implements FixtureGroupInterface
{
    private $faker;
    private $manager;

    public function __construct()
    {
        $this->faker = FakerFactory::create('fr_FR');
    }

    public function load(ObjectManager $manager): void
    {
        $this->manager = $manager;

        $this->loadEquipes();
        $this->loadJoueurs();
    }

    public function loadEquipes(): void
    {
        for ($i = 0; $i < 5; ++$i) {
            $equipe = new Equipe();
            $equipe->setNom($this->faker->company());
            $equipe->setVille($this->faker->city());
            $this->manager->persist($equipe);

            $score = new Score();
            $score->setPoints($this->faker->numberBetween(0, 100));
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

    public static function getGroups(): array
    {
        return ['test'];
    }
}
