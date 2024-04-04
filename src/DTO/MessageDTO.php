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
        public readonly string $content
    ) {
    }

    public static function create(Message $message): static
    {
        return new self(
            $message->getId(),
            $message->getSender()->getName(),
            $message->getType(),
            $message->getContent()
        );
    }


    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'userName' => $this->userName,
            'type' => $this->type,
            'content' => $this->content,
        ];
    }
}
