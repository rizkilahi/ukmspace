<?php

namespace App\Notifications;

use App\Models\Event;
use App\Models\EventRegistration;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class RegistrationStatusChanged extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct(
        public EventRegistration $registration,
        public string $oldStatus,
        public string $newStatus
    ) {
        //
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        $event = $this->registration->event;
        $ukm = $event->ukm;

        $message = (new MailMessage)
            ->subject("Registration {$this->newStatus} for {$event->title}");

        if ($this->newStatus === 'accepted') {
            $message->greeting('Great News!')
                ->line("Your registration for **{$event->title}** has been accepted!")
                ->line("**Event Details:**")
                ->line("📅 Date: {$event->event_date->format('l, F d, Y')}")
                ->line("📍 Location: {$event->location}")
                ->line("🏢 Organized by: {$ukm->name}")
                ->action('View Event Details', url('/events/' . $event->slug))
                ->line('We look forward to seeing you at the event!')
                ->salutation('Best regards, ' . $ukm->name);
        } elseif ($this->newStatus === 'rejected') {
            $message->greeting('Registration Update')
                ->line("We regret to inform you that your registration for **{$event->title}** has not been accepted at this time.")
                ->line("This could be due to limited capacity or specific requirements not being met.")
                ->line("**Event Details:**")
                ->line("📅 Date: {$event->event_date->format('l, F d, Y')}")
                ->line("📍 Location: {$event->location}")
                ->line("🏢 Organized by: {$ukm->name}")
                ->line('Please feel free to register for other events.')
                ->action('Browse Other Events', url('/events'))
                ->salutation('Best regards, ' . $ukm->name);
        } else {
            $message->greeting('Registration Status Update')
                ->line("The status of your registration for **{$event->title}** has been updated to: **{$this->newStatus}**")
                ->line("**Event Details:**")
                ->line("📅 Date: {$event->event_date->format('l, F d, Y')}")
                ->line("📍 Location: {$event->location}")
                ->action('View Event Details', url('/events/' . $event->slug))
                ->salutation('Best regards, ' . $ukm->name);
        }

        return $message;
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            //
        ];
    }
}
