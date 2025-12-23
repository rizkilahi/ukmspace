<?php

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\User;
use App\Models\Event;
use App\Models\EventRegistration;

// Get a regular user and an event
$user = User::where('role', 'user')->first();
$event = Event::first();

if (!$user) {
    echo "No regular user found. Creating one...\n";
    $user = User::create([
        'name' => 'Test User',
        'email' => 'testuser@example.com',
        'password' => bcrypt('password'),
        'role' => 'user',
        'phone' => '081234567890',
    ]);
}

if (!$event) {
    echo "No event found. Please create an event first.\n";
    exit(1);
}

// Check if registration already exists
$existing = EventRegistration::where('user_id', $user->id)
    ->where('event_id', $event->id)
    ->first();

if ($existing) {
    echo "Registration already exists with ID: {$existing->id}\n";
    echo "User: {$user->name} ({$user->email})\n";
    echo "Event: {$event->title}\n";
    echo "Status: {$existing->status}\n";
} else {
    $registration = EventRegistration::create([
        'user_id' => $user->id,
        'event_id' => $event->id,
        'status' => 'pending',
    ]);

    echo "✅ Test registration created!\n";
    echo "Registration ID: {$registration->id}\n";
    echo "User: {$user->name} ({$user->email})\n";
    echo "Event: {$event->title}\n";
    echo "Status: {$registration->status}\n";
}

echo "\nNow run: php artisan test:notification\n";
