@extends('layouts.app')
@section('content')
    <div class="container">
        <h1 class="mb-5">@lang('layout.labels')</h1>
        @auth
        <p>
            <a class="btn btn-primary" href="{{ route('labels.create') }}">@lang('layout.common.buttons.create')</a>
        </p>
        @endauth
        <main class="row justify-content-center">
            <table class="table">
                <thead>
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">@lang('layout.name')</th>
                    <th scope="col">@lang('layout.common.label.description')</th>
                    <th scope="col">@lang('layout.created_at')</th>
                    @auth
                    <th scope="col">@lang('layout.actions')</th>
                    @endauth
                </tr>
                </thead>
                <tbody>
                @foreach($labels as $label)
                    <tr>
                        <td>{{ $label->id }}</td>
                        <td>{{ $label->name }}</td>
                        <td>{{ $label->description }}</td>
                        <td>{{ $label->created_at->format('d.m.Y') }}</td>
                        <td>
                            @auth
                            <a href="{{ route('labels.destroy', $label) }}"
                               class="text-danger"
                               data-confirm="@lang('layout.common.confirm_destroy')"
                               data-method="delete"
                               rel="nofollow">
                                @lang('layout.common.buttons.destroy')
                            </a>
                            <a href="{{ route('labels.edit', $label) }}">@lang('layout.common.buttons.edit')</a>
                            @endauth
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </main>
    </div>
@endsection
