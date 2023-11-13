<?php

namespace App\Controller\Admin;


use App\Entity\User;
use App\Form\UserType;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

class UserController extends AbstractController
{
    // #[Route('/user', name: 'app_user')]
    // public function index(): Response
    // {
    //     return $this->render('user/index.html.twig', [
    //         'controller_name' => 'UserController',
    //     ]);
    // }



    #[IsGranted('ROLE_ADMIN')]
    #[Route('/admin/user/{id}/delete', name: 'app_admin_user_delete')]
    public function deleteUser(UserRepository $userRepository, int $id, EntityManagerInterface $entityManager, Security $security): Response
    {

        $admin = $security->getUser();
        if ($id === $admin->getId()) {
            $this->addFlash('danger', "You can't delete your own account!");
            return $this->redirectToRoute('app_admin_user');
        }

        $users = $userRepository->find($id);

        if (!empty($users)) {
            $entityManager->remove($users);
            $entityManager->flush();
            $this->addFlash('success', "User deleted!");
            return $this->redirectToRoute('app_admin_user');
        } else {
            $this->addFlash('danger', "User not found!");
            return $this->redirectToRoute('app_admin_user');
        }

    }
    #[Route('/admin/user/{id}/modify', name: 'app_admin_user_modify')]
    #[Route('/admin/user/new', name: 'app_admin_user_new')]
    public function usersModify(UserRepository $userRepository, ?User $user, Request $request, UserPasswordHasherInterface $passwordHasher): Response
    {
        $isCreation = false;
        if (is_null($user)) {

            $isCreation = true;
            $user = new User();
        }

        $form = $this->createForm(UserType::class, $user, ['isCreation' => $isCreation,]);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $hashedPassword = $passwordHasher->hashPassword(
                $user,
                $user->getPassword()
            );
            $user->setPassword($hashedPassword);
            $userRepository->save($user, true);

            $message = $isCreation == true ? 'User created!' : 'User updated!';
            $this->addFlash('success', $message);
            return $this->redirectToRoute('app_admin_user');

        }

        return $this->render('user/userUpdate.html.twig', [
            'controller_name' => 'UserController',
            'isCreation' => $isCreation,
            'form' => $form->createView(),
        ]);



    }

    #[Route('/admin/user', name: 'app_admin_user')]
    public function users(UserRepository $userRepository): Response
    {
        $users = $userRepository->findAll();


        return $this->render('admin/listUsers.html.twig', [
            'controller_name' => 'AdminController',
            'users' => $users
        ]);
    }


}
