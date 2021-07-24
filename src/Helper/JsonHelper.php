<?php

declare(strict_types=1);

namespace App\Helper;

class JsonHelper
{
    public static function decode(string $json): ?array
    {
        if ($json === '') {
            return [];
        }

        try {
            return json_decode($json, true, 512, JSON_THROW_ON_ERROR);
        } catch (\JsonException) {
            //
            return null;
        }
    }
}