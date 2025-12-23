# UKM Space - Student Organization Management System

<p align="center">
  <img src="https://img.shields.io/badge/Laravel-11.37.0-red?logo=laravel" alt="Laravel">
  <img src="https://img.shields.io/badge/PHP-8.4.10-blue?logo=php" alt="PHP">
  <img src="https://img.shields.io/badge/MySQL-8.0-orange?logo=mysql" alt="MySQL">
  <img src="https://img.shields.io/badge/Bootstrap-5.3-purple?logo=bootstrap" alt="Bootstrap">
  <img src="https://img.shields.io/badge/License-MIT-green" alt="License">
</p>

## 📋 About UKM Space

UKM Space is a comprehensive web-based management system designed for student organizations (Unit Kegiatan Mahasiswa/UKM) to manage their events, members, and activities efficiently. The platform provides tools for event management, registration tracking, attendance monitoring, analytics, and reporting.

### 🎯 Key Features

#### 1. 📧 Email Notifications

-   Automated email alerts for registration status changes
-   Queue-based processing for optimal performance
-   Customizable email templates
-   Status change notifications (accepted, rejected, pending)

#### 2. 📊 Analytics Dashboard

-   Real-time metrics and statistics
-   Event performance tracking
-   Registration trends analysis
-   Interactive Chart.js visualizations
-   Key metrics: total events, registrations, attendance rates

#### 3. 📅 Calendar View

-   Interactive FullCalendar.js integration
-   Month, week, and list view options
-   Event filtering by UKM
-   Click-to-view event details
-   Responsive design for mobile devices

#### 4. 🔗 Social Media Sharing

-   One-click sharing to WhatsApp, Facebook, Twitter, LinkedIn
-   Open Graph meta tags for rich previews
-   Copy-to-clipboard functionality
-   Email sharing support
-   Optimized for social media platforms

#### 5. 📱 QR Code Attendance

-   Contactless check-in system
-   Real-time QR code generation
-   API-based attendance tracking
-   Manual check-in fallback option
-   Attendance reports and statistics

#### 6. 📈 Advanced Reports

-   Comprehensive event performance analysis
-   Side-by-side event comparison
-   Registration and attendance metrics
-   Date-range filtering
-   Print-friendly report layouts
-   Export capabilities

## 🛠️ Technology Stack

### Backend

-   **Framework**: Laravel 11.37.0
-   **Language**: PHP 8.4.10
-   **Database**: MySQL 8.0
-   **Authentication**: Laravel Breeze
-   **Queue System**: Database driver

### Frontend

-   **CSS Framework**: Bootstrap 5.3
-   **Icons**: Bootstrap Icons
-   **JavaScript**: Vanilla JS with libraries:
    -   Chart.js 4.4.0 (Analytics)
    -   FullCalendar.js 6.1.10 (Calendar)
    -   QRCode.js (QR Code generation)
-   **Build Tool**: Vite 6.3.5

## 📦 Installation

### Prerequisites

-   PHP >= 8.4
-   Composer
-   Node.js & NPM
-   MySQL 8.0+
-   Git

### Steps

1. **Clone the repository**

```bash
git clone https://github.com/rizkilahi/ukmspace.git
cd ukmspace
```

2. **Install PHP dependencies**

```bash
composer install
```

3. **Install Node dependencies**

```bash
npm install
```

4. **Environment setup**

```bash
cp .env.example .env
php artisan key:generate
```

5. **Configure database**
   Edit `.env` file:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=ukmspace
DB_USERNAME=root
DB_PASSWORD=your_password
```

6. **Configure mail settings** (for email notifications)

```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=your_email@gmail.com
MAIL_PASSWORD=your_app_password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=your_email@gmail.com
MAIL_FROM_NAME="UKM Space"
```

7. **Run migrations**

```bash
php artisan migrate
```

8. **Seed database with sample data**

```bash
php artisan db:seed --class=CompleteDataSeeder
```

This will create:

-   3 UKMs with logos
-   23 users (3 coordinators + 20 students)
-   12 events with images
-   150-200+ registrations
-   Realistic attendance data

9. **Create storage symlink**

```bash
php artisan storage:link
```

10. **Build frontend assets**

```bash
npm run build
# or for development
npm run dev
```

11. **Start the application**

```bash
php artisan serve
```

12. **Start queue worker** (in separate terminal)

```bash
php artisan queue:work
```

Visit `http://127.0.0.1:8000` in your browser.

## 👥 Default User Accounts

After seeding, you can log in with these accounts:

### UKM Coordinators

-   **Robotics**: coordinator1@university.com / password
-   **Photography**: coordinator2@university.com / password
-   **Music**: coordinator3@university.com / password

### Students

-   student1@university.com to student20@university.com
-   Password: **password**

## 📂 Project Structure

