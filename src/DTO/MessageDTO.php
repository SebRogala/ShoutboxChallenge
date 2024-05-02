<?php

namespace App\DTO;

use App\Entity\Message;

class MessageDTO
{
    use DTOTrait;

    private function __construct(
        public readonly int $id,
        public readonly string $userName,
        public readonly string $type,
        public readonly string $content,
        public readonly \DateTimeImmutable $createdAt
    ) {
    }

    public static function create(Message $message): static
    {
        return new self(
            $message->getId(),
            $message->getSender()->getName(),
            $message->getType(),
            $message->getContent(),
            $message->getCreatedAt()
        );
    }
}
