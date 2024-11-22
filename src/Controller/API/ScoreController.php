<?php

namespace App\Controller\API;

use App\Entity\Score;
use App\Repository\ScoreRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Routing\Requirement\Requirement;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/api/scores')]
class ScoreController extends AbstractController
{
    #[Route('/', name: 'app_scores', methods: ['GET'])]
    public function index(ScoreRepository $scoreRepository): Response
    {
        $scores = $scoreRepository->findAll();
        return $this->json($scores, 200, [], ['groups' => 'read_scores']);
    }

    #[Route('/{id}', name: 'app_score_show', methods: ['GET'], requirements: ['id' => Requirement::DIGITS])]
    public function show(Score $score): Response
    {
        return $this->json($score,  200, [], ['groups' => 'show_score']);
    }

    #[Route('/{id}/edit/victoire', name: 'app_score_update_victoire', methods: ['PUT'], requirements: ['id' => Requirement::DIGITS])]
    public function updateVictoire(Score $score, EntityManagerInterface $em): Response
    {
        try {

            $score->setVictoire($score->getVictoire() + 1);
            $score->setPoints($score->getPoints() + 3);
            $em->persist($score);
            $em->flush();
            $message = "La victoire a bien été ajoutée et le score mis à jour";
            $code = 200;
        } catch (\Exception $e) {
            $message = $e->getMessage();
            $code = 400;
        }

        return $this->json([$message, $score],  $code, [], ['groups' => 'show_score']);
    }

    #[Route('/{id}/edit/nul', name: 'app_score_update_nul', methods: ['PUT'], requirements: ['id' => Requirement::DIGITS])]
    public function updateNul(Score $score, EntityManagerInterface $em): Response
    {
        try {

            $score->setNul($score->getNul() + 1);
            $score->setPoints($score->getPoints() + 1);
            $em->persist($score);
            $em->flush();
            $message = "Le nul a bien été ajouté et le score mis à jour";
            $code = 200;
        } catch (\Exception $e) {
            $message = $e->getMessage();
            $code = 400;
        }

        return $this->json([$message, $score],  $code, [], ['groups' => 'show_score']);
    }

    #[Route('/{id}/edit/defaite', name: 'app_score_update_defaite', methods: ['PUT'], requirements: ['id' => Requirement::DIGITS])]
    public function updateDefaite(Score $score, EntityManagerInterface $em): Response
    {
        try {

            $score->setDefaite($score->getDefaite() + 1);
            $em->persist($score);
            $em->flush();
            $message = "Le nul a bien été ajouté et le score mis à jour";
            $code = 200;
        } catch (\Exception $e) {
            $message = $e->getMessage();
            $code = 400;
        }

        return $this->json([$message, $score],  $code, [], ['groups' => 'show_score']);
    }
}
