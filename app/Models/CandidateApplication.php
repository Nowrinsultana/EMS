<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CandidateApplication extends Model
{
    protected $fillable = ['job_vacancy_id', 'name', 'email', 'phone_number', 'cover_letter', 'resume_url', 'status'];
    public function vacancy(): BelongsTo { return $this->belongsTo(JobVacancy::class, 'job_vacancy_id'); }
    public function interviews(): HasMany { return $this->hasMany(Interview::class); }
}
