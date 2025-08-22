<?php
// database/seeders/CategorySeeder.php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    public function run()
    {
        $categories = [
            [
                'name' => 'Elektronik',
                'slug' => 'elektronik',
                'description' => 'Handphone, laptop, kamera, dan perangkat elektronik lainnya',
                'icon' => 'electronics.png',
            ],
            [
                'name' => 'Dokumen',
                'slug' => 'dokumen',
                'description' => 'KTP, SIM, passport, ijazah, dan dokumen penting lainnya',
                'icon' => 'documents.png',
            ],
            [
                'name' => 'Kendaraan',
                'slug' => 'kendaraan',
                'description' => 'Motor, mobil, sepeda, dan kendaraan lainnya',
                'icon' => 'vehicle.png',
            ],
            [
                'name' => 'Tas & Dompet',
                'slug' => 'tas-dompet',
                'description' => 'Tas, dompet, ransel, dan perlengkapan serupa',
                'icon' => 'bag.png',
            ],
            [
                'name' => 'Perhiasan',
                'slug' => 'perhiasan',
                'description' => 'Jam tangan, kalung, cincin, dan perhiasan lainnya',
                'icon' => 'jewelry.png',
            ],
            [
                'name' => 'Pakaian',
                'slug' => 'pakaian',
                'description' => 'Baju, celana, sepatu, dan pakaian lainnya',
                'icon' => 'clothes.png',
            ],
            [
                'name' => 'Hewan Peliharaan',
                'slug' => 'hewan-peliharaan',
                'description' => 'Kucing, anjing, burung, dan hewan peliharaan lainnya',
                'icon' => 'pet.png',
            ],
            [
                'name' => 'Kunci',
                'slug' => 'kunci',
                'description' => 'Kunci rumah, kunci motor, kunci mobil, dan kunci lainnya',
                'icon' => 'key.png',
            ],
            [
                'name' => 'Alat Tulis',
                'slug' => 'alat-tulis',
                'description' => 'Pulpen, pensil, buku, dan alat tulis lainnya',
                'icon' => 'stationery.png',
            ],
            [
                'name' => 'Lainnya',
                'slug' => 'lainnya',
                'description' => 'Barang-barang lain yang tidak masuk kategori di atas',
                'icon' => 'other.png',
            ],
        ];

        foreach ($categories as $category) {
            Category::create($category);
        }
    }
}