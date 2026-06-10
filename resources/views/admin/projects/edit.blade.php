@extends('admin.layouts.app')

@section('title', 'Edit · ' . $project->title)
@section('heading', 'Edit project')
@section('subheading', $project->title)

@section('content')
    <form method="POST" action="{{ route('admin.projects.update', $project) }}" class="max-w-3xl">
        @include('admin.projects._form', ['mode' => 'edit'])
    </form>
@endsection
