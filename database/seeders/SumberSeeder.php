<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SumberSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $sumber = [
            [
                "nama_sumber" => "Dana Bos",
                "created_at" => now(),
                "updated_at" => now()
            ],
            [
                "nama_sumber" => "Donatur",
                "created_at" => now(),
                "updated_at" => now(),
            ],
            [
                "nama_sumber" => "PT Sentosa Sejahtera",
                "created_at" => now(),
                "updated_at" => now()
            ]
        ];

        foreach ($sumber as $data) {
            DB::table('sumber')->insert($data);
        }
    }
}
