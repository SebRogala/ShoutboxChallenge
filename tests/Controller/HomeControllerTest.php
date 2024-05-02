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

        $messageRepository = self::getContainer()->get(MessageRepository::class);
        $messages = $messageRepository->findInitialMessages(
            self::getContainer()->getParameter('MAX_MESSAGES_TO_SHOW')
        );

        self::assertCount(1, $messages);
    }
}
