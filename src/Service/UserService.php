<?php

namespace App\Service;

use App\Entity\AnonUser;
use App\Repository\AnonUserRepository;
use Faker\Factory;

class UserService
{
    public function __construct(private readonly AnonUserRepository $anonUserRepository)
    {
    }

    public function getOrCreateAnonUser(string $ip, string $userAgent): AnonUser
    {
        $user = $this->anonUserRepository->findOneBy([
            'ip' => $ip,
            'userAgent' => $userAgent,
        ]);

        if ($user) {
            return $user;
        }

        $faker = Factory::create();

        $user = new AnonUser(
            $faker->name(),
            $ip,
            $userAgent
        );

        $this->anonUserRepository->save($user);

        return $user;
    }
}
