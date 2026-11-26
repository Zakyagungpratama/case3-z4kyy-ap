<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $student = User::query()->updateOrCreate(['email' => 'mahasiswa@sakukuliah.test'], [
            'name' => 'Nadia Putri',
            'password' => 'password',
            'nim' => '2023001001',
            'faculty' => 'Ilmu Komputer',
            'study_program' => 'Sistem Informasi',
        ]);

        $student->courseSchedules()->firstOrCreate(
            ['course_name' => 'Pemrograman Web', 'day' => 'Senin', 'starts_at' => '09:00:00'],
            ['lecturer' => 'Dr. Rina Putri', 'ends_at' => '10:40:00', 'room' => 'Lab Komputer 2'],
        );
        $student->courseSchedules()->firstOrCreate(
            ['course_name' => 'Basis Data', 'day' => 'Rabu', 'starts_at' => '13:00:00'],
            ['lecturer' => 'Andi Pratama, M.Kom.', 'ends_at' => '14:40:00', 'room' => 'Ruang B204'],
        );
        $student->studyTasks()->firstOrCreate(
            ['title' => 'Rancangan antarmuka aplikasi'],
            ['course_name' => 'Pemrograman Web', 'deadline' => now()->addDays(2), 'priority' => 'Tinggi'],
        );
        $student->financeTransactions()->firstOrCreate(
            ['description' => 'Uang saku bulanan', 'date' => now()->toDateString()],
            ['type' => 'income', 'category' => 'Uang Saku', 'amount' => 1500000],
        );
        $student->financeTransactions()->firstOrCreate(
            ['description' => 'Makan siang di kampus', 'date' => now()->toDateString()],
            ['type' => 'expense', 'category' => 'Makanan', 'amount' => 25000],
        );
    }
}
