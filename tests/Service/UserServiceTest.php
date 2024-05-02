<?php

namespace App\Tests\Service;

use App\Repository\AnonUserRepository;
use App\Service\UserService;
use App\Tests\KernelTestCase;

class UserServiceTest extends KernelTestCase
{
    public function testItContainsFirstUser()
    {
        $anonUserRepo = self::getContainer()->get(AnonUserRepository::class);

        $users = $anonUserRepo->findAll();

        self::assertCount(1, $users);
    }

    public function testItDoesntCreateNewUserForRetrievingExistingOne()
    {
        $userService = self::getContainer()->get(UserService::class);
        $userService->getOrCreateAnonUser('123.123.123.123', 'Console');

        $anonUserRepo = self::getContainer()->get(AnonUserRepository::class);
        $users = $anonUserRepo->findAll();

        self::assertCount(1, $users);
    }

    public function testItAddsNewUserForNewIpAndAgentProvided()
    {
        $userService = self::getContainer()->get(UserService::class);
        $userService->getOrCreateAnonUser('123.123.123.0', 'Console');

        $userService = self::getContainer()->get(UserService::class);
        $userService->getOrCreateAnonUser('123.123.123.123', 'Remote Console');

        $anonUserRepo = self::getContainer()->get(AnonUserRepository::class);
        $users = $anonUserRepo->findAll();

        self::assertCount(3, $users);
    }
}
