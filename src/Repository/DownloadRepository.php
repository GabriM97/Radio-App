<?php

namespace App\Repository;

use App\Entity\Download;
use DateTimeImmutable;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Download>
 *
 * @method Download|null find($id, $lockMode = null, $lockVersion = null)
 * @method Download|null findOneBy(array $criteria, array $orderBy = null)
 * @method Download[]    findAll()
 * @method Download[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DownloadRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Download::class);
    }

    public function save(Download $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Download $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    /**
     * Retrieves all the downloads between the range of dates passed.
     * 
     * @param int $episodeId
     * @param DateTimeImmutable $fromDate
     * @param DateTimeImmutable $toDate
     * 
     * @return array
     */
    public function getDownloadsBetweenDatesByEpisode(
        int $episodeId,
        DateTimeImmutable $fromDate,
        DateTimeImmutable $toDate
    ): array {
        return 
            $this->createQueryBuilder('d')
            ->andWhere('d.episode = :id')->setParameter('id', $episodeId)
            ->andWhere('d.datetime >= :from_date')->setParameter('from_date', $fromDate)
            ->andWhere('d.datetime <= :to_date')->setParameter('to_date', $toDate)
            ->getQuery()
            ->getResult();
    }

//    /**
//     * @return Download[] Returns an array of Download objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('d')
//            ->andWhere('d.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('d.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Download
//    {
//        return $this->createQueryBuilder('d')
//            ->andWhere('d.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
