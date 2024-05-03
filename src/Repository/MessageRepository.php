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

    public function keepOnlyNewest(int $maxMessagesNumber): void
    {
        //Doing this by ORM instead of DBAL/query to keep Softdeleteable filters work
        $messagesToDelete = $this->createQueryBuilder('m')
            ->orderBy('m.createdAt', 'DESC')
            ->orderBy('m.id', 'DESC')
            ->setFirstResult($maxMessagesNumber)
            ->getQuery()
            ->getResult();

        if (!empty($messagesToDelete)) {
            foreach ($messagesToDelete as $message) {
                $this->getEntityManager()->remove($message);
            }
            $this->getEntityManager()->flush();
        }
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
