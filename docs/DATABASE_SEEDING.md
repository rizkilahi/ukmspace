# Database Seeding Guide

## Overview

The CompleteDataSeeder provides comprehensive sample data to test all features of the UKM Space application. **It also downloads real images from the internet** for events and UKM logos.

## What Gets Seeded

### 1. UKMs (3 organizations)

-   **UKM Robotics**: Focus on robotics, automation, and AI
-   **UKM Photography**: Photography skills and exhibitions
-   **UKM Music**: Musical performances and training
-   **Real logos downloaded**: Each UKM gets a unique placeholder image (400x400px)

### 2. Users

-   **3 UKM Coordinators** (one for each UKM)
-   **20 Students** (participants)
-   All passwords: `password`

### 3. Events (12 total)

-   **4 events per UKM**
-   Mix of past, ongoing, and future events
-   Various capacities (15-500 attendees)
-   Realistic descriptions and locations
-   **Real images downloaded**: Each event gets a unique placeholder image (800x600px)

### 4. Event Registrations

-   **150-200+ registrations** across all events
-   Mixed statuses:
    -   **Accepted**: ~60-80% of registrations
    -   **Pending**: ~10-20%
    -   **Rejected**: ~10-20%

### 5. Attendance Data

-   Past events: ~85% attendance rate
-   Includes check-in timestamps
-   Mix of QR and manual check-ins

### 6. Images (Automatically Downloaded)

