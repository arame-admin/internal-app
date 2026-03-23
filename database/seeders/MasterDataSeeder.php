<?php

namespace Database\Seeders;

use App\Models\Department;
use App\Models\Designation;
use App\Models\BusinessUnit;
use App\Models\Location;
use App\Models\ProjectDepartment;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class MasterDataSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Seed Departments
        $departments = [
            ['name' => 'Leadership', 'code' => 'LEAD', 'status' => 'active'],
            ['name' => 'Engineering', 'code' => 'ENG', 'status' => 'active'],
            ['name' => 'Design', 'code' => 'DSGN', 'status' => 'active'],
            ['name' => 'QA', 'code' => 'QA', 'status' => 'active'],
            ['name' => 'HR', 'code' => 'HR', 'status' => 'active'],
            ['name' => 'Finance', 'code' => 'FIN', 'status' => 'active'],
            ['name' => 'Sales', 'code' => 'SALES', 'status' => 'active'],
        ];
        $departmentMap = [];
        foreach ($departments as $department) {
            $dept = Department::firstOrCreate(
                ['name' => $department['name']],
                ['code' => $department['code'], 'status' => $department['status']]
            );
            $departmentMap[$department['name']] = $dept->id;
        }

        // Seed Project Departments
        $projectDepartments = [
            [
                'name' => 'AraMe Operations',
                'code' => 'AROPS',
                'status' => 'active',
                'available_tasks' => json_encode([
                    'Internal Meeting',
                    'Client Meeting',
                    'On Duty Task',
                    'Operational Task'
                ])
            ],
            [
                'name' => 'Service Projects',
                'code' => 'SRVPJ',
                'status' => 'active',
                'available_tasks' => json_encode([
                    'UI/UX',
                    'Coding',
                    'Testing',
                    'DevOps',
                    'Client Meeting',
                    'Project Meeting'
                ])
            ],
            [
                'name' => 'AraMe Product',
                'code' => 'ARPROD',
                'status' => 'active',
                'available_tasks' => json_encode([
                    'UI/UX',
                    'Coding',
                    'Testing',
                    'DevOps',
                    'Project Meeting'
                ])
            ],
            [
                'name' => 'Digital Marketing',
                'code' => 'DGMKT',
                'status' => 'active',
                'available_tasks' => json_encode([
                    'Client Meeting',
                    'Project Meeting',
                    'Creatives',
                    'Project Task'
                ])
            ]
        ];

        $projectDeptMap = [];
        foreach ($projectDepartments as $deptData) {
            $dept = ProjectDepartment::firstOrCreate(
                ['name' => $deptData['name']],
                [
                    'code' => $deptData['code'],
                    'status' => $deptData['status'],
                    'available_tasks' => $deptData['available_tasks']
                ]
            );
            $projectDeptMap[$deptData['name']] = $dept->id;
        }

        // Seed Designations
        $designations = [
            // Software Engineers
            ['name' => 'Jr. Software Engineer G2', 'code' => 'JR-SE-G2', 'department_id' => $departmentMap['Engineering'], 'status' => 'active'],
            ['name' => 'Jr. Software Engineer G1', 'code' => 'JR-SE-G1', 'department_id' => $departmentMap['Engineering'], 'status' => 'active'],
            ['name' => 'Software Engineer G2', 'code' => 'SE-G2', 'department_id' => $departmentMap['Engineering'], 'status' => 'active'],
            ['name' => 'Software Engineer G1', 'code' => 'SE-G1', 'department_id' => $departmentMap['Engineering'], 'status' => 'active'],
            ['name' => 'Sr. Software Engineer G3', 'code' => 'SR-SE-G3', 'department_id' => $departmentMap['Engineering'], 'status' => 'active'],
            ['name' => 'Sr. Software Engineer G2', 'code' => 'SR-SE-G2', 'department_id' => $departmentMap['Engineering'], 'status' => 'active'],
            ['name' => 'Sr. Software Engineer G1', 'code' => 'SR-SE-G1', 'department_id' => $departmentMap['Engineering'], 'status' => 'active'],

            // QA Engineers
            ['name' => 'Jr. QA Engineer G2', 'code' => 'JR-QA-G2', 'department_id' => $departmentMap['QA'], 'status' => 'active'],
            ['name' => 'Jr. QA Engineer G1', 'code' => 'JR-QA-G1', 'department_id' => $departmentMap['QA'], 'status' => 'active'],
            ['name' => 'QA Engineer G2', 'code' => 'QA-G2', 'department_id' => $departmentMap['QA'], 'status' => 'active'],
            ['name' => 'QA Engineer G1', 'code' => 'QA-G1', 'department_id' => $departmentMap['QA'], 'status' => 'active'],
            ['name' => 'Sr. QA Engineer G3', 'code' => 'SR-QA-G3', 'department_id' => $departmentMap['QA'], 'status' => 'active'],
            ['name' => 'Sr. QA Engineer G2', 'code' => 'SR-QA-G2', 'department_id' => $departmentMap['QA'], 'status' => 'active'],
            ['name' => 'Sr. QA Engineer G1', 'code' => 'SR-QA-G1', 'department_id' => $departmentMap['QA'], 'status' => 'active'],
            ['name' => 'QA Lead', 'code' => 'QAL', 'department_id' => $departmentMap['QA'], 'status' => 'active'],

            // UI/UX & Frontend
            ['name' => 'UI/UX Designer G2', 'code' => 'UX-G2', 'department_id' => $departmentMap['Design'], 'status' => 'active'],
            ['name' => 'UI/UX Designer G1', 'code' => 'UX-G1', 'department_id' => $departmentMap['Design'], 'status' => 'active'],
            ['name' => 'UI Developer G2', 'code' => 'UI-G2', 'department_id' => $departmentMap['Engineering'], 'status' => 'active'],
            ['name' => 'UI Developer G1', 'code' => 'UI-G1', 'department_id' => $departmentMap['Engineering'], 'status' => 'active'],

            // DevOps
            ['name' => 'DevOps Engineer G2', 'code' => 'DEVOPS-G2', 'department_id' => $departmentMap['Engineering'], 'status' => 'active'],
            ['name' => 'DevOps Engineer G1', 'code' => 'DEVOPS-G1', 'department_id' => $departmentMap['Engineering'], 'status' => 'active'],
            ['name' => 'Jr DevOps Engineer G2', 'code' => 'JR-DEVOPS-G2', 'department_id' => $departmentMap['Engineering'], 'status' => 'active'],
            ['name' => 'Jr DevOps Engineer G1', 'code' => 'JR-DEVOPS-G1', 'department_id' => $departmentMap['Engineering'], 'status' => 'active'],
            ['name' => 'Network Admin', 'code' => 'NETADMIN', 'department_id' => $departmentMap['Engineering'], 'status' => 'active'],

            // Management
            ['name' => 'CEO', 'code' => 'CEO', 'department_id' => $departmentMap['Leadership'], 'status' => 'active'],
            ['name' => 'CTO', 'code' => 'CTO', 'department_id' => $departmentMap['Leadership'], 'status' => 'active'],
            ['name' => 'CFO', 'code' => 'CFO', 'department_id' => $departmentMap['Leadership'], 'status' => 'active'],
            ['name' => 'COO', 'code' => 'COO', 'department_id' => $departmentMap['Leadership'], 'status' => 'active'],
            ['name' => 'VP Engineering', 'code' => 'VPE', 'department_id' => $departmentMap['Engineering'], 'status' => 'active'],
            ['name' => 'VP Operations', 'code' => 'VPOPS', 'department_id' => $departmentMap['Leadership'], 'status' => 'active'],
            ['name' => 'VP Sales', 'code' => 'VPS', 'department_id' => $departmentMap['Sales'], 'status' => 'active'],
            ['name' => 'VP Marketing', 'code' => 'VPM', 'department_id' => $departmentMap['Leadership'], 'status' => 'active'],
            ['name' => 'Director', 'code' => 'DIR', 'department_id' => $departmentMap['Leadership'], 'status' => 'active'],
            ['name' => 'Senior Director', 'code' => 'SR-DIR', 'department_id' => $departmentMap['Leadership'], 'status' => 'active'],
            ['name' => 'Head of Engineering', 'code' => 'HOE', 'department_id' => $departmentMap['Engineering'], 'status' => 'active'],
            ['name' => 'Head of Product', 'code' => 'HOP', 'department_id' => $departmentMap['Leadership'], 'status' => 'active'],
            ['name' => 'Head of HR', 'code' => 'HOHR', 'department_id' => $departmentMap['HR'], 'status' => 'active'],
            ['name' => 'Lead Engineer', 'code' => 'LE', 'department_id' => $departmentMap['Engineering'], 'status' => 'active'],
            ['name' => 'Project Manager', 'code' => 'PM', 'department_id' => $departmentMap['Leadership'], 'status' => 'active'],
            ['name' => 'Scrum Master', 'code' => 'SCRUM-M', 'department_id' => $departmentMap['Leadership'], 'status' => 'active'],
            ['name' => 'Product Owner', 'code' => 'PO', 'department_id' => $departmentMap['Leadership'], 'status' => 'active'],
            ['name' => 'Solution Architect G2', 'code' => 'SA-G2', 'department_id' => $departmentMap['Engineering'], 'status' => 'active'],
            ['name' => 'Solution Architect G1', 'code' => 'SA-G1', 'department_id' => $departmentMap['Engineering'], 'status' => 'active'],
            ['name' => 'Assistant Manager', 'code' => 'AM', 'department_id' => $departmentMap['Leadership'], 'status' => 'active'],
            ['name' => 'Manager', 'code' => 'MGR', 'department_id' => $departmentMap['Leadership'], 'status' => 'active'],

            // Mobile Developers
            ['name' => 'Jr. Mobile App Developer G2', 'code' => 'JR-MOB-G2', 'department_id' => $departmentMap['Engineering'], 'status' => 'active'],
            ['name' => 'Jr. Mobile App Developer G1', 'code' => 'JR-MOB-G1', 'department_id' => $departmentMap['Engineering'], 'status' => 'active'],
            ['name' => 'Mobile App Developer G2', 'code' => 'MOB-G2', 'department_id' => $departmentMap['Engineering'], 'status' => 'active'],
            ['name' => 'Mobile App Developer G1', 'code' => 'MOB-G1', 'department_id' => $departmentMap['Engineering'], 'status' => 'active'],
            ['name' => 'Sr. Mobile App Developer G3', 'code' => 'SR-MOB-G3', 'department_id' => $departmentMap['Engineering'], 'status' => 'active'],
            ['name' => 'Sr. Mobile App Developer G2', 'code' => 'SR-MOB-G2', 'department_id' => $departmentMap['Engineering'], 'status' => 'active'],
            ['name' => 'Sr. Mobile App Developer G1', 'code' => 'SR-MOB-G1', 'department_id' => $departmentMap['Engineering'], 'status' => 'active'],

            // Other Roles
            ['name' => 'Data Analyst', 'code' => 'DA', 'department_id' => $departmentMap['Leadership'], 'status' => 'active'],
            ['name' => 'Data Scientist', 'code' => 'DS', 'department_id' => $departmentMap['Leadership'], 'status' => 'active'],
            ['name' => 'Business Development Executive', 'code' => 'BDE', 'department_id' => $departmentMap['Sales'], 'status' => 'active'],
            ['name' => 'Sales Manager', 'code' => 'SAL-MGR', 'department_id' => $departmentMap['Sales'], 'status' => 'active'],
            ['name' => 'Marketing Executive', 'code' => 'ME', 'department_id' => $departmentMap['Leadership'], 'status' => 'active'],

            // HR Designations
            ['name' => 'HR Intern', 'code' => 'HR-INT', 'department_id' => $departmentMap['HR'], 'status' => 'active'],
            ['name' => 'Jr. HR Executive', 'code' => 'JR-HRE', 'department_id' => $departmentMap['HR'], 'status' => 'active'],
            ['name' => 'HR Executive', 'code' => 'HRE', 'department_id' => $departmentMap['HR'], 'status' => 'active'],
            ['name' => 'Sr. HR Executive', 'code' => 'SR-HRE', 'department_id' => $departmentMap['HR'], 'status' => 'active'],
            ['name' => 'Talent Acquisition', 'code' => 'TAM', 'department_id' => $departmentMap['HR'], 'status' => 'active'],
            ['name' => 'L&D Manager', 'code' => 'LDM', 'department_id' => $departmentMap['HR'], 'status' => 'active'],

            // Finance Designations
            ['name' => 'Finance Intern', 'code' => 'FIN-INT', 'department_id' => $departmentMap['Finance'], 'status' => 'active'],
            ['name' => 'Jr. Finance Executive', 'code' => 'JR-FIN', 'department_id' => $departmentMap['Finance'], 'status' => 'active'],
            ['name' => 'Finance Executive', 'code' => 'FIN', 'department_id' => $departmentMap['Finance'], 'status' => 'active'],
            ['name' => 'Finance Manager', 'code' => 'FINM', 'department_id' => $departmentMap['Finance'], 'status' => 'active'],
            ['name' => 'Finance Director', 'code' => 'FIND', 'department_id' => $departmentMap['Finance'], 'status' => 'active'],
        ];
        foreach ($designations as $designation) {
            Designation::firstOrCreate(
                ['name' => $designation['name']],
                $designation
            );
        }

        // Seed Business Unit
        BusinessUnit::firstOrCreate(
            ['name' => 'BU1'],
            ['code' => 'BU1', 'status' => 'active']
        );

        // Seed Location
        Location::firstOrCreate(
            ['name' => 'DotSpaces Trivandrum'],
            ['code' => 'LOC001', 'status' => 'active']
        );

        // Seed Project Departments (separate from employee departments)
        $projectDepartments = [
            [
                'name' => 'AraMe Operations',
                'code' => 'AROPS',
                'status' => 'active',
                'description' => 'Internal operations and administrative tasks',
                'available_tasks' => json_encode(['Internal Meeting', 'Client Meeting', 'On Duty Task', 'Operational Task'])
            ],
            [
                'name' => 'Service Projects',
                'code' => 'SRVPJ',
                'status' => 'active',
                'description' => 'Client-facing service projects',
                'available_tasks' => json_encode(['UI/UX', 'Coding', 'Testing', 'DevOps', 'Client Meeting', 'Project Meeting'])
            ],
            [
                'name' => 'AraMe Product',
                'code' => 'ARPROD',
                'status' => 'active',
                'description' => 'Internal product development projects',
                'available_tasks' => json_encode(['UI/UX', 'Coding', 'Testing', 'DevOps', 'Project Meeting'])
            ],
            [
                'name' => 'Digital Marketing',
                'code' => 'DGMKT',
                'status' => 'active',
                'description' => 'Digital marketing and campaign projects',
                'available_tasks' => json_encode(['Client Meeting', 'Project Meeting', 'Creatives', 'Project Task'])
            ],
        ];
        $projectDeptMap = [];
        foreach ($projectDepartments as $projDept) {
            $dept = ProjectDepartment::firstOrCreate(
                ['name' => $projDept['name']],
                $projDept
            );
            $projectDeptMap[$projDept['name']] = $dept->id;
        }

        // Seed sample projects for testing timesheet task selection
        $clients = \App\Models\Client::take(5)->get();
        
        $sampleProjects = [
            [
                'name' => 'Internal App Development',
                'client_id' => $clients[0]->id ?? 1,
                'project_department_id' => $projectDeptMap['AraMe Product'] ?? 3,
                'description' => 'Internal management app',
                'status' => 'in_progress',
                'tasks' => json_encode(['Frontend Development', 'Backend API', 'Testing', 'Deployment', 'Bug Fix']),
                'start_date' => now()->subMonths(2),
                'end_date' => now()->addMonths(3),
            ],
            [
                'name' => 'Client Portal v2',
                'client_id' => $clients[1]->id ?? 2,
                'project_department_id' => $projectDeptMap['Service Projects'] ?? 2,
                'description' => 'New client portal features',
                'status' => 'in_progress',
                'tasks' => json_encode(['UI/UX', 'Coding', 'Client Meeting', 'Project Meeting']),
                'start_date' => now()->subMonth(),
                'end_date' => now()->addMonth(),
            ],
            [
                'name' => 'Mobile App Update',
                'client_id' => $clients[2]->id ?? 3,
                'project_department_id' => $projectDeptMap['AraMe Product'] ?? 3,
                'description' => 'Bug fixes and new features',
                'status' => 'testing',
                'tasks' => json_encode(['Coding', 'Testing', 'DevOps']),
                'start_date' => now()->subWeeks(2),
                'end_date' => now()->addWeeks(4),
            ],
            [
                'name' => 'Ops Dashboard',
                'client_id' => $clients[0]->id ?? 1,
                'project_department_id' => $projectDeptMap['AraMe Operations'] ?? 1,
                'description' => 'Operations dashboard',
                'status' => 'planning',
                'tasks' => json_encode(['Internal Meeting', 'Client Meeting', 'On Duty Task']),
                'start_date' => now(),
                'end_date' => now()->addMonth(),
            ],
        ];

        foreach ($sampleProjects as $projData) {
            \App\Models\Project::firstOrCreate(
                ['name' => $projData['name']],
                $projData
            );
        }
    }
}
