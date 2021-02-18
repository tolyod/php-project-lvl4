@extends('layouts.app')
@section('content')
    <main class="container">
        <h1 class="mb-5">@lang('layout.common.headers.label_create')</h1>
        {!! Form::model($label, ['url' => route('labels.store', $label), 'method' => 'POST', 'class' => 'w-50']) !!}
        <div class="form-group">
            {!! Form::label(__('layout.common.label.name'), null, ['class' => 'control-label']) !!}
            {!! Form::text('name', null, ['class' => 'form-control']) !!}
        </div>
        {!! Form::token() !!}
        {!! Form::submit(__('layout.common.buttons.create'), ['class' => 'btn btn-primary']) !!}
        {!! Form::close() !!}
    </main>
@endsection
