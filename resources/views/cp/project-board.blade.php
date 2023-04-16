@extends('statamic::layout')
@section('title', 'Project Board')
@section('wrapper_class', 'max-w-full')

@section('content')
    <div class="flex items-center justify-between mb-3">
        <h1>{{ __('site.project_board') }}</h1>
    </div>

    <project-board :columns="@js($projects)"></project-board>
@endsection