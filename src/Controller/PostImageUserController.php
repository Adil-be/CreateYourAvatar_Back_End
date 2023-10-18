<?php

namespace App\Controller;

use App\Entity\UserImage;
use App\Repository\UserRepository;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;



class PostImageUserController extends AbstractController
{
    public function __construct(
        private Security $security,
        private UserRepository $userRepo
    ) {
    }

    public function __invoke(Request $request, int $id, EntityManagerInterface $entityManager)
    {

        $this->denyAccessUnlessGranted('IS_AUTHENTICATED');
        try {
            $authenticatedUser = $this->security->getUser();
            $user = $this->userRepo->find($authenticatedUser->getId());
            
        } catch (error) {
            new JsonResponse(['message' => "Must be authenticated to access this route"], Response::HTTP_UNAUTHORIZED);
        }

        if ($user->getId() == $id) {
            $uploadedFile = $request->files->get('file');
            if (!$uploadedFile) {
                return new JsonResponse(['message' => '"file" is required'], Response::HTTP_BAD_REQUEST);
            }

            $userImage = new UserImage();
            $userImage->setFile($uploadedFile);

            $user->setUserImage($userImage);
            $entityManager->persist($user);

            return $userImage;
        } else {
            return new JsonResponse(['message' => "Your are not authorized to perform this action"], Response::HTTP_UNAUTHORIZED);
        }

    }

}