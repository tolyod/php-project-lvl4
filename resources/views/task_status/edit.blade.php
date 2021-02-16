@extends('layouts.app')
@section('content')
    <main class="container">
        <h1 class="mb-5">@lang('layout.common.headers.task_status_update')</h1>
        {!! Form::model($taskStatus, ['url' => route('task_statuses.update', $taskStatus), 'method' => 'PATCH', 'class' => 'w-50']) !!}
        <div class="form-group">
            {!! Form::label(__('layout.common.label.name'), null, ['class' => 'control-label']) !!}
            {!! Form::text('name', null, ['class' => 'form-control']) !!}
        </div>
        {!! Form::token() !!}
        {!! Form::submit(__('layout.common.buttons.update'), ['class' => 'btn btn-primary']) !!}
        {!! Form::close() !!}
    </main>
@endsection
