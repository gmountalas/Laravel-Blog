@extends('layouts.app')

@section('content')
    <form action="{{ route('register') }}" method="post">
        @csrf
        <div class="form-group">
            <label for="name">Name</label>
            <input type="text" name="name" value="{{ old('name') }}" required 
                class="form-control @error('name') is-invalid @enderror">
            @error('name')
                <span class="invalid-feedback">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
        </div>

        <div class="form-group">
            <label for="email">E-mail</label>
            <input type="text" name="email" value="{{ old('email') }}" required 
                class="form-control @error('email') is-invalid @enderror">
            @error('email')
                <span class="invalid-feedback">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
        </div>

        <div class="form-group">
            <label for="password">Password</label>
            <input type="password" name="password" required 
                class="form-control @error('password') is-invalid @enderror">
            @error('password')
                <span class="invalid-feedback">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
        </div>

        <div class="form-group">
            <label for="password_confirmation">Retype Password</label>
            <input type="password" name="password_confirmation" required class="form-control">
        </div>
        <button type="submit" class="btn btn-primary btn-block">Register</button>
    </form>
@endsection