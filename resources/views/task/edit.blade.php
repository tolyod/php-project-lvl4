@extends('layouts.app')
@section('content')
        <h1 class="mb-5">@lang('layout.common.headers.task_update')</h1>
        {!! Form::model($task, ['url' => route('tasks.update', $task), 'method' => 'PATCH', 'class' => 'w-50']) !!}
        @include('task._form')
        {!! Form::submit(__('layout.common.buttons.update'), ['class' => 'btn btn-primary']) !!}
        {!! Form::close() !!}
@endsection
