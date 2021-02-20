@extends('layouts.app')
@section('content')
    <div class="container">
        <h1 class="mb-5">@lang('layout.task_statuses')</h1>
        @auth
            <p>
                <a class="btn btn-primary" href="{{ route('task_statuses.create') }}">@lang('layout.common.buttons.create')</a>
            </p>
        @endauth
        <main class="row justify-content-center">
            <table class="table">
                <thead>
                <tr>
                    <th scope="col">@lang('layout.id')</th>
                    <th scope="col">@lang('layout.name')</th>
                    <th scope="col">@lang('layout.created_at')</th>
                    @auth
                    <th scope="col">@lang('layout.actions')</th>
                    @endauth
                </tr>
                </thead>
                <tbody>
                @foreach($taskStatuses as $taskStatus)
                    <tr>
                        <td>{{ $taskStatus->id }}</td>
                        <td>{{ $taskStatus->name }}</td>
                        <td>{{ $taskStatus->created_at->format('d.m.Y') }}</td>
                        <td>
                            @auth
                            <a href="{{ route('task_statuses.destroy', $taskStatus) }}"
                               class="text-danger"
                               data-confirm="Вы уверены?"
                               data-method="delete"
                               rel="nofollow">
                               @lang('layout.common.buttons.destroy')
                            </a>
                            <a href="{{ route('task_statuses.edit', $taskStatus) }}">@lang('layout.common.buttons.edit')</a>
                            @endauth
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </main>
    </div>
@endsection
