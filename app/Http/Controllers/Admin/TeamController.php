<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class TeamController extends Controller
{
    /**
     * Display the team tree view.
     * Shows all users in a hierarchical tree structure based on reporting managers.
     */
    public function index()
    {
        // Get all users with their relationships
        $users = User::with(['reportingManager', 'designation', 'department'])
            ->orderBy('name')
            ->get();

        // Create a map of user IDs for quick lookup
        $userMap = $users->keyBy('id');

        // Build the tree structure - users without a reporting manager are top-level
        $topLevelUsers = $users->whereNull('reporting_manager_id')->values();

        // If no users with null reporting_manager_id, check if any users exist
        // This handles the case where all users have managers assigned
        if ($topLevelUsers->isEmpty() && $users->isNotEmpty()) {
            // Get users who are managers (others report to them)
            $managerIds = $users->pluck('reporting_manager_id')->filter()->unique();
            $topLevelUsers = $users->whereIn('id', $managerIds)->values();
        }

        // Track visited IDs to prevent circular references
        $visitedIds = [];

        // Build hierarchical tree for each top-level user
        $teamTree = $this->buildTeamTree($topLevelUsers, $users, $visitedIds);

        return view('Admin.team.index', compact('teamTree', 'users'));
    }

    /**
     * Recursively build the team tree structure.
     */
    private function buildTeamTree($managers, $allUsers, &$visitedIds, $depth = 0)
    {
        // Prevent too deep recursion
        if ($depth > 50) {
            return [];
        }

        $tree = [];

        foreach ($managers as $manager) {
            // Skip if already visited (prevents circular reference)
            if (isset($visitedIds[$manager->id])) {
                continue;
            }
            $visitedIds[$manager->id] = true;

            // Get direct subordinates (exclude self-reference)
            $subordinates = $allUsers->where('reporting_manager_id', $manager->id)
                ->where('id', '!=', $manager->id)
                ->values();

            // Recursively get subordinates for each subordinate
            $children = $this->buildTeamTree($subordinates, $allUsers, $visitedIds, $depth + 1);

            $tree[] = [
                'user' => $manager,
                'subordinates' => $subordinates,
                'children' => $children,
                'hasChildren' => $subordinates->isNotEmpty()
            ];
        }

        return $tree;
    }
}
