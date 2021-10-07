<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $categories = [
            [
                "nama_kategori" => "Elektronik",
                "created_at" => now(),
                "updated_at" => now()
            ],
            [
                "nama_kategori" => "Alat Praktek",
                "created_at" => now(),
                "updated_at" => now()
            ],
            [
                "nama_kategori" => "Keperluan Kelas",
                "created_at" => now(),
                "updated_at" => now()
            ],
        ];

        foreach ($categories as $category) {
            DB::table('categories')->insert($category);
        }
    }
}
