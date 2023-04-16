<?php

namespace App\Projects;

use Illuminate\Support\Collection;
use Statamic\Entries\Entry;
use Statamic\Facades\Blueprint as BlueprintAPI;
use Statamic\Facades\Entry as EntryAPI;
use Statamic\Fields\Blueprint;

/**
 * This class provides some simple methods for retrieving
 * and interacting with entries within the project_requests
 * collection. We use this class to power the project board
 * view within the Control Panel.
 *
 * @see routes/cp.php
 * @see resources/js/components/ProjectBoard.vue
 * @see resources/js/components/ProjectCard.vue
 */
class ProjectBoardRepository
{
    /**
     * Retrieve the project statuses from the project_request blueprint.
     *
     * @return array
     */
    public function getProjectStatuses()
    {
        // Locate the blueprint for the project_requests collection.
        /** @var Blueprint $blueprint */
        $blueprint = BlueprintAPI::find('collections/project_requests/project_request');

        // The fields method will return an instance of
        // Statamic\Fields\Fields, which exposes the `get` method.
        // The `get` method can be used to retrieve a single field
        // from the blueprint, without having to know which
        // section it is in. After we have the `Field` instance,
        // we can get its configuration using the `config()`
        // method. The `config` method will return an array of
        // configuration options for the field. In this case,
        // we are looking for the `options` array, which contains
        // the list of options for the select field.
        return $blueprint->fields()
            ->get('project_status')
            ->config()['options'];
    }

    /**
     * Retrieves all active project requests.
     *
     * @return Collection
     */
    public function getActiveProjects()
    {
        // The EntryAPI is an alias of the `Statamic\Facades\Entry` facade.
        // It was renamed at the top of this file to avoid confusion with
        // the `Statamic\Entries\Entry` class that is also used in this file.
        return collect(EntryAPI::whereCollection('project_requests')
            // Filters out any projects that have been completed.
            ->where('project_status', '!=', 'completed')
            ->all());
    }

    /**
     * Retrieves all active projects, grouped by their status.
     *
     * @return array
     */
    public function getProjectData()
    {
        // We will use the project_status field to represent the
        // columns in our project board view in the Control Panel.
        $columns = $this->getProjectStatuses();

        // We will use the `groupBy` method to group the projects
        // by their status. The `all` method will return an array
        // of collections, where the key is the group name.
        $projects = $this->getActiveProjects()
            ->groupBy('project_status')
            ->all();

        $data = [];

        // Massage our data into a format that is
        // easier to work with in our Vue components.
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

    /**
     * Retrieves a single project by its ID.
     *
     * @param  string  $projectId The ID of the project to retrieve.
     * @return array
     */
    public function getProjectDetails($projectId)
    {
        $project = EntryAPI::find($projectId);

        // Convert the project to an array so that we can
        // easily access its properties in our Vue components.
        return $project->toArray();
    }

    /**
     * Converts all projects in the provided $projects
     * array into a format that is easier to work with
     * in our Vue components.
     *
     * @return array
     */
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

    /**
     * Updates the status of a project.
     *
     * @return void
     */
    public function updateStatus($id, $status)
    {
        $entry = EntryAPI::find($id);

        if ($entry == null) {
            return;
        }

        // Update the project_status field on the entry.
        $entry->set('project_status', $status);

        // Save the entry. The `save()` method will trigger
        // the following events:
        // - Statamic\Events\EntrySaving
        // - Statamic\Events\EntrySaved

        // If we wanted to prevent these events from
        // firing, we could have used the
        // `saveQuietly()` method instead.
        $entry->save();
    }
}
