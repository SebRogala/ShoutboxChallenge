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
            'content' => 'sample first message',
        ]);
        $this->assertResponseIsSuccessful();

        $this->client->request('POST', '/message', [
            'content' => 'sample second message',
        ]);

        $this->client->request('POST', '/message', [
            'content' => 'should be only 2 left',
        ]);

        $this->client->request('POST', '/message', [
            'content' => 'http://test.com',
        ]);

        $crawler = $this->client->request('GET', '/');
        $this->assertResponseIsSuccessful();

        $messages = json_decode(
            $crawler->filter('div.shoutbox-wrapper')->attr('data-symfony--ux-react--react-props-value'),
            true
        )['initialMessages'];

        self::assertCount(2, $messages);
        self::assertStringMatchesFormat("%d-%d-%d %d:%d:%d", $messages[0]['createdAt']);
        self::assertSame('http://test.com', $messages[1]['content']);
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
