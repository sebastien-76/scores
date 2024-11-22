<?php

namespace App\Controller\API;

use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Routing\Requirement\Requirement;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;


#[Route('/api/users')]
class UserController extends AbstractController
{
    private $hasher;
    public function __construct(UserPasswordHasherInterface $hasher)
    {
        $this->hasher = $hasher;
    }

    #[Route('/', name: 'app_user')]
    public function index(UserRepository $userRepository): Response
    {
        $users = $userRepository->findAll();
        return $this->json($users, 200, [], ['groups' => 'read_users']);
    }

    #[Route('/{id}', name: 'app_user_show', methods: ['GET'], requirements: ['id' => Requirement::DIGITS])]
    public function show(User $user): Response
    {
        return $this->json($user, 200, [], ['groups' => 'read_users']);
    }

    #[Route('/{id}', name: 'app_user_delete', methods: ['DELETE'], requirements: ['id' => Requirement::DIGITS])]
    public function delete(User $user, EntityManagerInterface $em): Response
    {
        try {
            $em->remove($user);
            $em->flush();

            $message = "Le joueur a bien été supprimé";
            $code = 200;
        } catch (\Exception $e) {
            $message = $e->getMessage();
            $code = 400;
        }

        return $this->json($message, $code);
    }

    #[Route('/new', name: 'app_user_new', methods: ['POST'])]
    public function new(
        Request $request,
        UserRepository $userRepository,
        #[MapRequestPayload(
            serializationContext: [
                'groups' => 'new_user'
            ],
        )]
        User $user,
        EntityManagerInterface $em
    ) {
        try {
            $données = $request->toArray();
            isset($données['password']) && $user->setPassword($this->hasher->hashPassword($user, 'password'));
            isset($données['roles']) && $user->setRoles([$données['roles']]);
            $em->persist($user);
            $em->flush();

            $message = "Le user a bien été ajouté";
            $code = 200;
        } catch (\Exception $e) {
            $message = $e->getMessage();
            $code = 400;
        }

        return $this->json([$message, $user], $code, [], ['groups' => 'show_user']);
    }
}
