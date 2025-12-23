# QR Code Attendance System

## Overview

The QR Code Attendance System allows UKM coordinators to track event attendance using QR codes. Participants can scan the QR code to check in automatically, or coordinators can manually check in attendees.

## Features

### 1. **QR Code Generation**

-   Uses Google Charts API (no server-side packages required)
-   Generates secure, unique QR codes for each event
-   Token-based authentication prevents unauthorized check-ins

### 2. **Attendance Tracking**

-   Real-time attendance monitoring
-   Tracks check-in time and method (QR or manual)
-   Auto-refresh every 30 seconds to show latest status

### 3. **Multiple Check-In Methods**

-   **QR Code Scanning**: Participants scan QR code with their phones
-   **Manual Check-In**: Coordinators can manually mark attendance
-   **Toggle Feature**: Remove/restore check-in status

### 4. **Attendance Statistics**

-   Total registrations count
-   Checked-in count
-   Attendance rate percentage
-   Real-time progress bar

### 5. **QR Code Management**

-   Print QR code for physical display
-   Download QR code as PNG image
-   Copy check-in link to clipboard

## Database Changes

### Migration

```php
// Added to event_registrations table
$table->timestamp('checked_in_at')->nullable();
$table->string('check_in_method')->nullable(); // 'qr' or 'manual'
```

## Routes

### UKM Routes (Protected)

```php
// Show QR code page
GET /ukm/events/{event}/qr-code

// Manual check-in toggle
POST /ukm/events/{event}/registrations/{registration}/manual-checkin
```

### Public Routes (Auth Required)

```php
// QR code check-in endpoint
GET /events/{event}/check-in/{token}
```

## Security

### Token Generation

```php
$token = hash('sha256', config('app.key') . $event->id);
```

-   Uses SHA-256 hashing
-   Combined with application key
-   Unique per event
-   Cannot be forged without app key

### Authorization

-   Only UKM coordinators can view QR codes
-   Only registered users with accepted status can check in
-   Token verification prevents unauthorized access

## Usage Guide

### For UKM Coordinators

1. **Access QR Code Page**

    - Go to "Manage Events"
    - Click "QR Code" button on event card
    - Or from Registrations page, click "QR Code"

2. **Display QR Code**

    - Show QR code on screen/projector at event venue
    - Or print QR code for physical display
    - Or share check-in link via messaging apps

3. **Monitor Attendance**

    - View real-time attendance list
    - Filter by: All, Checked In, Pending
    - See check-in time and method for each participant

4. **Manual Check-In**
    - Click check mark button to mark as checked in
    - Click X button to remove check-in
    - Useful for technical issues or offline scenarios

### For Participants

1. **Check In with QR Code**

    - Scan QR code with phone camera or QR scanner app
    - Must be logged in to UKM Space
    - Must have accepted registration for the event
    - Redirected to event page with confirmation

2. **Check In with Link**
    - Click check-in link shared by coordinator
    - Same authentication requirements apply

## API Used

### Google Charts QR Code API

```
https://chart.googleapis.com/chart?cht=qr&chs=300x300&chl={URL}&choe=UTF-8
```

**Parameters:**

-   `cht=qr`: Chart type is QR code
-   `chs=300x300`: Size is 300x300 pixels
-   `chl={URL}`: Data encoded in QR code
-   `choe=UTF-8`: Character encoding

**Why Google Charts API?**

-   No server-side package installation needed
-   No PHP GD extension required
-   Reliable and fast
-   No rate limits for basic usage
-   Works offline after initial load

## Files Modified/Created

### Controllers

-   `app/Http/Controllers/EventController.php`
    -   `showQRCode()`: Display QR code page
    -   `checkIn()`: Handle QR scan check-in
    -   `manualCheckIn()`: Handle manual check-in toggle

### Models

-   `app/Models/EventRegistration.php`
    -   Added `checked_in_at` and `check_in_method` to fillable
    -   Added `checked_in_at` datetime cast

### Views

-   `resources/views/ukms/events/qr-code.blade.php`: QR code attendance page
-   `resources/views/ukms/events/manage.blade.php`: Added QR Code button
-   `resources/views/ukms/events/registrations.blade.php`: Added QR Code button

### Migrations

-   `database/migrations/2025_12_23_175515_add_checked_in_at_to_event_registrations_table.php`

### Routes

-   `routes/web.php`: Added QR code routes

## Testing

### Test Script

```bash
php test_qr_attendance.php
```

**Output:**

-   Event details
-   Check-in URL and QR code URL
-   List of accepted registrations
-   Attendance statistics
-   Test manual check-in

### Manual Testing Steps

1. **UKM Coordinator View**

    ```
    http://localhost:8000/ukm/events/{event_id}/qr-code
    ```

    - Verify QR code displays correctly
    - Check attendance list shows registrations
    - Test filter buttons (All, Checked In, Pending)
    - Test manual check-in toggle

2. **Participant Check-In**

    - Scan QR code with phone
    - Should redirect to login if not authenticated
    - Should redirect to event page with success message
    - Second scan should show "already checked in"

3. **Invalid Scenarios**
    - Wrong token → 403 error
    - Not registered → Error message
    - Rejected registration → Error message
    - Not logged in → Redirect to login

## Features Highlights

### 1. Real-Time Updates

-   Page auto-refreshes every 30 seconds
-   Shows latest check-in status
-   No manual refresh needed

### 2. Mobile Responsive

-   QR code displays properly on all devices
-   Attendance list scrollable on mobile
-   Touch-friendly buttons

### 3. Print Friendly

-   QR code optimized for printing
-   Removes unnecessary elements when printing
-   High-quality QR code image (300x300)

### 4. User Feedback

-   Success/error messages
-   Confirmation alerts
-   Status badges (Checked In, Pending)

### 5. Data Export

-   Export includes check-in status
-   Shows check-in time in CSV
-   Helps with post-event analysis

## Analytics Integration

The attendance data integrates with the analytics dashboard:

-   Track attendance rate per event
-   Compare check-in rates across events
-   Monitor real-time vs final attendance

## Future Enhancements (Optional)

1. **Bluetooth Proximity Check-In**

    - Auto check-in when within range
    - Reduces manual scanning

2. **Check-Out Tracking**

    - Track when participants leave
    - Calculate actual time spent at event

3. **Attendance Reports**

    - Generate PDF reports
    - Email attendance summary to coordinators

4. **Notifications**

    - Notify participants when checked in
    - Remind no-shows after event starts

5. **Multi-Session Events**
    - Track attendance per session
    - Require check-in for each session

## Troubleshooting

### QR Code Not Displaying

-   Check internet connection (Google Charts API)
-   Verify URL encoding is correct
-   Check browser console for errors

### Check-In Not Working

-   Ensure user is logged in
-   Verify registration status is "accepted"
-   Check token matches event ID
-   Confirm app key is set in .env

### Manual Check-In Failed

-   Verify coordinator owns the event
-   Check registration belongs to event
-   Ensure database connection is working

## Benefits

1. **Efficiency**: Faster than manual roll call
2. **Accuracy**: Timestamp proves attendance
3. **Convenience**: Contactless check-in
4. **Data**: Real-time attendance tracking
5. **Flexibility**: Manual override available
6. **Security**: Token-based authentication
7. **Cost**: Free solution (no paid APIs)
8. **Reliability**: Works with basic internet connection

## Conclusion

The QR Code Attendance System provides a modern, efficient way to track event attendance. It combines automation with manual control, ensuring flexibility while maintaining accuracy. The system is secure, user-friendly, and integrates seamlessly with existing event management features.
