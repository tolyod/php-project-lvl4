@extends('layouts.app')
@section('content')
    <main class="container">
        <h1 class="mb-5">@lang('layout.common.headers.task_create')</h1>
        {!! Form::model($task, ['url' => route('tasks.store', $task), 'method' => 'POST', 'class' => 'w-50']) !!}
        <div class="form-group">
            {!! Form::label('name', __('layout.common.label.name')) !!}
            {!! Form::text('name', null, ['class' => 'form-control']) !!}
        </div>
        <div class="form-group">
            {!! Form::label('description', __('layout.common.label.description')) !!}
            {!! Form::textarea('description', null, ['class' => 'form-control']) !!}
        </div>
        <div class="form-group">
            {!! Form::label('assigned_to_id', __('layout.task.assignee')) !!}
            {!! Form::select('assigned_to_id', $users, null, ['class' => 'form-control', 'placeholder' => __('layout.selected_default')]) !!}
        </div>
        <div class="form-group">
            {!! Form::label('status_id', __('layout.task_status')) !!}
            {!! Form::select('status_id', $taskStatuses, null, ['class' => 'form-control', 'placeholder' => __('layout.selected_default')]) !!}
        </div>
        <div class="form-group">
            {!! Form::label('labels', __('layout.labels')) !!}
            {!! Form::select('labels[]', $labels, null, ['class' => 'form-control', 'multiple' => true, 'placeholder' => '']) !!}
        </div>
        {!! Form::token() !!}
        {!! Form::submit(__('layout.common.buttons.create'), ['class' => 'btn btn-primary']) !!}
        {!! Form::close() !!}
    </main>
@endsection
