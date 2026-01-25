<?php

namespace App\Http\Controllers\Admin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;

class ProjectController extends Controller
{
    /**
     * Display a listing of projects with search, filter, and pagination.
     */
    public function index(Request $request)
    {
        // Sample data - replace with actual database query
        $projects = [
            ['id' => 1, 'name' => 'E-Commerce Platform', 'client_id' => 1, 'client_name' => 'John Doe', 'project_type' => ['web_application'], 'status' => 'in_progress', 'start_date' => '2024-01-15', 'end_date' => '2024-06-15', 'budget' => 50000, 'progress_percentage' => 65, 'priority' => 'high', 'assigned_users' => 4, 'team_members' => [['user_id' => 1, 'role' => 'project_manager'], ['user_id' => 2, 'role' => 'lead_developer'], ['user_id' => 3, 'role' => 'designer'], ['user_id' => 4, 'role' => 'developer']]],
            ['id' => 2, 'name' => 'Mobile Banking App', 'client_id' => 2, 'client_name' => 'Jane Smith', 'project_type' => ['mobile_application', 'web_application'], 'status' => 'planning', 'start_date' => '2024-02-01', 'end_date' => '2024-08-01', 'budget' => 75000, 'progress_percentage' => 20, 'priority' => 'high', 'assigned_users' => 6, 'team_members' => [['user_id' => 2, 'role' => 'project_manager'], ['user_id' => 5, 'role' => 'lead_developer'], ['user_id' => 7, 'role' => 'developer'], ['user_id' => 8, 'role' => 'designer'], ['user_id' => 4, 'role' => 'tester'], ['user_id' => 6, 'role' => 'business_analyst']]],
            ['id' => 3, 'name' => 'CRM System', 'client_id' => 3, 'client_name' => 'Bob Johnson', 'project_type' => ['web_application'], 'status' => 'completed', 'start_date' => '2023-09-01', 'end_date' => '2024-02-28', 'budget' => 35000, 'progress_percentage' => 100, 'priority' => 'medium', 'assigned_users' => 3, 'team_members' => [['user_id' => 6, 'role' => 'project_manager'], ['user_id' => 1, 'role' => 'developer'], ['user_id' => 3, 'role' => 'designer']]],
            ['id' => 4, 'name' => 'Restaurant POS', 'client_id' => 4, 'client_name' => 'Alice Brown', 'project_type' => ['desktop_application', 'api_integration'], 'status' => 'in_progress', 'start_date' => '2024-01-20', 'end_date' => '2024-05-20', 'budget' => 25000, 'progress_percentage' => 45, 'priority' => 'medium', 'assigned_users' => 2, 'team_members' => [['user_id' => 4, 'role' => 'project_manager'], ['user_id' => 7, 'role' => 'developer']]],
            ['id' => 5, 'name' => 'Learning Management', 'client_id' => 5, 'client_name' => 'Charlie Wilson', 'project_type' => ['web_application', 'mobile_application'], 'status' => 'on_hold', 'start_date' => '2024-03-01', 'end_date' => '2024-09-01', 'budget' => 60000, 'progress_percentage' => 10, 'priority' => 'low', 'assigned_users' => 5, 'team_members' => [['user_id' => 2, 'role' => 'project_manager'], ['user_id' => 5, 'role' => 'lead_developer'], ['user_id' => 1, 'role' => 'developer'], ['user_id' => 8, 'role' => 'designer'], ['user_id' => 4, 'role' => 'tester']]],
            ['id' => 6, 'name' => 'Healthcare Portal', 'client_id' => 6, 'client_name' => 'Diana Davis', 'project_type' => ['web_application', 'api_integration'], 'status' => 'in_progress', 'start_date' => '2023-12-01', 'end_date' => '2024-07-01', 'budget' => 80000, 'progress_percentage' => 75, 'priority' => 'high', 'assigned_users' => 8, 'team_members' => [['user_id' => 6, 'role' => 'project_manager'], ['user_id' => 2, 'role' => 'lead_developer'], ['user_id' => 1, 'role' => 'developer'], ['user_id' => 5, 'role' => 'developer'], ['user_id' => 7, 'role' => 'developer'], ['user_id' => 3, 'role' => 'designer'], ['user_id' => 8, 'role' => 'designer'], ['user_id' => 4, 'role' => 'tester']]],
            ['id' => 7, 'name' => 'Inventory System', 'client_id' => 7, 'client_name' => 'Edward Miller', 'project_type' => ['web_application'], 'status' => 'cancelled', 'start_date' => '2024-01-10', 'end_date' => '2024-04-10', 'budget' => 20000, 'progress_percentage' => 0, 'priority' => 'low', 'assigned_users' => 2, 'team_members' => [['user_id' => 4, 'role' => 'project_manager'], ['user_id' => 1, 'role' => 'developer']]],
            ['id' => 8, 'name' => 'Social Media Dashboard', 'client_id' => 8, 'client_name' => 'Fiona Garcia', 'project_type' => ['web_application', 'api_integration'], 'status' => 'testing', 'start_date' => '2023-11-15', 'end_date' => '2024-03-15', 'budget' => 40000, 'progress_percentage' => 90, 'priority' => 'medium', 'assigned_users' => 4, 'team_members' => [['user_id' => 2, 'role' => 'project_manager'], ['user_id' => 5, 'role' => 'lead_developer'], ['user_id' => 3, 'role' => 'designer'], ['user_id' => 4, 'role' => 'tester']]],
        ];

        // Search filter
        if ($request->has('search') && !empty($request->search)) {
            $search = strtolower($request->search);
            $projects = array_filter($projects, function($project) use ($search) {
                return str_contains(strtolower($project['name']), $search) ||
                       str_contains(strtolower($project['client_name']), $search) ||
                       str_contains(strtolower($project['project_type']), $search);
            });
            $projects = array_values($projects);
        }

        // Status filter
        if ($request->has('status') && !empty($request->status)) {
            $projects = array_filter($projects, function($project) use ($request) {
                return $project['status'] === $request->status;
            });
            $projects = array_values($projects);
        }

        // Client filter
        if ($request->has('client') && !empty($request->client)) {
            $projects = array_filter($projects, function($project) use ($request) {
                return $project['client_id'] == $request->client;
            });
            $projects = array_values($projects);
        }

        // Sort
        if ($request->has('sort') && !empty($request->sort)) {
            usort($projects, function($a, $b) use ($request) {
                switch ($request->sort) {
                    case 'name':
                        return strcmp($a['name'], $b['name']);
                    case 'client':
                        return strcmp($a['client_name'], $b['client_name']);
                    case 'budget':
                        return $b['budget'] - $a['budget']; // Descending
                    case 'progress':
                        return $b['progress_percentage'] - $a['progress_percentage']; // Descending
                    case 'date':
                        return strtotime($b['start_date']) - strtotime($a['start_date']); // Descending
                    default:
                        return 0;
                }
            });
        }

        // Paginate
        $perPage = 5;
        $page = $request->get('page', 1);
        $total = count($projects);
        $projects = array_slice($projects, ($page - 1) * $perPage, $perPage);

        // Create a paginator-like object
        $paginator = new \Illuminate\Pagination\LengthAwarePaginator(
            $projects,
            $total,
            $perPage,
            $page,
            ['path' => route('admin.projects.index', [], false)]
        );

        // Get clients for filter dropdown
        $clients = [
            ['id' => 1, 'name' => 'John Doe'],
            ['id' => 2, 'name' => 'Jane Smith'],
            ['id' => 3, 'name' => 'Bob Johnson'],
            ['id' => 4, 'name' => 'Alice Brown'],
            ['id' => 5, 'name' => 'Charlie Wilson'],
            ['id' => 6, 'name' => 'Diana Davis'],
            ['id' => 7, 'name' => 'Edward Miller'],
            ['id' => 8, 'name' => 'Fiona Garcia'],
        ];

        return view('projects.index', compact('paginator', 'projects', 'clients'));
    }

