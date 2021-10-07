<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Admin;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Admin::create([
            'name' => "Admins",
            'username' => "admin",
            'password' => Hash::make('admin123'),
            "jurusan_id" => 1,
            "status" => "admin"
        ]);
    }
}
