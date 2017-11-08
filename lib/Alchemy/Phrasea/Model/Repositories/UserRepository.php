<?php

/*
 * This file is part of Phraseanet
 *
 * (c) 2005-2014 Alchemy
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Alchemy\Phrasea\Model\Repositories;

use Doctrine\ORM\EntityRepository;
use Alchemy\Phrasea\Model\Entities\User;

/**
 * User
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class UserRepository extends EntityRepository
{
    /**
     * Finds admins.
     *
     * @return User[]
     */
    public function findAdmins()
    {
        $qb = $this->createQueryBuilder('u');

        $qb->where($qb->expr()->eq('u.admin', $qb->expr()->literal(true)))
            ->andWhere($qb->expr()->isNull('u.templateOwner'))
            ->andWhere($qb->expr()->eq('u.deleted', $qb->expr()->literal(false)));

        return $qb->getQuery()->getResult();
    }

    /**
     * Finds a user by login.
     *
     * @param string $login
     *
     * @return null|User
     */
    public function findByLogin($login)
    {
        return $this->findOneBy(['login' => $login]);
    }

    /**
     * Finds deleted users.
     *
     * @return User[]
     */
    public function findDeleted()
    {
        return $this->findBy(['deleted' => true]);
    }

    /**
     * Finds a user by email.
     * nb : mail match is CASE INSENSITIVE, "john@doe"=="John@Doe"=="john@DOE"
     *
     * @param string $email
     *
     * @return null|User
     */
    public function findByEmail($email)
    {
        $qb = $this->createQueryBuilder('u');

        $qb->where($qb->expr()->eq($qb->expr()->lower('u.email'), $qb->expr()->lower($qb->expr()->literal($email))))
            ->andWhere($qb->expr()->isNotNull('u.email'))
            ->andWhere($qb->expr()->eq('u.deleted', $qb->expr()->literal(false)));

        return $qb->getQuery()->getOneOrNullResult();
    }

    /**
     * Finds a user that is not deleted, not a model and not a guest.
     * nb : login match is CASE INSENSITIVE, "doe"=="Doe"=="DOE"
     *
     * @param $login
     * @param $emailOptionalForLogin
     *
     * @return null|User
     */
    public function findRealUserByLogin($login, $emailOptionalForLogin = true)
    {
        $qb = $this->createQueryBuilder('u');
        $qb->where($qb->expr()->eq($qb->expr()->lower('u.login'), $qb->expr()->lower($qb->expr()->literal($login))))
            ->andWhere($qb->expr()->isNull('u.templateOwner'))
            ->andWhere($qb->expr()->eq('u.guest', $qb->expr()->literal(false)))
            ->andWhere($qb->expr()->eq('u.deleted', $qb->expr()->literal(false)));

        if (!$emailOptionalForLogin)
        {
            $qb->andWhere($qb->expr()->isNotNull('u.email'));
        }

        return $qb->getQuery()->getOneOrNullResult();
    }

    /**
     * Finds templates owned by a given user.
     *
     * @param User $user
     *
     * @return array
     */
    public function findTemplateOwner(User $user)
    {
        return $this->findBy(['templateOwner' => $user->getId()]);
    }
}
