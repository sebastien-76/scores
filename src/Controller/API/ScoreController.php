<?php

namespace App\Controller\API;

use App\Repository\ScoreRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Routing\Requirement\Requirement;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

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
    public function show(ScoreRepository $scoreRepository, int $id): Response
    {
        $score = $scoreRepository->find($id);
        return $this->json($score,  200, [], ['groups' => 'show_score']);
    }
}
