<?php

use App\Utilities\SiteMapGenerator;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;
use Statamic\Facades\Entry;

// Route for generating and displaying the sitemap.xml file.
Route::get('sitemap.xml', function () {
    // Instantiate the SiteMapGenerator.
    $generator = new SiteMapGenerator();

    // Generate the sitemap and set the content type header to 'text/xml'.
    return response($generator->generate())
        ->header('Content-Type', 'text/xml');
})->name('sitemap.xml');

// Route for handling project requests submissions.
Route::post('project-request', function () {
    // Create a new project request entry.
    $newProjectRequest = Entry::make()->collection('project_requests');
    $requestBlueprint = $newProjectRequest->blueprint();

    // Check if the honeypot field is filled, and if so, return a fake success message.
    if (request()->has('honeypot') && ! empty(request()->input('honeypot'))) {
        // We can use the `{{ session }}` Antlers Tag to
        // retrieve information from the session. Flashed
        // data from the back()->with(...) will be available
        // in the session for the next request.
        //
        // For an example of how we can check the session
        // for the flashed data, see the following file:
        // - resources/views/pages/contact.antlers.html
        return redirect()->back()->with('success', true);
    }

    // Retrieve the fields from the blueprint and remove the title and slug fields.
    $fields = $requestBlueprint->fields()
        ->all()
        ->except(['title', 'slug'])
        ->keys()
        ->all();

    // Retrieve the data from the request that matches the fields.
    // This helps to prevent someone stuffing a lot of data into the request.
    $data = request()->only($fields);

    // Generate a new title for the project request.
    $data['title'] = 'New Project Request: '.$data['business_name'];

    // Add some extra data.
    $data['project_status'] = 'submitted';
    $data['submitted_on'] = now()->toDateTimeString();

    // Validate the data against the blueprint.
    $requestBlueprint->fields()->addValues($data)->validate();

    // Save the project request entry with a unique slug.
    Entry::make()->collection('project_requests')
        ->slug('request_'.Str::uuid())
        ->data($data)
        ->save();

    // Redirect back with a success message.
    return redirect()->back()->with('success', true);
})->name('project-request')
    ->middleware('honeypot'); // Use the 'honeypot' middleware for spam prevention.
