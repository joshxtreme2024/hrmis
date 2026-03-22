<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PersonalDataSheets extends Model
{
    protected $table = 'personal_data_sheets';

    protected $fillable = [
        'deleted_at',
        'position_id',
        'user_id',
        'employee_id',
        'photo_path',
        'cs_form_number',
        'revision_year',
        'first_name',
        'middle_name',
        'last_name',
        'name_extension',
        'date_of_birth',
        'place_of_birth',
        'sex',
        'civil_status',
        'citizenship',
        'dual_citizenship_type',
        'dual_citizenship_country',
        'height_m',
        'weight_kg',
        'blood_type',
        'gsis_id_no',
        'pagibig_id_no',
        'philhealth_no',
        'sss_no',
        'tin_no',
        'agency_employee_no',
        'telephone_no',
        'mobile_no',
        'email_address',
        'is_pwd',
        'pwd_id_no',
        'is_solo_parent',
        'solo_parent_id_no',
        'is_indigenous',
        'indigenous_details',
        'is_under_oath',
        'declaration_date',
        'declaration_place',
        'declaration_signature_path',
        'notary_name',
        'notary_commission_no',
        'notary_commission_expiry',
        'notary_doc_no',
        'notary_page_no',
        'notary_book_no',
        'notary_series_year',
        'work_experience_sheet_path',
        'pds_file_path',
        'status',
        'rejection_reason',
        'submitted_at',
        'approved_by',
        'approved_at',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
    public function position()
    {
        return $this->belongsTo(Positions::class, 'position_id');
    }
}
