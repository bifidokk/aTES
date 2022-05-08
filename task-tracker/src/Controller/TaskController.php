<?php

namespace Task\Controller;

use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Task\Entity\Task;
use Task\Repository\TaskRepository;
use Task\Service\User\AssigneeResolver;

#[IsGranted('ROLE_USER')]
class TaskController
{
    private TaskRepository $taskRepository;
    private TokenStorageInterface $tokenStorage;
    private AssigneeResolver $assigneeResolver;
    private EntityManagerInterface $entityManager;

    public function __construct(
        TaskRepository $taskRepository,
        TokenStorageInterface $tokenStorage,
        AssigneeResolver $assigneeResolver,
        EntityManagerInterface $entityManager
    ) {
        $this->taskRepository = $taskRepository;
        $this->tokenStorage = $tokenStorage;
        $this->assigneeResolver = $assigneeResolver;
        $this->entityManager = $entityManager;
    }

    #[Route('/api/tasks', name: 'task_list')]
    public function taskList(Request $request): JsonResponse
    {
        $user = $this->tokenStorage->getToken()->getUser();

        if ($user->canManageTasks()) {
            $tasks = $this->taskRepository->findAll();
        } else {
            $tasks = $this->taskRepository->findBy([
                'assignee' => $user,
            ]);
        }

        $tasks = array_map(function (Task $task) {
            return $task->toArray();
        }, $tasks);

        return new JsonResponse($tasks);
    }

    #[Route('/api/task/create', name: 'create_task', methods: ['POST'])]
    public function createTask(Request $request): JsonResponse
    {
        $user = $this->tokenStorage->getToken()->getUser();
        $data = json_decode($request->getContent(), true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new HttpException(400, 'Invalid json');
        }

        if (!isset($data['name'])) {
            throw new HttpException(400, 'Invalid json');
        }

        $task = new Task();
        $task->setName($data['name']);
        $task->setOwner($user);
        $task->setAssignee($this->assigneeResolver->getRandomAssignee());

        $this->entityManager->persist($task);
        $this->entityManager->flush();

        // dispatch Task.Created event

        return new JsonResponse($task->toArray());
    }
}
