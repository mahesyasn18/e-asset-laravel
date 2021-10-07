<?php
namespace Database\Seeders;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call([
            JurusanSeeder::class,
            AdminSeeder::class,
            CategorySeeder::class,
            SumberSeeder::class,
            TahunAjaranSeeder::class,
            StatusSeeder::class,
            KelasSeeder::class,
            UsersTableSeeder::class,
        ]);
    }
}
