<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class StatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = [
            [
                "keterangan_status" => "pending",
                "created_at" => now(),
                "updated_at" => now()
            ],
            [
                "keterangan_status" => "menunggu scan",
                "created_at" => now(),
                "updated_at" => now()
            ],
            [
                "keterangan_status" => "sedang dipinjam",
                "created_at" => now(),
                "updated_at" => now()
            ],
            [
                "keterangan_status" => "dikembalikan",
                "created_at" => now(),
                "updated_at" => now()
            ],
            [
                "keterangan_status" => "ditolak",
                "created_at" => now(),
                "updated_at" => now()
            ]
        ];
        foreach ($data as $d) {
            DB::table("status")->insert($d);
        }
    }
}
