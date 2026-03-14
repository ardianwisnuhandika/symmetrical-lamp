<?php

namespace Database\Seeders;

use App\Models\PjuType;
use Illuminate\Database\Seeder;

class PjuTypeSeeder extends Seeder
{
    public function run(): void
    {
        $types = [
            ['name' => 'Sonte'],
            ['name' => 'LED'],
            ['name' => 'Kalipucang'],
        ];

        foreach ($types as $type) {
            PjuType::create($type);
        }
    }
}
