<?php

namespace App\DTO;

trait DTOTrait
{
    public static function createCollection(array $collection): array
    {
        $temp = [];

        if (empty($collection)) {
            return $temp;
        }

        foreach ($collection as $item) {
            $temp[] = self::create($item);
        }

        return $temp;
    }
}
