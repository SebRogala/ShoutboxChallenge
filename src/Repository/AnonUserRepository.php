<?php

namespace App\Repository;

use App\Entity\AnonUser;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<AnonUser>
 *
 * @method AnonUser|null find($id, $lockMode = null, $lockVersion = null)
 * @method AnonUser|null findOneBy(array $criteria, array $orderBy = null)
 * @method AnonUser[]    findAll()
 * @method AnonUser[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AnonUserRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, AnonUser::class);
    }

    public function save(AnonUser $anonUser): void
    {
        $this->getEntityManager()->persist($anonUser);
        $this->getEntityManager()->flush();
    }
}
