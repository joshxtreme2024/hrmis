<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * CS Form No. 6 (Revised 2020) - Application for Leave
 * LGU Aguinaldo, Ifugao
 *
 * Assumes existing tables: `employees`, `departments`, `users`.
 * Rename the foreign key targets below if yours differ.
 */
return new class extends Migration
{
    public function up(): void
    {
        // Item 6.A - the list of leave types printed on the form
        Schema::create('leave_types', function (Blueprint $table) {
            $table->id();
            $table->string('code', 20)->unique();          // VL, SL, ML ...
            $table->string('name');
            $table->string('legal_basis')->nullable();     // e.g. "Sec. 51, Rule XVI, Omnibus Rules ... E.O. No. 292"
            $table->boolean('is_credit_based')->default(false); // true for VL / SL (deducted from 7.A balances)
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // Running VL / SL balances (feeds 7.A Certification of Leave Credits)
        Schema::create('leave_credits', function (Blueprint $table) {
            $table->id();
            $table->foreignId('emp_id')->constrained('pds_personal_data')->cascadeOnDelete();
            $table->foreignId('leave_type_id')->constrained('leave_types')->restrictOnDelete();
            $table->decimal('total_earned', 8, 3)->default(0);
            $table->decimal('total_used', 8, 3)->default(0);
            $table->date('as_of_date');
            $table->timestamps();

            $table->unique(['emp_id', 'leave_type_id']);
        });

        // The form itself (Sections 1-7)
        Schema::create('leave_applications', function (Blueprint $table) {
            $table->id();

            /* ---- Items 1-5: applicant ---- */
            $table->foreignId('emp_id')->constrained('pds_personal_data')->restrictOnDelete();
            $table->foreignId('department_id')->nullable()->constrained('departments')->nullOnDelete(); // 1. Office/Department
            $table->date('date_of_filing');                                                              // 3.
            $table->string('position');                                                                  // 4. (snapshot at filing)
            $table->decimal('salary', 12, 2)->nullable();                                                // 5. (snapshot at filing)

            /* ---- 6.A Type of leave ---- */
            $table->foreignId('leave_type_id')->constrained('leave_types')->restrictOnDelete();
            $table->string('leave_type_other')->nullable();        // "Others: ______"

            /* ---- 6.B Details of leave ---- */
            // Vacation / Special Privilege Leave
            $table->enum('vacation_location', ['within_philippines', 'abroad'])->nullable();
            $table->string('vacation_location_specify')->nullable();   // "Abroad (Specify)" / destination
            // Sick Leave
            $table->enum('sick_setting', ['in_hospital', 'out_patient'])->nullable();
            // Sick Leave illness, or Special Leave Benefits for Women illness
            $table->string('illness_specify')->nullable();
            // Study Leave
            $table->enum('study_purpose', ['masters_degree', 'bar_board_review'])->nullable();
            // Other purpose
            $table->enum('other_purpose', ['monetization', 'terminal_leave'])->nullable();

            /* ---- 6.C Working days applied for / inclusive dates ---- */
            $table->decimal('working_days', 5, 2);                  // allows half days (0.5)
            $table->date('date_from');
            $table->date('date_to');

            /* ---- 6.D Commutation ---- */
            $table->boolean('commutation_requested')->default(false); // false = Not Requested

            $table->string('applicant_signature_path')->nullable();   // "(Signature of Applicant)"

            /* ---- 7.A Certification of leave credits (HR) ---- */
            $table->date('credits_as_of')->nullable();
            $table->decimal('vl_total_earned', 8, 3)->nullable();
            $table->decimal('sl_total_earned', 8, 3)->nullable();
            $table->decimal('vl_less_this_application', 8, 3)->nullable();
            $table->decimal('sl_less_this_application', 8, 3)->nullable();
            $table->decimal('vl_balance', 8, 3)->nullable();
            $table->decimal('sl_balance', 8, 3)->nullable();
            $table->foreignId('certified_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('certified_at')->nullable();

            /* ---- 7.B Recommendation (immediate supervisor / dept. head) ---- */
            $table->enum('recommendation', ['for_approval', 'for_disapproval'])->nullable();
            $table->text('recommendation_disapproval_reason')->nullable();
            $table->foreignId('recommended_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('recommended_at')->nullable();

            /* ---- 7.C Approved for / 7.D Disapproved due to (Municipal Mayor) ---- */
            $table->decimal('approved_days_with_pay', 5, 2)->nullable();
            $table->decimal('approved_days_without_pay', 5, 2)->nullable();
            $table->decimal('approved_days_others', 5, 2)->nullable();
            $table->string('approved_others_specify')->nullable();
            $table->text('disapproval_reason')->nullable();
            $table->foreignId('approved_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('decided_at')->nullable();

            /* ---- Workflow ---- */
            $table->enum('status', [
                'draft', 'pending', 'certified', 'recommended',
                'approved', 'disapproved', 'cancelled',
            ])->default('draft');

            $table->timestamps();
            $table->softDeletes();

            $table->index(['emp_id', 'status']);
            $table->index('date_of_filing');
            $table->index(['date_from', 'date_to']);
        });

        // "Inclusive dates" - one row per actual leave day
        Schema::create('leave_application_dates', function (Blueprint $table) {
            $table->id();
            $table->foreignId('leave_application_id')->constrained('leave_applications')->cascadeOnDelete();
            $table->date('leave_date');
            $table->decimal('day_fraction', 3, 2)->default(1.00);   // 1.00 whole day, 0.50 half day
            $table->timestamps();

            $table->unique(['leave_application_id', 'leave_date']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('leave_application_dates');
        Schema::dropIfExists('leave_applications');
        Schema::dropIfExists('leave_credits');
        Schema::dropIfExists('leave_types');
    }
};