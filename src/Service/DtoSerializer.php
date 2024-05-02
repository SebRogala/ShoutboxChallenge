<?php

namespace App\Service;

use Symfony\Component\Serializer\Normalizer\DateTimeNormalizer;
use Symfony\Component\Serializer\SerializerInterface;

class DtoSerializer
{
    public function __construct(private SerializerInterface $serializer, private string $defaultDateTimeFormat)
    {
    }

    public function toArray(array $collection): array
    {
        return $this->serializer->normalize($collection, null, [
            DateTimeNormalizer::FORMAT_KEY => $this->defaultDateTimeFormat,
        ]);
    }

    public function toJson(mixed $object): string
    {
        return $this->serializer->serialize($object, 'json', [
            DateTimeNormalizer::FORMAT_KEY => $this->defaultDateTimeFormat,
        ]);
    }
}
