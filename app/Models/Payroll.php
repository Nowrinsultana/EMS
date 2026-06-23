<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Payroll extends Model
{
    protected $fillable = ['user_id', 'department_id', 'payroll_month', 'basic_salary', 'total_bonus', 'total_deduction', 'net_salary', 'generated_at'];
    protected function casts(): array { return ['payroll_month' => 'date', 'generated_at' => 'datetime', 'basic_salary' => 'decimal:2', 'total_bonus' => 'decimal:2', 'total_deduction' => 'decimal:2', 'net_salary' => 'decimal:2']; }
    public function employee(): BelongsTo { return $this->belongsTo(User::class, 'user_id'); }
}
