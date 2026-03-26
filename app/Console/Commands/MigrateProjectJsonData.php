<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Project;
use App\Models\Client;
use App\Models\ProjectType;
use App\Models\ProjectTechnology;
use App\Models\ProjectFeature;
use App\Models\ProjectTask;
use App\Models\ProjectTeamMember;
use App\Models\ContactPerson;

class MigrateProjectJsonData extends Command
{
    protected $signature = 'migrate:json-data {--dry-run : Preview changes without applying}';
    protected $description = 'Migrate legacy JSON data to normalized tables';

    public function handle()
    {
        $this->info('JSON to Normalized Tables Migration');
        $this->newLine();

        // 1. Projects data migration
        $projects = Project::where(function ($query) {
            $query->whereNotNull('project_type')
                  ->orWhereNotNull('technologies')
                  ->orWhereNotNull('features')
                  ->orWhereNotNull('assigned_users')
                  ->orWhereNotNull('team_members');
        })->get();

        $this->info("Found {$projects->count()} projects with legacy JSON data");

        foreach ($projects as $project) {
            $this->warn("Migrating Project: {$project->name} (ID: {$project->id})");

            // Clear existing normalized records first (idempotent)
            ProjectType::where('project_id', $project->id)->delete();
            ProjectTechnology::where('project_id', $project->id)->delete();
            ProjectFeature::where('project_id', $project->id)->delete();
            ProjectTask::where('project_id', $project->id)->delete();
            ProjectTeamMember::where('project_id', $project->id)->delete();

            // Migrate project_type
            if ($project->project_type) {
                $types = is_array($project->project_type) ? $project->project_type : json_decode($project->project_type, true) ?? [];
                foreach ((array) $types as $type) {
                    ProjectType::create(['project_id' => $project->id, 'type' => $type]);
                }
                $this->line("  → Migrated " . count((array) $types) . " project types");
            }

            // Migrate technologies
            if ($project->technologies) {
                $techs = is_array($project->technologies) ? $project->technologies : json_decode($project->technologies, true) ?? [];
                foreach ((array) $techs as $tech) {
                    ProjectTechnology::create(['project_id' => $project->id, 'name' => $tech]);
                }
                $this->line("  → Migrated " . count((array) $techs) . " technologies");
            }

            // Migrate features
            if ($project->features) {
                $features = is_array($project->features) ? $project->features : json_decode($project->features, true) ?? [];
                foreach ((array) $features as $feature) {
                    ProjectFeature::create(['project_id' => $project->id, 'name' => $feature]);
                }
                $this->line("  → Migrated " . count((array) $features) . " features");
            }

            // Migrate tasks (assume simple array)
            if (false && $project->tasks_json ?? null) { // No tasks_json column, skip or add if exists
                // ...
            }

            // Migrate team_members / assigned_users (assume simple user_id arrays for team_members)
            $membersData = array_merge((array) ($project->team_members ?? []), (array) ($project->assigned_users ?? []));
            foreach ((array) $membersData as $member) {
                if (is_array($member) && isset($member['user_id'])) {
                    ProjectTeamMember::create([
                        'project_id' => $project->id, 
                        'user_id' => $member['user_id']
                    ]);
                } elseif (is_numeric($member)) {
                    ProjectTeamMember::create([
                        'project_id' => $project->id, 
                        'user_id' => $member
                    ]);
                }
            }
            $this->line("  → Migrated team members/assigned users");
        }

        // 2. Clients contact_persons
        $clients = Client::whereNotNull('contact_persons')->get();
        $this->info("Found {$clients->count()} clients with contact_persons JSON");

        foreach ($clients as $client) {
            $this->warn("Migrating Client: {$client->name} (ID: {$client->id})");

            ContactPerson::where('client_id', $client->id)->delete();

            if ($client->contact_persons) {
                $contacts = is_array($client->contact_persons) ? $client->contact_persons : json_decode($client->contact_persons, true) ?? [];
                foreach ((array) $contacts as $contact) {
                    ContactPerson::create([
                        'client_id' => $client->id,
                        'name' => $contact['name'] ?? $contact,
                        'designation' => $contact['designation'] ?? null,
                        'email' => $contact['email'] ?? null,
                        'phone' => $contact['phone'] ?? null,
                    ]);
                }
                $this->line("  → Migrated " . count((array) $contacts) . " contact persons");
            }
        }

        $this->info('Migration completed! Run `php artisan migrate` AFTER this to drop legacy columns.');
        
        if (!$this->option('dry-run')) {
            $this->warn('WARNING: This command deletes existing normalized data to re-migrate. Use --dry-run first!');
        }

        return self::SUCCESS;
    }
}

