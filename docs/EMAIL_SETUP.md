# Email Notification Setup Guide

## Overview

UKMSpace sends automatic email notifications when event registration status changes (approved/rejected). This guide helps you configure email sending.

## Development Environment (Log Driver)

By default, emails are logged to `storage/logs/laravel.log` instead of being sent. This is perfect for development.

**Configuration (.env):**

```env
MAIL_MAILER=log
MAIL_FROM_ADDRESS="noreply@ukmspace.test"
MAIL_FROM_NAME="UKMSpace"
```

**Testing:**

1. Update a registration status in the admin panel
2. Check `storage/logs/laravel.log` for the email content
3. You'll see the complete email body and recipient details

## Testing with Mailtrap

Mailtrap is a free email testing service that catches all emails sent from your application.

**Steps:**

1. Sign up at [mailtrap.io](https://mailtrap.io)
2. Get your SMTP credentials from the inbox settings
3. Update `.env`:

```env
MAIL_MAILER=smtp
MAIL_HOST=sandbox.smtp.mailtrap.io
MAIL_PORT=2525
MAIL_USERNAME=your-mailtrap-username
MAIL_PASSWORD=your-mailtrap-password
MAIL_ENCRYPTION=null
MAIL_FROM_ADDRESS="noreply@ukmspace.test"
MAIL_FROM_NAME="UKMSpace"
```

4. Run `php artisan config:clear`
5. Test by updating a registration status
6. Check your Mailtrap inbox to see the email

## Production Setup (Gmail)

**Prerequisites:**

-   Gmail account with 2FA enabled
-   App-specific password generated

**Steps to generate App Password:**

1. Go to [Google Account Security](https://myaccount.google.com/security)
2. Enable 2-Step Verification
3. Go to "App passwords"
4. Generate a new app password for "Mail"
5. Copy the 16-character password

**Configuration (.env):**

```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=your-email@gmail.com
MAIL_PASSWORD=your-16-character-app-password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=your-email@gmail.com
MAIL_FROM_NAME="UKMSpace"
```

**After configuration:**

```bash
php artisan config:clear
php artisan queue:work
```

## Using Queue for Better Performance

Email notifications implement `ShouldQueue`, meaning they'll be sent in the background if you configure queues.

**Setup:**

1. Set `QUEUE_CONNECTION=database` in `.env` (already configured)
2. Run migrations (already done)
3. Start queue worker:

```bash
php artisan queue:work
```

**For production (supervisor recommended):**

```bash
# Install supervisor
sudo apt-get install supervisor

# Create supervisor config
sudo nano /etc/supervisor/conf.d/ukmspace-queue.conf
```

Add:

```ini
[program:ukmspace-queue]
process_name=%(program_name)s_%(process_num)02d
command=php /path/to/ukmspace/artisan queue:work --sleep=3 --tries=3 --max-time=3600
autostart=true
autorestart=true
stopasgroup=true
killasgroup=true
user=www-data
numprocs=2
redirect_stderr=true
stdout_logfile=/path/to/ukmspace/storage/logs/queue.log
stopwaitsecs=3600
```

Restart supervisor:

```bash
sudo supervisorctl reread
sudo supervisorctl update
sudo supervisorctl start ukmspace-queue:*
```

## Other Email Providers

### SendGrid

```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.sendgrid.net
MAIL_PORT=587
MAIL_USERNAME=apikey
MAIL_PASSWORD=your-sendgrid-api-key
MAIL_ENCRYPTION=tls
```

### Mailgun

```env
MAIL_MAILER=mailgun
MAILGUN_DOMAIN=your-domain.mailgun.org
MAILGUN_SECRET=your-mailgun-api-key
```

### Amazon SES

```env
MAIL_MAILER=ses
AWS_ACCESS_KEY_ID=your-access-key
AWS_SECRET_ACCESS_KEY=your-secret-key
AWS_DEFAULT_REGION=us-east-1
```

## Troubleshooting

### Emails not sending:

```bash
# Clear config cache
php artisan config:clear

# Check queue status
php artisan queue:failed

# Retry failed jobs
php artisan queue:retry all

# Test mail configuration
php artisan tinker
>>> Notification::route('mail', 'test@example.com')->notify(new App\Notifications\RegistrationStatusChanged(App\Models\EventRegistration::first(), 'pending', 'approved'));
```

### Check logs:

```bash
tail -f storage/logs/laravel.log
```

### Permission issues:

```bash
chmod -R 775 storage
chown -R www-data:www-data storage
```

## Email Customization

Email templates are located in `resources/views/vendor/mail/`. You can customize:

-   `html/header.blade.php` - Email header with logo
-   `html/footer.blade.php` - Email footer
-   `html/button.blade.php` - Button styling
-   `html/themes/default.css` - Overall email styling

After customization, no cache clearing is needed as views are compiled on the fly in development.

## Email Content

The notification includes:

-   **Subject:** "Registration {status} for {Event Title}"
-   **Greeting:** Personalized based on status (approved/rejected)
-   **Event details:** Date, location, organizer
-   **Action button:** Links to event details or browse events
-   **Signature:** From the UKM organizing the event

## Testing Checklist

-   [ ] Send test email in development (log driver)
-   [ ] Verify email content and formatting
-   [ ] Test with Mailtrap to check actual email rendering
-   [ ] Test queue processing
-   [ ] Verify approved status email
-   [ ] Verify rejected status email
-   [ ] Check email arrives in inbox (not spam)
-   [ ] Test on mobile email clients
-   [ ] Verify links work correctly
-   [ ] Check email load time and images

## Security Notes

1. **Never commit `.env` file** - It contains sensitive credentials
2. **Use app passwords** - Don't use your main email password
3. **Enable rate limiting** - Prevent spam abuse
4. **Monitor queue** - Watch for failed jobs
5. **Use TLS/SSL** - Always encrypt email transmission
6. **Validate recipients** - Ensure email addresses are valid before sending
