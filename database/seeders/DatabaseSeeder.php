<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Akun dosen default
        User::firstOrCreate(
            ['email' => 'dosen@uas.ac.id'],
            [
                'name'     => 'Dosen Pengampu',
                'nim'      => null,
                'password' => Hash::make('dosen123'),
                'role'     => 'dosen',
            ]
        );

        $this->call(QuestionSeeder::class);
    }
}
