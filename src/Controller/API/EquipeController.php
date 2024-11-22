<?php

namespace App\Controller\API;

use App\Entity\Score;
use App\Entity\Equipe;
use App\Repository\EquipeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Routing\Requirement\Requirement;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/api/equipes')]
class EquipeController extends AbstractController
{
    #[Route('/', name: 'app_equipes', methods: ['GET'])]
    public function index(EquipeRepository $equipeRepository): Response
    {
        $equipes = $equipeRepository->findAll();
        return $this->json($equipes, 200, [], ['groups' => 'read_equipes']);
    }


    #[Route('/{id}', name: 'app_equipe_show', methods: ['GET'], requirements: ['id' => Requirement::DIGITS])]
    public function show(Equipe $equipe): Response
    {
        return $this->json($equipe,  200, [], ['groups' => 'show_equipe']);
    }

    #[Route('/{id}', name: 'app_equipe_delete', methods: ['DELETE'])]
    public function delete(Equipe $equipe, EntityManagerInterface $em): Response
    {
        try {
            $em->remove($equipe);
            $em->flush();

            $message = "L'equipe a bien été supprimée";
            $code = 200;
        } catch (\Exception $e) {
            $message = $e->getMessage();
            $code = 400;
        }

        return $this->json($message, $code);
    }

    #[Route('/new', name: 'app_equipe_new', methods: ['POST'])]
    public function new(
        Request $request,
        #[MapRequestPayload(
            serializationContext: [
                'groups' => 'new_equipe'
            ]
        )]
        Equipe $equipe,
        EntityManagerInterface $em
    ) {
        try {
            $score = new Score();
            $score->setPoints(0);
            $score->setVictoire(0);
            $score->setNul(0);
            $score->setDefaite(0);
            $em->persist($score);

            $equipe->setScore($score);

            $em->persist($equipe);
            $em->flush();

            $message = "L'equipe a bien été ajoutée";
            $code = 200;
        } catch (\Exception $e) {
            $message = $e->getMessage();
            $code = 400;
        }
        return $this->json([$message, $equipe], $code, [], ['groups' => 'show_equipe']);
    }

    #[Route('/{id}/edit', name: 'app_equipe_update', methods: ['PUT'], requirements: ['id' => Requirement::DIGITS])]
    public function update(Equipe $equipe, EntityManagerInterface $em, Request $request): Response
    {
        try {
            $données = $request->toArray();
            isset($données['nom']) && $equipe->setNom($données['nom']);
            isset($données['ville']) && $equipe->setVille($données['logo']);

            $em->persist($equipe);
            $em->flush();

            $message = 'L\'équipe a bien été modifiée';
            $code = 200;
        } catch (\Exception $e) {
            $message = $e->getMessage();
            $code = 400;
        }

        return $this->json([$message, $equipe], $code, [], ['groups' => 'show_equipe']);
    }

}
