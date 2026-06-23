<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Interview extends Model
{
    protected $fillable = ['candidate_application_id', 'scheduled_at', 'interview_type', 'location', 'notes', 'outcome'];
    protected function casts(): array { return ['scheduled_at' => 'datetime']; }
    public function application(): BelongsTo { return $this->belongsTo(CandidateApplication::class, 'candidate_application_id'); }
}
