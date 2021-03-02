@extends('layouts.app')
@section('content')
    <h1 class="mb-5">@lang('layout.common.headers.task_status_create')</h1>
    {!! Form::model($taskStatus, ['url' => route('task_statuses.store', $taskStatus), 'method' => 'POST', 'class' => 'w-50']) !!}
    @include('task_status._form')
    {!! Form::submit(__('layout.common.buttons.create'), ['class' => 'btn btn-primary']) !!}
    {!! Form::close() !!}
@endsection
