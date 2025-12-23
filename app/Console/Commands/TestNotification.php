<?php

namespace App\Console\Commands;

use App\Models\EventRegistration;
use App\Notifications\RegistrationStatusChanged;
use Illuminate\Console\Command;

class TestNotification extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'test:notification {registration_id? : The registration ID to test}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Test the registration status notification email';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $registrationId = $this->argument('registration_id');

        if (!$registrationId) {
            // Get first registration or create a test scenario
            $registration = EventRegistration::with(['user', 'event.ukm'])->first();

            if (!$registration) {
                $this->error('No registrations found in database.');
                $this->info('Please create at least one event registration first.');
                return 1;
            }
        } else {
            $registration = EventRegistration::with(['user', 'event.ukm'])->find($registrationId);

            if (!$registration) {
                $this->error("Registration with ID {$registrationId} not found.");
                return 1;
            }
        }

        $this->info("Testing notification for:");
        $this->line("User: {$registration->user->name} ({$registration->user->email})");
        $this->line("Event: {$registration->event->title}");
        $this->line("Current Status: {$registration->status}");
        $this->newLine();

        // Test approved notification
        $this->info("Sending 'approved' notification...");
        $registration->user->notify(new RegistrationStatusChanged(
            $registration,
            'pending',
            'approved'
        ));

        $this->newLine();
        $this->info("✅ Notification sent successfully!");
        $this->newLine();

        if (config('mail.default') === 'log') {
            $this->warn("📧 Mail driver is set to 'log'");
            $this->info("Check the email content in: storage/logs/laravel.log");
            $this->newLine();
            $this->info("To send real emails:");
            $this->line("1. Configure mail settings in .env (see docs/EMAIL_SETUP.md)");
            $this->line("2. Run: php artisan config:clear");
            $this->line("3. For testing, use Mailtrap: https://mailtrap.io");
        } else {
            $this->info("📧 Mail driver: " . config('mail.default'));
            $this->info("Email should arrive at: {$registration->user->email}");
        }

        return 0;
    }
}
