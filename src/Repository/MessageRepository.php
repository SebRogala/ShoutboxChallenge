<?php

namespace App\Repository;

use App\Entity\Message;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\AbstractQuery;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Message>
 *
 * @method Message|null find($id, $lockMode = null, $lockVersion = null)
 * @method Message|null findOneBy(array $criteria, array $orderBy = null)
 * @method Message[]    findAll()
 * @method Message[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MessageRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Message::class);
    }

    public function save(Message $message): void
    {
        $this->getEntityManager()->persist($message);
        $this->getEntityManager()->flush();
    }

    public function keepOnlyNewest(int $maxMessagesNumber): int
    {
        $toKeepIds = $this->createQueryBuilder('m2')
            ->select('m2.id')
            ->andWhere('m2.deletedAt is NULL')
            ->orderBy('m2.createdAt', 'DESC')
            ->orderBy('m2.id', 'DESC')
            ->setMaxResults($maxMessagesNumber)
            ->getQuery()
            ->getResult(AbstractQuery::HYDRATE_SCALAR_COLUMN)
        ;

        $queryBuilder = $this->createQueryBuilder('m');
        $queryBuilder
            ->update(Message::class, 'm')
            ->set('m.deletedAt', ':p')
            ->where($queryBuilder->expr()->notIn('m.id', $toKeepIds))
            ->andWhere('m.deletedAt is NULL')
            ->setParameter('p', (new \DateTimeImmutable())->format('Y-m-d H:i:s'))
        ;

        return $queryBuilder->getQuery()->getResult();
    }

    /**
     * @return Message[]
     */
    public function findInitialMessages(): array
    {
        return $this->createQueryBuilder('m')
            ->orderBy('m.createdAt', 'ASC')
            ->orderBy('m.id', 'ASC')
            ->getQuery()
            ->getResult();
    }
}
