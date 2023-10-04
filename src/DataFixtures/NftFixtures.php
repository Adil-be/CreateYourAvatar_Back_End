<?php

namespace App\DataFixtures;

use App\Entity\Nft;
use App\Entity\NftModel;
use App\Entity\User;
use App\Repository\NftModelRepository;
use App\Repository\UserRepository;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker;

class NftFixtures extends Fixture implements DependentFixtureInterface
{
    private UserRepository $userRepository;
    private NftModelRepository $nftModelRepository;

    private Faker\Generator $faker;

    public function __construct(NftModelRepository $nftModelRepository, UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
        $this->nftModelRepository = $nftModelRepository;
        $this->faker = Faker\Factory::create('fr_FR');
    }

    public function load(ObjectManager $manager): void
    {
        $nftModels = $this->nftModelRepository->findAll();
        $admin = $this->userRepository->findOneBy(['email' => 'adil-b@gmail.com']);
        $taty = $this->userRepository->findOneBy(['email' => 'TatyJosy@gmail.com']);
        $toto = $this->userRepository->findOneBy(['email' => 'toto@gmail.com']);

        $users = [$taty, $admin];

        foreach ($users as $user) {
            foreach ($nftModels as $nftModel) {
                $max = $this->faker->numberBetween(1, 2);
                for ($i = 0; $i < $max; $i++) {
        
                    $nft = $this->createNft($nftModel, $user);
                    $nft->setInSale($this->faker->boolean(66));
                    $nft->setFeatured($this->faker->boolean(20));
                    $manager->persist($nft);
                }
            }
        }
        foreach ($nftModels as $nftModel) {
            $max = $this->faker->numberBetween(0, 1);
            for ($i = 0; $i < $max; $i++) {
                # code...
                $nft = $this->createNft($nftModel, $toto);
                $nft->setInSale($this->faker->boolean());
                $nft->setFeatured($this->faker->boolean(20));

                $manager->persist($nft);
            }
        }
        $manager->flush();
    }

    public function createNft(NftModel $nftModel, User $user): Nft
    {
        $nft = new Nft();
        $nft->setBuyingPrice($nftModel->getInitialPrice());
        $nft->setPurchaseDate(\DateTimeImmutable::createFromMutable($this->faker->dateTime()));
        $nft->setSellingPrice($this->faker->randomFloat(2, 0, 2));
        $nft->setToken($this->faker->md5());
        $nft->setNftModel($nftModel);
        $nft->setUser($user);
        return $nft;
    }

    public function getDependencies()
    {
        return [
            UserFixtures::class,
            NftModelFixtures::class
        ];
    }
}