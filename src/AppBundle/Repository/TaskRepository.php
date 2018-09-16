<?php

namespace AppBundle\Repository;

use AppBundle\Entity\Task;
use Doctrine\ORM\EntityRepository;

/**
 * Class TaskRepository
 *
 * @package AppBundle\Repository
 */
class TaskRepository extends EntityRepository
{
    /**
     * @param Task $task
     *
     * @return Task
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function save(Task $task)
    {
        $updatedAt = new \DateTime();
        $task->setCreatedAt($updatedAt);

        $this->_em->persist($task);
        $this->_em->flush();

        return $task;
    }

    /**
     * @param $taskId
     *
     * @return array
     */
    public function findTaskById($taskId)
    {
        return $this->createQueryBuilder('q')
            ->where('q.id = :id')
            ->setParameters([
                'id' => $taskId,
            ])
            ->getQuery()
            ->getArrayResult();
    }

    /**
     * @param Task $task
     */
    public function deleteTask(Task $task)
    {
        $params = [
            'id' => $task->getId(),
        ];

        $qb = $this->createQueryBuilder('c')
            ->delete()
            ->where('c.id = :id');

        $qb->setParameters($params)
            ->getQuery()
            ->execute();
    }
}
