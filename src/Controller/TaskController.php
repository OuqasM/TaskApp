<?php
namespace App\Controller;

use App\Entity\Task;
use App\Repository\TaskRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;

class TaskController extends AbstractController
{
    private $entityManager;
    private $taskrepository;

    public function __construct(EntityManagerInterface $entityManager, TaskRepository $taskrepository)
    {
        $this->entityManager = $entityManager;
        $this->taskrepository = $taskrepository;
    }

    #[Route('/tasks', name: 'task_list', methods: ['GET'])]
    public function index(Request $request): Response
    {
        $page = $request->query->get('page', 1); // hna comment
            
        $perPage = 3; // Nombre de taches par page
    
        $repository = $this->taskrepository;
        $tasks = $repository->findBy([], ['DateDeCreation' => 'DESC'], $perPage, ($page - 1) * $perPage);
    
        return $this->render('task/index.html.twig', [
            'tasks' => $tasks,
        ]);
    }

    // #[Route('/task/{id}/complete', name: 'task_complete', methods: ['POST'])]
    // public function complete(Task $task): JsonResponse
    // {
    //     $task->setCompleted(true);
    //     $this->entityManager->flush();

    //     return $this->json(['message' => 'Task marked as completed.']);
    // }

    // #[Route('/task/{id}', name: 'task_delete', methods: ['DELETE'])]
    // public function delete(Task $task): JsonResponse
    // {
    //     $this->entityManager->remove($task);
    //     $this->entityManager->flush();

    //     return $this->json(['message' => 'Task deleted.']);
    // }

}