-   **3 UKM logos**: Saved to `storage/app/public/logos/`
-   **12 Event images**: Saved to `storage/app/public/events/`
-   Images are downloaded from Lorem Picsum (https://picsum.photos)
-   No manual image setup required!

## How to Seed

### Method 1: Direct Seeder Command (Recommended)

```bash
php artisan db:seed --class=CompleteDataSeeder
```

**Note**: First run may take longer as it downloads images from the internet.

### Method 2: Fresh Migration + Seed

```bash
php artisan migrate:fresh
php artisan db:seed --class=CompleteDataSeeder
```

### Method 3: Via DatabaseSeeder

Uncomment the line in `database/seeders/DatabaseSeeder.php`:

```php
$this->call(CompleteDataSeeder::class);
```

Then run:

```bash
php artisan db:seed
```

## Login Credentials

After seeding, you can log in with these accounts:

### UKM Coordinators

```
Email: coordinator1@university.com
Password: password
Role: UKM Coordinator (Robotics)

Email: coordinator2@university.com
Password: password
Role: UKM Coordinator (Photography)

Email: coordinator3@university.com
Password: password
Role: UKM Coordinator (Music)
```

### Students (Sample)

```
Email: student1@university.com to student20@university.com
Password: password
Role: Student/User
```

## What You Can Test

### 1. Email Notifications ✅

-   Log in as UKM coordinator
-   Go to event registrations
-   Change registration status (pending → accepted/rejected)
-   Email notification will be logged (check `storage/logs/laravel.log`)

### 2. Analytics Dashboard ✅

-   Log in as UKM coordinator
-   Navigate to: UKM > Analytics
-   View metrics, charts, and trends
-   See top performing events

### 3. Calendar View ✅

-   Access: Main Menu > Calendar
-   Switch between Month/Week/List views
-   Click events to view details
-   Events are color-coded

### 4. Social Media Sharing ✅

-   View any event details page
-   Click share buttons (WhatsApp, Facebook, Twitter, LinkedIn)
-   Try "Copy Link" functionality
-   View Open Graph meta tags in page source

### 5. QR Code Attendance ✅

-   Log in as UKM coordinator
-   Go to: Manage Events > [Select Event] > QR Code
-   View generated QR code
-   See attendance list and statistics
-   Try manual check-in/check-out toggle
-   Print or download QR code

### 6. Advanced Reports ✅

-   Log in as UKM coordinator
-   Navigate to: UKM > Reports
-   Filter by date range
-   View event performance report (click "Details" on any event)
-   Compare multiple events (click "Compare Events")
-   Test print functionality

## Event Distribution

### UKM Robotics (4 events)

1. **Introduction to Arduino Workshop** (Past - 45 days ago)
    - 15-25 registrations, ~85% attended
2. **AI & Machine Learning Workshop** (Past - 30 days ago)
    - 15-25 registrations, ~85% attended
3. **Robot Competition Preparation** (Upcoming - in 15 days)
    - 5-15 registrations, no attendance yet
4. **IoT Development Bootcamp** (Future - in 45 days)
    - 5-15 registrations, no attendance yet

### UKM Photography (4 events)

1. **Portrait Photography Masterclass** (Past - 50 days ago)
    - 15-25 registrations, ~85% attended
2. **Street Photography Walk** (Past - 20 days ago)
    - 15-25 registrations, ~85% attended
3. **Product Photography Workshop** (Upcoming - in 10 days)
    - 5-15 registrations, no attendance yet
4. **Photo Exhibition: Campus Life** (Future - in 60 days)
    - 5-15 registrations, no attendance yet

### UKM Music (4 events)

1. **Guitar Basics for Beginners** (Past - 35 days ago)
    - 15-25 registrations, ~85% attended
2. **Vocal Training Workshop** (Past - 10 days ago)
    - 15-25 registrations, ~85% attended
3. **Band Formation & Collaboration** (Upcoming - in 5 days)
    - 5-15 registrations, no attendance yet
4. **Annual Music Festival** (Future - in 75 days)
    - 5-15 registrations, no attendance yet

## Testing Scenarios

### Scenario 1: Event Management Flow

1. Log in as coordinator1@university.com
2. View Manage Events page
3. See 4 events for Robotics
4. Click "Registrations" on any event
5. View and export registration list
6. Change some registration statuses
7. Check email logs for notifications

### Scenario 2: Analytics & Reports

1. Log in as any coordinator
2. Go to Analytics dashboard
3. View metrics and charts
4. Navigate to Reports
5. Filter by date range (last 3 months)
6. Click "Details" on highest performing event
7. View comprehensive event report
8. Go back and compare 3 events
9. Print comparison report

### Scenario 3: QR Attendance Tracking

1. Log in as coordinator
2. Select a past event with attendance
3. Click "QR Code" button
4. View attendance statistics
5. Toggle manual check-in for a user
6. Download QR code
7. Copy check-in link

### Scenario 4: Student Registration

1. Log in as student1@university.com
2. Browse events from home page
3. Register for an upcoming event
4. View "My Events" to see registration status
5. Log out and log in as coordinator
6. Accept the registration
7. Log back in as student
8. Check for status update

### Scenario 5: Calendar & Social Sharing

1. Visit Calendar page (no login required)
2. Switch between views (Month/Week/List)
3. Click on an event
4. Test social share buttons
5. Copy event link
6. Paste in browser to verify URL

## Data Statistics (After Seeding)

Expected counts:

-   **Users**: 23 (3 coordinators + 20 students)
-   **UKMs**: 3
-   **Events**: 12 (4 per UKM)
-   **Registrations**: 150-200+ (random)
-   **Checked-in**: ~50-70% of accepted registrations from past events

## Resetting Database

To start fresh:

```bash
# Option 1: Fresh migration + seed
php artisan migrate:fresh --seed --seeder=CompleteDataSeeder

# Option 2: Manually reset
php artisan migrate:fresh
php artisan db:seed --class=CompleteDataSeeder
```

## Troubleshooting

### No events showing in calendar

-   Make sure events were created successfully
-   Check console for JavaScript errors
-   Verify `/api/calendar-events` returns JSON

### QR code not displaying

-   Check internet connection (uses Google Charts API)
-   Verify event has accepted registrations
-   Check browser console for errors

### Charts not rendering

-   Clear cache: `php artisan optimize:clear`
-   Check Chart.js CDN is accessible
-   Verify browser supports modern JavaScript

### Email notifications not working

-   Check `.env` has correct mail configuration
-   For testing, use `MAIL_MAILER=log`
-   Check `storage/logs/laravel.log` for logged emails

## Notes

-   All timestamps are relative to current date/time
-   Registration creation dates are randomized (1-60 days ago)
-   Check-in times are randomized around event time (-15 to +30 minutes)
-   **Real images are automatically downloaded** from Lorem Picsum during seeding
-   Phone numbers are dummy data

## Image Management

### Where Images Are Stored

-   **UKM Logos**: `storage/app/public/logos/` (400x400px)
-   **Event Images**: `storage/app/public/events/` (800x600px)

### Viewing Images in Browser

Images are accessible via the symlinked public path:

-   UKM Logos: `http://127.0.0.1:8000/storage/logos/robotics.png`
-   Event Images: `http://127.0.0.1:8000/storage/events/arduino.jpg`

### Image Source

All images are downloaded from **Lorem Picsum** (https://picsum.photos), a free placeholder image service. Each image has a unique seed to ensure consistent images across multiple seeding runs.

### Re-downloading Images

If you want fresh images, simply delete the images folder and run the seeder again:

```bash
# Delete existing images
Remove-Item storage\app\public\logos\* -Force
Remove-Item storage\app\public\events\* -Force

# Re-run seeder to download new images
php artisan db:seed --class=CompleteDataSeeder
```

## Quick Test Commands

```bash
# Seed database (includes downloading images)
php artisan db:seed --class=CompleteDataSeeder

# Clear cache
php artisan optimize:clear

# Start development server
php artisan serve

# Run queue worker (for email notifications)
php artisan queue:work

# Check seeded data
php artisan tinker
>>> User::count()
>>> Event::count()
>>> EventRegistration::count()

# Verify images were downloaded
>>> Event::first()->image_url
>>> UKM::first()->logo
```

## Feature Coverage

✅ **Email Notifications**: Status change data included  
✅ **Analytics Dashboard**: Multiple events with varied metrics  
✅ **Calendar View**: 12 events across different dates  
✅ **Social Sharing**: Event details with descriptions  
✅ **QR Attendance**: Check-in data for past events  
✅ **Advanced Reports**: Diverse event performance data

All features can be fully tested with this seeded data! 🎉
