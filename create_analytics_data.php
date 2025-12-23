<?php

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\User;
use App\Models\Event;
use App\Models\EventRegistration;

// Get all regular users
$users = User::where('role', 'user')->get();
$events = Event::all();

if ($users->count() < 3) {
    echo "Creating additional test users...\n";
    for ($i = $users->count(); $i < 3; $i++) {
        User::create([
            'name' => 'Test User ' . ($i + 1),
            'email' => 'testuser' . ($i + 1) . '@example.com',
            'password' => bcrypt('password'),
            'role' => 'user',
            'phone' => '0812345678' . $i,
        ]);
    }
    $users = User::where('role', 'user')->get();
}

if ($events->count() === 0) {
    echo "No events found. Please create events first.\n";
    exit(1);
}

echo "Creating diverse test registrations...\n\n";

$statuses = ['pending', 'accepted', 'rejected'];
$created = 0;

// Create registrations for different events and users
foreach ($events as $event) {
    // Pick 2-4 random users for each event
    $eventUsers = $users->random(min(rand(2, 4), $users->count()));

    foreach ($eventUsers as $user) {
        // Check if registration already exists
        $existing = EventRegistration::where('user_id', $user->id)
            ->where('event_id', $event->id)
            ->first();

        if (!$existing) {
            $status = $statuses[array_rand($statuses)];

            // Create registration with random date in the past 3 months
            $createdAt = now()->subDays(rand(0, 90));

            $registration = EventRegistration::create([
                'user_id' => $user->id,
                'event_id' => $event->id,
                'status' => $status,
                'created_at' => $createdAt,
                'updated_at' => $createdAt,
            ]);

            echo "✅ Created: {$user->name} -> {$event->title} [{$status}]\n";
            $created++;
        }
    }
}

echo "\n";
echo "✅ Total new registrations created: {$created}\n";
echo "📊 Total registrations in database: " . EventRegistration::count() . "\n";
echo "\n";
echo "Now you can view analytics at: http://localhost:8000/ukm/analytics\n";
echo "(Login as a UKM coordinator)\n";
