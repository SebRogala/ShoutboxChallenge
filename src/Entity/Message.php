<?php

namespace App\Entity;

use App\Repository\MessageRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

#[ORM\Entity(repositoryClass: MessageRepository::class)]
#[Gedmo\SoftDeleteable(fieldName: 'deletedAt')]
class Message
{
    const TYPE_TEXT = 'text';
    const TYPE_FILE_URI = 'file_uri';

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $content = null;

    #[ORM\Column(length: 255)]
    private ?string $type = 'text';

    #[ORM\ManyToOne(inversedBy: 'messages')]
    #[ORM\JoinColumn(nullable: false)]
    private ?AnonUser $sender = null;

    #[Gedmo\Timestampable(on: 'create')]
    #[ORM\Column(type: Types::DATETIME_IMMUTABLE)]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\Column(type: Types::DATETIME_IMMUTABLE,nullable: true)]
    private ?\DateTimeImmutable $deletedAt = null;

    public function __construct(string $content, string $type, AnonUser $sender)
    {
        $this->content = $content;
        $this->type = $type;
        $this->sender = $sender;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function getSender(): ?AnonUser
    {
        return $this->sender;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function getDeletedAt(): ?\DateTimeImmutable
    {
        return $this->deletedAt;
    }
}
