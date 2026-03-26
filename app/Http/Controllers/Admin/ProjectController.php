<?php

namespace App\Http\Controllers\Admin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Models\Client;
use App\Models\User;
use App\Models\Department;
use App\Models\ProjectDepartment;
use App\Models\ProjectTechnology;
use App\Models\ProjectFeature;
use App\Models\ProjectTask;
use App\Models\ProjectTeamMember;
use App\Models\ProjectType;

class ProjectController extends Controller
{
    /**
     * Display a listing of projects with search, filter, and pagination.
     */
    public function index(Request $request)
    {
        // Query projects from database
        $query = Project::with('client')->orderBy('created_at', 'desc');

        // Search filter
        if ($request->has('search') && !empty($request->search)) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhereHas('client', function($q) use ($search) {
                      $q->where('name', 'like', "%{$search}%");
                  });
            });
        }

        // Status filter
        if ($request->has('status') && !empty($request->status)) {
            $query->where('status', $request->status);
        }

        // Client filter
        if ($request->has('client') && !empty($request->client)) {
            $query->where('client_id', $request->client);
        }

        // Sort
        if ($request->has('sort') && !empty($request->sort)) {
            switch ($request->sort) {
                case 'name':
                    $query->orderBy('name', 'asc');
                    break;
                case 'client':
                    $query->join('clients', 'projects.client_id', '=', 'clients.id')
                          ->orderBy('clients.name', 'asc')
                          ->select('projects.*');
                    break;
                case 'budget':
                    $query->orderBy('budget', 'desc');
                    break;
                case 'progress':
                    $query->orderBy('progress_percentage', 'desc');
                    break;
                case 'date':
                    $query->orderBy('start_date', 'desc');
                    break;
            }
        }

        // Paginate
        $perPage = $request->get('per_page', 10);
        $projects = $query->paginate($perPage);

        // Get clients for filter dropdown
        $clients = Client::orderBy('name')->get();

        return view('Admin.projects.index', compact('projects', 'clients'));
    }

    /**
     * Show the form for creating a new project.
     */
    public function create()
    {
        // Get clients from database
        $clients = Client::where('status', 'active')->orderBy('name')->get();
        $projectDepartments = ProjectDepartment::where('status', 'active')->orderBy('name')->get();
        $users = User::where('is_active', true)->orderBy('name')->get();

        return view('Admin.projects.create', compact('clients', 'projectDepartments', 'users'));
    }

    /**
     * Store a newly created project.
     */
    public function store(Request $request)
    {
        // Validation
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'client_id' => 'nullable|integer|exists:clients,id',
            'project_department_id' => 'required|exists:project_departments,id',
            'description' => 'nullable|string|max:1000',
            'project_type' => 'nullable|array',
            'project_type.*' => 'in:web_application,mobile_application,desktop_application,api_integration,other',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after:start_date',
            'budget' => 'nullable|numeric|min:0',
            'technologies' => 'nullable|array',
            'technologies.*' => 'string|max:50',
            'features' => 'nullable|array',
            'features.*' => 'string|max:100',
            'tasks' => 'nullable|array',
            'tasks.*' => 'string|max:255',
            'design_required' => 'boolean',
            'mobile_app_required' => 'boolean',
            'web_app_required' => 'boolean',
            'deployment_required' => 'boolean',
            'testing_required' => 'boolean',
            'maintenance_required' => 'boolean',
            'priority' => 'nullable|in:low,medium,high,critical',
            'team_members' => 'nullable|array',
            'team_members.*.user_id' => 'integer',
            'team_members.*.role' => 'string|in:project_manager,lead_developer,developer,designer,tester,business_analyst,devops,qa_lead',
            'status' => 'nullable|in:planning,in_progress,on_hold,testing,completed,cancelled',
        ]);

        // Save project data without JSON fields
        $projectData = $validated;
        unset($projectData['technologies'], $projectData['features'], $projectData['tasks'], $projectData['team_members'], $projectData['project_type']);
        
        $project = Project::create($projectData);

        // Save technologies
        if (!empty($validated['technologies'])) {
            foreach ($validated['technologies'] as $technology) {
                ProjectTechnology::create([
                    'project_id' => $project->id,
                    'name' => $technology,
                ]);
            }
        }

        // Save features
        if (!empty($validated['features'])) {
            foreach ($validated['features'] as $feature) {
                ProjectFeature::create([
                    'project_id' => $project->id,
                    'name' => $feature,
                ]);
            }
        }

        // Save tasks
        if (!empty($validated['tasks'])) {
            foreach ($validated['tasks'] as $task) {
                ProjectTask::create([
                    'project_id' => $project->id,
                    'name' => $task,
                ]);
            }
        }

        // Save team members
        if (!empty($validated['team_members'])) {
            foreach ($validated['team_members'] as $member) {
                ProjectTeamMember::create([
                    'project_id' => $project->id,
                    'user_id' => $member['user_id'],
                    'role' => $member['role'] ?? null,
                ]);
            }
        }

        // Save project types
        if (!empty($validated['project_type'])) {
            foreach ($validated['project_type'] as $type) {
                ProjectType::create([
                    'project_id' => $project->id,
                    'type' => $type,
                ]);
            }
        }

        return redirect()->route('admin.projects.index')->with('success', 'Project created successfully.');
    }

    /**
     * Show the form for editing a project.
     */
    public function edit($id)
    {
        // Get project from database
        $project = Project::with(['technologies', 'features', 'tasks', 'teamMembers.user', 'types'])->findOrFail($id);

        // Get clients for dropdown
        $clients = Client::where('status', 'active')->orderBy('name')->get();
        $projectDepartments = ProjectDepartment::where('status', 'active')->orderBy('name')->get();
        $users = User::with('designation')->where('is_active', true)->orderBy('name')->get();

        return view('Admin.projects.edit', compact('project', 'clients', 'projectDepartments', 'users'));
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
            'project_department_id' => 'required|exists:project_departments,id',
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
            'tasks' => 'nullable|array',
            'tasks.*' => 'string|max:255',
            'design_required' => 'boolean',
            'mobile_app_required' => 'boolean',
            'web_app_required' => 'boolean',
            'deployment_required' => 'boolean',
            'testing_required' => 'boolean',
            'maintenance_required' => 'boolean',
            'team_members' => 'nullable|array',
            'team_members.*.user_id' => 'integer|exists:users,id',
            'team_members.*.role' => 'string|in:project_manager,lead_developer,developer,designer,tester,business_analyst,devops,qa_lead',
            'priority' => 'required|in:low,medium,high,critical',
            'status' => 'required|in:planning,in_progress,on_hold,testing,completed,cancelled',
        ]);

        // Get project
        $project = Project::findOrFail($id);

        // Update project data without JSON fields
        $projectData = $validated;
        unset($projectData['technologies'], $projectData['features'], $projectData['tasks'], $projectData['team_members'], $projectData['project_type']);
        
        $project->update($projectData);

        // Update technologies - delete old and create new
        ProjectTechnology::where('project_id', $project->id)->delete();
        if (!empty($validated['technologies'])) {
            foreach ($validated['technologies'] as $technology) {
                ProjectTechnology::create([
                    'project_id' => $project->id,
                    'name' => $technology,
                ]);
            }
        }

        // Update features - delete old and create new
        ProjectFeature::where('project_id', $project->id)->delete();
        if (!empty($validated['features'])) {
            foreach ($validated['features'] as $feature) {
                ProjectFeature::create([
                    'project_id' => $project->id,
                    'name' => $feature,
                ]);
            }
        }

        // Update tasks - delete old and create new
        ProjectTask::where('project_id', $project->id)->delete();
        if (!empty($validated['tasks'])) {
            foreach ($validated['tasks'] as $task) {
                ProjectTask::create([
                    'project_id' => $project->id,
                    'name' => $task,
                ]);
            }
        }

        // Update team members - delete old and create new
        ProjectTeamMember::where('project_id', $project->id)->delete();
        if (!empty($validated['team_members'])) {
            foreach ($validated['team_members'] as $member) {
                ProjectTeamMember::create([
                    'project_id' => $project->id,
                    'user_id' => $member['user_id'],
                    'role' => $member['role'] ?? null,
                ]);
            }
        }

        // Update project types - delete old and create new
        ProjectType::where('project_id', $project->id)->delete();
        if (!empty($validated['project_type'])) {
            foreach ($validated['project_type'] as $type) {
                ProjectType::create([
                    'project_id' => $project->id,
                    'type' => $type,
                ]);
            }
        }

        return redirect()->route('admin.projects.index')->with('success', 'Project updated successfully.');
    }

    /**
     * Show the form for changing project status.
     */
    public function showStatus($id)
    {
        return view('Admin.projects.status', ['id' => $id]);
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

        // Update status in database
        Project::where('id', $id)->update(['status' => $validated['status']]);

        $statusMessage = match($validated['status']) {
            'planning' => 'moved to planning',
            'in_progress' => 'started',
            'on_hold' => 'put on hold',
            'testing' => 'moved to testing',
            'completed' => 'completed',
            'cancelled' => 'cancelled',
            default => 'updated'
        };

        return redirect()->route('admin.projects.index')->with('success', "Project {$statusMessage} successfully.");
    }

    /**
     * Remove the specified project.
     */
    public function destroy($id)
    {
        // Delete related records first
        ProjectTechnology::where('project_id', $id)->delete();
        ProjectFeature::where('project_id', $id)->delete();
        ProjectTask::where('project_id', $id)->delete();
        ProjectTeamMember::where('project_id', $id)->delete();
        ProjectType::where('project_id', $id)->delete();
        
        // Delete from database
        Project::where('id', $id)->delete();

        return redirect()->route('admin.projects.index')->with('success', 'Project deleted successfully.');
    }
}