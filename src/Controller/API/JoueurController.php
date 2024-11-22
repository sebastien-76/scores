<?php

namespace App\Controller\API;

use App\Entity\Joueur;
use App\Repository\EquipeRepository;
use App\Repository\JoueurRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Routing\Requirement\Requirement;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;

#[Route('/api/joueurs')]

class JoueurController extends AbstractController
{
    #[Route('/', name: 'app_joueurs', methods: ['GET'])]
    public function index(JoueurRepository $joueurRepository): Response
    {
        $joueurs = $joueurRepository->findAll();
        return $this->json($joueurs, 200, [], ['groups' => 'read_joueurs']);
    }

    #[Route('/{id}', name: 'app_joueur_show', methods: ['GET'], requirements: ['id' => Requirement::DIGITS])]
    public function show(Joueur $joueur): Response
    {
        return $this->json($joueur,  200, [], ['groups' => 'show_joueur']);
    }

    #[Route('/new', name: 'app_joueur_new', methods: ['POST'])]
    public function new(
        Request $request,
        EquipeRepository $equipeRepository,
        #[MapRequestPayload(
            serializationContext: [
                'groups' => 'new_joueur'
            ],
        )]
        Joueur $joueur,
        EntityManagerInterface $em
    ) {
        try {
            $données = $request->toArray();
            if (isset($données['equipe'])) {
                $equipe = $equipeRepository->find($données['equipe']);
                $joueur->setEquipe($equipe);
            }

            $em->persist($joueur);
            $em->flush();

            $message = "Le joueur a bien été ajouté";
            $code = 200;
        } catch (\Exception $e) {
            $message = $e->getMessage();
            $code = 400;
        }

        return $this->json([$message, $joueur], $code, [], ['groups' => 'show_joueur']);
    }

    #[Route('/{id}', name: 'app_joueur_delete', methods: ['DELETE'], requirements: ['id' => Requirement::DIGITS])]
    public function delete(Joueur $joueur, EntityManagerInterface $em): Response
    {
        try {
            $em->remove($joueur);
            $em->flush();

            $message = "Le joueur a bien été supprimé";
            $code = 200;
        } catch (\Exception $e) {
            $message = $e->getMessage();
            $code = 400;
        }

        return $this->json($message, $code);
    }

    #[Route('/{id}/edit', name: 'app_joueur_update', methods: ['PUT'], requirements: ['id' => Requirement::DIGITS])]
    public function update(Joueur $joueur, EquipeRepository $equipeRepository, EntityManagerInterface $em, Request $request): Response
    {
        try {
            $données = $request->toArray();
            isset($données['nom']) && $joueur->setNom($données['nom']);
            isset($données['prenom']) && $joueur->setPrenom($données['prenom']);
            if (isset($données['equipe'])) {
                $equipe = $equipeRepository->find($données['equipe']);
                $joueur->setEquipe($equipe);
            }

            $em->persist($joueur);
            $em->flush();

            $message = 'Le joueur a bien été modifié';
            $code = 200;
        } catch (\Exception $e) {
            $message = $e->getMessage();
            $code = 400;
        }

        return $this->json([$message, $joueur], $code, [], ['groups' => 'show_joueur']);
    }
}
