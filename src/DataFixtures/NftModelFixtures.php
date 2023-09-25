<?php

namespace App\DataFixtures;

use App\DataFixtures\CategoryFixtures;
use App\Entity\NftModel;
use App\Repository\CategoryRepository;
use App\Repository\NftCollectionRepository;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker;

class NftModelFixtures extends Fixture implements DependentFixtureInterface
{
    protected Faker\Generator $faker;

    protected CategoryRepository $categoryRepository;
    protected NftCollectionRepository $nftCollectionRepository;

    public function __construct(CategoryRepository $categoryRepository, NftCollectionRepository $nftCollectionRepository)
    {
        $this->categoryRepository = $categoryRepository;
        $this->nftCollectionRepository = $nftCollectionRepository;
        $this->faker = Faker\Factory::create('fr_FR');
    }
    public function load(ObjectManager $manager): void
    {
        $categories = $this->categoryRepository->findAll();

        // arms => gauntlet, bracer, glove
        // legs =>  greave, boot, shoe
        // chest => chestplate, jacket, vest
        // head => hat, helmet

        $arm = 'arms';
        $gauntlet = 'gauntlet';
        $bracer = 'bracer';
        $glove = 'glove';

        $leg = 'legs';
        $greave = 'greave';
        $boot = 'boot';
        $shoe = 'shoe';

        $chest = 'chest';
        $chestplate = 'chestplate';
        $jacket = 'jacket';
        $vest = 'vest';

        $head = 'head';
        $hat = 'hat';
        $helmet = 'helmet';



        $SteamPunk = 'SteamPunk';
        $HeroicFantasy = 'HeroicFantasy';
        $CyberTech = 'CyberTech';

        //$collections = ['SteamPunk', 'HeroicFantasy', 'CyberTech']; 

        $models = [
            'Void Guantlet' => [
                'categories' => [$arm, $gauntlet],
                'collection' => $HeroicFantasy,
            ],
            'Ice Guantlet' => [
                'categories' => [$arm, $gauntlet],
                'collection' => $HeroicFantasy,
            ],
            'Iron Fist' => [
                'categories' => [$arm, $bracer],
                'collection' => $SteamPunk,
            ],
            'Jacket of the Phoenix' => [
                'categories' => [$chest, $jacket],
                'collection' => $CyberTech,
            ],
            'Chestplate of light' => [
                'categories' => [$chest, $chestplate],
                'collection' => $HeroicFantasy,
            ],
            'Chestplate of strength' => [
                'categories' => [$chest, $chestplate],
                'collection' => $SteamPunk,
            ],
            'Helm of Swiftness' => [
                'categories' => [$head, $helmet],
                'collection' => $SteamPunk,
            ],
            'Helm of Precision' => [
                'categories' => [$head, $helmet],
                'collection' => $SteamPunk,
            ],
            'Helm of Magic Resist' => [
                'categories' => [$head, $helmet],
                'collection' => $HeroicFantasy,
            ],
            'Helm of the knignt' => [
                'categories' => [$head, $helmet],
                'collection' => $HeroicFantasy,
            ],
            'Helm of ether' => [
                'categories' => [$head, $helmet],
                'collection' => $CyberTech,
            ],
            'Boots of Courage' => [
                'categories' => [$leg, $boot],
                'collection' => $HeroicFantasy,
            ],
            'Greaves of Vigor' => [
                'categories' => [$leg, $greave],
                'collection' => $CyberTech,
            ],
        ];

        foreach ($models as $modelName => $data) {
            $collection = $this->nftCollectionRepository->findOneBy(['name' => $data['collection']]);

            $model = new NftModel();
            $model->setName($modelName)
                ->setNftCollection($collection)
                ->setDescription($this->faker->text())
                ->setInitialPrice($this->faker->randomFloat(2, 0, 2))
                ->setCreatedAt(\DateTimeImmutable::createFromMutable($this->faker->dateTime()));
            foreach ($data['categories'] as $categoryName) {
                $category = $this->categoryRepository->findOneBy(['name' => $categoryName]);

                $model->addCategory($category);
            }
            $manager->persist($model);
        }

        $manager->flush();
    }

    public function getDependencies()
    {
        return [
            CategoryFixtures::class,
            NftCollectionFixtures::class
        ];
    }
}