<?php

namespace App\Tests\Service;

use App\DTO\DTOTrait;
use App\Service\DtoSerializer;
use App\Tests\KernelTestCase;
use Symfony\Component\Serializer\Attribute\Groups;

class DtoSerializerTest extends KernelTestCase
{
    private object $dto;

    /** @before */
    public function setUpBefore(): void
    {
        $this->dto = new class(1, 'public name', new \DateTimeImmutable('2024-05-01')) {
            use DTOTrait;

            public function __construct(
                public readonly int $id,
                #[Groups('public')]
                public readonly string $name,
                #[Groups('public')]
                public readonly \DateTimeImmutable $createdAt
            ) {
            }
        };
    }

    public function testDtoNormalizesObjectToArray()
    {
        $dtoSerializer = self::getContainer()->get(DtoSerializer::class);

        $array = $dtoSerializer->toArray($this->dto);

        self::assertIsArray($array);
        self::assertSame([
            'id' => 1,
            'name' => 'public name',
            'createdAt' => '2024-05-01 00:00:00',
        ], $array);
    }

    public function testDtoNormalizesGroupToArray()
    {
        $dtoSerializer = self::getContainer()->get(DtoSerializer::class);

        $array = $dtoSerializer->toArray($this->dto, 'public');

        self::assertIsArray($array);
        self::assertSame([
            'name' => 'public name',
            'createdAt' => '2024-05-01 00:00:00',
        ], $array);
    }

    public function testDtoSerializesObjectToJsonWithGroup()
    {
        $dtoSerializer = self::getContainer()->get(DtoSerializer::class);

        $serializedJson = $dtoSerializer->toJson($this->dto, 'public');

        self::assertIsString($serializedJson);
        self::assertSame('{"name":"public name","createdAt":"2024-05-01 00:00:00"}', $serializedJson);
    }
}
