<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PayrollAdjustment extends Model
{
    protected $fillable = ['user_id', 'department_id', 'type', 'amount', 'description', 'payroll_month'];
    protected function casts(): array { return ['amount' => 'decimal:2', 'payroll_month' => 'date']; }
    public function employee(): BelongsTo { return $this->belongsTo(User::class, 'user_id'); }
}