    /**
     * Show the form for creating a new project.
     */
    public function create()
    {
        // Get clients for dropdown
        $clients = [
            ['id' => 1, 'name' => 'John Doe'],
            ['id' => 2, 'name' => 'Jane Smith'],
            ['id' => 3, 'name' => 'Bob Johnson'],
            ['id' => 4, 'name' => 'Alice Brown'],
            ['id' => 5, 'name' => 'Charlie Wilson'],
            ['id' => 6, 'name' => 'Diana Davis'],
            ['id' => 7, 'name' => 'Edward Miller'],
            ['id' => 8, 'name' => 'Fiona Garcia'],
        ];

        return view('projects.create', compact('clients'));
    }

    /**
     * Store a newly created project.
     */
    public function store(Request $request)
    {
        // Validation
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'client_id' => 'required|integer|exists:clients,id',
            'description' => 'nullable|string|max:1000',
            'project_type' => 'required|array',
            'project_type.*' => 'in:web_application,mobile_application,desktop_application,api_integration,other',
            'start_date' => 'required|date',
            'end_date' => 'nullable|date|after:start_date',
            'budget' => 'nullable|numeric|min:0',
            'technologies' => 'nullable|array',
            'technologies.*' => 'string|max:50',
            'features' => 'nullable|array',
            'features.*' => 'string|max:100',
            'design_required' => 'boolean',
            'mobile_app_required' => 'boolean',
            'web_app_required' => 'boolean',
            'deployment_required' => 'boolean',
            'testing_required' => 'boolean',
            'maintenance_required' => 'boolean',
            'priority' => 'required|in:low,medium,high,critical',
            'assigned_users' => 'nullable|array',
            'assigned_users.*' => 'integer',
            'team_members' => 'nullable|array',
            'team_members.*.user_id' => 'required|integer',
            'team_members.*.role' => 'required|string|in:project_manager,lead_developer,developer,designer,tester,business_analyst,devops,qa_lead',
            'status' => 'required|in:planning,in_progress,on_hold,testing,completed,cancelled',
        ]);

        // TODO: Save to database
        // Project::create($validated);

        return redirect()->route('projects.index')->with('success', 'Project created successfully.');
    }

    /**
     * Show the form for editing a project.
     */
    public function edit($id)
    {
        // Sample data - replace with actual database query
        $project = [
            'id' => $id,
            'name' => 'E-Commerce Platform',
            'client_id' => 1,
            'description' => 'Complete e-commerce solution with payment integration',
            'project_type' => ['web_application'],
            'status' => 'in_progress',
            'start_date' => '2024-01-15',
            'end_date' => '2024-06-15',
            'budget' => 50000,
            'technologies' => ['PHP', 'Laravel', 'MySQL', 'Vue.js'],
            'features' => ['User Authentication', 'Product Catalog', 'Shopping Cart', 'Payment Gateway'],
            'design_required' => true,
            'mobile_app_required' => false,
            'web_app_required' => true,
            'deployment_required' => true,
            'testing_required' => true,
            'maintenance_required' => true,
            'priority' => 'high',
            'assigned_users' => [1, 2, 3, 4],
            'team_members' => [
                ['user_id' => 1, 'role' => 'project_manager'],
                ['user_id' => 2, 'role' => 'lead_developer'],
                ['user_id' => 3, 'role' => 'designer'],
                ['user_id' => 4, 'role' => 'developer']
            ],
            'progress_percentage' => 65,
        ];

        // Get clients for dropdown
        $clients = [
            ['id' => 1, 'name' => 'John Doe'],
            ['id' => 2, 'name' => 'Jane Smith'],
            ['id' => 3, 'name' => 'Bob Johnson'],
            ['id' => 4, 'name' => 'Alice Brown'],
            ['id' => 5, 'name' => 'Charlie Wilson'],
            ['id' => 6, 'name' => 'Diana Davis'],
            ['id' => 7, 'name' => 'Edward Miller'],
            ['id' => 8, 'name' => 'Fiona Garcia'],
        ];

        return view('projects.edit', compact('project', 'clients', 'id'));
    }

    /**
     * Update the specified project.
     */
    public function update(Request $request, $id)
    {
        // Validation
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'client_id' => 'required|integer|exists:clients,id',
            'description' => 'nullable|string|max:1000',
            'project_type' => 'required|in:web_application,mobile_application,desktop_application,api_integration,other',
            'start_date' => 'required|date',
            'end_date' => 'nullable|date|after:start_date',
            'budget' => 'nullable|numeric|min:0',
            'technologies' => 'nullable|array',
            'technologies.*' => 'string|max:50',
            'features' => 'nullable|array',
            'features.*' => 'string|max:100',
            'design_required' => 'boolean',
            'mobile_app_required' => 'boolean',
            'web_app_required' => 'boolean',
            'deployment_required' => 'boolean',
            'testing_required' => 'boolean',
            'maintenance_required' => 'boolean',
            'priority' => 'required|in:low,medium,high,critical',
            'assigned_users' => 'nullable|array',
            'assigned_users.*' => 'integer',
            'status' => 'required|in:planning,in_progress,on_hold,testing,completed,cancelled',
        ]);

        // TODO: Update in database
        // Project::where('id', $id)->update($validated);

        return redirect()->route('projects.index')->with('success', 'Project updated successfully.');
    }

    /**
     * Show the form for changing project status.
     */
    public function showStatus($id)
    {
        return view('projects.status', ['id' => $id]);
    }

    /**
     * Update the status of the specified project.
     */
    public function updateStatus(Request $request, $id)
    {
        $validated = $request->validate([
            'status' => 'required|in:planning,in_progress,on_hold,testing,completed,cancelled',
            'reason' => 'nullable|string|max:500',
        ]);

        // TODO: Update status in database
        // Project::where('id', $id)->update(['status' => $validated['status']]);

        $statusMessage = match($validated['status']) {
            'planning' => 'moved to planning',
            'in_progress' => 'started',
            'on_hold' => 'put on hold',
            'testing' => 'moved to testing',
            'completed' => 'completed',
            'cancelled' => 'cancelled',
            default => 'updated'
        };

        return redirect()->route('projects.index')->with('success', "Project {$statusMessage} successfully.");
    }

    /**
     * Remove the specified project.
     */
    public function destroy($id)
    {
        // TODO: Delete from database
        // Project::where('id', $id)->delete();

        return redirect()->route('projects.index')->with('success', 'Project deleted successfully.');
    }
}