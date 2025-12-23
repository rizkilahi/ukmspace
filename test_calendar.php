<?php

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\Event;

$events = Event::with('ukm')->get();

echo "Total events: " . $events->count() . "\n\n";

foreach($events as $event) {
    echo "✅ {$event->title}\n";
    echo "   Date: {$event->event_date->format('Y-m-d')}\n";
    echo "   Status: {$event->status}\n";
    echo "   UKM: {$event->ukm->name}\n\n";
}

echo "\nCalendar is ready to display these events!\n";
echo "Visit: http://localhost:8000/calendar\n";
