<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BasicSalary extends Model
{
    protected $fillable = ['user_id', 'department_id', 'amount', 'effective_from'];
    protected function casts(): array { return ['amount' => 'decimal:2', 'effective_from' => 'date']; }
    public function employee(): BelongsTo { return $this->belongsTo(User::class, 'user_id'); }
    public function department(): BelongsTo { return $this->belongsTo(Department::class); }
}
