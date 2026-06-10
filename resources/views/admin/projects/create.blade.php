@extends('admin.layouts.app')

@section('title', 'New project')
@section('heading', 'New project')
@section('subheading', 'Track hosting, domain, and status.')

@section('content')
    <form method="POST" action="{{ route('admin.projects.store') }}" class="max-w-3xl">
        @include('admin.projects._form', ['mode' => 'create'])
    </form>
@endsection
