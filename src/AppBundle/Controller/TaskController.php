<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Task;
use AppBundle\Entity\User;
use AppBundle\Form\TaskType;
use AppBundle\Repository\TaskRepository;
use FOS\RestBundle\Controller\FOSRestController;
use Symfony\Component\HttpFoundation\JsonResponse;
use FOS\RestBundle\Controller\Annotations as Rest;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class TaskController
 *
 * @package AppBundle\Controller
 *
 */
class TaskController extends FOSRestController
{
    /**
     * @param $id
     * @Rest\Get("/task/{id}", name="get_task_by_id")
     *
     * @return JsonResponse
     */
    public function getTaskAction($id)
    {
        $entityManager = $this->getDoctrine()->getManager();
        /** @var TaskRepository $taskRepository */
        $taskRepository = $entityManager->getRepository(Task::class);

        $task = $taskRepository->findTaskById($id);

        return new JsonResponse($task);
    }

    /**
     * @param Request $request
     *
     * @Rest\Post("/task", name="post_new_task")
     *
     * @return JsonResponse
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function postTaskAction(Request $request)
    {
        $user          = $this->getUser();
        $entityManager = $this->getDoctrine()->getManager();
        /** @var TaskRepository $taskRepository */
        $taskRepository = $entityManager->getRepository(Task::class);

        $task = new Task();
        $task->setUser($user);

        $form = $this->createForm(TaskType::class, $task);

        $form->handleRequest($request);

        if ($form->isValid()) {
            $taskRepository->save($task);

            return new JsonResponse(['success' => true, 'taskId' => $task->getId(),]);
        }

        return new JsonResponse(['success' => false, 'taskId' => null,]);
    }

    /**
     * @param Request $request
     * @param Task    $task
     *
     * @Rest\Put("/task/{id}", name="update_task")
     *
     * @return JsonResponse
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function updateTaskAction(Request $request, Task $task)
    {
        /** @var User $user */
        $user = $this->getUser();

        if($user->getId() !== $task->getUser()->getId()) {
            throw $this->createAccessDeniedException('You can\'t update this task!');
        }

        $managerRegistry = $this->getDoctrine();
        /** @var TaskRepository $repo */
        $repo = $managerRegistry->getRepository(Task::class);

        $form = $this->createForm(TaskType::class, $task, [
            'method' => Request::METHOD_PUT,
        ]);

        $form->handleRequest($request);

        if ($form->isValid()) {
            $repo->save($task);

            return new JsonResponse(['success' => true, 'taskId' => $task->getId(),]);
        }

        return new JsonResponse(['success' => false, 'taskId' => null,]);
    }

    /**
     * @param Request $request
     * @param Task    $task
     *
     * @Rest\Delete("/task/{id}", name="delete_task")
     *
     * @return JsonResponse
     */
    public function deleteTaskAction(Request $request, Task $task)
    {
        if (!$task) {
            throw $this->createNotFoundException('No task found with current ID');
        }

        /** @var User $user */
        $user = $this->getUser();

        if($user->getId() !== $task->getUser()->getId()) {
            throw $this->createAccessDeniedException('You can\'t delete this task!');
        }

        $managerRegistry = $this->getDoctrine();
        /** @var TaskRepository $repo */
        $repo = $managerRegistry->getRepository(Task::class);

        $repo->deleteTask($task);

        return new JsonResponse([]);
    }

    private function getErrorMessages(\Symfony\Component\Form\Form $form) {
        $errors = array();

        foreach ($form->getErrors() as $key => $error) {
            if ($form->isRoot()) {
                $errors['#'][] = $error->getMessage();
            } else {
                $errors[] = $error->getMessage();
            }
        }

        foreach ($form->all() as $child) {
            if (!$child->isValid()) {
                $errors[$child->getName()] = $this->getErrorMessages($child);
            }
        }

        return $errors;
    }
}
