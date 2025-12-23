<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use App\Models\User;
use App\Models\UKM;
use App\Models\Event;
use App\Models\EventRegistration;
use Carbon\Carbon;

class CompleteDataSeeder extends Seeder
{
    /**
     * Download image from URL and save to storage.
     */
    private function downloadImage($url, $path)
    {
        try {
            $imageContent = @file_get_contents($url);
            if ($imageContent !== false) {
                Storage::disk('public')->put($path, $imageContent);
                return $path;
            }
        } catch (\Exception $e) {
            $this->command->warn("Failed to download image from {$url}");
        }
        return null;
    }

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Disable foreign key checks
        \DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        // Clear existing data
        EventRegistration::truncate();
        Event::truncate();
        User::where('role', '!=', 'admin')->delete();
        UKM::truncate();

        // Re-enable foreign key checks
        \DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        // Create storage directories if they don't exist
        Storage::disk('public')->makeDirectory('logos');
        Storage::disk('public')->makeDirectory('events');

        $this->command->info('Downloading images...');

        // Download UKM logos
        $logos = [
            'robotics.png' => $this->downloadImage('https://picsum.photos/seed/robotics/400/400', 'logos/robotics.png'),
            'photography.png' => $this->downloadImage('https://picsum.photos/seed/photography/400/400', 'logos/photography.png'),
            'music.png' => $this->downloadImage('https://picsum.photos/seed/music/400/400', 'logos/music.png'),
        ];

        $this->command->info('Creating UKMs...');

        // Create UKMs
        $ukms = [
            [
                'name' => 'UKM Robotics',
                'description' => 'Student organization focused on robotics, automation, and artificial intelligence.',
                'email' => 'robotics@university.com',
                'phone' => '081234567890',
                'address' => 'Lab Robotika Lantai 3, Gedung Teknik',
                'website' => 'https://robotics.university.com',
                'established_date' => '2020-01-15',
                'password' => Hash::make('password'),
                'logo' => $logos['robotics.png'],
                'verification_status' => 'active',
            ],
            [
                'name' => 'UKM Photography',
                'description' => 'Capturing moments and developing photography skills through workshops and projects.',
                'email' => 'photo@university.com',
                'phone' => '081234567891',
                'address' => 'Studio Fotografi, Gedung Seni',
                'website' => 'https://photo.university.com',
                'established_date' => '2019-08-20',
                'password' => Hash::make('password'),
                'logo' => $logos['photography.png'],
                'verification_status' => 'active',
            ],
            [
                'name' => 'UKM Music',
                'description' => 'Developing musical talents through performances, workshops, and collaborations.',
                'email' => 'music@university.com',
                'phone' => '081234567892',
                'address' => 'Ruang Musik, Gedung Kesenian',
                'website' => 'https://music.university.com',
                'established_date' => '2018-03-10',
                'password' => Hash::make('password'),
                'logo' => $logos['music.png'],
                'verification_status' => 'active',
            ],
        ];

        $createdUkms = [];
        foreach ($ukms as $ukmData) {
            $createdUkms[] = UKM::create($ukmData);
        }

        $this->command->info('Creating Users...');

        // Create UKM Coordinators
        $coordinators = [];
        foreach ($createdUkms as $index => $ukm) {
            $coordinators[] = User::create([
                'name' => 'Coordinator ' . $ukm->name,
                'email' => 'coordinator' . ($index + 1) . '@university.com',
                'password' => Hash::make('password'),
                'phone' => '0812345678' . (90 + $index),
                'role' => 'ukm',
                'ukm_id' => $ukm->id,
            ]);
        }

        // Create Student Users
        $students = [];
        $studentNames = [
            'Ahmad Rizki', 'Siti Nurhaliza', 'Budi Santoso', 'Dewi Lestari',
            'Eko Prasetyo', 'Fitri Handayani', 'Gilang Ramadhan', 'Hana Pertiwi',
            'Irfan Hakim', 'Julia Rahmawati', 'Kevin Wijaya', 'Lina Marlina',
            'Muhammad Fadli', 'Nadia Putri', 'Omar Abdullah', 'Putri Ayu',
            'Qori Syahputra', 'Rina Susanti', 'Satria Putra', 'Tania Wulandari'
        ];

        foreach ($studentNames as $index => $name) {
            $students[] = User::create([
                'name' => $name,
                'email' => 'student' . ($index + 1) . '@university.com',
                'password' => Hash::make('password'),
                'phone' => '0856789' . str_pad($index, 5, '0', STR_PAD_LEFT),
                'role' => 'user',
            ]);
        }

        $this->command->info('Creating Events...');

