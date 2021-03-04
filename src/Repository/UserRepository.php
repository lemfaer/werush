<?php

namespace App\Repository;

use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Validator\Exception\ValidationFailedException;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class UserRepository extends ServiceEntityRepository
{
    protected ValidatorInterface $validator;

    public function __construct(ManagerRegistry $registry, ValidatorInterface $validator)
    {
        parent::__construct($registry, User::class);

        $this->validator = $validator;
    }

    /**
     * Get list of users
     *
     * @param string|null $search username or email
     * @param int $limit
     * @param int $offset
     *
     * @return array
     */
    public function getList(?string $search, int $limit, int $offset): array
    {
        $qb = $this->createQueryBuilder("u");

        $limit = $limit < 100 ? $limit : 100;

        if (isset($search)) {
            $qb
                ->where("u.name like :search")
                ->orWhere("u.email like :search")
                ->setParameter("search", "%$search%");
        }

        $qb
            ->orderBy("u.id", "asc")
            ->setFirstResult($offset)
            ->setMaxResults($limit);

        $query = $qb->getQuery();

        return $query->getArrayResult();
    }

    /**
     * Select one user or return null
     *
     * @param int $id
     *
     * @return User|null
     */
    public function getOne(int $id): ?User
    {
        /**
         * @noinspection PhpIncompatibleReturnTypeInspection
         */
        return $this->find($id);
    }

    /**
     * Create new or replace user
     *
     * @param User|null $current saved
     * @param User $modified new data
     * @param bool $replace replace or patch
     *
     * @throws ORMException
     * @throws OptimisticLockException
     * @throws ValidationFailedException
     */
    public function save(?User $current, User $modified, bool $replace = false): void
    {
        $em = $this->getEntityManager();

        if (isset($current)) {
            $save = $current;

            if ($replace || null !== $modified->getName()) {
                $save->setName($modified->getName());
            }

            if ($replace || null !== $modified->getEmail()) {
                $save->setEmail($modified->getEmail());
            }

            if ($replace || null !== $modified->getPassword()) {
                $save->setPassword($modified->getPassword());
            }
        } else {
            $save = $modified;
        }

        $violations = $this->validator->validate($save);

        if (0 !== $violations->count()) {
            throw new ValidationFailedException($save, $violations);
        }

        $em->persist($save);
        $em->flush();
    }

    /**
     * Delete user
     *
     * @param User $entity record to delete
     *
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function delete(User $entity): void
    {
        $em = $this->getEntityManager();
        $em->remove($entity);
        $em->flush();
    }
}