```
ukmspace/
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── EventController.php      # Event management
│   │   │   ├── ReportController.php     # Advanced reports
│   │   │   ├── HomeController.php       # Public pages
│   │   │   └── ProfileController.php    # User profile
│   │   └── Middleware/
│   │       ├── IsAdmin.php              # Admin access
│   │       ├── IsUKM.php                # UKM coordinator access
│   │       └── IsUser.php               # Regular user access
│   ├── Models/
│   │   ├── Event.php                    # Event model
│   │   ├── EventRegistration.php        # Registration model
│   │   ├── UKM.php                      # UKM organization model
│   │   └── User.php                     # User model
│   ├── Mail/
│   │   └── RegistrationStatusChanged.php # Email notification
│   └── Jobs/                             # Background jobs
├── database/
│   ├── migrations/                       # Database schema
│   └── seeders/
│       └── CompleteDataSeeder.php       # Sample data with images
├── resources/
│   ├── views/
│   │   ├── ukms/                        # UKM coordinator views
│   │   ├── user/                        # Student user views
│   │   ├── admin/                       # Admin views
│   │   └── layouts/                     # Layout templates
│   ├── css/
│   └── js/
├── routes/
│   ├── web.php                          # Web routes
│   └── auth.php                         # Authentication routes
└── docs/                                # Documentation
    ├── ADVANCED_REPORTS.md              # Reports documentation
    ├── DATABASE_SEEDING.md              # Seeding guide
    ├── FEATURES_SUMMARY.md              # Features overview
    └── DATABASE_FIX_SUMMARY.md          # Database fixes
```

## 🎨 User Roles & Permissions

### 1. Regular Users (Students)

-   Browse and search events
-   View event details
-   Register for events
-   View registration status
-   Track personal event history
-   Share events on social media

### 2. UKM Coordinators

-   All user permissions, plus:
-   Create and manage events
-   View event registrations
-   Approve/reject registrations
-   Generate QR codes for check-in
-   Manual attendance check-in
-   View analytics dashboard
-   Generate advanced reports
-   Export registration data

### 3. Admin (System Administrator)

-   All coordinator permissions, plus:
-   Manage UKM organizations
-   Manage users
-   System-wide oversight

## 🚀 Features in Detail

### Event Management

-   Create events with rich descriptions
-   Set capacity limits
-   Upload event images
-   Automatic image download for seeded data
-   Event status tracking (upcoming, ongoing, past)

### Registration System

-   One-click registration for students
-   Status workflow: pending → accepted/rejected
-   Email notifications for status changes
-   Registration history tracking
-   Capacity management

### Attendance Tracking

-   QR code generation for each event
-   Real-time QR scanning via API
-   Manual check-in option
-   Timestamp recording
-   Method tracking (QR/manual)
-   Attendance reports

### Analytics & Reporting

-   Visual charts and graphs
-   Event comparison tools
-   Date range filtering
-   Performance metrics
-   Export to CSV/Excel
-   Print-friendly layouts

### Search & Discovery

-   Full-text search across events
-   Filter by UKM organization
-   Date range filtering
-   Sort by relevance, date, popularity
-   Calendar view for timeline

## 📱 API Endpoints

### QR Code Check-in API

```
POST /api/qr-checkin
Content-Type: application/json

{
  "event_id": 1,
  "registration_id": 123
}
```

## 🧪 Testing

Run the test suite:

```bash
php artisan test
```

Test specific features:

```bash
# Test reports system
php test_reports.php

# Test QR attendance
php test_qr_attendance.php
```

## 📖 Documentation

Comprehensive documentation available in the `/docs` folder:

-   **[ADVANCED_REPORTS.md](docs/ADVANCED_REPORTS.md)** - Reports feature guide
-   **[DATABASE_SEEDING.md](docs/DATABASE_SEEDING.md)** - Seeding instructions
-   **[FEATURES_SUMMARY.md](docs/FEATURES_SUMMARY.md)** - Complete feature list
-   **[DATABASE_FIX_SUMMARY.md](docs/DATABASE_FIX_SUMMARY.md)** - Database structure fixes

## 🔧 Development

### Running in development mode

```bash
# Terminal 1: PHP development server
php artisan serve

# Terminal 2: Vite dev server (hot reload)
npm run dev

# Terminal 3: Queue worker
php artisan queue:work

# Terminal 4: Watch for file changes
npm run watch
```

### Clearing cache

```bash
php artisan optimize:clear
```

### Database operations

```bash
# Fresh migration
php artisan migrate:fresh

# Seed with sample data
php artisan db:seed --class=CompleteDataSeeder

# Fresh migration + seed
php artisan migrate:fresh --seed
```

## 🤝 Contributing

Contributions are welcome! Please follow these steps:

1. Fork the repository
2. Create a feature branch (`git checkout -b feature/AmazingFeature`)
3. Commit your changes (`git commit -m 'Add some AmazingFeature'`)
4. Push to the branch (`git push origin feature/AmazingFeature`)
5. Open a Pull Request

## 📄 License

This project is licensed under the MIT License - see the [LICENSE](LICENSE) file for details.

## 👤 Author

**Rizki Lahi**

-   GitHub: [@rizkilahi](https://github.com/rizkilahi)

## 🙏 Acknowledgments

-   Laravel Framework - for the robust backend foundation
-   Bootstrap - for responsive UI components
-   Chart.js - for beautiful data visualizations
-   FullCalendar.js - for interactive calendar views
-   Lorem Picsum - for placeholder images during development

## 📞 Support

If you encounter any issues or have questions:

1. Check the [documentation](docs/)
2. Open an issue on GitHub
3. Contact the maintainer

---

<p align="center">Made with ❤️ for Student Organizations</p>

## Contributing

Thank you for considering contributing to the Laravel framework! The contribution guide can be found in the [Laravel documentation](https://laravel.com/docs/contributions).

## Code of Conduct

In order to ensure that the Laravel community is welcoming to all, please review and abide by the [Code of Conduct](https://laravel.com/docs/contributions#code-of-conduct).

## Security Vulnerabilities

If you discover a security vulnerability within Laravel, please send an e-mail to Taylor Otwell via [taylor@laravel.com](mailto:taylor@laravel.com). All security vulnerabilities will be promptly addressed.

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
