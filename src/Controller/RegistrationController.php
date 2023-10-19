<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\DBAL\Driver\PDO\Exception as PDOException;
use Doctrine\DBAL\Driver\PgSQL\Exception;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasher;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

class RegistrationController extends AbstractController
{
    #[Route('/app/register', name: 'app_register', methods: ['POST'])]
    public function register(Request $request, UserPasswordHasherInterface $hashedPass, EntityManagerInterface $entityManagerInterface, UserRepository $userRepository): Response
    {
        try {

            $data = json_decode($request->getContent());

            $checkUser = $userRepository->findBy(['email' => $data->email]);

            if($checkUser){

                return $this->json([
                    'message' => 'User already exists'
                ], Response::HTTP_BAD_REQUEST);
            }

            $user = new User();
            $user->setName($data->name);
            $user->setEmail($data->email);

            $user->setPassword(
                $hashedPass->hashPassword(
                    $user,
                    $data->password
                )
            );

            $user->setRoles(['ROLE_USER']);

            $entityManagerInterface->persist($user);
            $entityManagerInterface->flush();

            return $this->json([
                'message' => 'Record save success',
                'email' => $user->getEmail()
            ], Response::HTTP_CREATED);
            
        } catch (PDOException $e) {
            return $this->json([
                'message' => 'Record not save'
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
