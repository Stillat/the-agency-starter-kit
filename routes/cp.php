<?php

use App\Projects\ProjectBoardRepository;
use Illuminate\Support\Facades\Route;

Route::get('project-board', function () {
    /** @var ProjectBoardRepository $boardRepo */
    $boardRepo = app(ProjectBoardRepository::class);

    $projects = $boardRepo->getProjectData();

    return view('cp.project-board')->with('projects', $projects);
})->name('project-board');

Route::get('get-projects', function () {
    /** @var ProjectBoardRepository $boardRepo */
    $boardRepo = app(ProjectBoardRepository::class);

    $projects = $boardRepo->getProjectData();

    return response()->json([
        'projects' => $projects,
    ]);
});

Route::post('get-project-details', function () {
    /** @var ProjectBoardRepository $boardRepo */
    $boardRepo = app(ProjectBoardRepository::class);

    return response()->json([
        'project' => $boardRepo->getProjectDetails(request()->get('projectId')),
    ]);
});

Route::post('update-project', function () {
    /** @var ProjectBoardRepository $boardRepo */
    $boardRepo = app(ProjectBoardRepository::class);

    $boardRepo->updateStatus(
        request()->get('projectId'),
        request()->get('newStatus')
    );
});
