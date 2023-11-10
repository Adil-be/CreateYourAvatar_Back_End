<?php

namespace App\Controller;

use App\Entity\Nft;
use App\Repository\NftRepository;
use App\Repository\UserRepository;
use stdClass;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

class GaleryController extends AbstractController
{

    public function __construct(
        private Security $security,
        private UserRepository $userRepo,
        private NftRepository $nftRepo,
    ) {

    }

    #[IsGranted("ROLE_USER")]
    #[Route('/galery', name: 'app_galery')]
    public function index(): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED');
        $authenticatedUser = $this->security->getUser();

        try {
            $authenticatedUser = $this->security->getUser();
            $user = $this->userRepo->find($authenticatedUser->getId());

        } catch (error) {
            new JsonResponse(['message' => "Must be authenticated to access this route"], Response::HTTP_UNAUTHORIZED);
        }

        $nftsGalery = [];

        $nfts = [];

        $nfts = $this->nftRepo->findBy(
            ['user' => $user],
            ['purchaseDate' => 'DESC']
        );

        foreach ($nfts as $nft) {

            $model = $nft->getNftModel();
            $nftImages = $model->getNftImages()->toArray();
            $values = $model->getNftValues();

            $lastValue = count($values) - 1;

            $object = [
                'nft' => $nft,
                'nftModel' => $model,
                'nftImages' => $nftImages,
                'current' => $values[$lastValue],
                'previous' => $values[$lastValue - 1],
            ];

            $nftsGalery[] = $object;
        }

        return $this->json($nftsGalery);



    }
}
