<?php

namespace App\Controller\Admin;

use App\Entity\NftCollection;
use App\Form\NftCollectionType;
use App\Repository\NftCollectionRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

class NftCollectionController extends AbstractController
{

    #[IsGranted('ROLE_ADMIN')]
    #[Route('/admin/nftCollection/{id}/delete', name: 'app_admin_nftCollection_delete')]
    public function deleteNftCollection(NftCollectionRepository $nftCollectionRepository, int $id, EntityManagerInterface $entityManager): Response
    {


        $nftCollection = $nftCollectionRepository->find($id);

        if (!empty($nftCollection)) {

            $nbrModel = count($nftCollection->getNftModels());

            if (0 == $nbrModel) {
                $entityManager->remove($nftCollection);
                $entityManager->flush();
                $this->addFlash('success', "nftCollection deleted!");
                return $this->redirectToRoute('app_admin_nftCollection');
            }else{
                $this->addFlash('danger', "you can't delete a collection associeted with models !");
            return $this->redirectToRoute('app_admin_nftCollection');
            }

        } else {
            $this->addFlash('danger', "nftCollection not found!");
            return $this->redirectToRoute('app_admin_nftCollection');
        }

    }

    #[Route('/admin/nftCollection/{id}/modify', name: 'app_admin_nftCollection_modify')]
    #[Route('/admin/nftCollection/new', name: 'app_admin_nftCollection_new')]
    public function ModifyNftCollection(NftCollectionRepository $nftCollectionrepository, ?NftCollection $nftCollection, Request $request): Response
    {
        $isCreation = false;
        if (is_null($nftCollection)) {

            $isCreation = true;
            $nftCollection = new NftCollection();
        }

        $form = $this->createForm(NftCollectionType::class, $nftCollection, ['isCreation' => $isCreation,]);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {


            $nftCollectionrepository->save($nftCollection, true);

            $message = $isCreation == true ? 'nftCollection created!' : 'nftCollection updated!';
            $this->addFlash('success', $message);
            return $this->redirectToRoute('app_admin_nftCollection');

        }

        return $this->render('nft_collection/nftCollectionUpdate.html.twig', [
            'controller_name' => 'nftCollectionController',
            'isCreation' => $isCreation,
            'form' => $form->createView(),
        ]);



    }

    #[Route('/admin/nftCollection', name: 'app_admin_nftCollection')]
    public function NftCollections(NftCollectionRepository $nftCollectionRepository): Response
    {
        $nftCollections = $nftCollectionRepository->findAll();


        return $this->render('admin/listNftCollection.html.twig', [
            'controller_name' => 'NftCollectionController',
            'nftCollections' => $nftCollections
        ]);
    }
}
