<?php

namespace App\Controller;

use App\Entity\User;
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
    public function register(Request $request, UserPasswordHasherInterface $hashedPass, EntityManagerInterface $entityManagerInterface): Response
    {
        $data = $request->getContent();
        $data = json_decode($data);

        try {

            if($data){
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

                $data->id = $user->getId();
            } else {
                throw new Exception('Opss....');
            }
            
        } catch (Exception $e) {
            return $this->json([
                'message' => $e->getMessage()
            ], 404);
        }

        return $this->json([
            'email' => $user->getEmail()
        ]);
    }
}
