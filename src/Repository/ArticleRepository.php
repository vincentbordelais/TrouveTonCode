<?php

namespace App\Repository;

use App\Entity\Article;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Article|null find($id, $lockMode = null, $lockVersion = null)
 * @method Article|null findOneBy(array $criteria, array $orderBy = null)
 * @method Article[]    findAll()
 * @method Article[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ArticleRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Article::class);
    }

    public function findByKeywords(array $keywords)
    {
        /*
         * SELECT article.*, count(article.id) as rank FROM `article`
         * LEFT JOIN article_tag as att ON article.id = att.article_id
         * LEFT JOIN tag ON att.tag_id = tag.id WHERE tag.name
         * IN ('php', 'cli') GROUP BY article.id ORDER BY rank DESC
         */

        /**
         * Permet de récupérer 3 articles max contenant nos keywords
         *  par ordre croissant.
         */
        return $this->createQueryBuilder('a')
            ->addSelect('COUNT(att) as c')
            ->leftJoin('a.tags', 'att')

            ->andWhere('att.name IN (:keywords)')
            ->setParameter('keywords', $keywords)
            ->groupBy('a.id')
            ->orderBy('c', 'DESC')
            ->setMaxResults(3)
            ->getQuery()
            ->getResult()
        ;
    }



    // /**
    //  * @return Article[] Returns an array of Article objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('a.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Article
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
