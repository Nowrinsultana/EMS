<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('job_vacancies', function (Blueprint $table) { $table->id(); $table->foreignId('department_id')->constrained()->cascadeOnDelete(); $table->string('title'); $table->longText('description'); $table->string('location')->nullable(); $table->string('employment_type')->default('Full-time'); $table->date('closing_date')->nullable(); $table->enum('status', ['open', 'closed'])->default('open'); $table->timestamps(); });
        Schema::create('candidate_applications', function (Blueprint $table) { $table->id(); $table->foreignId('job_vacancy_id')->constrained()->cascadeOnDelete(); $table->string('name'); $table->string('email'); $table->string('phone_number', 30); $table->text('cover_letter')->nullable(); $table->string('resume_url')->nullable(); $table->enum('status', ['new', 'reviewing', 'interview', 'selected', 'rejected'])->default('new'); $table->timestamps(); });
        Schema::create('interviews', function (Blueprint $table) { $table->id(); $table->foreignId('candidate_application_id')->constrained()->cascadeOnDelete(); $table->dateTime('scheduled_at'); $table->string('interview_type')->default('In-person'); $table->string('location')->nullable(); $table->text('notes')->nullable(); $table->enum('outcome', ['pending', 'passed', 'failed'])->default('pending'); $table->timestamps(); });
    }
    public function down(): void { Schema::dropIfExists('interviews'); Schema::dropIfExists('candidate_applications'); Schema::dropIfExists('job_vacancies'); }
};
