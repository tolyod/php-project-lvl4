@extends('layouts.app')
@section('content')
        <h1 class="mb-5">@lang('layout.common.headers.task_create')</h1>
        {!! Form::model($task, ['url' => route('tasks.store', $task), 'method' => 'POST', 'class' => 'w-50']) !!}
        @include('task._form')
        {!! Form::submit(__('layout.common.buttons.create'), ['class' => 'btn btn-primary']) !!}
        {!! Form::close() !!}
@endsection
