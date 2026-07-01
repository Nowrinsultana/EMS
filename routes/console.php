<?php

use App\Models\DailyQrCode;
use Illuminate\Support\Facades\Schedule;

Schedule::call(function () {
    $today = now()->format('Y-m-d');
    DailyQrCode::firstOrCreate(
        ['date' => $today],
        [
            'check_in_token' => bin2hex(random_bytes(32)),
            'is_active' => true,
        ],
    );
})->dailyAt('00:00')->name('generate-daily-qr')->withoutOverlapping();
