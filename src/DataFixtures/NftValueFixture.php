<?php

namespace App\DataFixtures;

use App\Entity\NftValue;
use App\Repository\NftModelRepository;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker;


class NftValueFixture extends Fixture implements DependentFixtureInterface
{
    private NftModelRepository $nftModelRepo;
    private Faker\Generator $faker;

    public function __construct(NftModelRepository $nftModelRepo)
    {
        $this->nftModelRepo = $nftModelRepo;
        $this->faker = Faker\Factory::create('fr_FR');
    }
    public function load(ObjectManager $manager): void
    {
        $nftModels = $this->nftModelRepo->findAll();


        foreach ($nftModels as $nftModel) {
            $initialePrice = $nftModel->getInitialPrice();

            for ($i = 0; $i < 7; $i++) {

                $date = new \DateTimeImmutable('today');
                $nftValue = new NftValue();
                $nftValue->setNftModel($nftModel);
                $day = 6 - $i;
                $offset = $day . " days";
                date_interval_create_from_date_string($offset);
                $date = $date->sub(date_interval_create_from_date_string($offset));

                if ($i == 0) {
                    $value = $initialePrice;
                } else {
                    $value = round(1.07 ** $i * $this->faker->randomFloat(2, $initialePrice * 0.8, $initialePrice * 1.2), 2);
                }
                $nftValue->setValue($value)->setValueDate($date);
                $manager->persist($nftValue);
            }

        }
        $manager->flush();
    }

    public function getDependencies()
    {
        return [
            NftModelFixtures::class
        ];
    }
}
