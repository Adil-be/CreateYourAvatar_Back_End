<?php

namespace App\Controller\Admin;

use App\Entity\NftImage;
use App\Entity\NftModel;
use App\Entity\NftValue;
use App\Form\NftModelType;
use App\Repository\NftModelRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

class NftModelController extends AbstractController
{
    #[IsGranted('ROLE_ADMIN')]
    #[Route('/admin/nftModel/{id}/delete', name: 'app_admin_nftModel_delete')]
    public function deleteNftModel(NftModelRepository $nftModelRepository, int $id, EntityManagerInterface $entityManager): Response
    {


        $nftModel = $nftModelRepository->find($id);

        if (!empty($nftModel)) {


            $nbr = count($nftModel->getNfts());


            if ($nbr == 0) {
                $entityManager->remove($nftModel);
                $entityManager->flush();

                $this->addFlash('success', "nftModel deleted!");
                return $this->redirectToRoute('app_admin_nftModel');
            } else {
                $this->addFlash('danger', "You can't delete a model used by nfts!");
                return $this->redirectToRoute('app_admin_nftModel');
            }
        } else {
            $this->addFlash('danger', "nft not found!");
            return $this->redirectToRoute('app_admin_nftModel');
        }

    }

    #[Route('/admin/nftModel/{id}/modify', name: 'app_admin_nftModel_modify')]
    #[Route('/admin/nftModel/new', name: 'app_admin_nftModel_new')]
    public function ModifyNftModel(NftModelRepository $nftModelRepository, ?NftModel $nftModel, Request $request): Response
    {
        $isCreation = false;
        if (is_null($nftModel)) {

            $isCreation = true;
            $nftModel = new NftModel();
            // $nftImage = new NftImage();
            // $nftModel->setNftImage($nftImage);
        }

        $form = $this->createForm(NftModelType::class, $nftModel, ['isCreation' => $isCreation,]);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            if ($isCreation) {

                $nftModel = $this->CreateValue($nftModel);
            }

            $nftModelRepository->save($nftModel, true);

            $message = $isCreation == true ? 'nftModel created!' : 'nftModel updated!';
            $this->addFlash('success', $message);
            return $this->redirectToRoute('app_admin_nftModel');

        }

        return $this->render('nft_model/nftModelUdapte.html.twig', [
            'controller_name' => 'nftModelController',
            'isCreation' => $isCreation,
            'form' => $form->createView(),
        ]);



    }

    #[Route('/admin/nftModel', name: 'app_admin_nftModel')]
    public function NftModels(NftModelRepository $nftModelRepository): Response
    {
        $nftModels = $nftModelRepository->findAll();


        return $this->render('admin/listNftModel.html.twig', [
            'controller_name' => 'AdminController',
            'nftModels' => $nftModels
        ]);
    }

    private function CreateValue(NftModel $nftModel)
    {
        $initialePrice = $nftModel->getInitialPrice();

        for ($i = 0; $i < 7; $i++) {

            $date = new \DateTimeImmutable('today');
            $nftValue = new NftValue();
            $nftValue->setNftModel($nftModel);
            $day = 6 - $i;
            $offset = $day . " days";
            date_interval_create_from_date_string($offset);
            $date = $date->sub(date_interval_create_from_date_string($offset));

            $value = $initialePrice;

            $nftValue->setValue($value)->setValueDate($date);

            $nftModel->addNftValue($nftValue);

        }
        return $nftModel;
    }
}
