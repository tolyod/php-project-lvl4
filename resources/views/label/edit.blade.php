@extends('layouts.app')
@section('content')
    <h1 class="mb-5">@lang('layout.common.headers.label_update')</h1>
    {!! Form::model($label, ['url' => route('labels.update', $label), 'method' => 'PATCH', 'class' => 'w-50']) !!}
    @include('label._form');
    {!! Form::submit(__('layout.common.buttons.update'), ['class' => 'btn btn-primary']) !!}
    {!! Form::close() !!}
@endsection
