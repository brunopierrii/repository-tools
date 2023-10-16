<?php

namespace App\Controller;

use App\Entity\Tools;
use App\Entity\User;
use App\Repository\ToolsRepository;
use App\Repository\UserRepository;
use Doctrine\DBAL\Driver\PgSQL\Exception;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\CurrentUser;

class ToolsController extends AbstractController
{
    #[Route('/tools', name: 'tools_list_all', methods: ['GET'])]
    public function showToolsAll(ToolsRepository $toolsRepository, #[CurrentUser] ?User $user): Response
    {   
        $tools = $toolsRepository->findBy(['user_tool' => $user->getId()], ['id' => 'ASC']);

        $response = [];
        foreach ($tools as $tool) {

            $response[] = [
                'id' => $tool->getId(),
                'title' => $tool->getTitle(),
                'link' => $tool->getLink(),
                'description' => $tool->getDescription(),
                'tags' => explode(';', $tool->getTags()),
            ];
        }

        return $this->json($response);
    }

    #[Route('/tools/new', name: 'tools_new', methods: ['POST'])]
    public function newTool(
        #[CurrentUser] ?User $user,
        Request $request, 
        EntityManagerInterface $entityManagerInterface, 
        UserRepository $userRepository
    ): Response
    {   
        try{
            $data = json_decode($request->getContent());

            if(null === $data){
                throw new Exception('Invalid JSON data');
            }
    
            $tool = new Tools();
            $tool->setTitle($data->title);
            $tool->setLink($data->link);
            $tool->setDescription($data->description);
            
            if(!empty($data->tags) && is_array($data->tags)){
                $tags = implode(';', $data->tags);
                $tool->setTags($tags);
            } else {
                $tool->setTags('');
            }

            $user = $userRepository->find($user->getId());
            $tool->setUserTool($user);
            
            $entityManagerInterface->persist($tool);
            $entityManagerInterface->flush();

            $response = [
                'id' => $tool->getId(),
                'title' => $tool->getTitle(),
                'link' => $tool->getLink(),
                'description' => $tool->getDescription(),
                'tags' => explode(';', $tool->getTags()),
                'user' => [
                    'id' => $tool->getUserTool()->getId(),
                    'name' => $tool->getUserTool()->getName(),
                    'email' => $tool->getUserTool()->getEmail(),
                    'roles' => $tool->getUserTool()->getRoles()
                ]
            ];

            return $this->json($response, Response::HTTP_CREATED);            
        } catch(Exception $e){
            return $this->json([
                'message' => 'register not save!',
                'error' => $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    #[Route('/tools/tag/{tag}', name: 'tools_find_tag', methods: ['GET'])]
    public function findByTag(string $tag, ToolsRepository $toolsRepository, #[CurrentUser] ?User $user): Response
    {
        $tools = $toolsRepository->findByTagName($tag, $user->getId());

        if($tools){
            
            $response = [];
            foreach ($tools as $tool) {

                $response[] = [
                    'id' => $tool->getId(),
                    'title' => $tool->getTitle(),
                    'link' => $tool->getLink(),
                    'description' => $tool->getDescription(),
                    'tags' => explode(';', $tool->getTags()),
                ];
            }

            return $this->json($response);
        }

        return $this->json([]);
    }

    #[Route('/tools/edit/{id}', name: 'tools_edit', methods: ['PUT'])]
    public function editTool(
        int $id, 
        EntityManagerInterface $entityManager, 
        ToolsRepository $toolsRepository, 
        Request $request
    ): Response
    {
        $tool = $toolsRepository->find($id);

        if(!$tool){
            return $this->json(['message' => 'Record not found'], Response::HTTP_NOT_FOUND);
        }

        $data = json_decode( $request->getContent() );

        if(!$data){
            return $this->json(['message' => 'Invalid JSON data'], Response::HTTP_BAD_REQUEST);
        }

        if(!property_exists($data, 'title') || !property_exists($data, 'link')){
            return $this->json(['message' => 'missing required fields'], Response::HTTP_BAD_REQUEST);
        }

        $tool->setTitle($data->title);
        $tool->setLink($data->link);
        $tool->setDescription($data->description ?? $tool->getDescription());
        
        if(property_exists($data, 'tags')){
            $tags = implode(';', $data->tags);
            $tool->setTags($tags);
        }

        $entityManager->flush();

        $response[] = [
            'id' => $tool->getId(),
            'title' => $tool->getTitle(),
            'link' => $tool->getLink(),
            'description' => $tool->getDescription(),
            'tags' => explode(';', $tool->getTags()),
        ];

        return $this->json($response);
    }

    #[Route('/tools/delete/{id}', name: 'tools_delete', methods: ['delete'])]
    public function deleteTools(int $id, EntityManagerInterface $entityManager, ToolsRepository $toolsRepository): Response
    {
        $tool = $toolsRepository->find($id);
        
        if(null !== $tool){
            $entityManager->remove($tool);
            $entityManager->flush();

            return $this->json(['message' => 'Record deleted'], Response::HTTP_OK);
        } 

        return $this->json(['message' => 'Record not deleted'], Response::HTTP_NOT_FOUND);
    }
}
