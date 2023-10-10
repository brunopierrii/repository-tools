<?php

namespace App\Controller;

use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\CurrentUser;

class LoginController extends AbstractController
{
    #[Route('/app/login', name: 'app_login', methods: ['POST', 'GET'])]
    public function login(#[CurrentUser] ?User $user): Response
    {
        if(null === $user){
            return $this->json([
                'message' => 'missing credentials', 
            ], Response::HTTP_UNAUTHORIZED);
        }

        return $this->json([
            'user' =>  $user->getUserIdentifier()
        ]);
    }
}
