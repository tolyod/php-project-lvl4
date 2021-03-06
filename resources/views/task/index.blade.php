@extends('layouts.app')
@section('content')
        <h1 class="mb-5">@lang('layout.tasks')</h1>
        <div class="d-flex">
            <div>
            {!! Form::open(['route' => 'tasks.index', 'method' => 'GET', 'class' => 'form-inline']) !!}
                {!! Form::select(
                    'filter[status_id]',
                    $taskStatuses,
                    $filter['status_id'] ?? '',
                    ['class' => 'form-control mr-2', 'placeholder' => __('layout.task_status')])
                !!}
                {!! Form::select(
                    'filter[created_by_id]',
                    $creators,
                    $filter['created_by_id'] ?? '',
                    ['class' => 'form-control mr-2', 'placeholder' => __('layout.task.creator')])
                !!}
                {!! Form::select(
                        'filter[assigned_to_id]',
                        $assigns,
                        $filter['assigned_to_id'] ?? '',
                        ['class' => 'form-control mr-2', 'placeholder' => __('layout.task.assignee')])
                !!}
                {!! Form::submit(__('layout.common.buttons.apply'), ['class' => 'btn btn-outline-primary mr-2']) !!}
            {!! Form::close() !!}
            </div>
            @auth
                <a href="{{ route('tasks.create') }}" class="btn btn-primary ml-auto">@lang('layout.common.buttons.task_create')</a>
            @endauth
        </div>
            <table class="table mt-2">
                <thead>
                <tr>
                    <th scope="col">@lang('layout.id')</th>
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
                        <td>{{ $task->created_at->format('d.m.Y') }}</td>
                        <td>
                        @auth
                            @can('delete', $task)
                            <a href="{{ route('tasks.destroy', $task) }}" class="text-danger"
                               data-confirm="@lang('layout.common.confirm_destroy')" data-method="delete"
                               rel="nofollow">
                                @lang('layout.common.buttons.destroy')
                            </a>
                            @endcan
                            <a href="{{ route('tasks.edit', $task) }}">@lang('layout.common.buttons.edit')</a>
                        @endauth
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
            <div>{{ $tasks->links() }}</div>
@endsection
