<?php

namespace App\Tests;

use App\Entity\AnonUser;
use App\Repository\AnonUserRepository;

class KernelTestCase extends \Symfony\Bundle\FrameworkBundle\Test\KernelTestCase
{
    public function __construct(?string $name = null, array $data = [], $dataName = '')
    {
        parent::__construct($name, $data, $dataName);
        self::bootKernel();
    }

    protected function getDefaultUser(): AnonUser
    {
        $anonUserRepo = self::getContainer()->get(AnonUserRepository::class);

        return $anonUserRepo->findOneById(1);
    }
}
