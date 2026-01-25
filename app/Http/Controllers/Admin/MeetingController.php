<?php

namespace App\Http\Controllers\Admin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;

class MeetingController extends Controller
{
    /**
     * Display a listing of meetings for a specific project.
     */
    public function index($projectId)
    {
        // Sample data - replace with actual database query
        $meetings = [
            [
                'id' => 1,
                'project_id' => $projectId,
                'title' => 'Project Kickoff Meeting',
                'meeting_date' => '2024-01-15',
                'meeting_time' => '2024-01-15 10:00:00',
                'duration' => 120,
                'location' => 'Conference Room A',
                'meeting_type' => 'kickoff',
                'attendees' => ['John Doe', 'Jane Smith', 'Bob Johnson'],
                'agenda' => ['Project Overview', 'Timeline Discussion', 'Resource Allocation'],
                'status' => 'completed',
                'created_by' => 'John Doe'
            ],
            [
                'id' => 2,
                'project_id' => $projectId,
                'title' => 'Design Review Meeting',
                'meeting_date' => '2024-01-22',
                'meeting_time' => '2024-01-22 14:00:00',
                'duration' => 90,
                'location' => 'Virtual Meeting',
                'meeting_type' => 'review',
                'attendees' => ['John Doe', 'Alice Brown', 'Charlie Wilson'],
                'agenda' => ['UI Design Review', 'User Experience Feedback', 'Design Approval'],
                'status' => 'completed',
                'created_by' => 'Jane Smith'
            ],
            [
                'id' => 3,
                'project_id' => $projectId,
                'title' => 'Weekly Status Update',
                'meeting_date' => '2024-01-29',
                'meeting_time' => '2024-01-29 11:00:00',
                'duration' => 60,
                'location' => 'Conference Room B',
                'meeting_type' => 'status',
                'attendees' => ['John Doe', 'Jane Smith', 'Bob Johnson', 'Alice Brown'],
                'agenda' => ['Progress Update', 'Blockers Discussion', 'Next Week Planning'],
                'status' => 'scheduled',
                'created_by' => 'John Doe'
            ],
        ];

        // Filter meetings by project
        $meetings = array_filter($meetings, function($meeting) use ($projectId) {
            return $meeting['project_id'] == $projectId;
        });
        $meetings = array_values($meetings);

        // Get project info
        $project = [
            'id' => $projectId,
            'name' => 'E-Commerce Platform',
            'client_name' => 'John Doe'
        ];

        return view('meetings.index', compact('meetings', 'project'));
    }

    /**
     * Show the form for creating a new meeting.
     */
    public function create($projectId)
    {
        $project = [
            'id' => $projectId,
            'name' => 'E-Commerce Platform',
            'client_name' => 'John Doe'
        ];

        // Sample team members for attendees
        $teamMembers = [
            ['id' => 1, 'name' => 'John Doe'],
            ['id' => 2, 'name' => 'Jane Smith'],
            ['id' => 3, 'name' => 'Bob Johnson'],
            ['id' => 4, 'name' => 'Alice Brown'],
            ['id' => 5, 'name' => 'Charlie Wilson'],
        ];

        return view('meetings.create', compact('project', 'teamMembers'));
    }

