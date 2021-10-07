<?php
namespace Database\Seeders;

use App\Models\KelasSiswa;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $user = User::create([
            'name' => 'Users',
            'username' => 'user',
            'password' => Hash::make('secret'),
            'nomor_induk' => "11111111",
            "status" => "siswa",
            'created_at' => now(),
            'updated_at' => now()
        ]);

        KelasSiswa::create([
            "user_id" => $user->id,
            "kelas_id" => 19,
            "tahunajaran_id" => 1
        ]);
    }
}
