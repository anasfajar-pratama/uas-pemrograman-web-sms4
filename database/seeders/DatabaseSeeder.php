<?php

namespace Database\Seeders;

use App\Models\Exam;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $dosen = User::firstOrCreate(
            ['email' => 'dosen@uas.ac.id'],
            [
                'name'     => 'Dosen Pengampu',
                'nim'      => null,
                'password' => Hash::make('dosen123'),
                'role'     => 'dosen',
            ]
        );

        $exam = Exam::firstOrCreate(
            ['code' => 'UAS-WEB-001'],
            [
                'name'             => 'Ujian Akhir Semester — Pemrograman Web',
                'duration_minutes' => 120,
                'is_active'        => true,
                'created_by'       => $dosen->id,
            ]
        );

        $this->callWith(QuestionSeeder::class, ['examId' => $exam->id]);
    }
}
