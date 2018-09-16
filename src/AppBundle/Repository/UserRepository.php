<?php

namespace AppBundle\Repository;

use AppBundle\Entity\User;
use Doctrine\ORM\EntityRepository;

/**
 * Class UserRepository
 *
 * @package AppBundle\Repository
 */
class UserRepository extends EntityRepository
{
    /**
     * @param User $user
     *
     * @return User
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function save(User $user)
    {
        $updatedAt = new \DateTime();
        $user->setUpdatedAt($updatedAt);

        $this->_em->persist($user);
        $this->_em->flush();

        return $user;
    }

    /**
     * @param string $username
     *
     * @return User
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function getByUsername($username)
    {
        return $this
            ->createQueryBuilder('u')
            ->where('u.username = :username')
            ->setParameter('username', $username)
            ->getQuery()
            ->getOneOrNullResult();
    }
}
