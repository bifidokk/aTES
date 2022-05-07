<?php

namespace Task\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

#[IsGranted('ROLE_ADMIN')]
class TaskController
{
    #[Route('/api/tasks', name: 'task_list')]
    public function taskList(Request $request): JsonResponse
    {
        return new JsonResponse([]);
    }
}
