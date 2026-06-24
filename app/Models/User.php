<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

#[Fillable(['name', 'email', 'password', 'date_of_birth', 'phone_number', 'passport_number', 'leave_dates', 'staff_id', 'isadmin', 'leave_balance', 'profile_photo', 'status', 'superuser', 'start_date', 'setup_token', 'department_id'])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    public function department(): BelongsTo
    {
        return $this->belongsTo(Department::class);
    }

    public function syncLeaveDates(
        bool $nowApproved,
        string $startDate,
        string $endDate,
        ?bool $wasApproved = null,
        ?string $oldStartDate = null,
        ?string $oldEndDate = null,
    ): void {
        $leaveDates = $this->leave_dates ?? [];
        $balance = $this->leave_balance ?? 0;

        $datesInRange = [];
        $period = new \DatePeriod(
            new \DateTime($startDate),
            new \DateInterval('P1D'),
            (new \DateTime($endDate))->modify('+1 day'),
        );
        foreach ($period as $dt) {
            $datesInRange[] = $dt->format('Y-m-d');
        }

        if ($wasApproved && !$nowApproved) {
            $oldDates = [];
            if ($oldStartDate && $oldEndDate) {
                $oldPeriod = new \DatePeriod(
                    new \DateTime($oldStartDate),
                    new \DateInterval('P1D'),
                    (new \DateTime($oldEndDate))->modify('+1 day'),
                );
                foreach ($oldPeriod as $dt) {
                    $oldDates[] = $dt->format('Y-m-d');
                }
            }
            $this->leave_dates = array_values(array_diff($leaveDates, $oldDates ?: $datesInRange));
            $oldDays = $oldStartDate
                ? (new \DateTime($oldStartDate))->diff(new \DateTime($oldEndDate ?? $endDate))->days + 1
                : count($datesInRange);
            $this->leave_balance = $balance + $oldDays;
        } elseif (!$wasApproved && $nowApproved) {
            $this->leave_dates = array_values(array_unique(array_merge($leaveDates, $datesInRange)));
            $this->leave_balance = max(0, $balance - count($datesInRange));
        } elseif ($wasApproved && $nowApproved && $oldStartDate && $oldEndDate && ($oldStartDate !== $startDate || $oldEndDate !== $endDate)) {
            $oldDates = [];
            $oldPeriod = new \DatePeriod(
                new \DateTime($oldStartDate),
                new \DateInterval('P1D'),
                (new \DateTime($oldEndDate))->modify('+1 day'),
            );
            foreach ($oldPeriod as $dt) {
                $oldDates[] = $dt->format('Y-m-d');
            }
            $this->leave_dates = array_values(array_unique(array_merge(
                array_diff($leaveDates, $oldDates),
                $datesInRange,
            )));
            $oldDays = (new \DateTime($oldStartDate))->diff(new \DateTime($oldEndDate))->days + 1;
            $this->leave_balance = max(0, $balance + $oldDays - count($datesInRange));
        }

        $this->save();
    }

    public function documents(): HasMany
    {
        return $this->hasMany(Document::class);
    }

    public function basicSalaries(): HasMany
    {
        return $this->hasMany(BasicSalary::class);
    }

    public function payrolls(): HasMany
    {
        return $this->hasMany(Payroll::class);
    }

    public function payrollAdjustments(): HasMany
    {
        return $this->hasMany(PayrollAdjustment::class);
    }

    public function documents(): HasMany
    {
        return $this->hasMany(Document::class);
    }

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'date_of_birth' => 'date',
            'start_date' => 'date',
            'leave_dates' => 'array',
            'isadmin' => 'boolean',
            'status' => 'boolean',
            'superuser' => 'boolean',
        ];
    }
}
