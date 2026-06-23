# EMS Project Summary

## Stack
- Laravel 13, PHP 8.3, MySQL
- Sanctum for API auth
- Blade + Tailwind (Vite) for frontend

## Namespaces
- `App\` → `app/` (standard Laravel)
- `Src\` → `src/` (custom)

## User Model
- `app/Models/User.php` (default) — uses Laravel auth
- `src/models/User.php` (mirror) — `Src\Models` namespace
- Custom columns added via migration: `date_of_birth`, `phone_number`, `passport_number`, `leave_dates` (JSON), `staff_id` (unique)

## Auth (Web Routes)
| Route | Method | Controller | Notes |
|---|---|---|---|
| `/login` | GET/POST | `Auth\LoginController` | Guest only |
| `/register` | GET/POST | `Auth\RegisterController` | Guest only, auto-login on register |
| `/logout` | POST | `Auth\LoginController@destroy` | Auth required |
| `/dashboard` | GET | Closure view | Auth required |
| `/profile` | GET/PUT | `ProfileController` | Auth required, edit staff info (no email) |
| `/password` | GET/PUT | `PasswordController` | Auth required, separate change password page |

## API Routes (prefix: `/api`, auth: `auth:sanctum`)
| Method | Endpoint | Controller |
|---|---|---|
| GET | `/api/profile` | `Api\ProfileController@show` |
| PUT | `/api/profile` | `Api\ProfileController@update` |
| PUT | `/api/password` | `Api\PasswordController@update` |

## Key Files Created/Modified
- `app/Http/Controllers/Auth/LoginController.php`
- `app/Http/Controllers/Auth/RegisterController.php`
- `app/Http/Controllers/ProfileController.php`
- `app/Http/Controllers/PasswordController.php`
- `app/Http/Controllers/Api/ProfileController.php`
- `app/Http/Controllers/Api/PasswordController.php`
- `app/Http/Requests/RegisterRequest.php`
- `app/Http/Requests/LoginRequest.php`
- `app/Http/Requests/ProfileUpdateRequest.php`
- `app/Http/Requests/ChangePasswordRequest.php`
- `resources/views/auth/login.blade.php`
- `resources/views/auth/register.blade.php`
- `resources/views/profile/edit.blade.php`
- `resources/views/profile/password.blade.php`
- `resources/views/dashboard.blade.php`
- `resources/views/components/layout.blade.php`
- `routes/web.php`
- `routes/api.php`
- `bootstrap/app.php` (added api routes)
- `composer.json` (added `Src\` namespace)

## Conventions
- Validation in FormRequest classes
- Blade layout via `<x-layout>` component
- API returns JSON with 200/403 status codes
- Blueprint methods: nullable strings, unique where appropriate
