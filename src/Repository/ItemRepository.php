<?php

namespace App\Repository;

use App\Entity\Item;
use App\Entity\TodoList;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Collections\Criteria;
use Doctrine\ORM\NonUniqueResultException;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Item|null find($id, $lockMode = null, $lockVersion = null)
 * @method Item|null findOneBy(array $criteria, array $orderBy = null)
 * @method Item[]    findAll()
 * @method Item[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ItemRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Item::class);
    }

    public function findMaxWeight(int $listId): int
    {
        return (int)$this->createQueryBuilder('i')
            ->select('MAX(i.weight)')
            ->where('i.list = :listId')
            ->setParameter('listId', $listId)
            ->getQuery()
            ->getSingleScalarResult();
    }

    public function findAllByUserAndList(User $user, TodoList $list, array $ids): array
    {
        $queryBuilder = $this->createQueryBuilder('i');
        $queryBuilder
            ->select('i')
            ->join('i.list', 'l')
            ->where('l.id = :listId')
            ->andWhere('l.user = :user')
            ->andWhere($queryBuilder->expr()->in('i.id', ':ids'))
            ->orderBy('i.weight', Criteria::ASC)
            ->setParameter('listId', $list->getId())
            ->setParameter('user', $user)
            ->setParameter('ids', $ids)
        ;

        return $queryBuilder->getQuery()->getResult();
    }
}
