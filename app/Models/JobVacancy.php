<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class JobVacancy extends Model
{
    protected $fillable = ['department_id', 'title', 'description', 'location', 'employment_type', 'closing_date', 'status'];
    protected function casts(): array { return ['closing_date' => 'date']; }
    public function department(): BelongsTo { return $this->belongsTo(Department::class); }
    public function applications(): HasMany { return $this->hasMany(CandidateApplication::class); }
}
