<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Admin;
use App\Models\Student;
use App\Models\Coach;
use App\Models\Extracurricular;
use App\Models\Interest;
use App\Models\Enrollment;
use App\Models\Meeting;
use App\Models\Attendance;
use App\Models\Achievement;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // Create Admin User
        $adminUser = User::create([
            'name' => 'Administrator',
            'email' => 'admin@example.com',
            'password' => Hash::make('password'),
            'role' => 'admin'
        ]);

        Admin::create([
            'user_id' => $adminUser->id,
            'department' => 'TU'
        ]);

        // Create Pembina Users
        $pembinaUser1 = User::create([
            'name' => 'Pembina Olahraga',
            'email' => 'pembina.olahraga@example.com',
            'password' => Hash::make('password'),
            'role' => 'pembina'
        ]);

        $coach1 = Coach::create([
            'user_id' => $pembinaUser1->id,
            'specialty' => 'Olahraga',
            'experience' => 5,
            'certificates' => 'Sertifikat Pelatih Olahraga'
        ]);

        $pembinaUser2 = User::create([
            'name' => 'Pembina Seni',
            'email' => 'pembina.seni@example.com',
            'password' => Hash::make('password'),
            'role' => 'pembina'
        ]);

        $coach2 = Coach::create([
            'user_id' => $pembinaUser2->id,
            'specialty' => 'Seni',
            'experience' => 3,
            'certificates' => 'Sertifikat Pelatih Seni'
        ]);

        $pembinaUser3 = User::create([
            'name' => 'Pembina Akademik',
            'email' => 'pembina.akademik@example.com',
            'password' => Hash::make('password'),
            'role' => 'pembina'
        ]);

        $coach3 = Coach::create([
            'user_id' => $pembinaUser3->id,
            'specialty' => 'Akademik',
            'experience' => 4,
            'certificates' => 'Sertifikat Pelatih Akademik'
        ]);

        // Create Extracurricular Activities
        $futsal = Extracurricular::create([
            'name' => 'Futsal',
            'description' => 'Kegiatan ekstrakurikuler olahraga futsal yang melatih keterampilan bermain futsal, kerja sama tim, dan kebugaran fisik.',
            'capacity' => 20,
            'schedule' => 'Selasa & Kamis, 15:00 - 17:00',
            'coach_id' => $coach1->id,
            'location' => 'Lapangan Futsal Sekolah',
            'status' => 'active'
        ]);

        $basket = Extracurricular::create([
            'name' => 'Basket',
            'description' => 'Kegiatan ekstrakurikuler olahraga basket yang melatih keterampilan bermain basket, kerja sama tim, dan kebugaran fisik.',
            'capacity' => 15,
            'schedule' => 'Senin & Rabu, 15:00 - 17:00',
            'coach_id' => $coach1->id,
            'location' => 'Lapangan Basket Sekolah',
            'status' => 'active'
        ]);

        $music = Extracurricular::create([
            'name' => 'Musik',
            'description' => 'Kegiatan ekstrakurikuler seni musik yang mengembangkan keterampilan bermain alat musik, bernyanyi, dan kreativitas dalam bermusik.',
            'capacity' => 20,
            'schedule' => 'Jumat, 14:00 - 16:00',
            'coach_id' => $coach2->id,
            'location' => 'Ruang Musik',
            'status' => 'active'
        ]);

        $dance = Extracurricular::create([
            'name' => 'Tari',
            'description' => 'Kegiatan ekstrakurikuler seni tari yang mempelajari berbagai jenis tarian tradisional dan modern.',
            'capacity' => 15,
            'schedule' => 'Selasa & Kamis, 14:00 - 16:00',
            'coach_id' => $coach2->id,
            'location' => 'Ruang Tari',
            'status' => 'active'
        ]);

        $scienceClub = Extracurricular::create([
            'name' => 'KIR (Kelompok Ilmiah Remaja)',
            'description' => 'Kegiatan ekstrakurikuler akademik yang mempelajari dan melakukan penelitian ilmiah pada berbagai bidang sains.',
            'capacity' => 20,
            'schedule' => 'Rabu, 15:00 - 17:00',
            'coach_id' => $coach3->id,
            'location' => 'Laboratorium Sains',
            'status' => 'active'
        ]);

        $debateClub = Extracurricular::create([
            'name' => 'Debat',
            'description' => 'Kegiatan ekstrakurikuler akademik yang melatih keterampilan berdebat, berpikir kritis, dan berbicara di depan umum.',
            'capacity' => 15,
            'schedule' => 'Senin, 15:00 - 17:00',
            'coach_id' => $coach3->id,
            'location' => 'Ruang Kelas 2-A',
            'status' => 'active'
        ]);

        // Create Student Users
        $studentNames = [
            'Ahmad Fadillah',
            'Budi Santoso',
            'Citra Dewi',
            'Dian Pratama',
            'Eko Saputra',
            'Fitri Handayani',
            'Gita Nurhasanah',
            'Hadi Wijaya',
            'Indah Permata',
            'Joko Susilo',
            'Kurnia Sari',
            'Lutfi Rahman',
            'Mira Lestari',
            'Nanda Putra',
            'Oktavia Rahmawati',
            'Putri Anggraini',
            'Qori Maulana',
            'Rina Fitriani',
            'Surya Darma',
            'Tika Maharani'
        ];

        $classes = ['10-A', '10-B', '11-A', '11-B', '12-A', '12-B'];
        $students = [];

        foreach ($studentNames as $index => $name) {
            $nameSlug = strtolower(str_replace(' ', '.', $name));
            $studentUser = User::create([
                'name' => $name,
                'email' => $nameSlug . '@example.com',
                'password' => Hash::make('password'),
                'role' => 'siswa'
            ]);

            $student = Student::create([
                'user_id' => $studentUser->id,
                'nis' => '1000' . ($index + 1),
                'kelas' => $classes[array_rand($classes)],
                'gender' => $index % 2 == 0 ? 'L' : 'P',
                'birthdate' => '2000-01-01',
                'address' => 'Jl. Contoh No. ' . ($index + 1),
                'phone' => '08123456' . str_pad($index + 1, 4, '0', STR_PAD_LEFT),
                'academic_score' => rand(70, 95)
            ]);

            $students[] = $student;

            // Create Interests for each student
            $categories = ['olahraga', 'seni', 'akademik', 'teknologi', 'sosial', 'leadership', 'keagamaan'];

            foreach ($categories as $category) {
                Interest::create([
                    'student_id' => $student->id,
                    'category' => $category,
                    'score' => rand(1, 5)
                ]);
            }
        }

        // Create Enrollments
        $extracurriculars = [$futsal, $basket, $music, $dance, $scienceClub, $debateClub];
        $academicYear = '2024/2025';
        $statuses = ['pending', 'approved', 'approved', 'approved', 'completed'];

        foreach ($students as $student) {
            // Each student enrolls in 1-3 random extracurriculars
            $enrollCount = rand(1, 3);
            $selectedExtracurriculars = array_rand($extracurriculars, $enrollCount);

            if (!is_array($selectedExtracurriculars)) {
                $selectedExtracurriculars = [$selectedExtracurriculars];
            }

            foreach ($selectedExtracurriculars as $key) {
                $enrollment = Enrollment::create([
                    'student_id' => $student->id,
                    'extracurricular_id' => $extracurriculars[$key]->id,
                    'academic_year' => $academicYear,
                    'status' => $statuses[array_rand($statuses)]
                ]);

                // Only create meetings and attendance for approved or completed enrollments
                if ($enrollment->status == 'approved' || $enrollment->status == 'completed') {
                    // Create meetings for this extracurricular if not created yet
                    $meetingCount = Meeting::where('extracurricular_id', $extracurriculars[$key]->id)->count();

                    if ($meetingCount == 0) {
                        // Create 10 meetings
                        for ($i = 1; $i <= 10; $i++) {
                            $meeting = Meeting::create([
                                'extracurricular_id' => $extracurriculars[$key]->id,
                                'title' => 'Pertemuan ke-' . $i,
                                'description' => 'Deskripsi kegiatan pertemuan ke-' . $i,
                                'date' => now()->subDays(70 - $i * 7), // Weekly meetings
                                'duration' => 120, // 2 hours
                                'status' => $i <= 8 ? 'completed' : ($i == 9 ? 'ongoing' : 'scheduled')
                            ]);

                            // Create attendance record for completed meetings
                            if ($i <= 8) {
                                $statuses = ['hadir', 'hadir', 'hadir', 'hadir', 'izin', 'sakit', 'alpha'];

                                Attendance::create([
                                    'enrollment_id' => $enrollment->id,
                                    'meeting_id' => $meeting->id,
                                    'status' => $statuses[array_rand($statuses)],
                                    'notes' => null
                                ]);
                            }
                        }
                    } else {
                        // Meetings already created, just add attendance
                        $meetings = Meeting::where('extracurricular_id', $extracurriculars[$key]->id)
                            ->where('status', 'completed')
                            ->get();

                        foreach ($meetings as $meeting) {
                            $statuses = ['hadir', 'hadir', 'hadir', 'hadir', 'izin', 'sakit', 'alpha'];

                            Attendance::create([
                                'enrollment_id' => $enrollment->id,
                                'meeting_id' => $meeting->id,
                                'status' => $statuses[array_rand($statuses)],
                                'notes' => null
                            ]);
                        }
                    }

                    // Create achievements for some students
                    if (rand(1, 5) == 1) { // 20% chance
                        $achievementTitles = [
                            'Juara 1 Kompetisi Antar Sekolah',
                            'Finalis Lomba Tingkat Kota',
                            'Peserta Terbaik',
                            'Penampil Terbaik',
                            'Kontributor Terbaik'
                        ];

                        $levels = ['sekolah', 'kecamatan', 'kabupaten', 'provinsi', 'nasional'];

                        Achievement::create([
                            'student_id' => $student->id,
                            'extracurricular_id' => $extracurriculars[$key]->id,
                            'title' => $achievementTitles[array_rand($achievementTitles)],
                            'description' => 'Deskripsi prestasi yang diraih dalam kegiatan ekstrakurikuler',
                            'date' => now()->subDays(rand(10, 60)),
                            'certificate' => null,
                            'level' => $levels[array_rand($levels)]
                        ]);
                    }
                }
            }
        }
    }
}
