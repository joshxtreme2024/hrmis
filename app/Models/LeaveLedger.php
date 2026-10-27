<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/** One immutable row of the leave card. Never update or delete; post a reversal instead. */
class LeaveLedger extends Model
{
    protected $guarded = ['id'];

    protected $casts = [
        'transaction_date' => 'date',
        'period_start'     => 'date',
        'period_end'       => 'date',
        'earned'           => 'float',
        'days_with_pay'    => 'float',
        'days_without_pay' => 'float',
        'balance'          => 'float',
    ];

    public const ENTRY_TYPES = [
        'opening_balance' => 'Opening balance',
        'earned'          => 'Earned',
        'used'            => 'Used',
        'adjustment'      => 'Adjustment',
        'monetization'    => 'Monetization',
        'forfeiture'      => 'Forfeiture',
        'reversal'        => 'Reversal',
    ];

    public function employee()    { return $this->belongsTo(PDSPersonalData::class, 'emp_id'); }
    public function leaveType()   { return $this->belongsTo(LeaveType::class); }
    public function application() { return $this->belongsTo(LeaveApplication::class, 'leave_application_id'); }
    public function recorder()    { return $this->belongsTo(User::class, 'recorded_by'); }
    public function reverses()    { return $this->belongsTo(self::class, 'reverses_entry_id'); }
    public function reversal()    { return $this->hasOne(self::class, 'reverses_entry_id'); }

    public function typeLabel(): string { return self::ENTRY_TYPES[$this->entry_type] ?? ucfirst($this->entry_type); }

    public function typeBadge(): string
    {
        return match ($this->entry_type) {
            'earned', 'opening_balance' => 'bg-green-100 text-green-800',
            'used'                      => 'bg-indigo-100 text-indigo-800',
            'reversal'                  => 'bg-gray-200 text-gray-700',
            'monetization', 'forfeiture' => 'bg-red-100 text-red-800',
            default                     => 'bg-amber-100 text-amber-800',
        };
    }

    /** Load the `reversal` relation first to avoid extra queries. */
    public function isReversible(): bool
    {
        return $this->entry_type !== 'reversal' && ! $this->reversal;
    }
}