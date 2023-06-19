<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class UserController extends AbstractController
{
    #[Route('/user', name: 'app_user')]
    public function index(): Response
    {
        return $this->render('user/index.html.twig');
    }
    #[Route('/admin/user/manager', name: 'app_user_manage')]
    public function manager(UserRepository $repository): Response
    {
        return $this->render('user/manager.html.twig', [
            'users' => $repository->findAll()
        ]);
    }
    #[Route('/admin/user/promote/{id}', name: 'app_user_promote')]
    public function promote(User $user, UserRepository $repository,  EntityManagerInterface $manager): Response
    {
        $user->setRoles(['ROLE_ADMIN']);
        $manager->flush();
        return $this->render('user/manager.html.twig', [
            'users' => $repository->findAll()
        ]);
    }
    #[Route('/admin/user/demote/{id}', name: 'app_user_demote')]
    public function demote(User $user, UserRepository $repository, EntityManagerInterface $manager): Response
    {
        $user->setRoles(['ROLE_USER']);
        $manager->flush();
        return $this->render('user/manager.html.twig', [
            'users' => $repository->findAll()
        ]);
    }
}
