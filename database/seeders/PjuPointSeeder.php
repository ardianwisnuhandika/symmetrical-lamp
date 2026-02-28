<?php

namespace Database\Seeders;

use App\Models\PjuPoint;
use Illuminate\Database\Seeder;

class PjuPointSeeder extends Seeder
{
    public function run(): void
    {
        $points = [
            [
                'nama' => 'PJU-001 - Jl. Pemuda',
                'kategori' => 'pju',
                'jenis' => 'led',
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
                'kategori' => 'pju',
                'jenis' => 'led',
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
                'kategori' => 'pju',
                'jenis' => 'sonte',
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
                'kategori' => 'pju',
                'jenis' => 'led',
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
                'kategori' => 'pju',
                'jenis' => 'kalipucang',
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
                'kategori' => 'pju',
                'jenis' => 'led',
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
                'kategori' => 'rambu',
                'jenis' => 'led',
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
                'kategori' => 'pju',
                'jenis' => 'sonte',
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
                'kategori' => 'cermin',
                'jenis' => 'led',
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
                'kategori' => 'pju',
                'jenis' => 'led',
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
