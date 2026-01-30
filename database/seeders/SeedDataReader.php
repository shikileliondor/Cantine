<?php

namespace Database\Seeders;

class SeedDataReader
{
    /**
     * @return array<int, mixed>
     */
    public static function read(string $filename): array
    {
        $path = storage_path('app/seed-data/' . $filename);

        if (!is_file($path)) {
            return [];
        }

        $contents = file_get_contents($path);

        if ($contents === false) {
            return [];
        }

        $data = json_decode($contents, true);

        return is_array($data) ? $data : [];
    }
}
