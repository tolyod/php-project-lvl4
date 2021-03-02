@extends('layouts.app')
@section('content')
    <h1 class="mb-5">@lang('layout.common.headers.task_status_update')</h1>
    {!! Form::model($taskStatus, ['url' => route('task_statuses.update', $taskStatus), 'method' => 'PATCH', 'class' => 'w-50']) !!}
    @include('task_status._form')
    {!! Form::submit(__('layout.common.buttons.update'), ['class' => 'btn btn-primary']) !!}
    {!! Form::close() !!}
@endsection
