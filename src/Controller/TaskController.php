<?php

namespace App\Controller;

use App\Entity\Task;
use App\Form\TaskType;
use App\Repository\TaskRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class TaskController extends AbstractController
{

	private TaskRepository $taskRepository;
	private EntityManagerInterface $entityManager;

	public function __construct(
		TaskRepository $taskRepository,
		EntityManagerInterface $entityManager
	) {
		$this->taskRepository = $taskRepository;
		$this->entityManager = $entityManager;
	}

	#[Route('/task', name: 'app_task')]
	public function index(Request $request): Response
	{
		$newTask = new Task();
		$addTaskForm = $this->createForm(TaskType::class, $newTask);
		$addTaskForm->handleRequest($request);
		$tasks = $this->taskRepository->findAll();
		if ($addTaskForm->isSubmitted() && $addTaskForm->isValid()) {
			$this->entityManager->persist($newTask);
			$this->entityManager->flush();
			return $this->redirectToRoute('app_task');
		}
		return $this->render('task/index.html.twig', [
			'controller_name' => 'TaskController',
			'addTaskForm' => $addTaskForm->createView(),
			'tasks' => $tasks,
		]);

	}
}
