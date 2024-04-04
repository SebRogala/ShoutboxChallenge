<?php

namespace App\Service;

use App\DTO\MessageDTO;
use App\Entity\AnonUser;
use App\Entity\Message;
use App\Repository\MessageRepository;
use Symfony\Component\Mercure\HubInterface;
use Symfony\Component\Mercure\Update;

class MessageService
{
    public function __construct(
        private int $maxMessagesToShow,
        private string $mercureMessageTopicName,
        private MessageRepository $messageRepository,
        private HubInterface $hub
    ) {
    }

    /**
     * @return MessageDTO[]
     */
    public function getInitialMessages(): array
    {
        $result = $this->messageRepository->findInitialMessages($this->maxMessagesToShow);

        return MessageDTO::createCollection($result);
    }

    public function handleNewMessage(AnonUser $user, string $content): void
    {
        $message = new Message($content, Message::TYPE_TEXT, $user);
        $this->messageRepository->save($message);

        $this->hub->publish(
            new Update(
                $this->mercureMessageTopicName,
                json_encode(
                    (MessageDTO::create($message))->toArray()
                )
            )
        );
    }
}
