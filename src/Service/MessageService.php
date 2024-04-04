<?php

namespace App\Service;

use App\Entity\AnonUser;
use App\Entity\Message;
use App\Repository\MessageRepository;

class MessageService
{
    public function __construct(private int $maxMessagesToShow, private MessageRepository $messageRepository)
    {
    }

    public function handleNewMessage(AnonUser $user, string $content)
    {
        $message = new Message($content, Message::TYPE_TEXT, $user);
        $this->messageRepository->save($message);

    }
}
