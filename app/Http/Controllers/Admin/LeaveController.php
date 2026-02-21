<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Leave;
use Illuminate\Support\Facades\Crypt;

class LeaveController extends Controller
{
    /**
     * Get a leave by its encrypted ID.
     */
    private function getLeave($encryptedId)
    {
        $id = Crypt::decrypt($encryptedId);
        return Leave::findOrFail($id);
    }

    /**
     * Display the leaves list page.
     */
    public function index(Request $request)
    {
        $leaves = Leave::orderBy('year', 'desc')->get()->map(function ($leave) {
            return [
                'id' => $leave->id,
                'year' => $leave->year,
                'sick_leaves' => $leave->sick_leave,
                'casual_leaves' => $leave->casual_leave,
                'earned_leaves' => $leave->earned_leaves,
                'status' => $leave->status,
                'created_at' => $leave->created_at,
            ];
        });
        return view('leaves.index', compact('leaves'));
    }

    /**
     * Show the form for creating a new leave configuration.
     */
    public function create()
    {
        return view('leaves.create');
    }

    /**
     * Store a newly created leave configuration.
     */
    public function store(Request $request)
    {
        $request->validate([
            'year' => 'required|integer|min:2025|unique:leaves,year',
            'sick_leaves' => 'required|numeric|min:0|max:99',
            'casual_leaves' => 'required|numeric|min:0|max:99',
            'earned_leaves' => 'required|numeric|min:0|max:99',
        ]);

        Leave::create([
            'year' => $request->year,
            'sick_leave' => $request->sick_leaves,
            'casual_leave' => $request->casual_leaves,
            'earned_leaves' => $request->earned_leaves,
            'status' => 'active',
        ]);

        return redirect()->route('admin.leaves.index')->with('success', 'Leave configuration created successfully.');
    }

    /**
     * Show the form for editing the specified leave configuration.
     */
    public function edit($encryptedId)
    {
        $leave = $this->getLeave($encryptedId);
        $leaveData = [
            'id' => $leave->id,
            'year' => $leave->year,
            'sick_leaves' => $leave->sick_leave,
            'casual_leaves' => $leave->casual_leave,
            'earned_leaves' => $leave->earned_leaves,
            'status' => $leave->status,
            'created_at' => $leave->created_at,
        ];
        return view('leaves.edit', compact('leaveData'));
    }

    /**
     * Update the specified leave configuration.
     */
    public function update(Request $request, $encryptedId)
    {
        $leave = $this->getLeave($encryptedId);

        $request->validate([
            'sick_leaves' => 'required|numeric|min:0|max:99',
            'casual_leaves' => 'required|numeric|min:0|max:99',
            'earned_leaves' => 'required|numeric|min:0|max:99',
        ]);

        $leave->update([
            'sick_leave' => $request->sick_leaves,
            'casual_leave' => $request->casual_leaves,
            'earned_leaves' => $request->earned_leaves,
        ]);

        return redirect()->route('admin.leaves.index')->with('success', 'Leave configuration updated successfully.');
    }

    /**
     * Remove the specified leave configuration.
     */
    public function destroy($encryptedId)
    {
        $leave = $this->getLeave($encryptedId);
        $leave->delete();

        return redirect()->route('admin.leaves.index')->with('success', 'Leave configuration deleted successfully.');
    }

    /**
     * Update status of the specified leave configuration.
     */
    public function updateStatus(Request $request, $encryptedId)
    {
        $leave = $this->getLeave($encryptedId);

        $request->validate([
            'status' => 'required|in:active,inactive',
        ]);

        $leave->update([
            'status' => $request->status,
        ]);

        $statusMessage = $request->status === 'active' ? 'activated' : 'deactivated';

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => "Leave configuration {$statusMessage} successfully.",
                'status' => $request->status
            ]);
        }

        return redirect()->route('admin.leaves.index')->with('success', "Leave configuration {$statusMessage} successfully.");
    }
}
