<?php

require_once __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\User;
use App\Models\UKM;
use App\Models\Event;

// Create admin user
User::create([
    'name' => 'Admin UKMSpace',
    'email' => 'admin@ukmspace.com',
    'password' => bcrypt('password'),
    'phone' => '081234567890',
    'role' => 'admin'
]);

// Create UKM
$ukm1 = UKM::create([
    'name' => 'UKM Robotics',
    'description' => 'Unit Kegiatan Mahasiswa yang fokus pada pengembangan teknologi robotika',
    'email' => 'robotics@university.com',
    'password' => bcrypt('password'),
    'verification_status' => 'active',
    'address' => 'Gedung Teknik Lantai 3',
    'phone' => '081234567891',
    'website' => 'https://robotics.university.com',
    'established_date' => now()->subYears(5)
]);

$ukm2 = UKM::create([
    'name' => 'UKM Photography',
    'description' => 'Komunitas fotografi untuk mengembangkan kemampuan visual dan artistik',
    'email' => 'photo@university.com',
    'password' => bcrypt('password'),
    'verification_status' => 'active',
    'address' => 'Gedung Seni Lantai 2',
    'phone' => '081234567892',
    'website' => 'https://photo.university.com',
    'established_date' => now()->subYears(3)
]);

// Create UKM user accounts
User::create([
    'name' => 'Manager Robotics',
    'email' => 'robotics@university.com',
    'password' => bcrypt('password'),
    'phone' => '081234567891',
    'role' => 'ukm',
    'ukm_id' => $ukm1->id
]);

User::create([
    'name' => 'Manager Photography',
    'email' => 'photo@university.com',
    'password' => bcrypt('password'),
    'phone' => '081234567892',
    'role' => 'ukm',
    'ukm_id' => $ukm2->id
]);

// Create regular users
User::create([
    'name' => 'Mahasiswa Satu',
    'email' => 'mahasiswa1@university.com',
    'password' => bcrypt('password'),
    'phone' => '081234567893',
    'role' => 'user'
]);

User::create([
    'name' => 'Mahasiswa Dua',
    'email' => 'mahasiswa2@university.com',
    'password' => bcrypt('password'),
    'phone' => '081234567894',
    'role' => 'user'
]);

// Create events
Event::create([
    'title' => 'Workshop Arduino Basic',
    'description' => 'Workshop dasar penggunaan Arduino untuk pemula. Akan dipelajari komponen dasar, pemrograman, dan project sederhana.',
    'image_url' => 'robotics.jpg',
    'location' => 'Lab Robotika Gedung Teknik',
    'event_date' => now()->addDays(7),
    'ukm_id' => $ukm1->id
]);

Event::create([
    'title' => 'Kompetisi Robot Line Follower',
    'description' => 'Kompetisi tahunan robot line follower antar mahasiswa. Hadiah menarik untuk juara 1, 2, dan 3.',
    'image_url' => 'robotics.jpg',
    'location' => 'Auditorium Utama',
    'event_date' => now()->addDays(14),
    'ukm_id' => $ukm1->id
]);

Event::create([
    'title' => 'Photography Workshop: Portrait Photography',
    'description' => 'Belajar teknik fotografi portrait dari dasar hingga mahir. Termasuk lighting, komposisi, dan post-processing.',
    'image_url' => 'photography-club.jpg',
    'location' => 'Studio Fotografi Gedung Seni',
    'event_date' => now()->addDays(10),
    'ukm_id' => $ukm2->id
]);

Event::create([
    'title' => 'Photo Walk Campus',
    'description' => 'Jalan-jalan foto keliling kampus untuk menangkap moment dan keindahan arsitektur universitas.',
    'image_url' => 'photography-club.jpg',
    'location' => 'Meeting Point Rektorat',
    'event_date' => now()->addDays(5),
    'ukm_id' => $ukm2->id
]);

echo "Sample data created successfully!\n";
echo "Test accounts:\n";
echo "- Admin: admin@ukmspace.com / password\n";
echo "- UKM Robotics: robotics@university.com / password\n";
echo "- UKM Photography: photo@university.com / password\n";
echo "- Student 1: mahasiswa1@university.com / password\n";
echo "- Student 2: mahasiswa2@university.com / password\n";
