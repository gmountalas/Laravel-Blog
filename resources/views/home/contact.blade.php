@extends('layouts.app')

@section('title', 'Contact page')

@section('content')
    <h1>Contact page</h1>
    <p> Hello from Contact Page!</p>  
    
    @can('home.secret')
        <p>
            <a href="{{ route('home.secret') }}">
                Special Contact details
            </a>
        </p>
    @endcan
@endsection