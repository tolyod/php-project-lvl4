@extends('layouts.app')
@section('content')
    <main class="container py-4">
        <h1 class="mb-5">
            @lang('layout.task.show_task') : {{ $task->name }}
            @auth
            <a href="{{ route('tasks.edit', $task) }}">⚙</a>
            @endauth
        </h1>
        <p>@lang('layout.name'): {{ $task->name }} </p>
        <p>@lang('layout.task_status'): {{ $task->status->name }}</p>
        <p>@lang('layout.common.label.description'): {{ $task->description }}</p>
    </main>
@endsection
