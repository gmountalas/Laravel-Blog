@extends('layouts.app')

@section('content')
    <form action="{{ route('login') }}" method="post">
        @csrf

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
            <div class="form-check">
                <input type="checkbox" name="remember" id="" class="form-check-input"
                    value="{{ old('remenber') ? 'checked' : '' }}">
                    <label for="remeber" class="form-check-label">
                        Remember Me
                    </label>
            </div>
        </div>
        <button type="submit" class="btn btn-primary btn-block">Login</button>
    </form>
@endsection