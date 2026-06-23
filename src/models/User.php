<?php

namespace Src\Models;

use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

#[Fillable(['name', 'email', 'password', 'date_of_birth', 'phone_number', 'passport_number', 'leave_dates', 'staff_id', 'isadmin', 'leave_balance', 'profile_photo', 'status', 'superuser', 'start_date', 'setup_token', 'department_id'])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    public function department(): BelongsTo
    {
        return $this->belongsTo(\App\Models\Department::class);
    }

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'date_of_birth' => 'date',
            'leave_dates' => 'array',
            'isadmin' => 'boolean',
            'status' => 'boolean',
            'superuser' => 'boolean',
        ];
    }
}
