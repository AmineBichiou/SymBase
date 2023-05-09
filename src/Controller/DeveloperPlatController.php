<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Developer;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;

#[Route(defaults: ['cors' => true])]
class DeveloperPlatController extends AbstractController
{
    #[Route('/developer/plat', name: 'app_developer_plat')]
    public function index(): JsonResponse
    {
        return $this->json([
            'message' => 'Welcome to your new controller!',
            'path' => 'src/Controller/DeveloperPlatController.php',
        ]);
    }
    #[Route('/developer', name: 'app_developer',methods : ['GET'],options: ['cors' => true])]
public function listDevelopers(EntityManagerInterface $entityManager)
{
    $developers = $entityManager->getRepository(Developer::class)->findAll();

    $response = [];

    foreach ($developers as $developer) {
        $response[] = [
            'id' => $developer->getId(),
            'name' => $developer->getName(),
            'email' => $developer->getEmail(),
        ];
    }


    return $this->json($response);
}
#[Route('/developer', name: 'app_developer_add',methods : ['POST'])]
public function addDeveloper(Request $request, EntityManagerInterface $entityManager)
{
    $developer = new Developer();
    $developer->setName($request->request->get('name'));
    $developer->setEmail($request->request->get('email'));

    $entityManager->persist($developer);
    $entityManager->flush();

    return $this->json([
        'id' => $developer->getId(),
        'name' => $developer->getName(),
        'email' => $developer->getEmail(),
    ]);
}
#[Route('/developer/{id}', name: 'app_developer_delete',methods : ['DELETE'])]
public function deleteDeveloper($id ,  EntityManagerInterface $entityManager)
{
    $developer = $entityManager->getRepository(Developer::class)->find($id);

    if (!$developer) {
        throw $this->createNotFoundException('Developer not found');
    }

    $entityManager->remove($developer);
    $entityManager->flush();

    return $this->json([
        'message' => 'Developer deleted successfully',
    ]);
}
#[Route('/developer/{id}', name: 'app_developer_update',methods : ['PUT'])]

public function updateDeveloper($id, Request $request, EntityManagerInterface $entityManager): JsonResponse
{
    $developer = $entityManager->getRepository(Developer::class)->find($id);

    if (!$developer) {
        throw $this->createNotFoundException('Developer not found');
    }
     //dd($request->request->get('name'));

    $developer->setName($request->request->get('name'));
    $developer->setEmail($request->request->get('email'));

    $entityManager->flush();


    return $this->json([
        'id' => $developer->getId(),
        'name' => $developer->getName(),
        'email' => $developer->getEmail(),
    ]);
}


}
