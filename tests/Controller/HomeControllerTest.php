<?php

namespace App\Tests\Controller;

use App\Repository\MessageRepository;
use App\Tests\WebTestCase;

class HomeControllerTest extends WebTestCase
{
    public function testHomepageLoadsWithEmptyInitialMessages(): void
    {
        $crawler = $this->client->request('GET', '/');

        $this->assertResponseIsSuccessful();
        $this->assertSelectorExists('div.shoutbox-wrapper');

        self::assertEmpty(
            json_decode(
                $crawler->filter('div.shoutbox-wrapper')->attr('data-symfony--ux-react--react-props-value'),
                true
            )['initialMessages']
        );
    }

    public function testItSuccessfullyAddsNewMessage(): void
    {
        $this->client->request('POST', '/message', [
            'content' => 'sample message',
        ]);

        $this->assertResponseIsSuccessful();

        $this->client->request('POST', '/message', [
            'content' => 'http://test.com',
        ]);

        $this->assertResponseIsSuccessful();

        $messageRepository = self::getContainer()->get(MessageRepository::class);
        $messages = $messageRepository->findInitialMessages(
            self::getContainer()->getParameter('MAX_MESSAGES_TO_SHOW')
        );

        self::assertCount(2, $messages);
        self::assertSame('http://test.com', $messages[1]->getContent());
    }

    public function testItPreventsEmptyMessageToBeProceeded(): void
    {
        $this->client->request('POST', '/message', [
            'content' => '',
        ]);

        $this->assertResponseIsUnprocessable();
    }

    public function testItSanitizesHtml(): void
    {
        $this->client->request('POST', '/message', [
            'content' => '<script>alert("test");</script>',
        ]);
        $this->assertResponseIsUnprocessable();

        $this->client->request('POST', '/message', [
            'content' => '<script>alert("test");</script>test',
        ]);

        $this->assertResponseIsSuccessful();

        $messageRepository = self::getContainer()->get(MessageRepository::class);
        $messages = $messageRepository->findInitialMessages(
            self::getContainer()->getParameter('MAX_MESSAGES_TO_SHOW')
        );

        self::assertSame('test', $messages[0]->getContent());
    }
}
