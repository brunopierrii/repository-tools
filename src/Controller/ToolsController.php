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
    #[Route('/tools', name: 'tools_list_all', methods: ['GET'])]
    public function showToolsAll(ToolsRepository $toolsRepository): Response
    {   
        $toolsArr = $toolsRepository->findAll(['id' => 'ASC']);
        
        if($toolsArr){
            
            $response = [];
            foreach ($toolsArr as $tools) {

                $response[] = [
                    'id' => $tools->getId(),
                    'title' => $tools->getTitle(),
                    'link' => $tools->getLink(),
                    'description' => $tools->getDescription(),
                    'tags' => explode(';', $tools->getTags()),
                ];
            }
            return $this->json($response);
        }

        return $this->json(['']);
    }

    #[Route('/tools/new', name: 'tools_new', methods: ['POST'])]
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

            $data->id = $tool->getId();
            
        } catch(Exception $e){
            return $this->json([
                'message' => 'register not save!',
            ], 400);
        }

        return $this->json($data, 201);
    }

    #[Route('/tools/{tag}', name: 'tools_find_tag', methods: ['GET'])]
    public function findByTag(string $tag, ToolsRepository $toolsRepository, Request $request): Response
    {
        $toolsArr = $toolsRepository->findByTagName($tag);
        
        if($toolsArr){
            
            $response = [];
            foreach ($toolsArr as $tools) {

                $response[] = [
                    'id' => $tools->getId(),
                    'title' => $tools->getTitle(),
                    'link' => $tools->getLink(),
                    'description' => $tools->getDescription(),
                    'tags' => explode(';', $tools->getTags()),
                ];
            }

            return $this->json($response);
        }

        return $this->json([]);
    }

    #[Route('/tools/edit/{id}', name: 'tools_edit', methods: ['PUT'])]
    public function editTools(int $id, EntityManagerInterface $entityManager, ToolsRepository $toolsRepository, Request $request): Response
    {
        $tool = $toolsRepository->find($id);

        try{
            if(null !== $tool){
                $data = $request->getContent();
                $data = json_decode($data);
    
                $tool->setTitle($data->title);
                $tool->setLink($data->link);
                $tool->setDescription($data->description);
                
                $tags = implode(';', $data->tags);
                $tool->setTags($tags);
                
                // $entityManager->persist($tool);
                $entityManager->flush();
    
                $data->id = $tool->getId();
            } else {
                throw new Exception("record not found!");
            }

        } catch(Exception $e) {
            return $this->json([
                'message' => $e->getMessage()
            ], 404);
        }
        
        return $this->json($data);
    }

    #[Route('/tools/delete/{id}', name: 'tools_delete', methods: ['delete'])]
    public function deleteTools(int $id, EntityManagerInterface $entityManager, ToolsRepository $toolsRepository): Response
    {
        $tool = $toolsRepository->find($id);

        try {

            if(null !== $tool){
                $entityManager->remove($tool);
                $entityManager->flush();
            } else {
                throw new Exception("record not found!");
            }

            
        } catch (Exception $e) {
            return $this->json([
                'message' => $e->getMessage()
            ], 404);
        }

        return $this->json('{}');

    }
}
