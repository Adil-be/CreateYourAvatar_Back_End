<?php

namespace App\Controller\Admin;

use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdminController extends AbstractController
{

    
    #[Route('/admin', name: 'app_admin')]
    public function index(): Response
    {
        return $this->render('admin/index.html.twig', [
            'controller_name' => 'AdminController',
        ]);
    }

    

    #[Route(path: '/redirectCompte', name: 'security_redirect_admin')]
    public function redirectCompte(): RedirectResponse
    {
        $user = $this->getUser();
        $roles = $user->getRoles();
        if (in_array('ROLE_ADMIN', $roles, true)) {
            return $this->redirectToRoute('app_admin');
        }
         else {
            $this->addFlash('danger', 'You must be admin to acces this page');
            return $this->redirectToRoute('app_login');
        }
    }
}
