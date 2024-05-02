<?php

namespace App\Tests\Service;

use App\Service\MessageService;
use App\Tests\KernelTestCase;
use Symfony\Component\Mercure\HubInterface;

class MessageServiceTest extends KernelTestCase
{
    private MessageService $messageService;

    /** @before  */
    public function setUpBefore(): void
    {
        $hubMock = $this->createMock(HubInterface::class);
        self::getContainer()->set(HubInterface::class, $hubMock);

        $this->messageService = self::getContainer()->get(MessageService::class);
    }

    public function testItDoesntContainAnyMessages()
    {
        self::assertCount(0, $this->messageService->getInitialMessages());
    }

    public function testItProperlyKeepsLimitedMessages()
    {
        $user = $this->getDefaultUser();
        $this->messageService->handleNewMessage($user, 'test');
        self::assertCount(1, $this->messageService->getInitialMessages());

        $this->messageService->handleNewMessage($user, 'test 2');
        self::assertCount(2, $this->messageService->getInitialMessages());

        $this->messageService->handleNewMessage($user, 'test 3');
        self::assertCount(2, $this->messageService->getInitialMessages());
    }
}
