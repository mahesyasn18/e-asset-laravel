<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TahunAjaranSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $datas = [
            [
                "tahun_ajaran" => "2019/2020",
                "status" => "active",
                "created_at" => now(),
                "updated_at" => now(),
            ],
            [
                "tahun_ajaran" => "2020/2021",
                "status" => "",
                "created_at" => now(),
                "updated_at" => now(),
            ],
            [
                "tahun_ajaran" => "2021/2022",
                "status" => "",
                "created_at" => now(),
                "updated_at" => now(),
            ],
            [
                "tahun_ajaran" => "2022/2023",
                "status" => "",
                "created_at" => now(),
                "updated_at" => now(),
            ]
        ];

        foreach ($datas as $data) {
            DB::table('tahun_ajaran')->insert($data);
        }
    }
}
