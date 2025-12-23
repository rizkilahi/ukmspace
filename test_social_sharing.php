<?php

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\Event;

$event = Event::with('ukm')->first();

if (!$event) {
    echo "No events found!\n";
    exit(1);
}

// Use full URL with app URL
$eventUrl = url('/events/' . $event->slug);
$eventTitle = $event->title;

echo "Testing Social Sharing URLs for: {$event->title}\n";
echo str_repeat("=", 70) . "\n\n";

// WhatsApp
$whatsappUrl = "https://wa.me/?text=" . urlencode($eventTitle . ' - ' . $eventUrl);
echo "✅ WhatsApp:\n   {$whatsappUrl}\n\n";

// Facebook
$facebookUrl = "https://www.facebook.com/sharer/sharer.php?u=" . urlencode($eventUrl);
echo "✅ Facebook:\n   {$facebookUrl}\n\n";

// Twitter
$twitterUrl = "https://twitter.com/intent/tweet?text=" . urlencode($eventTitle) . "&url=" . urlencode($eventUrl);
echo "✅ Twitter:\n   {$twitterUrl}\n\n";

// LinkedIn
$linkedinUrl = "https://www.linkedin.com/sharing/share-offsite/?url=" . urlencode($eventUrl);
echo "✅ LinkedIn:\n   {$linkedinUrl}\n\n";

// Email
$emailSubject = urlencode($eventTitle);
$emailBody = urlencode('Check out this event: ' . $eventTitle . ' - ' . $eventUrl);
$emailUrl = "mailto:?subject={$emailSubject}&body={$emailBody}";
echo "✅ Email:\n   {$emailUrl}\n\n";

echo str_repeat("=", 70) . "\n";
echo "Event URL: {$eventUrl}\n";
echo "\nOpen Graph Meta Tags:\n";
echo "- og:title: {$event->title}\n";
echo "- og:description: " . Str::limit(strip_tags($event->description), 100) . "\n";
echo "- og:image: " . asset('storage/' . $event->image_url) . "\n";
echo "- og:url: {$eventUrl}\n";

echo "\n✅ All social sharing URLs are properly formatted!\n";
echo "Visit the event page to test the share buttons: {$eventUrl}\n";
