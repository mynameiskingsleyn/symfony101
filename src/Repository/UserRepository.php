<?php

namespace App\Repository;

use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Doctrine\ORM\QueryBuilder;

/**
 * @method User|null find($id, $lockMode = null, $lockVersion = null)
 * @method User|null findOneBy(array $criteria, array $orderBy = null)
 * @method User[]    findAll()
 * @method User[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, User::class);
    }

    public function findAllWithMoreThanFivePosts()
    {
        return $this->getFindAllWithMoreThanFivePostsQuery()
                  ->getQuery()
                  ->getResult();
    }

    public function findAllWithMoreThanFivePostsExceptUser(User $user)
    {
        $query =  $this->getFindAllWithMoreThanFivePostsQuery();
        $result = $query->andHaving('u != :user')
                        ->setParameter('user', $user)
                        ->getQuery()
                        ->getResult();

        return $result;
        // ->andHaving('u != :user')
                    // ->setParameters('user', $user)
                    // ->getQuery()
                    // ->getResult();
    }

    private function getFindAllWithMoreThanFivePostsQuery(): QueryBuilder
    {
        $qb = $this->createQueryBuilder('u'); // alias

        $result = $qb->select('u')
                ->innerJoin('u.posts', 'mp')
                ->groupBy('u')
                ->having('count(mp)>5');
        return $result;
    }

    // /**
    //  * @return User[] Returns an array of User objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('u.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?User
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
