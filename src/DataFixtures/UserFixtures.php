<?php

namespace App\DataFixtures;

use App\Entity\User;
use App\Entity\UserImage;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserFixtures extends Fixture
{
    private $passwordHasher;
    public function __construct(UserPasswordHasherInterface $passwordHasher)
    {
        $this->passwordHasher = $passwordHasher;
    }

    public function load(ObjectManager $manager): void
    {
        $TatyJosy = new User();
        $TatyJosy->setRoles(['ROLE_ADMIN']);
        $TatyJosy->setEmail("TatyJosy@gmail.com");
        $TatyJosy->setUsername('TatyJosy');
        $TatyJosy->setPassword(
            $this->passwordHasher->hashPassword(
                $TatyJosy,
                '1234'
            )
        );

        $admin = new User();
        $admin->setEmail("adel-b@gmail.com");
        $admin->setRoles(['ROLE_ADMIN']);
        $admin->setUsername('adel');
        $admin->setPassword(
            $this->passwordHasher->hashPassword(
                $admin,
                '007'
            )
        );

        $toto = new User();
        $toto->setEmail("toto@gmail.com");
        $toto->setUsername('toto');
        $toto->setPassword(
            $this->passwordHasher->hashPassword(
                $toto,
                '1234'
            )
        );

        // images Users
        $users = [$toto, $TatyJosy, $admin];
        $fileSystem = new Filesystem();
        $folder = 'images/userImages/';
        $destination = __DIR__ . '/../../public/images/userImages/';

        $init = $this->deleteDir($destination);

    

        foreach ($users as $user) {

            $imageFile = $this->createImage($user->getUsername());
            $fileSystem->copy(
                $imageFile->getRealPath(),
                $destination . $imageFile->getFilename()
            );

            $userImage = new UserImage();
            $userImage
                ->setSize($imageFile->getSize())
                ->setPath($folder . $imageFile->getFilename())
                ->setName($imageFile->getFilename());

            $user->setUserImage($userImage);

            $manager->persist($userImage);

            $manager->persist($user);
        }
        $manager->flush();
    }

    public function createImage(string $name): UploadedFile
    {
        $folder = __DIR__ . '/../../var/images/userImages/';
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