        // Download event images
        $eventImages = [
            'arduino.jpg' => $this->downloadImage('https://picsum.photos/seed/arduino/800/600', 'events/arduino.jpg'),
            'ai.jpg' => $this->downloadImage('https://picsum.photos/seed/ai/800/600', 'events/ai.jpg'),
            'robot-comp.jpg' => $this->downloadImage('https://picsum.photos/seed/robotcomp/800/600', 'events/robot-comp.jpg'),
            'iot.jpg' => $this->downloadImage('https://picsum.photos/seed/iot/800/600', 'events/iot.jpg'),
            'portrait.jpg' => $this->downloadImage('https://picsum.photos/seed/portrait/800/600', 'events/portrait.jpg'),
            'street.jpg' => $this->downloadImage('https://picsum.photos/seed/street/800/600', 'events/street.jpg'),
            'product.jpg' => $this->downloadImage('https://picsum.photos/seed/product/800/600', 'events/product.jpg'),
            'exhibition.jpg' => $this->downloadImage('https://picsum.photos/seed/exhibition/800/600', 'events/exhibition.jpg'),
            'guitar.jpg' => $this->downloadImage('https://picsum.photos/seed/guitar/800/600', 'events/guitar.jpg'),
            'vocal.jpg' => $this->downloadImage('https://picsum.photos/seed/vocal/800/600', 'events/vocal.jpg'),
            'band.jpg' => $this->downloadImage('https://picsum.photos/seed/band/800/600', 'events/band.jpg'),
            'festival.jpg' => $this->downloadImage('https://picsum.photos/seed/festival/800/600', 'events/festival.jpg'),
        ];

        // Create Events for each UKM
        $events = [];

        // UKM Robotics Events
        $roboticsEvents = [
            [
                'title' => 'Introduction to Arduino Workshop',
                'description' => 'Learn the basics of Arduino programming and build your first robot. Perfect for beginners who want to start their journey in robotics and embedded systems.',
                'location' => 'Lab Robotika Lantai 3, Gedung Teknik',
                'event_date' => Carbon::now()->subDays(45),
                'capacity' => 30,
                'image_url' => $eventImages['arduino.jpg'],
            ],
            [
                'title' => 'AI & Machine Learning Workshop',
                'description' => 'Explore artificial intelligence and machine learning concepts with practical examples. Learn how to implement basic AI algorithms and neural networks.',
                'location' => 'Ruang Seminar A, Gedung Pascasarjana',
                'event_date' => Carbon::now()->subDays(30),
                'capacity' => 40,
                'image_url' => $eventImages['ai.jpg'],
            ],
            [
                'title' => 'Robot Competition Preparation',
                'description' => 'Intensive training for upcoming national robot competition. Teams will work on line follower, sumo robot, and autonomous navigation challenges.',
                'location' => 'Arena Robotika, Gedung Olahraga',
                'event_date' => Carbon::now()->addDays(15),
                'capacity' => 25,
                'image_url' => $eventImages['robot-comp.jpg'],
            ],
            [
                'title' => 'IoT Development Bootcamp',
                'description' => 'Three-day intensive bootcamp on Internet of Things development. Build smart home devices and learn cloud integration.',
                'location' => 'Innovation Center Lantai 2',
                'event_date' => Carbon::now()->addDays(45),
                'capacity' => 35,
                'image_url' => $eventImages['iot.jpg'],
            ],
        ];

        foreach ($roboticsEvents as $eventData) {
            $events[] = Event::create(array_merge($eventData, ['ukm_id' => $createdUkms[0]->id]));
        }

        // UKM Photography Events
        $photoEvents = [
            [
                'title' => 'Portrait Photography Masterclass',
                'description' => 'Master the art of portrait photography with professional techniques. Learn about lighting, composition, and post-processing for stunning portraits.',
                'location' => 'Studio Fotografi, Gedung Seni',
                'event_date' => Carbon::now()->subDays(50),
                'capacity' => 20,
                'image_url' => $eventImages['portrait.jpg'],
            ],
            [
                'title' => 'Street Photography Walk',
                'description' => 'Capture the essence of urban life in this guided street photography session. Learn candid photography techniques and storytelling through images.',
                'location' => 'Kota Tua Jakarta',
                'event_date' => Carbon::now()->subDays(20),
                'capacity' => 25,
                'image_url' => $eventImages['street.jpg'],
            ],
            [
                'title' => 'Product Photography Workshop',
                'description' => 'Professional product photography for e-commerce and marketing. Learn studio setup, lighting techniques, and editing workflow.',
                'location' => 'Studio Commercial, Gedung Bisnis',
                'event_date' => Carbon::now()->addDays(10),
                'capacity' => 15,
                'image_url' => $eventImages['product.jpg'],
            ],
            [
                'title' => 'Photo Exhibition: Campus Life',
                'description' => 'Annual photography exhibition showcasing the best works from our members. Theme: Life on Campus.',
                'location' => 'Galeri Seni Universitas',
                'event_date' => Carbon::now()->addDays(60),
                'capacity' => 100,
                'image_url' => $eventImages['exhibition.jpg'],
            ],
        ];

