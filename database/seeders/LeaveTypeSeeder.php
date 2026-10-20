<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class LeaveTypeSeeder extends Seeder
{
    public function run(): void
    {
        $now = now();

        $types = [
            ['VL',     'Vacation Leave',                    'Sec. 51, Rule XVI, Omnibus Rules Implementing E.O. No. 292', true],
            ['MFL',    'Mandatory/Forced Leave',            'Sec. 25, Rule XVI, Omnibus Rules Implementing E.O. No. 292', false],
            ['SL',     'Sick Leave',                        'Sec. 43, Rule XVI, Omnibus Rules Implementing E.O. No. 292', true],
            ['ML',     'Maternity Leave',                   'R.A. No. 11210 / IRR issued by CSC, DOLE and SSS', false],
            ['PL',     'Paternity Leave',                   'R.A. No. 8187 / CSC MC No. 71, s. 1998, as amended', false],
            ['SPL',    'Special Privilege Leave',           'Sec. 21, Rule XVI, Omnibus Rules Implementing E.O. No. 292', false],
            ['SOLO',   'Solo Parent Leave',                 'RA No. 8972 / CSC MC No. 8, s. 2004', false],
            ['STUDY',  'Study Leave',                       'Sec. 68, Rule XVI, Omnibus Rules Implementing E.O. No. 292', false],
            ['VAWC',   '10-Day VAWC Leave',                 'RA No. 9262 / CSC MC No. 15, s. 2005', false],
            ['REHAB',  'Rehabilitation Privilege',          'Sec. 55, Rule XVI, Omnibus Rules Implementing E.O. No. 292', false],
            ['SLBW',   'Special Leave Benefits for Women',  'RA No. 9710 / CSC MC No. 25, s. 2010', false],
            ['SEL',    'Special Emergency (Calamity) Leave','CSC MC No. 2, s. 2012, as amended', false],
            ['ADOPT',  'Adoption Leave',                    'R.A. No. 8552', false],
            ['WL',     'Wellness Leave',                    null, false],
            ['OTHER',  'Others',                            null, false],
        ];

        DB::table('leave_types')->upsert(
            array_map(fn ($t) => [
                'code' => $t[0], 'name' => $t[1], 'legal_basis' => $t[2],
                'is_credit_based' => $t[3], 'is_active' => true,
                'created_at' => $now, 'updated_at' => $now,
            ], $types),
            ['code'],
            ['name', 'legal_basis', 'is_credit_based', 'updated_at']
        );
    }
}