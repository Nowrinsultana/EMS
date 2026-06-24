<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DailyQrCode extends Model
{
    protected $fillable = [
        'date', 'check_in_token', 'check_out_token', 'is_active',
    ];

    protected function casts(): array
    {
        return [
            'date' => 'date',
            'is_active' => 'boolean',
        ];
    }
}
