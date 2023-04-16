<?php

namespace App\Projects;

use Statamic\Entries\Entry;
use Statamic\Facades\Blueprint as BlueprintAPI;
use Statamic\Facades\Entry as EntryAPI;
use Statamic\Fields\Blueprint;

class ProjectBoardRepository
{
    public function getProjectStatuses()
    {
        /** @var Blueprint $blueprint */
        $blueprint = BlueprintAPI::find('collections/project_requests/project_request');

        return $blueprint->fields()
            ->get('project_status')
            ->config()['options'];
    }

    public function getActiveProjects()
    {
        return collect(EntryAPI::whereCollection('project_requests')
            ->where('project_status', '!=', 'completed')
            ->all());
    }

    public function getProjectData()
    {
        $columns = $this->getProjectStatuses();
        $projects = $this->getActiveProjects()
            ->groupBy('project_status')
            ->all();

        $data = [];

        foreach ($columns as $column => $columnDisplayName) {
            $columnProjects = $this->convertProjectsToArray($projects[$column] ?? []);

            $data[] = [
                'title' => $columnDisplayName,
                'status' => $column,
                'projects' => $columnProjects,
            ];
        }

        return $data;
    }

    public function getProjectDetails($projectId)
    {
        $project = EntryAPI::find($projectId);

        return $project->toArray();
    }

    protected function convertProjectsToArray($projects)
    {
        return collect($projects)->map(function (Entry $project) {
            return [
                'title' => $project->get('title'),
                'id' => $project->id(),
                'status' => $project->get('project_status'),
                'business_name' => $project->get('business_name'),
                'contact_name' => $project->get('contact_name'),
                'email_address' => $project->get('email_address'),
                'submitted_on' => $project->submitted_on->isoFormat('LLL'),
                // budget_range and project_type return a LabeledValue.
                // The label will contain the "display" of the value.
                'budget_range' => $project->budget_range->label(),
                'project_type' => $project->project_type->label(),
            ];
        })->all();
    }

    public function updateStatus($id, $status)
    {
        $entry = EntryAPI::find($id);

        if ($entry == null) {
            return;
        }

        $entry->set('project_status', $status);
        $entry->save();
    }
}
