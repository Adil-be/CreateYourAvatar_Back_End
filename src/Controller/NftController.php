<?php

namespace App\Controller;


use App\Entity\Nft;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class NftController extends AbstractController
{

    public function __invoke(Request $request, EntityManagerInterface $entityManager)
    {

        $data = $request->request->all();

        return new JsonResponse($data, Response::HTTP_OK);

    }
}
