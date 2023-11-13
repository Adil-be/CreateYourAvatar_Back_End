<?php

namespace App\Controller\Admin;


use App\Entity\Nft;
use App\Form\NftType;
use App\Repository\NftRepository;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

class NftController extends AbstractController
{

    #[IsGranted('ROLE_ADMIN')]
    #[Route('/admin/nft/{id}/delete', name: 'app_admin_nft_delete')]
    public function deleteNft(NftRepository $nftRepository, int $id, EntityManagerInterface $entityManager): Response
    {


        $nft = $nftRepository->find($id);

        if (!empty($nft)) {
            $entityManager->remove($nft);
            $entityManager->flush();
            $this->addFlash('success', "nft deleted!");
            return $this->redirectToRoute('app_admin_nft');
        } else {
            $this->addFlash('danger', "nft not found!");
            return $this->redirectToRoute('app_admin_nft');
        }

    }

    #[Route('/admin/nft/{id}/modify', name: 'app_admin_nft_modify')]
    #[Route('/admin/nft/new', name: 'app_admin_nft_new')]
    public function ModifyNft(NftRepository $nftRepository, ?Nft $nft, Request $request): Response
    {
        $isCreation = false;
        if (is_null($nft)) {

            $isCreation = true;
            $nft = new Nft();
            $nft->setPurchaseDate(new \DateTimeImmutable());
        }

        $form = $this->createForm(NftType::class, $nft, ['isCreation' => $isCreation,]);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {


            $nftRepository->save($nft, true);

            $message = $isCreation == true ? 'nft created!' : 'nft updated!';
            $this->addFlash('success', $message);
            return $this->redirectToRoute('app_admin_nft');

        }

        return $this->render('nft/nftUpdate.html.twig', [
            'controller_name' => 'nftController',
            'isCreation' => $isCreation,
            'form' => $form->createView(),
        ]);



    }

    #[Route('/admin/nft', name: 'app_admin_nft')]
    public function Nfts(NftRepository $nftRepository): Response
    {
        $nfts = $nftRepository->findAll();


        return $this->render('admin/listNft.html.twig', [
            'controller_name' => 'AdminController',
            'nfts' => $nfts
        ]);
    }


}
