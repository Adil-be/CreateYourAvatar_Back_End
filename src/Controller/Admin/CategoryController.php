<?php

namespace App\Controller\Admin;


use App\Entity\Category;
use App\Form\CategoryType;
use App\Repository\CategoryRepository;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

class CategoryController extends AbstractController
{
    #[IsGranted('ROLE_ADMIN')]
    #[Route('/admin/category/{id}/delete', name: 'app_admin_category_delete')]
    public function deleteCategory(CategoryRepository $categoryRepository, int $id, EntityManagerInterface $entityManager): Response
    {


        $category = $categoryRepository->find($id);

        if (!empty($category)) {
            $entityManager->remove($category);
            $entityManager->flush();
            $this->addFlash('success', "category deleted!");
            return $this->redirectToRoute('app_admin_category');
        } else {
            $this->addFlash('danger', "category not found!");
            return $this->redirectToRoute('app_admin_category');
        }

    }

    #[Route('/admin/category/{id}/modify', name: 'app_admin_category_modify')]
    #[Route('/admin/category/new', name: 'app_admin_category_new')]
    public function ModifyCategory(CategoryRepository $categoryRepository, ?Category $category, Request $request): Response
    {
        $isCreation = false;
        if (is_null($category)) {

            $isCreation = true;
            $category = new Category();
        }

        $form = $this->createForm(CategoryType::class, $category, ['isCreation' => $isCreation,]);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {


            $categoryRepository->save($category, true);

            $message = $isCreation == true ? 'category created!' : 'category updated!';
            $this->addFlash('success', $message);
            return $this->redirectToRoute('app_admin_category');

        }

        return $this->render('category/categoryUpdate.html.twig', [
            'controller_name' => 'categoryController',
            'isCreation' => $isCreation,
            'form' => $form->createView(),
        ]);



    }

    #[Route('/admin/category', name: 'app_admin_category')]
    public function Categories(CategoryRepository $categoryRepository): Response
    {
        $categories = $categoryRepository->findAll();


        return $this->render('admin/listCategory.html.twig', [
            'controller_name' => 'AdminController',
            'categories' => $categories
        ]);
    }
}
