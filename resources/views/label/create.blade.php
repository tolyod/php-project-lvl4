@extends('layouts.app')
@section('content')
    <h1 class="mb-5">@lang('layout.common.headers.label_create')</h1>
    {!! Form::model($label, ['url' => route('labels.store', $label), 'method' => 'POST', 'class' => 'w-50']) !!}
    @include('label._form');
    {!! Form::submit(__('layout.common.buttons.create'), ['class' => 'btn btn-primary']) !!}
    {!! Form::close() !!}
@endsection
