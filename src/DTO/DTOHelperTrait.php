<?php

namespace App\DTO;

trait DTOHelperTrait
{
    public function collectionToArray(array $collection): array
    {
        $temp = [];

        if (empty($collection)) {
            return $temp;
        }

        foreach ($collection as $item) {
            $temp[] = $item->toArray();
        }

        return $temp;
    }
}
