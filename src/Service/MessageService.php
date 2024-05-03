<?php

namespace App\Service;

use App\DTO\MessageDTO;
use App\Entity\AnonUser;
use App\Entity\Message;
use App\Repository\MessageRepository;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Mercure\HubInterface;
use Symfony\Component\Mercure\Update;
use Symfony\Component\String\Slugger\SluggerInterface;

class MessageService
{
    public function __construct(
        private int $maxMessagesToShow,
        private string $mercureMessageTopicName,
        private MessageRepository $messageRepository,
        private HubInterface $hub,
        private DtoSerializer $dtoSerializer,
        private SluggerInterface $slugger
    ) {
    }

    /**
     * @return MessageDTO[]
     */
    public function getInitialMessages(): array
    {
        $result = $this->messageRepository->findInitialMessages();

        return MessageDTO::createCollection($result);
    }

    public function handleNewMessage(AnonUser $user, string $content): void
    {
        $message = new Message($content, Message::TYPE_TEXT, $user);
        $this->messageRepository->save($message);

        $this->messageRepository->keepOnlyNewest($this->maxMessagesToShow);

        $this->publishToHub($message);
    }

    public function handleNewImage(AnonUser $user, UploadedFile $file, string $messageFileUploadDir): void
    {
        $originalFilename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
        $safeFilename = $this->slugger->slug($originalFilename);
        $newFilename = $safeFilename . '-' . uniqid() . '.' . $file->guessExtension();

        $file->move($messageFileUploadDir, $newFilename);

        $message = new Message($newFilename, Message::TYPE_FILE_URI, $user);
        $this->messageRepository->save($message);

        $this->messageRepository->keepOnlyNewest($this->maxMessagesToShow);

        $this->publishToHub($message);
    }

    public function publishToHub(Message $message): void
    {
        $this->hub->publish(
            new Update(
                $this->mercureMessageTopicName,
                $this->dtoSerializer->toJson(MessageDTO::create($message))
            )
        );
    }
}
