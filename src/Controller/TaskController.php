<?php
namespace App\Controller;

use App\Entity\Task;
use App\Form\TaskFormType;
use App\Repository\TaskRepository;
use DateTime;
use Knp\Component\Pager\PaginatorInterface;
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

    // Ajouter une nouvelle tache
    #[Route('/task/new', name: 'add_task', methods: ['GET', 'POST'])]
    public function addTask(Request $request): Response
    {
        $task = new Task();
        $form = $this->createForm(TaskFormType::class, $task);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Traiter les données du formulaire
            $task->setDateDeCreation(new DateTime());
            $task->setEtat("En cours");
            $this->entityManager->persist($task);
            $this->entityManager->flush();

            $this->addFlash('message', 'La tâche a été ajoutée avec succès.');

            return $this->redirectToRoute('add_task'); // Redirect to the "add_task" route (create page)
        }

        return $this->render('task/create.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    // Afficher la liste des taches avec filtrage et pagination
    #[Route('/tasks', name: 'task_list', methods: ['GET'])]
    public function listTasks(Request $request, PaginatorInterface $paginator): Response
    {
        $filter = $request->query->get('filter', '');

        $page = $request->query->getInt('page', 1);

        if ($page < 1) {
            $page = 1;
        }

        $queryBuilder = $this->entityManager->getRepository(Task::class)->createQueryBuilder('t');

        if ($filter) {
            $queryBuilder->andWhere('t.Etat = :state')
                ->setParameter('state', $filter);
        }
        $queryBuilder->orderBy('t.DateDeCreation', 'DESC');

        $query = $queryBuilder->getQuery();

        // Pagination des résultats
        $pagination = $paginator->paginate(
            $query,
            $page,
            3,
        );

        return $this->render('task/index.html.twig', [
            'pagination' => $pagination,
            'filter' => $filter,
        ]);
    }

    // Marquer une tache comme terminée
    #[Route('/task/{id}/complete', name: 'task_complete', methods: ['PUT'])]
    public function complete(Task $task): JsonResponse
    {
        $task->setEtat("Terminée");
        $this->entityManager->flush();

        return $this->json(['message' => 'Tache Marquer Comme Terminée.']);
    }

    // Supprimer une tache (la marquer comme annulée)
    #[Route('/task/{id}', name: 'task_delete', methods: ['PUT'])]
    public function delete(Task $task): JsonResponse
    {
        $task->setEtat("Annulée");
        $this->entityManager->flush();

        return $this->json(['message' => 'Tache Supprimé Avec Success.']);
    }
}
