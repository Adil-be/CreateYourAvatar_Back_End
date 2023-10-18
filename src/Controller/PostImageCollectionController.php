<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PostImageCollectionController extends AbstractController
{
    #[Route('/post/image/collection', name: 'app_post_image_collection')]
    public function index(): Response
    {
        return $this->render('post_image_collection/index.html.twig', [
            'controller_name' => 'PostImageCollectionController',
        ]);
    }
}
