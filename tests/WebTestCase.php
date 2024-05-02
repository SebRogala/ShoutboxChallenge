<?php

namespace App\Tests;

use Symfony\Component\BrowserKit\AbstractBrowser;

class WebTestCase extends \Symfony\Bundle\FrameworkBundle\Test\WebTestCase
{
    protected AbstractBrowser $client;

    protected function setUp(): void
    {
        $this->ensureKernelShutdown();
        $this->client = static::createClient();
    }
}