        foreach ($photoEvents as $eventData) {
            $events[] = Event::create(array_merge($eventData, ['ukm_id' => $createdUkms[1]->id]));
        }

        // UKM Music Events
        $musicEvents = [
            [
                'title' => 'Guitar Basics for Beginners',
                'description' => 'Start your musical journey with basic guitar techniques. Learn chords, strumming patterns, and play your first songs.',
                'location' => 'Ruang Musik Lantai 1, Gedung Kesenian',
                'event_date' => Carbon::now()->subDays(35),
                'capacity' => 20,
                'image_url' => $eventImages['guitar.jpg'],
            ],
            [
                'title' => 'Vocal Training Workshop',
                'description' => 'Improve your singing with professional vocal techniques. Learn breathing, pitch control, and stage performance.',
                'location' => 'Studio Vokal, Gedung Kesenian',
                'event_date' => Carbon::now()->subDays(10),
                'capacity' => 15,
                'image_url' => $eventImages['vocal.jpg'],
            ],
            [
                'title' => 'Band Formation & Collaboration',
                'description' => 'Form bands and collaborate with other musicians. Practice sessions and prepare for upcoming performances.',
                'location' => 'Ruang Rehearsal Utama',
                'event_date' => Carbon::now()->addDays(5),
                'capacity' => 30,
                'image_url' => $eventImages['band.jpg'],
            ],
            [
                'title' => 'Annual Music Festival',
                'description' => 'The biggest music event of the year! Featuring student bands, solo performances, and special guest artists.',
                'location' => 'Auditorium Utama Universitas',
                'event_date' => Carbon::now()->addDays(75),
                'capacity' => 500,
                'image_url' => $eventImages['festival.jpg'],
            ],
        ];

        foreach ($musicEvents as $eventData) {
            $events[] = Event::create(array_merge($eventData, ['ukm_id' => $createdUkms[2]->id]));
        }

        $this->command->info('Creating Event Registrations...');

        // Create registrations with various statuses and attendance
        $statuses = ['accepted', 'pending', 'rejected'];
        $checkInMethods = ['qr', 'manual'];

        foreach ($events as $eventIndex => $event) {
            // Determine number of registrations based on event (past events have more registrations)
            $isPastEvent = $event->event_date < Carbon::now();
            $registrationCount = $isPastEvent ? rand(15, 25) : rand(5, 15);

            // Shuffle students to get random registrants
            $shuffledStudents = collect($students)->shuffle();

            for ($i = 0; $i < min($registrationCount, count($students)); $i++) {
                $student = $shuffledStudents[$i];

                // Past events: mostly accepted with attendance
                if ($isPastEvent) {
                    $status = $i < $registrationCount * 0.8 ? 'accepted' : ($i < $registrationCount * 0.9 ? 'rejected' : 'pending');
                    $checkedIn = ($status === 'accepted' && rand(1, 100) <= 85); // 85% attendance rate
                } else {
                    // Future/upcoming events: mix of statuses, no check-ins yet
                    $status = $i < $registrationCount * 0.6 ? 'accepted' : ($i < $registrationCount * 0.8 ? 'pending' : 'rejected');
                    $checkedIn = false;
                }

                $registration = EventRegistration::create([
                    'event_id' => $event->id,
                    'user_id' => $student->id,
                    'status' => $status,
                    'created_at' => Carbon::now()->subDays(rand(1, 60)),
                ]);

                // Add check-in data for attended registrations
                if ($checkedIn) {
                    $checkInTime = $event->event_date->copy()->addMinutes(rand(-15, 30));
                    $registration->update([
                        'checked_in_at' => $checkInTime,
                        'check_in_method' => $checkInMethods[array_rand($checkInMethods)],
                    ]);
                }
            }
        }

        $this->command->info('Database seeding completed successfully!');
        $this->command->info('');
        $this->command->info('=== LOGIN CREDENTIALS ===');
        $this->command->info('');
        $this->command->info('UKM Coordinators:');
        foreach ($coordinators as $index => $coordinator) {
            $this->command->info("  {$coordinator->name}");
            $this->command->info("  Email: {$coordinator->email}");
            $this->command->info("  Password: password");
            $this->command->info('');
        }
        $this->command->info('Students (sample):');
        for ($i = 0; $i < 5; $i++) {
            $this->command->info("  {$students[$i]->name}");
            $this->command->info("  Email: {$students[$i]->email}");
            $this->command->info("  Password: password");
            $this->command->info('');
        }
        $this->command->info('Total Users: ' . User::count());
        $this->command->info('Total UKMs: ' . UKM::count());
        $this->command->info('Total Events: ' . Event::count());
        $this->command->info('Total Registrations: ' . EventRegistration::count());
        $this->command->info('Checked-in Registrations: ' . EventRegistration::whereNotNull('checked_in_at')->count());
    }
}