    /**
     * Store a newly created meeting.
     */
    public function store(Request $request, $projectId)
    {
        // Validation
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'meeting_date' => 'required|date',
            'meeting_time' => 'required|date_format:H:i',
            'duration' => 'required|integer|min:15|max:480',
            'location' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'meeting_type' => 'required|in:kickoff,review,status,planning,retrospective,other',
            'attendees' => 'required|array|min:1',
            'attendees.*' => 'string|max:255',
            'agenda' => 'nullable|array',
            'agenda.*' => 'string|max:500',
            'discussion_points' => 'nullable|array',
            'discussion_points.*' => 'string|max:1000',
            'decisions' => 'nullable|array',
            'decisions.*' => 'string|max:1000',
            'action_items' => 'nullable|array',
            'action_items.*' => 'string|max:1000',
            'next_meeting_date' => 'nullable|date|after:meeting_date',
            'status' => 'required|in:scheduled,completed,cancelled',
        ]);

        $validated['project_id'] = $projectId;
        $validated['created_by'] = auth()->user()->name ?? 'System';

        // TODO: Save to database
        // Meeting::create($validated);

        return redirect()->route('admin.projects.meetings.index', $projectId)->with('success', 'Meeting created successfully.');
    }

    /**
     * Show the specified meeting.
     */
    public function show($projectId, $meetingId)
    {
        // Sample meeting data
        $meeting = [
            'id' => $meetingId,
            'project_id' => $projectId,
            'title' => 'Project Kickoff Meeting',
            'meeting_date' => '2024-01-15',
            'meeting_time' => '2024-01-15 10:00:00',
            'duration' => 120,
            'location' => 'Conference Room A',
            'description' => 'Initial kickoff meeting to discuss project scope, timeline, and resource allocation for the E-Commerce Platform development.',
            'meeting_type' => 'kickoff',
            'attendees' => ['John Doe', 'Jane Smith', 'Bob Johnson'],
            'agenda' => ['Project Overview', 'Timeline Discussion', 'Resource Allocation'],
            'discussion_points' => ['Discussed project scope and objectives', 'Reviewed timeline constraints', 'Allocated team resources'],
            'decisions' => ['Approved project timeline', 'Assigned team leads', 'Set up weekly status meetings'],
            'action_items' => ['John to prepare project documentation', 'Jane to set up development environment', 'Bob to create design mockups'],
            'next_meeting_date' => '2024-01-22',
            'status' => 'completed',
            'created_by' => 'John Doe'
        ];

        $project = [
            'id' => $projectId,
            'name' => 'E-Commerce Platform',
            'client_name' => 'John Doe'
        ];

        return view('meetings.show', compact('meeting', 'project'));
    }

    /**
     * Show the form for editing a meeting.
     */
    public function edit($projectId, $meetingId)
    {
        // Sample meeting data
        $meeting = [
            'id' => $meetingId,
            'project_id' => $projectId,
            'title' => 'Project Kickoff Meeting',
            'meeting_date' => '2024-01-15',
            'meeting_time' => '10:00',
            'duration' => 120,
            'location' => 'Conference Room A',
            'description' => 'Initial kickoff meeting to discuss project scope, timeline, and resource allocation for the E-Commerce Platform development.',
            'meeting_type' => 'kickoff',
            'attendees' => ['John Doe', 'Jane Smith', 'Bob Johnson'],
            'agenda' => ['Project Overview', 'Timeline Discussion', 'Resource Allocation'],
            'discussion_points' => ['Discussed project scope and objectives', 'Reviewed timeline constraints', 'Allocated team resources'],
            'decisions' => ['Approved project timeline', 'Assigned team leads', 'Set up weekly status meetings'],
            'action_items' => ['John to prepare project documentation', 'Jane to set up development environment', 'Bob to create design mockups'],
            'next_meeting_date' => '2024-01-22',
            'status' => 'completed',
            'created_by' => 'John Doe'
        ];

        $project = [
            'id' => $projectId,
            'name' => 'E-Commerce Platform',
            'client_name' => 'John Doe'
        ];

        // Sample team members for attendees
        $teamMembers = [
            ['id' => 1, 'name' => 'John Doe'],
            ['id' => 2, 'name' => 'Jane Smith'],
            ['id' => 3, 'name' => 'Bob Johnson'],
            ['id' => 4, 'name' => 'Alice Brown'],
            ['id' => 5, 'name' => 'Charlie Wilson'],
        ];

        return view('meetings.edit', compact('meeting', 'project', 'teamMembers'));
    }

    /**
     * Update the specified meeting.
     */
    public function update(Request $request, $projectId, $meetingId)
    {
        // Validation
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'meeting_date' => 'required|date',
            'meeting_time' => 'required|date_format:H:i',
            'duration' => 'required|integer|min:15|max:480',
            'location' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'meeting_type' => 'required|in:kickoff,review,status,planning,retrospective,other',
            'attendees' => 'required|array|min:1',
            'attendees.*' => 'string|max:255',
            'agenda' => 'nullable|array',
            'agenda.*' => 'string|max:500',
            'discussion_points' => 'nullable|array',
            'discussion_points.*' => 'string|max:1000',
            'decisions' => 'nullable|array',
            'decisions.*' => 'string|max:1000',
            'action_items' => 'nullable|array',
            'action_items.*' => 'string|max:1000',
            'next_meeting_date' => 'nullable|date|after:meeting_date',
            'status' => 'required|in:scheduled,completed,cancelled',
        ]);

        // TODO: Update in database
        // Meeting::where('id', $meetingId)->update($validated);

        return redirect()->route('admin.projects.meetings.index', $projectId)->with('success', 'Meeting updated successfully.');
    }

    /**
     * Remove the specified meeting.
     */
    public function destroy($projectId, $meetingId)
    {
        // TODO: Delete from database
        // Meeting::where('id', $meetingId)->delete();

        return redirect()->route('admin.projects.meetings.index', $projectId)->with('success', 'Meeting deleted successfully.');
    }

    /**
     * Download the meeting MOM as PDF.
     */
    public function download($projectId, $meetingId)
    {
        // Sample meeting data
        $meeting = [
            'id' => $meetingId,
            'project_id' => $projectId,
            'title' => 'Project Kickoff Meeting',
            'meeting_date' => '2024-01-15',
            'meeting_time' => '2024-01-15 10:00:00',
            'duration' => 120,
            'location' => 'Conference Room A',
            'description' => 'Initial kickoff meeting to discuss project scope, timeline, and resource allocation for the E-Commerce Platform development.',
            'meeting_type' => 'kickoff',
            'attendees' => ['John Doe', 'Jane Smith', 'Bob Johnson'],
            'agenda' => ['Project Overview', 'Timeline Discussion', 'Resource Allocation'],
            'discussion_points' => ['Discussed project scope and objectives', 'Reviewed timeline constraints', 'Allocated team resources'],
            'decisions' => ['Approved project timeline', 'Assigned team leads', 'Set up weekly status meetings'],
            'action_items' => ['John to prepare project documentation', 'Jane to set up development environment', 'Bob to create design mockups'],
            'next_meeting_date' => '2024-01-22',
            'status' => 'completed',
            'created_by' => 'John Doe'
        ];

        $project = [
            'id' => $projectId,
            'name' => 'E-Commerce Platform',
            'client_name' => 'John Doe'
        ];

        // Generate PDF using a simple HTML template
        $html = $this->generateMOMPDF($meeting, $project);

        // For demo purposes, return HTML instead of PDF
        // In real implementation, you would use: return PDF::loadHTML($html)->download('MOM-' . $meeting['title'] . '.pdf');
        return response($html, 200, [
            'Content-Type' => 'text/html',
            'Content-Disposition' => 'attachment; filename="MOM-' . str_replace(' ', '_', $meeting['title']) . '.html"'
        ]);
    }

    /**
     * Send the meeting MOM as PDF to attendees.
     */
    public function send(Request $request, $projectId, $meetingId)
    {
        // Validate the request
        $validated = $request->validate([
            'subject' => 'required|string|max:255',
            'recipients' => 'required|array|min:1',
            'recipients.*' => 'email',
        ]);

        // Sample meeting data
        $meeting = [
            'id' => $meetingId,
            'project_id' => $projectId,
            'title' => 'Project Kickoff Meeting',
            'meeting_date' => '2024-01-15',
            'meeting_time' => '2024-01-15 10:00:00',
            'duration' => 120,
            'location' => 'Conference Room A',
            'description' => 'Initial kickoff meeting to discuss project scope, timeline, and resource allocation for the E-Commerce Platform development.',
            'meeting_type' => 'kickoff',
            'attendees' => ['John Doe', 'Jane Smith', 'Bob Johnson'],
            'agenda' => ['Project Overview', 'Timeline Discussion', 'Resource Allocation'],
            'discussion_points' => ['Discussed project scope and objectives', 'Reviewed timeline constraints', 'Allocated team resources'],
            'decisions' => ['Approved project timeline', 'Assigned team leads', 'Set up weekly status meetings'],
            'action_items' => ['John to prepare project documentation', 'Jane to set up development environment', 'Bob to create design mockups'],
            'next_meeting_date' => '2024-01-22',
            'status' => 'completed',
            'created_by' => 'John Doe'
        ];

        $project = [
            'id' => $projectId,
            'name' => 'E-Commerce Platform',
            'client_name' => 'John Doe'
        ];

        // In real implementation, you would:
        // 1. Generate PDF using dompdf
        // 2. Send email to recipients with PDF attachment
        // 3. Use the custom subject from the form
        // 4. Log the sending activity

        $subject = $validated['subject'];
        $recipients = $validated['recipients'];

        // For demo, just return success message with details
        $recipientCount = count($recipients);
        return redirect()->route('admin.projects.meetings.index', $projectId)
                        ->with('success', "MOM has been sent to {$recipientCount} recipient(s) successfully with subject: '{$subject}'");
    }

    /**
     * Generate HTML template for MOM PDF.
     */
    private function generateMOMPDF($meeting, $project)
    {
        $typeLabels = [
            'kickoff' => 'Kickoff Meeting',
            'review' => 'Review Meeting',
            'status' => 'Status Update',
            'planning' => 'Planning Meeting',
            'retrospective' => 'Retrospective',
            'other' => 'Other',
        ];

        $html = '
        <!DOCTYPE html>
        <html>
        <head>
            <meta charset="utf-8">
            <title>Minutes of Meeting - ' . $meeting['title'] . '</title>
            <style>
                body { font-family: Arial, sans-serif; margin: 40px; line-height: 1.6; }
                .header { text-align: center; border-bottom: 2px solid #333; padding-bottom: 20px; margin-bottom: 30px; }
                .meeting-info { margin-bottom: 30px; }
                .section { margin-bottom: 25px; }
                .section h3 { color: #333; border-bottom: 1px solid #ddd; padding-bottom: 5px; }
                .attendees { display: flex; flex-wrap: wrap; gap: 10px; margin-bottom: 15px; }
                .attendee { background: #f0f0f0; padding: 5px 10px; border-radius: 15px; font-size: 12px; }
                .list-item { margin-bottom: 8px; }
                .footer { margin-top: 50px; text-align: center; font-size: 12px; color: #666; }
            </style>
        </head>
        <body>
            <div class="header">
                <h1>Minutes of Meeting</h1>
                <h2>' . $meeting['title'] . '</h2>
                <p><strong>Project:</strong> ' . $project['name'] . '</p>
                <p><strong>Client:</strong> ' . $project['client_name'] . '</p>
            </div>

            <div class="meeting-info">
                <p><strong>Date:</strong> ' . date('F j, Y', strtotime($meeting['meeting_date'])) . '</p>
                <p><strong>Time:</strong> ' . date('g:i A', strtotime($meeting['meeting_time'])) . '</p>
                <p><strong>Duration:</strong> ' . $meeting['duration'] . ' minutes</p>
                <p><strong>Location:</strong> ' . $meeting['location'] . '</p>
                <p><strong>Meeting Type:</strong> ' . ($typeLabels[$meeting['meeting_type']] ?? ucwords(str_replace('_', ' ', $meeting['meeting_type']))) . '</p>
                <p><strong>Status:</strong> ' . ucwords(str_replace('_', ' ', $meeting['status'])) . '</p>';
       if (!empty($meeting['description'])) {
           $html .= '
                <p><strong>Description:</strong> ' . $meeting['description'] . '</p>';
       }
       $html .= '
            </div>

            <div class="section">
                <h3>Attendees</h3>
                <div class="attendees">';

        foreach ($meeting['attendees'] as $attendee) {
            $html .= '<span class="attendee">' . $attendee . '</span>';
        }

        $html .= '
                </div>
            </div>';

        if (!empty($meeting['agenda'])) {
            $html .= '
            <div class="section">
                <h3>Agenda</h3>
                <ol>';
            foreach ($meeting['agenda'] as $item) {
                $html .= '<li class="list-item">' . $item . '</li>';
            }
            $html .= '</ol>
            </div>';
        }

        if ($meeting['status'] === 'completed') {
            if (!empty($meeting['discussion_points'])) {
                $html .= '
                <div class="section">
                    <h3>Discussion Points</h3>
                    <ul>';
                foreach ($meeting['discussion_points'] as $point) {
                    $html .= '<li class="list-item">' . $point . '</li>';
                }
                $html .= '</ul>
                </div>';
            }

            if (!empty($meeting['decisions'])) {
                $html .= '
                <div class="section">
                    <h3>Decisions Made</h3>
                    <ul>';
                foreach ($meeting['decisions'] as $decision) {
                    $html .= '<li class="list-item">' . $decision . '</li>';
                }
                $html .= '</ul>
                </div>';
            }

            if (!empty($meeting['action_items'])) {
                $html .= '
                <div class="section">
                    <h3>Action Items</h3>
                    <ul>';
                foreach ($meeting['action_items'] as $item) {
                    $html .= '<li class="list-item">' . $item . '</li>';
                }
                $html .= '</ul>
                </div>';
            }

            if (!empty($meeting['next_meeting_date'])) {
                $html .= '
                <div class="section">
                    <h3>Next Meeting</h3>
                    <p>Scheduled for ' . date('F j, Y', strtotime($meeting['next_meeting_date'])) . '</p>
                </div>';
            }
        }

        $html .= '
            <div class="footer">
                <p>Generated on ' . date('F j, Y \a\t g:i A') . '</p>
                <p>Meeting documented by ' . $meeting['created_by'] . '</p>
            </div>
        </body>
        </html>';

        return $html;
    }
}