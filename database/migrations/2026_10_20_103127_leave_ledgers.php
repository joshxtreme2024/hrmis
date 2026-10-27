<?php

use App\Models\PDSPersonalData;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Leave ledger (leave card): an append-only history of every change to an employee's leave credits.
 *
 * One row per transaction per leave type. `balance` is the running balance AFTER the row, so the
 * latest row of an (emp_id, leave_type_id) pair is the current balance. Never edit or delete rows;
 * correct mistakes with a 'reversal' or 'adjustment' entry.
 *
 * Run after 2026_10_19_000001_create_leave_management_tables.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('leave_ledgers', function (Blueprint $table) {
            $table->id();

            $table->foreignId('emp_id')->constrained((new PDSPersonalData)->getTable())->restrictOnDelete();
            $table->foreignId('leave_type_id')->constrained('leave_types')->restrictOnDelete();

            // The application that caused this entry (used / reversal), if any
            $table->foreignId('leave_application_id')->nullable()->constrained('leave_applications')->nullOnDelete();

            // For a reversal: the entry being cancelled out
            $table->foreignId('reverses_entry_id')->nullable()->constrained('leave_ledgers')->nullOnDelete();

            $table->enum('entry_type', [
                'opening_balance',   // balance brought forward when the ledger starts
                'earned',            // monthly accrual
                'used',              // approved leave (or absence / undertime)
                'adjustment',        // manual correction by HR (use + or - in earned / days_with_pay)
                'monetization',      // leave credits converted to cash
                'forfeiture',        // credits lost (e.g. unused forced leave)
                'reversal',          // cancels an earlier entry (e.g. approved leave later cancelled)
            ]);

            $table->date('transaction_date');            // when the entry was posted
            $table->date('period_start')->nullable();    // "Period" column of the leave card (month or leave dates)
            $table->date('period_end')->nullable();
            $table->string('particulars')->nullable();   // e.g. "Monthly accrual - Oct 2026", "Vacation Leave 3 days"

            // Amounts, in days (3 decimals so hours/minutes of undertime convert exactly)
            $table->decimal('earned', 8, 3)->default(0);             // credits added
            $table->decimal('days_with_pay', 8, 3)->default(0);      // credits deducted
            $table->decimal('days_without_pay', 8, 3)->default(0);   // not deducted, recorded for the card
            $table->decimal('balance', 8, 3);                        // running balance after this entry

            $table->string('action_taken')->nullable();  // "Date & action taken on application", e.g. "Approved 10/20/2026"

            $table->foreignId('recorded_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();

            // Leave card reads: one employee, one leave type, in order
            $table->index(['emp_id', 'leave_type_id', 'transaction_date', 'id'], 'leave_ledgers_card_index');
            $table->index('leave_application_id');

            // Stops the same monthly accrual / opening balance being posted twice.
            // Leave period_start NULL on adjustments so several can share a date.
            $table->unique(['emp_id', 'leave_type_id', 'entry_type', 'period_start'], 'leave_ledgers_no_duplicates');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('leave_ledgers');
    }
};