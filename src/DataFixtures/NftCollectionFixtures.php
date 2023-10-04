<?php

namespace App\DataFixtures;

use App\Entity\NftCollection;
use App\Repository\NftModelRepository;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Faker;

class NftCollectionFixtures extends Fixture
{
    private NftModelRepository $nftModelRepository;

    private Faker\Generator $faker;

    public function __construct(NftModelRepository $nftModelRepository)
    {
        $this->nftModelRepository = $nftModelRepository;
        $this->faker = Faker\Factory::create('fr_FR');
    }
    public function load(ObjectManager $manager): void
    {

        $fileSystem = new Filesystem();
        $destination = __DIR__ . '/../../public/images/collectionImages/';
        $folder = 'images/collectionImages/';
        $collections = ['SteamPunk', 'HeroicFantasy', 'CyberTech'];

        $init = $this->deleteDir($destination);


        foreach ($collections as $collectionName) {
            $nftCollection = new NftCollection();
            $nftCollection->setName($collectionName)
                ->setDescription($this->faker->text())
                ->setPath($folder . $nftCollection->getName());

            $imageFile = $this->createImage($nftCollection->getName());
            $fileSystem->copy(
                $imageFile->getRealPath(),
                $destination . $imageFile->getFilename()
            );
            $manager->persist($nftCollection);
        }

        $manager->flush();
    }

    public function createImage(string $name): UploadedFile
    {

        $folder = __DIR__ . '/../../var/images/collectionImages/';
        $imageName = str_replace(' ', '_', $name);
        $imageName = $imageName . '.jpg';
        $src = $folder . $imageName;

        return new UploadedFile(
            path: $src,
            originalName: $imageName,
            mimeType: 'image/jpeg',
            test: true
        );
    }

    public function deleteDir(string $dir): bool
    {
        $files = [];
        if (false !== scandir($dir)) {
            $files = array_diff(scandir($dir), ['.', '..']);
        }

        foreach ($files as $file) {
            (is_dir("$dir/$file")) ? $this->deleteDir("$dir/$file") : unlink("$dir/$file");
        }

        // https://www.php.net/manual/en/function.rmdir.php
        return rmdir($dir);
    }
}