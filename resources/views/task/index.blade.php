@extends('layouts.app')
@section('content')
    <div class="container">
        <h1 class="mb-5">@lang('layout.tasks')</h1>
        @auth
            <p>
                <a class="btn btn-primary" href="{{ route('tasks.create') }}">@lang('layout.common.buttons.create')</a>
            </p>
        @endauth
        <main class="row justify-content-center">
            <table class="table">
                <thead>
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">@lang('layout.task_status')</th>
                    <th scope="col">@lang('layout.name')</th>
                    <th scope="col">@lang('layout.task.creator')</th>
                    <th scope="col">@lang('layout.task.assignee')</th>
                    <th scope="col">@lang('layout.created_at')</th>
                    @auth
                    <th scope="col">@lang('layout.actions')</th>
                    @endauth
                </tr>
                </thead>
                <tbody>
                @foreach($tasks as $task)
                    <tr>
                        <td>{{ $task->id }}</td>
                        <td>{{ $task->status->name }}</td>
                        <td>
                            <a href="{{ route('tasks.show', $task) }}">{{ $task->name }}</a>
                        </td>
                        <td>{{ $task->creator->name }}</td>
                        <td>{{ optional($task->assignee)->name }}</td>
                        <td>{{ $task->created_at }}</td>
                        <td>
                        @auth
                            <a href="{{ route('tasks.edit', $task) }}">@lang('layout.common.buttons.edit')</a>
                            @can('delete', $task)
                            <a href="{{ route('tasks.destroy', $task) }}" class="text-danger"
                               data-confirm="@lang('layout.common.confirm_destroy')" data-method="delete"
                               rel="nofollow">
                                @lang('layout.common.buttons.destroy')
                            </a>
                            @endcan
                        @endauth
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </main>
    </div>
@endsection
