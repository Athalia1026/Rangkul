<?php

namespace App\Support;

class IndonesianCities
{
    /**
     * @return array<int, string>
     */
    public static function all(): array
    {
        return json_decode(file_get_contents(public_path('data/kota-indonesia.json')), true) ?? [];
    }
}
