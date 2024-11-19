<?php

namespace App\Controller;

use App\Entity\Joueur;
use App\Repository\JoueurRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/api/joueur')]
class JoueurController extends AbstractController
{
    #[Route('/', name: 'app_joueur')]
    public function index(JoueurRepository $joueurRepository): Response
    {
        $joueurs = $joueurRepository->findAll();
        return $this->json($joueurs);
    }

    #[Route('/{id}', name: 'app_joueur_show')]
    public function show(JoueurRepository $joueurRepository, int $id): Response
    {
        $joueur = $joueurRepository->find($id);
        return $this->json($joueur);
    }

    #[Route('/new', name: 'app_joueur_new')]
    public function new(JoueurRepository $joueurRepository): Response
    {
        $joueur = new Joueur();
        $joueurRepository->add($joueur);
        return $this->json($joueur);
    }
    
    #[Route('/{id}', name: 'app_joueur_delete')]
    public function delete(JoueurRepository $joueurRepository, int $id): Response
    {
        $joueur = $joueurRepository->find($id);
        $joueurRepository->remove($joueur);
        return $this->json($joueur);
    }
}
