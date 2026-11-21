<?php

namespace Database\Seeders;

use App\Models\Assignment;
use App\Models\MataKuliah;
use App\Models\Material;
use App\Models\Submission;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Akun Dosen Pengampu Default
        $dosen = User::updateOrCreate(
            ['email' => 'dosen@celoe.test'],
            [
                'name' => 'Dr. Ir. Budi Santoso, M.T.',
                'password' => Hash::make('dosen123'),
                'role' => 'dosen',
                'nomor_induk' => '198501012015041001',
            ]
        );

        // 2. Akun Mahasiswa Default
        $mahasiswa1 = User::updateOrCreate(
            ['email' => 'mahasiswa@celoe.test'],
            [
                'name' => 'Ahmad Pratama',
                'password' => Hash::make('mahasiswa123'),
                'role' => 'mahasiswa',
                'nomor_induk' => '1301210001',
            ]
        );

        // 3. Mata Kuliah Dasar (Modul 3)
        $course1 = MataKuliah::updateOrCreate(
            ['kode_mk' => 'IF301'],
            [
                'nama_mk' => 'Pengembangan Aplikasi Website',
                'deskripsi' => 'Mata kuliah yang membahas arsitektur aplikasi web modern menggunakan framework Laravel, MVC, Eloquent ORM, dan keamanan web.',
                'sks' => 4,
                'semester' => 'Ganjil 2026/2027',
                'dosen_id' => $dosen->id,
            ]
        );

        // 4. Daftarkan Mahasiswa ke Mata Kuliah Pertama (status approved)
        $course1->students()->syncWithoutDetaching([
            $mahasiswa1->id => ['status' => 'approved'],
        ]);

        // 5. Tambahkan Materi Contoh
        Material::updateOrCreate(
            ['course_id' => $course1->id, 'minggu_ke' => 1, 'urutan' => 1],
            [
                'judul' => 'Pengenalan Arsitektur MVC & Routing di Laravel 12',
                'konten' => 'Mempelajari alur HTTP Request, Route Middleware, Controller, dan pembuatan View menggunakan Blade Template.',
            ]
        );

        // TODO 8: Tambahkan minimal 2 mata kuliah lagi dengan semester berbeda, 1 mahasiswa tambahan, 2 tugas, dan contoh data submission
        $mahasiswa2 = User::updateOrCreate(
            ['email' => 'mahasiswa2@celoe.test'],
            [
                'name' => 'Siti Nurhaliza',
                'password' => Hash::make('mahasiswa123'),
                'role' => 'mahasiswa',
                'nomor_induk' => '1301210002',
            ]
        );

        $course2 = MataKuliah::updateOrCreate(
            ['kode_mk' => 'IF202'],
            [
                'nama_mk' => 'Basis Data',
                'deskripsi' => 'Perancangan basis data relasional, normalisasi, dan SQL.',
                'sks' => 3,
                'semester' => 'Genap 2025/2026',
                'dosen_id' => $dosen->id,
            ]
        );

        $course3 = MataKuliah::updateOrCreate(
            ['kode_mk' => 'IF401'],
            [
                'nama_mk' => 'Rekayasa Perangkat Lunak',
                'deskripsi' => 'Siklus pengembangan perangkat lunak dan manajemen proyek.',
                'sks' => 2,
                'semester' => 'Ganjil 2026/2027',
                'dosen_id' => $dosen->id,
            ]
        );

        $course1->students()->syncWithoutDetaching([$mahasiswa2->id => ['status' => 'approved']]);

        $tugas1 = Assignment::updateOrCreate(
            ['course_id' => $course1->id, 'judul' => 'Membangun CRUD dengan Laravel'],
            ['deskripsi' => 'Buat fitur CRUD sederhana dan jelaskan alurnya.', 'deadline' => now()->addDays(7)]
        );

        $tugas2 = Assignment::updateOrCreate(
            ['course_id' => $course1->id, 'judul' => 'Eloquent Relationship'],
            ['deskripsi' => 'Jelaskan relasi one-to-many dan many-to-many beserta contohnya.', 'deadline' => now()->addDays(14)]
        );

        Submission::updateOrCreate(
            ['assignment_id' => $tugas1->id, 'student_id' => $mahasiswa1->id],
            [
                'jawaban' => 'Saya membuat CRUD dengan controller, model, dan view Blade.',
                'submitted_at' => now(),
                'nilai' => 85,
                'catatan_dosen' => 'Sudah baik.',
            ]
        );
    }
}
