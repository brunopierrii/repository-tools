<?php

namespace App\Controller;

use App\Entity\Tools;
use App\Repository\ToolsRepository;
use Doctrine\DBAL\Driver\PgSQL\Exception;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ToolsController extends AbstractController
{
    #[Route('/api/tools', name: 'tools_list', methods: ['GET'])]
    public function showTools(): Response
    {

        return $this->json([
            'sending' => 'tools list',
        ]);
    }

    #[Route('/api/tools/new', name: 'tools_new', methods: ['POST'])]
    // #[Route('/api/tools/new', name: 'tools_new', methods: ['GET'])]
    public function newTools(Request $request, EntityManagerInterface $entityManagerInterface, ToolsRepository $toolsRepository): Response
    {   
        try{
            $data =  $request->getContent();
            $data = json_decode($data);
    
            $tool = new Tools();
            $tool->setTitle($data->title);
            $tool->setLink($data->link);
            $tool->setDescription($data->description);
            
            $tags = implode(';', $data->tags);
            $tool->setTags($tags);
            
            $entityManagerInterface->persist($tool);
            $entityManagerInterface->flush();
            
        } catch(Exception $e){
            return $this->json([
                'message' => 'register not save!',
            ], 400);
        }

        return $this->json([
            'message' => 'register save',
        ], 201);
    }
}
