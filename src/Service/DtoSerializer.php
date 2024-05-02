<?php

namespace App\Service;

use Symfony\Component\Serializer\Normalizer\DateTimeNormalizer;
use Symfony\Component\Serializer\SerializerInterface;

class DtoSerializer
{
    public function __construct(private SerializerInterface $serializer, private string $defaultDateTimeFormat)
    {
    }

    public function toArray(mixed $object, ?string $groups = null): array
    {
        return $this->serializer->normalize($object, null, [
            DateTimeNormalizer::FORMAT_KEY => $this->defaultDateTimeFormat,
            'groups' => $groups,
        ]);
    }

    public function toJson(mixed $object, ?string $groups = null): string
    {
        return $this->serializer->serialize($object, 'json', [
            DateTimeNormalizer::FORMAT_KEY => $this->defaultDateTimeFormat,
            'groups' => $groups,
        ]);
    }
}
