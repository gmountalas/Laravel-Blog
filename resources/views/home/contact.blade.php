@extends('layouts.app')

@section('title', 'Contact page')

@section('content')
    <h1>@lang('Contact')</h1>
    <p> @lang('Hello this is contact!')</p>  
    
    @can('home.secret')
        <p>
            <a href="{{ route('home.secret') }}">
                Special Contact details
            </a>
        </p>
    @endcan
@endsection