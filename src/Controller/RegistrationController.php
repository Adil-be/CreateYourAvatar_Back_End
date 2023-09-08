<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

class RegistrationController extends AbstractController
{
    #[Route('/register', name: 'app_registration', methods: 'POST')]
    public function register(
        Request $request,
        UserPasswordHasherInterface $passwordHasher,
        UserRepository $userRepository,
        EntityManagerInterface $manager
    ): Response {

        $jsonData = $request->getContent();

        $userData = json_decode($jsonData);


        $email = $userData->email;
        $password = $userData->password;


        if (!empty($email) && !empty($password)) {

            $user = new User();
            $hashedPassword = $passwordHasher->hashPassword(
                $user,
                $password
            );
            $user->setEmail($email)
                ->setPassword($hashedPassword);

            if (isset($userData->username) && !empty($userData->username)) {
                $user->setUsername($userData->username);
            }
            if (isset($userData->firstname) && !empty($userData->firstname)) {
                $user->setFirstname($userData->firstname);
            }
            if (isset($userData->lastname) && !empty($userData->lastname)) {
                $user->setLastname($userData->lastname);
            }
            if (isset($userData->birthday) && !empty($userData->birthday)) {

                $date = new \DateTimeImmutable($userData->birthday);
                $user->setBirthday($date);
            }
            if (isset($userData->gender) && !empty($userData->gender)) {
                $user->setGender($userData->gender);
            }

            $result = $userRepository->findOneBy(['email' => $email]);

            if (empty($result)) {
                $manager->persist($user);
                $manager->flush();

                $data = [
                    'message' => "l'utilisateur a bien été enregistré !",
                    "success" => true,
                ];
                $response = new JsonResponse($data);
            } else {
                $data =
                    [
                        'message' => 'Email already exist',
                        "success" => false
                    ]
                ;
                $response = new JsonResponse($data);
            }
        } else {
            $data = ['message' => 'Some field are invalid or missing', "success" => false];
            $response = new JsonResponse(
                $data
            );
        }

        return $response;
    }
}