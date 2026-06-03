# School Fee Management System

A complete Laravel 10 school fee management system for secondary schools, TVET demonstrations, and university presentations. It manages students, classes, fee structures, payments, receipts, balances, users, dashboards, PDF reports, and Excel-compatible reports.

## Features

- Laravel authentication with admin and accountant roles
- Student, class, fee structure, payment, report, and user modules
- Automatic receipt numbers such as `REC-2026-0001`
- Balance calculation: `School Fee - Total Paid`
- Dashboard cards and Chart.js charts
- Student profile page with payment history
- Search and filtering
- Printable receipts
- PDF report export using DomPDF
- Excel-compatible `.xls` export
- Bootstrap 5 responsive admin panel
- Dark mode
- Seeded presentation data

## Installation

```bash
composer install
cp .env.example .env
php artisan key:generate
```

## Database Setup

Create a MySQL database, then update `.env`:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=school_fee_db
DB_USERNAME=root
DB_PASSWORD=
CURRENT_ACADEMIC_YEAR=2026
```

Run migrations and seeders:

```bash
php artisan migrate --seed
php artisan serve
```

Open `http://127.0.0.1:8000`.

## Login Credentials

Administrator:

- Email: `admin@school.com`
- Password: `password`

Accountant:

- Email: `accountant@school.com`
- Password: `password`

## Main Modules

- Dashboard
- Students
- Classes
- Fee Structure
- Payments
- Receipts
- Reports
- Users

## Documentation

See [SYSTEM_DOCUMENTATION.md](SYSTEM_DOCUMENTATION.md) for the problem statement, requirements, ERD, use case diagram, database design, testing plan, and conclusion.
