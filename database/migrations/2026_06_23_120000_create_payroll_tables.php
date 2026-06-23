<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('basic_salaries', function (Blueprint $table) { $table->id(); $table->foreignId('user_id')->constrained()->cascadeOnDelete(); $table->foreignId('department_id')->constrained()->cascadeOnDelete(); $table->decimal('amount', 12, 2); $table->date('effective_from'); $table->timestamps(); $table->index(['user_id', 'effective_from']); });
        Schema::create('payroll_adjustments', function (Blueprint $table) { $table->id(); $table->foreignId('user_id')->constrained()->cascadeOnDelete(); $table->foreignId('department_id')->constrained()->cascadeOnDelete(); $table->enum('type', ['bonus', 'deduction']); $table->decimal('amount', 12, 2); $table->string('description'); $table->date('payroll_month'); $table->timestamps(); $table->index(['user_id', 'payroll_month']); });
        Schema::create('payrolls', function (Blueprint $table) { $table->id(); $table->foreignId('user_id')->constrained()->cascadeOnDelete(); $table->foreignId('department_id')->constrained()->cascadeOnDelete(); $table->date('payroll_month'); $table->decimal('basic_salary', 12, 2); $table->decimal('total_bonus', 12, 2)->default(0); $table->decimal('total_deduction', 12, 2)->default(0); $table->decimal('net_salary', 12, 2); $table->timestamp('generated_at'); $table->timestamps(); $table->unique(['user_id', 'payroll_month']); });
    }
    public function down(): void { Schema::dropIfExists('payrolls'); Schema::dropIfExists('payroll_adjustments'); Schema::dropIfExists('basic_salaries'); }
};
