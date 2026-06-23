<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('daily_qr_codes', function (Blueprint $table) {
            $table->id();
            $table->date('date');
            $table->string('check_in_token', 64)->unique();
            $table->string('check_out_token', 64)->unique()->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->unique('date');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('daily_qr_codes');
    }
};
