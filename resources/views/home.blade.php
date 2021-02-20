@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="jumbotron">
            <h1 class="display-4">{{ __('layout.hellow_message') }}</h1>
            <p class="lead">{{ __('layout.practicle_courses') }}</p>
            <hr class="my-4">
            <a class="btn btn-primary btn-lg" href="https://hexlet.io" role="button">{{ __('layout.read_more') }}</a>
        </div>
    </div>
{{-- <div class="container"> --}}
{{--     <div class="row justify-content-center"> --}}
{{--         <div class="col-md-8"> --}}
{{--             <div class="card"> --}}
{{--                 <div class="card-header">{{ __('Dashboard') }}</div> --}}

{{--                 <div class="card-body"> --}}
{{--                     @if (session('status')) --}}
{{--                         <div class="alert alert-success" role="alert"> --}}
{{--                             {{ session('status') }} --}}
{{--                         </div> --}}
{{--                     @endif --}}

{{--                     {{ __('You are logged in!') }} --}}
{{--                 </div> --}}
{{--             </div> --}}
{{--         </div> --}}
{{--     </div> --}}
{{-- </div> --}}
@endsection
