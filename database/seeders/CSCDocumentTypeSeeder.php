<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\DocumentType;

class CSCDocumentTypeSeeder extends Seeder
{
    public function run()
    {
        // First, get all existing document type codes
        $existingTypes = DocumentType::all()->pluck('code')->toArray();
        
        $documentTypes = [
            // 1. Appointments
            [
                'code' => 'APPT',
                'name' => 'Appointment',
                'category' => 'Personnel Actions',
                'description' => 'Official appointment document from CSC or agency head',
                'is_required' => true,
                'sort_order' => 1,
                'csc_circular' => 'CSC MC No. 14, s. 1999'
            ],
            
            // 2. Assumption to Duty
            [
                'code' => 'ASSUM',
                'name' => 'Assumption to Duty',
                'category' => 'Personnel Actions',
                'description' => 'Document confirming assumption of duty/position',
                'is_required' => true,
                'sort_order' => 2,
                'csc_circular' => 'CSC MC No. 14, s. 1999'
            ],
            
            // 3. Oath of Office
            [
                'code' => 'OATH',
                'name' => 'Oath of Office',
                'category' => 'Personnel Actions',
                'description' => 'Signed oath of office document',
                'is_required' => true,
                'sort_order' => 3,
                'csc_circular' => 'CSC MC No. 14, s. 1999'
            ],
            
            // 4. Position Description Form (PDF)
            [
                'code' => 'PDF',
                'name' => 'Position Description Form',
                'category' => 'Personnel Actions',
                'description' => 'CSC Form No. 1 - Position Description Form',
                'is_required' => true,
                'sort_order' => 4,
                'csc_circular' => 'CSC MC No. 14, s. 1999'
            ],
            
            // 5. Designations
            [
                'code' => 'DESIG',
                'name' => 'Designations',
                'category' => 'Personnel Actions',
                'description' => 'Designation to other positions or committees',
                'is_required' => false,
                'sort_order' => 5,
                'csc_circular' => 'CSC MC No. 14, s. 1999'
            ],
            
            // 6. Notice of Salary Adjustment/Step Increment
            [
                'code' => 'SALARY',
                'name' => 'Notice of Salary Adjustment/Step Increment',
                'category' => 'Personnel Actions',
                'description' => 'Official notice of salary adjustment or step increment',
                'is_required' => false,
                'sort_order' => 6,
                'csc_circular' => 'CSC MC No. 14, s. 1999'
            ],
            
            // 7. Certificate of Eligibility
            [
                'code' => 'CSELIG',
                'name' => 'Certificate of Eligibility',
                'category' => 'Eligibility & Education',
                'description' => 'CSC issued certificate of eligibility (Civil Service, Bar, Board, etc.)',
                'is_required' => true,
                'sort_order' => 7,
                'csc_circular' => 'CSC MC No. 14, s. 1999'
            ],
            
            // 8. Copy of Diplomas
            [
                'code' => 'DIPLOMA',
                'name' => 'Copy of Diplomas',
                'category' => 'Eligibility & Education',
                'description' => 'College diplomas and other educational certificates',
                'is_required' => true,
                'sort_order' => 8,
                'csc_circular' => 'CSC MC No. 14, s. 1999'
            ],
            
            // 9. Transcript of Records
            [
                'code' => 'TOR',
                'name' => 'Transcript of Records',
                'category' => 'Eligibility & Education',
                'description' => 'Official Transcript of Records',
                'is_required' => true,
                'sort_order' => 9,
                'csc_circular' => 'CSC MC No. 14, s. 1999'
            ],
            
            // 10. Contracts of Service
            [
                'code' => 'CONTRACT',
                'name' => 'Contracts of Service',
                'category' => 'Employment',
                'description' => 'Contract of service or employment contract (if applicable)',
                'is_required' => false,
                'sort_order' => 10,
                'csc_circular' => 'CSC MC No. 14, s. 1999'
            ],
            
            // 11. Certification of Leave Balances (Transferees)
            [
                'code' => 'LEAVE',
                'name' => 'Certification of Leave Balances',
                'category' => 'Transferee Documents',
                'description' => 'Certification of leave balances for transferees',
                'is_required' => false,
                'sort_order' => 11,
                'csc_circular' => 'CSC MC No. 14, s. 1999'
            ],
            
            // 12. Clearance from Property and Money Accountabilities (Transferees)
            [
                'code' => 'CLEAR',
                'name' => 'Clearance from Property and Money Accountabilities',
                'category' => 'Transferee Documents',
                'description' => 'Clearance from property and money accountabilities for transferees',
                'is_required' => false,
                'sort_order' => 12,
                'csc_circular' => 'CSC MC No. 14, s. 1999'
            ],
            
            // 13. NBI Clearance
            [
                'code' => 'NBI',
                'name' => 'NBI Clearance',
                'category' => 'Government Requirements',
                'description' => 'NBI Clearance Certificate',
                'is_required' => true,
                'sort_order' => 13,
                'csc_circular' => 'CSC MC No. 14, s. 1999'
            ],
            
            // 14. Medical Certificate
            [
                'code' => 'MEDICAL',
                'name' => 'Medical Certificate',
                'category' => 'Medical',
                'description' => 'Medical examination certificate',
                'is_required' => true,
                'sort_order' => 14,
                'csc_circular' => 'CSC MC No. 14, s. 1999'
            ],
            
            // 15. Marriage Contract (if applicable)
            [
                'code' => 'MARRIAGE',
                'name' => 'Marriage Contract',
                'category' => 'Personal',
                'description' => 'Copy of Marriage Contract (if applicable)',
                'is_required' => false,
                'sort_order' => 15,
                'csc_circular' => 'CSC MC No. 14, s. 1999'
            ],
            
            // 16. Commendations and Awards
            [
                'code' => 'AWARDS',
                'name' => 'Commendations and Awards',
                'category' => 'Performance',
                'description' => 'Awards, commendations, and recognition received',
                'is_required' => false,
                'sort_order' => 16,
                'csc_circular' => 'CSC MC No. 14, s. 1999'
            ],
            
            // 17. Disciplinary Actions (if any)
            [
                'code' => 'DISCIPLINE',
                'name' => 'Disciplinary Actions',
                'category' => 'Performance',
                'description' => 'Copies of disciplinary actions (if any)',
                'is_required' => false,
                'sort_order' => 17,
                'csc_circular' => 'CSC MC No. 14, s. 1999'
            ],
            
            // 18. Service Records
            [
                'code' => 'SERVICE',
                'name' => 'Service Records',
                'category' => 'Employment',
                'description' => 'Service Record - CSC Form No. 212',
                'is_required' => true,
                'sort_order' => 18,
                'csc_circular' => 'CSC MC No. 14, s. 1999'
            ],
        ];

        // Create the main document types
        foreach ($documentTypes as $type) {
            DocumentType::updateOrCreate(
                ['code' => $type['code']],
                $type
            );
        }

        // New document types to add
        $newTypes = [
            [
                'code' => 'SALN',
                'name' => 'Statement of Assets, Liabilities, and Net Worth (SALN)',
                'category' => 'Performance',
                'description' => 'Annual SALN filing as required by the Civil Service Commission',
                'is_required' => true,
                'sort_order' => 19,
                'csc_circular' => 'CSC MC No. 14, s. 1999'
            ],
            [
                'code' => 'IPCR',
                'name' => 'Individual Performance Commitment and Review (IPCR)',
                'category' => 'Performance',
                'description' => 'IPCR rating for performance evaluation',
                'is_required' => true,
                'sort_order' => 20,
                'csc_circular' => 'CSC MC No. 14, s. 1999'
            ],
        ];
        
        // Only add new types if they don't already exist
        foreach ($newTypes as $type) {
            if (!in_array($type['code'], $existingTypes)) {
                DocumentType::create($type);
            }
        }
    }
}