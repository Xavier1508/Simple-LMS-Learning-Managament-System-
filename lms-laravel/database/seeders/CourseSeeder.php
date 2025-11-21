<?php

namespace Database\Seeders;

use App\Models\Course;
use Illuminate\Database\Seeder;

class CourseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Course::create([
            'title' => 'Blockchain Fundamental',
            'code' => 'COMP68733001',
            'description' => 'Mempelajari dasar-dasar teknologi blockchain dan aplikasinya.',
        ]);

        Course::create([
            'title' => 'Compilation Techniques',
            'code' => 'COMP6062001',
            'description' => 'Mempelajari teknik dan proses kompilasi sebuah program.',
        ]);

        Course::create([
            'title' => 'Computer Forensic',
            'code' => 'COMP6646001',
            'description' => 'Investigasi digital dan analisis forensik pada sistem komputer.',
        ]);

        Course::create([
            'title' => 'Network Penetration Testing',
            'code' => 'COMP6544001',
            'description' => 'Teknik untuk menguji keamanan jaringan komputer.',
        ]);
    }
}
