<?php

namespace App\Repository;

use App\Entity\MaintenanceRecord;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<MaintenanceRecord>
 */
class MaintenanceRecordRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, MaintenanceRecord::class);
    }

    public function findByUserVehicles($user): array
    {
        return $this->createQueryBuilder('m')
            ->join('m.vehicle', 'v')
            ->andWhere('v.user = :user')
            ->setParameter('user', $user)
            ->getQuery()
            ->getResult();
    }

    public function findLastMaintenanceForUser($user): ?\App\Entity\MaintenanceRecord
    {
        return $this->createQueryBuilder('m')
            ->join('m.vehicle', 'v')
            ->andWhere('v.user = :user')
            ->setParameter('user', $user)
            ->orderBy('m.date', 'DESC')
            ->setMaxResults(1)
            ->getQuery()
            ->getOneOrNullResult();
    }

    public function findFilteredForUser($user, $vehicleId = null, $from = null, $to = null): array
    {
        $qb = $this->createQueryBuilder('m')
            ->join('m.vehicle', 'v')
            ->andWhere('v.user = :user')
            ->setParameter('user', $user);

        if ($vehicleId) {
            $qb->andWhere('v.id = :vehicleId')->setParameter('vehicleId', $vehicleId);
        }

        if ($from) {
            $qb->andWhere('m.date >= :from')->setParameter('from', new \DateTime($from));
        }

        if ($to) {
            $qb->andWhere('m.date <= :to')->setParameter('to', new \DateTime($to));
        }

        return $qb->orderBy('m.date', 'DESC')->getQuery()->getResult();
    }

    public function findUpcomingForUser($user, int $days = 30): array
    {
        $cutoff = (new \DateTime())->modify("+{$days} days");

        return $this->createQueryBuilder('m')
            ->join('m.vehicle', 'v')
            ->andWhere('v.user = :user')
            ->andWhere('m.date >= :today')
            ->andWhere('m.date <= :cutoff')
            ->setParameter('user', $user)
            ->setParameter('today', new \DateTime())
            ->setParameter('cutoff', $cutoff)
            ->orderBy('m.date', 'ASC')
            ->getQuery()
            ->getResult();
    }

}
