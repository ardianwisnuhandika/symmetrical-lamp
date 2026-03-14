<?php

namespace Database\Seeders;

use App\Models\PjuPoint;
use App\Models\Category;
use App\Models\PjuType;
use Illuminate\Database\Seeder;

class PjuPointSeeder extends Seeder
{
    public function run(): void
    {
        // Get category and type IDs
        $pjuCat = Category::where('slug', 'pju')->first()->id;
        $rambuCat = Category::where('slug', 'rambu')->first()->id;
        $cerminCat = Category::where('slug', 'cermin')->first()->id;
        
        $ledType = PjuType::where('slug', 'led')->first()->id;
        $sonteType = PjuType::where('slug', 'sonte')->first()->id;
        $kalipucangType = PjuType::where('slug', 'kalipucang')->first()->id;

        $points = [
            [
                'nama' => 'PJU-001 - Jl. Pemuda',
                'category_id' => $pjuCat,
                'pju_type_id' => $ledType,
                'daya' => '150w',
                'letak' => 'kiri',
                'type' => 'Stang 4m',
                'lat' => -6.5879200,
                'long' => 110.6684100,
                'status' => 'normal',
                'is_verified' => true,
            ],
            [
                'nama' => 'PJU-002 - Jl. Sultan Hadlirin',
                'category_id' => $pjuCat,
                'pju_type_id' => $ledType,
                'daya' => '150w',
                'letak' => 'kanan',
                'type' => 'Stang 4m',
                'lat' => -6.5901500,
                'long' => 110.6712000,
                'status' => 'mati',
                'is_verified' => true,
            ],
            [
                'nama' => 'PJU-003 - Jl. Diponegoro',
                'category_id' => $pjuCat,
                'pju_type_id' => $sonteType,
                'daya' => '250w',
                'letak' => 'kiri',
                'type' => 'Tiang 6m',
                'lat' => -6.5850300,
                'long' => 110.6660500,
                'status' => 'normal',
                'is_verified' => true,
            ],
            [
                'nama' => 'PJU-004 - Jl. Kartini',
                'category_id' => $pjuCat,
                'pju_type_id' => $ledType,
                'daya' => '100w',
                'letak' => 'kiri',
                'type' => 'Stang 4m',
                'lat' => -6.5862400,
                'long' => 110.6700000,
                'status' => 'mati',
                'is_verified' => false,
            ],
            [
                'nama' => 'PJU-005 - Jl. Ahmad Yani',
                'category_id' => $pjuCat,
                'pju_type_id' => $kalipucangType,
                'daya' => '150w',
                'letak' => 'kanan',
                'type' => 'Stang 5m',
                'lat' => -6.5925100,
                'long' => 110.6740000,
                'status' => 'normal',
                'is_verified' => true,
            ],
            [
                'nama' => 'PJU-006 - Jl. R.A. Kartini (Tahunan)',
                'category_id' => $pjuCat,
                'pju_type_id' => $ledType,
                'daya' => '150w',
                'letak' => 'kiri',
                'type' => 'Stang 4m',
                'lat' => -6.6012000,
                'long' => 110.6651000,
                'status' => 'normal',
                'is_verified' => true,
            ],
            [
                'nama' => 'RAMBU-001 - Pertigaan Jl. Pemuda',
                'category_id' => $rambuCat,
                'pju_type_id' => $ledType,
                'daya' => '50w',
                'letak' => 'kiri',
                'type' => 'Tiang 3m',
                'lat' => -6.5889000,
                'long' => 110.6695000,
                'status' => 'normal',
                'is_verified' => true,
            ],
            [
                'nama' => 'PJU-007 - Jl. Hos Cokroaminoto',
                'category_id' => $pjuCat,
                'pju_type_id' => $sonteType,
                'daya' => '250w',
                'letak' => 'kanan',
                'type' => 'Tiang 8m',
                'lat' => -6.5834000,
                'long' => 110.6725000,
                'status' => 'mati',
                'is_verified' => false,
            ],
            [
                'nama' => 'CERMIN-001 - Tikungan Jl. Dr. Sutomo',
                'category_id' => $cerminCat,
                'pju_type_id' => $ledType,
                'daya' => '30w',
                'letak' => 'kiri',
                'type' => 'Tiang 2m',
                'lat' => -6.5872000,
                'long' => 110.6718000,
                'status' => 'normal',
                'is_verified' => true,
            ],
            [
                'nama' => 'PJU-008 - Alun-Alun Jepara',
                'category_id' => $pjuCat,
                'pju_type_id' => $ledType,
                'daya' => '200w',
                'letak' => 'kiri',
                'type' => 'Stang Dekoratif 6m',
                'lat' => -6.5886660,
                'long' => 110.6679250,
                'status' => 'normal',
                'is_verified' => true,
            ],
        ];

        foreach ($points as $point) {
            PjuPoint::create($point);
        }
    }
}
