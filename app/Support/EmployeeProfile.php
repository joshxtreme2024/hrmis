<?php

namespace App\Support;

use App\Models\PDSEmployment;

/**
 * The ONE place that knows how a PDSPersonalData record maps to the fields the leave module needs.
 *  - Name: PDSPersonalData::completeName()
 *  - Department / position: the user's PDSEmployment record (same as User::department_name / position_title)
 */
class EmployeeProfile
{
    // Columns of the PDS personal data table (only used for searching the HR employee list)
    public const SURNAME    = 'surname';
    public const FIRST_NAME = 'first_name';

    // Column on PDSEmployment that holds the monthly salary
    public const SALARY = 'salary';

    public static function name($pds): string
    {
        if (! $pds) return '—';

        return (method_exists($pds, 'completeName') ? $pds->completeName() : null) ?: '—';
    }

    /** The PDSEmployment of this PDS record's user. Pass a preloaded one to avoid extra queries. */
    public static function employment($pds, $employment = null)
    {
        if ($employment) return $employment;

        return $pds ? PDSEmployment::with(['department', 'position'])->where('user_id', $pds->user_id)->first() : null;
    }

    public static function departmentId($pds, $employment = null)
    {
        return self::employment($pds, $employment)?->department?->id;
    }

    public static function position($pds, $employment = null)
    {
        return self::employment($pds, $employment)?->position?->title;
    }

    public static function salary($pds, $employment = null)
    {
        return data_get(self::employment($pds, $employment), self::SALARY);
    }
}