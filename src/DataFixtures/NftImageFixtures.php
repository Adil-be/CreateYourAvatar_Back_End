<?php

namespace App\DataFixtures;

use App\Entity\NftImage;
use App\Repository\NftModelRepository;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\File\UploadedFile;


class NftImageFixtures extends Fixture implements DependentFixtureInterface
{
    private NftModelRepository $nftModelRepository;

    public function __construct(NftModelRepository $nftModelRepository)
    {

        $this->nftModelRepository = $nftModelRepository;
    }


    public function load(ObjectManager $manager): void
    {

        $nftModels = $this->nftModelRepository->findAll();
        $fileSystem = new Filesystem();
        $destination = __DIR__ . '/../../public/images/nftImages/';

        $init = $this->deleteDir($destination);

        foreach ($nftModels as $nftModel) {
            $imageFile = $this->createImage($nftModel->getName());
            $fileSystem->copy(
                $imageFile->getRealPath(),
                $destination . $imageFile->getFilename()
            );

            $nftImage = new NftImage();
            $nftImage
                ->setSize($imageFile->getSize())
                ->setPath($destination . $imageFile->getFilename().'.png')
                ->setName($imageFile->getFilename())
                ->setNftModel($nftModel);

            $manager->persist($nftImage);

        }
        $manager->flush();
    }
    public function getDependencies()
    {
        return [
            NftModelFixtures::class,
        ];
    }
    public function createImage(string $name): UploadedFile
    {
        $folder = __DIR__ . '/../../var/images/nftImages/';
        $imageName = str_replace(' ', '_', $name);
        $imageName = $imageName . '.png';
        $src = $folder . $imageName;

        return new UploadedFile(
            path: $src,
            originalName: $imageName,
            mimeType: 'image/png',
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