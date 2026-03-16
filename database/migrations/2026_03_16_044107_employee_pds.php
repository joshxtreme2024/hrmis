<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // MAIN PERSONAL DATA SHEET TABLE - Core information only
        Schema::create('personal_data_sheets', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->softDeletes();
            
            // Reference
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->string('employee_id')->nullable()->unique();
            
            // CS Form identification
            $table->string('cs_form_number')->default('CS Form No. 212');
            $table->string('revision_year')->default('Revised 2025');
            
            /**
             * PERSONAL INFORMATION - Reduced VARCHAR sizes
             */
            $table->string('first_name', 100);
            $table->string('middle_name', 100)->nullable();
            $table->string('last_name', 100);
            $table->string('name_extension', 20)->nullable();
            $table->date('date_of_birth');
            $table->string('place_of_birth', 200);
            $table->enum('sex', ['Male', 'Female'])->nullable();
            $table->enum('civil_status', ['Single', 'Married', 'Widowed', 'Separated', 'Divorced', 'Annulled'])->nullable();
            $table->string('citizenship', 50)->default('Filipino');
            $table->string('dual_citizenship_type', 50)->nullable();
            $table->string('dual_citizenship_country', 100)->nullable();
            
            // Physical attributes
            $table->decimal('height_m', 5, 2)->nullable();
            $table->decimal('weight_kg', 5, 2)->nullable();
            $table->string('blood_type', 5)->nullable();
            
            // Government IDs - Reduced sizes
            $table->string('gsis_id_no', 50)->nullable();
            $table->string('pagibig_id_no', 50)->nullable();
            $table->string('philhealth_no', 50)->nullable();
            $table->string('sss_no', 50)->nullable();
            $table->string('tin_no', 50)->nullable();
            $table->string('agency_employee_no', 50)->nullable();
            
            // Contact - Reduced sizes
            $table->string('telephone_no', 30)->nullable();
            $table->string('mobile_no', 30);
            $table->string('email_address', 100);
            
            // Status flags
            $table->boolean('is_pwd')->default(false);
            $table->string('pwd_id_no', 50)->nullable();
            $table->boolean('is_solo_parent')->default(false);
            $table->string('solo_parent_id_no', 50)->nullable();
            $table->boolean('is_indigenous')->default(false);
            $table->string('indigenous_details', 200)->nullable();
            
            /**
             * DECLARATION
             */
            $table->boolean('is_under_oath')->default(false);
            $table->date('declaration_date')->nullable();
            $table->string('declaration_place', 200)->nullable();
            $table->string('declaration_signature_path', 255)->nullable();
            
            // Notary Information - Reduced sizes
            $table->string('notary_name', 200)->nullable();
            $table->string('notary_commission_no', 50)->nullable();
            $table->date('notary_commission_expiry')->nullable();
            $table->string('notary_doc_no', 50)->nullable();
            $table->string('notary_page_no', 20)->nullable();
            $table->string('notary_book_no', 20)->nullable();
            $table->string('notary_series_year', 10)->nullable();
            
            // File attachments
            $table->string('work_experience_sheet_path', 255)->nullable();
            $table->string('pds_file_path', 255)->nullable();
            
            /**
             * Status tracking
             */
            $table->string('status', 20)->default('draft');
            $table->text('rejection_reason')->nullable();
            $table->timestamp('submitted_at')->nullable();
            $table->foreignId('approved_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('approved_at')->nullable();
            
            // Indexes
            $table->index(['last_name', 'first_name']);
            $table->index('employee_id');
            $table->index('sss_no');
            $table->index('tin_no');
            $table->index('status');
        });

        // FAMILY BACKGROUND TABLE
        Schema::create('pds_family_background', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->foreignId('personal_data_sheet_id')->constrained()->cascadeOnDelete();
            
            // Spouse
            $table->string('spouse_first_name', 100)->nullable();
            $table->string('spouse_middle_name', 100)->nullable();
            $table->string('spouse_last_name', 100)->nullable();
            $table->string('spouse_name_extension', 20)->nullable();
            $table->string('spouse_occupation', 200)->nullable();
            $table->string('spouse_employer_business', 200)->nullable();
            $table->string('spouse_business_address', 255)->nullable();
            $table->string('spouse_telephone_no', 30)->nullable();
            
            // Father
            $table->string('father_first_name', 100);
            $table->string('father_middle_name', 100)->nullable();
            $table->string('father_last_name', 100);
            $table->string('father_name_extension', 20)->nullable();
            
            // Mother
            $table->string('mother_first_name', 100);
            $table->string('mother_middle_name', 100)->nullable();
            $table->string('mother_last_name', 100);
            $table->string('mother_maiden_last_name', 100)->nullable();
        });

        // CHILDREN TABLE
        Schema::create('pds_children', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->foreignId('personal_data_sheet_id')->constrained()->cascadeOnDelete();
            $table->string('name', 200);
            $table->date('date_of_birth')->nullable();
            $table->integer('order')->default(0);
        });

        // EDUCATIONAL BACKGROUND TABLE
        Schema::create('pds_education', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->foreignId('personal_data_sheet_id')->constrained()->cascadeOnDelete();
            $table->enum('level', ['Elementary', 'Secondary', 'Senior High', 'College', 'Graduate Studies', 'Post Graduate']);
            $table->string('school_name', 200);
            $table->string('school_address', 255)->nullable();
            $table->string('degree_course', 200)->nullable();
            $table->string('period_from', 10)->nullable();
            $table->string('period_to', 10)->nullable();
            $table->string('highest_level_earned', 100)->nullable();
            $table->string('year_graduated', 10)->nullable();
            $table->string('scholarship_honors', 255)->nullable();
            $table->integer('order')->default(0);
            
            $table->index(['personal_data_sheet_id', 'level']);
        });

        // CIVIL SERVICE ELIGIBILITY TABLE
        Schema::create('pds_eligibilities', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->foreignId('personal_data_sheet_id')->constrained()->cascadeOnDelete();
            $table->string('career_service', 200);
            $table->string('rating', 20)->nullable();
            $table->date('examination_date')->nullable();
            $table->string('examination_place', 200)->nullable();
            $table->string('license_number', 50)->nullable();
            $table->date('license_date_validity')->nullable();
            $table->integer('order')->default(0);
        });

        // WORK EXPERIENCE TABLE
        Schema::create('pds_work_experiences', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->foreignId('personal_data_sheet_id')->constrained()->cascadeOnDelete();
            $table->date('inclusive_from');
            $table->date('inclusive_to')->nullable();
            $table->string('position_title', 200);
            $table->string('department_agency_office', 255);
            $table->decimal('monthly_salary', 12, 2)->nullable();
            $table->string('salary_grade', 20)->nullable();
            $table->string('status_of_appointment', 50)->nullable();
            $table->boolean('is_government_service')->default(false);
            $table->integer('order')->default(0);
            
            $table->index(['personal_data_sheet_id', 'inclusive_from']);
        });

        // VOLUNTARY WORK TABLE
        Schema::create('pds_voluntary_works', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->foreignId('personal_data_sheet_id')->constrained()->cascadeOnDelete();
            $table->string('organization_name', 200);
            $table->string('organization_address', 255)->nullable();
            $table->date('inclusive_from');
            $table->date('inclusive_to')->nullable();
            $table->decimal('number_of_hours', 8, 2)->nullable();
            $table->string('position_nature_of_work', 200)->nullable();
            $table->integer('order')->default(0);
        });

        // TRAININGS TABLE
        Schema::create('pds_trainings', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->foreignId('personal_data_sheet_id')->constrained()->cascadeOnDelete();
            $table->string('title_of_program', 255);
            $table->date('inclusive_from');
            $table->date('inclusive_to');
            $table->decimal('number_of_hours', 8, 2);
            $table->string('type_of_ld', 100)->nullable();
            $table->string('conducted_by', 200)->nullable();
            $table->integer('order')->default(0);
            
            $table->index(['personal_data_sheet_id', 'inclusive_from']);
        });

        // OTHER INFORMATION TABLE
        Schema::create('pds_other_information', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->foreignId('personal_data_sheet_id')->constrained()->cascadeOnDelete();
            
            // Skills and hobbies (using TEXT for longer content)
            $table->text('special_skills')->nullable();
            $table->text('hobbies')->nullable();
            $table->text('non_academic_distinctions')->nullable();
            $table->text('membership_associations')->nullable();
            
            // Government issues
            $table->boolean('has_administrative_offense')->default(false);
            $table->text('administrative_offense_details')->nullable();
            
            $table->boolean('has_criminal_charge')->default(false);
            $table->text('criminal_charge_details')->nullable();
            
            $table->boolean('has_criminal_conviction')->default(false);
            $table->text('criminal_conviction_details')->nullable();
            
            $table->boolean('has_separation_from_service')->default(false);
            $table->text('separation_details')->nullable();
            
            $table->boolean('has_been_election_candidate')->default(false);
            $table->text('election_candidate_details')->nullable();
            
            $table->boolean('has_resigned_for_election')->default(false);
            $table->text('resignation_election_details')->nullable();
            
            $table->boolean('is_immigrant')->default(false);
            $table->string('immigrant_country', 100)->nullable();
        });

        // CHARACTER REFERENCES TABLE
        Schema::create('pds_references', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->foreignId('personal_data_sheet_id')->constrained()->cascadeOnDelete();
            $table->string('name', 200);
            $table->string('address', 255);
            $table->string('telephone_no', 30)->nullable();
            $table->string('mobile_no', 30)->nullable();
            $table->string('email', 100)->nullable();
            $table->integer('order')->default(0);
        });
    }

    public function down(): void
    {
        // Drop in reverse order to avoid foreign key constraints
        Schema::dropIfExists('pds_references');
        Schema::dropIfExists('pds_other_information');
        Schema::dropIfExists('pds_trainings');
        Schema::dropIfExists('pds_voluntary_works');
        Schema::dropIfExists('pds_work_experiences');
        Schema::dropIfExists('pds_eligibilities');
        Schema::dropIfExists('pds_education');
        Schema::dropIfExists('pds_children');
        Schema::dropIfExists('pds_family_background');
        Schema::dropIfExists('personal_data_sheets');
    }
};