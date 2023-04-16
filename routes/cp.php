<?php

use App\Projects\ProjectBoardRepository;
use Illuminate\Support\Facades\Route;

// This file contains custom routes that are used by
// the "Project Board" Control Panel page. This page
// is used to manage the projects the status of
// each submitted project in a visual way.

// This file interacts/is used by a number of other files:
// - app/Projects/ProjectBoardRepository.php
// - resources/js/components/ProjectBoard.vue
// - resources/js/components/ProjectCard.vue
// - resources/views/cp/project-board.blade.php

// Route for displaying the project board page.
Route::get('project-board', function () {
    // Instantiate the ProjectBoardRepository.
    /** @var ProjectBoardRepository $boardRepo */
    $boardRepo = app(ProjectBoardRepository::class);

    // Retrieve the project data.
    $projects = $boardRepo->getProjectData();

    // Return the 'cp.project-board' view with the project data.
    return view('cp.project-board')->with('projects', $projects);
})->name('project-board');

// Route for fetching all projects as a JSON response.
Route::get('get-projects', function () {
    // Instantiate the ProjectBoardRepository.
    /** @var ProjectBoardRepository $boardRepo */
    $boardRepo = app(ProjectBoardRepository::class);

    // Retrieve the project data.
    $projects = $boardRepo->getProjectData();

    // Return the projects data as a JSON response.
    return response()->json([
        'projects' => $projects,
    ]);
});

// Route for fetching details of a specific project as a JSON response.
Route::post('get-project-details', function () {
    // Instantiate the ProjectBoardRepository.
    /** @var ProjectBoardRepository $boardRepo */
    $boardRepo = app(ProjectBoardRepository::class);

    // Return the project details as a JSON response.
    return response()->json([
        'project' => $boardRepo->getProjectDetails(request()->get('projectId')),
    ]);
});

// Route for updating the status of a specific project.
Route::post('update-project', function () {
    // Instantiate the ProjectBoardRepository.
    /** @var ProjectBoardRepository $boardRepo */
    $boardRepo = app(ProjectBoardRepository::class);

    // Update the project status.
    $boardRepo->updateStatus(
        // Retrieve the project ID and new status from the request.
        request()->get('projectId'),
        request()->get('newStatus')
    );
});
