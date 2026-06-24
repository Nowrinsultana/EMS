# Employee Management System (EMS)

EMS is a Laravel-based office management application for managing employees, departments, leave, payroll, and recruitment from a single web interface.

## Features

### Core administration

- Authentication, registration, password setup, profile management, and password changes
- Department management for superusers
- Department-scoped employee management
- Role-based access for staff, department admins, and superusers

### Leave management

- Staff leave requests
- Admin approval and decline workflow
- Leave balance updates and date-overlap checks
- Personal leave view for staff

### Payroll management

- Basic monthly salary management with an effective date
- Monthly bonuses and deductions
- Monthly payroll calculation per active employee
- Downloadable PDF payslips

### Recruitment management

- Job vacancy posting and editing
- Public candidate application form
- PDF résumé upload (up to 5 MB)
- Private résumé downloads for authorized department admins
- Candidate tracking: New, Reviewing, Interview, Selected, or Rejected
- Interview scheduling and notes

## Technology

- PHP 8.3+
- Laravel 13
- MySQL
- Laravel Sanctum
- Blade, Tailwind CSS, Vite

## Requirements

- PHP 8.3 or newer with the OpenSSL extension enabled
- Composer
- Node.js and npm
- MySQL 8+ or compatible MySQL server

## Installation

1. Clone the repository and open the project directory.

   ```bash
   git clone <repository-url>
   cd EMS
   ```

2. Install PHP dependencies.

   ```bash
   composer install
   ```

3. Create your local environment file and application key.

   ```bash
   copy .env.example .env
   php artisan key:generate
   ```

   On macOS or Linux, use `cp .env.example .env` instead.

4. Update the database section in `.env`.

   ```env
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=ems
   DB_USERNAME=root
   DB_PASSWORD=
   ```

5. Create the `ems` MySQL database, then run migrations.

   ```bash
   php artisan migrate
   ```

6. Install and build frontend assets.

   ```bash
   npm install
   npm run build
   ```

7. Start the application.

   ```bash
   php artisan serve
   ```

   Open `http://127.0.0.1:8000` in your browser.

## Roles and access

| Role | Access |
| --- | --- |
| Staff | Profile, password, personal leave, and personal attendance pages |
| Department admin | Department employees, leave management, attendance, payroll, and recruitment |
| Superuser | All department-admin access across departments, plus department settings |

Payroll and recruitment pages are department-scoped. A department admin can access only their own department; a superuser can switch departments from the top navigation.

## Payroll workflow

1. Sign in as a department admin or superuser.
2. Open **Payroll** from the top navigation, or visit `/{department-id}/payroll`.
3. Save each employee's basic salary and effective date.
4. Add any monthly bonus or deduction.
5. Choose a month and select **Calculate Monthly Payroll**.
6. Use **PDF Slip** in the payroll table to download a payslip.

Net salary is calculated as:

```text
Net salary = Basic salary + Total bonus - Total deduction
```

## Recruitment workflow

1. Sign in as a department admin or superuser.
2. Open **Recruitment** and select **Post Vacancy**.
3. Complete the vacancy details and set the status to **Open**.
4. Share the generated public application URL:

   ```text
   /jobs/{vacancy-id}/apply
   ```

5. Candidates submit their personal information, cover letter, and optional PDF résumé.
6. Open **Candidates** for a vacancy to download résumés, update candidate status, and schedule interviews.

Résumé files are stored on Laravel's private `local` disk. They are available only through the authenticated department-admin download route and are not public web files.

## Useful commands

```bash
# Run migrations
php artisan migrate

# Run automated tests
php artisan test

# Format PHP source code
./vendor/bin/pint

# Start Laravel, Vite, and queue worker together
composer run dev
```

On Windows PowerShell, use:

```powershell
.\vendor\bin\pint
```

## Project structure

```text
app/Http/Controllers/     Web and API controllers
app/Http/Requests/        Request validation classes
app/Http/Middleware/      Role and department access middleware
app/Models/               Eloquent models
database/migrations/      Database schema migrations
resources/views/          Blade views
routes/web.php            Web routes
routes/api.php            API routes
```

## API endpoints

All API endpoints require Sanctum authentication.

| Method | Endpoint | Purpose |
| --- | --- | --- |
| GET | `/api/profile` | Get the current user's profile |
| PUT | `/api/profile` | Update the current user's profile |
| PUT | `/api/password` | Change the current user's password |

## Troubleshooting

### Composer reports that OpenSSL is unavailable

Enable the `openssl` extension in the `php.ini` file used by your command-line PHP installation, then restart the terminal and run:

```bash
composer install
```

Verify the active configuration file with:

```bash
php --ini
```

### A new feature is missing after pulling changes

Run the latest migrations and rebuild assets:

```bash
php artisan migrate
npm install
npm run build
```

## Security notes

- Do not commit `.env`, `vendor/`, `node_modules/`, or uploaded files.
- Keep `APP_DEBUG=false` in production.
- Use strong database credentials in production.
- Candidate résumés should remain on private storage and be downloaded only through authorized application routes.
