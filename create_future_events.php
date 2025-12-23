<?php

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\Event;
use App\Models\UKM;

$ukms = UKM::all();

if ($ukms->count() === 0) {
    echo "No UKMs found!\n";
    exit(1);
}

$futureEvents = [
    [
        'title' => 'Web Development Workshop',
        'description' => 'Learn HTML, CSS, and JavaScript basics in this comprehensive workshop.',
        'location' => 'Computer Lab A',
        'event_date' => now()->addDays(5),
        'image_url' => 'events/default.jpg',
    ],
    [
        'title' => 'Mobile App Development Bootcamp',
        'description' => 'Build your first mobile app with Flutter in this hands-on bootcamp.',
        'location' => 'Innovation Center',
        'event_date' => now()->addDays(12),
        'image_url' => 'events/default.jpg',
    ],
    [
        'title' => 'Graphic Design Masterclass',
        'description' => 'Master Adobe Photoshop and Illustrator with industry professionals.',
        'location' => 'Design Studio',
        'event_date' => now()->addDays(18),
        'image_url' => 'events/default.jpg',
    ],
    [
        'title' => 'Photography Competition 2026',
        'description' => 'Showcase your best photography work and win amazing prizes.',
        'location' => 'Main Hall',
        'event_date' => now()->addDays(25),
        'image_url' => 'events/default.jpg',
    ],
    [
        'title' => 'Robotics Exhibition',
        'description' => 'Annual robotics exhibition showcasing student projects and innovations.',
        'location' => 'Engineering Building',
        'event_date' => now()->addDays(32),
        'image_url' => 'events/default.jpg',
    ],
    [
        'title' => 'AI & Machine Learning Seminar',
        'description' => 'Explore the future of AI and machine learning technologies.',
        'location' => 'Auditorium',
        'event_date' => now()->addMonths(2),
        'image_url' => 'events/default.jpg',
    ],
];

$created = 0;

foreach ($futureEvents as $eventData) {
    // Assign to random UKM
    $ukm = $ukms->random();
    $eventData['ukm_id'] = $ukm->id;

    // Check if similar event exists
    $existing = Event::where('title', $eventData['title'])->first();

    if (!$existing) {
        Event::create($eventData);
        echo "✅ Created: {$eventData['title']} - {$eventData['event_date']->format('M d, Y')}\n";
        $created++;
    } else {
        echo "⏭️  Skipped: {$eventData['title']} (already exists)\n";
    }
}

echo "\n✅ Created {$created} future events!\n";
echo "📊 Total events in database: " . Event::count() . "\n\n";
echo "Now visit http://localhost:8000/calendar to see the calendar view!\n";
