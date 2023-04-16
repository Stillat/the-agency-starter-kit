<?php

use App\Utilities\SiteMapGenerator;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;
use Statamic\Facades\Entry;

Route::get('sitemap.xml', function () {
    $generator = new SiteMapGenerator();

    return response($generator->generate())
        ->header('Content-Type', 'text/xml');
})->name('sitemap.xml');

Route::post('project-request', function () {
    $newProjectRequest = Entry::make()->collection('project_requests');
    $requestBlueprint = $newProjectRequest->blueprint();

    if (request()->has('honeypot') && ! empty(request()->input('honeypot'))) {
        return redirect()->back()->with('success', 'Your project request has been submitted.');
    }

    // Retrieve the fields from the blueprint and remove the title and slug fields
    $fields = $requestBlueprint->fields()
                                ->all()
                                ->except(['title', 'slug'])
                                ->keys()
                                ->all();

    // Retrieve the data from the request that matches the fields. This
    // will help to prevent someone stuffing a lot of data into the request.
    $data = request()->only($fields);

    // Now we will generate a new title.
    $data['title'] = 'New Project Request: '.$data['business_name'];

    // Add some extra data.
    $data['project_status'] = 'submitted';
    $data['submitted_on'] = now()->toDateTimeString();

    // Validate the data against the blueprint.
    $requestBlueprint->fields()->addValues($data)->validate();

    Entry::make()->collection('project_requests')
                    ->slug('request_'.Str::uuid())
                    ->data($data)
                    ->save();

    return redirect()->back()->with('success', true);
})->name('project-request')
  ->middleware('honeypot');
