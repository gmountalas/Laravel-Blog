@extends('layouts.app')

@section('content')
    <form action="{{ route('users.update', ['user' => $user->id]) }}" 
        method="POST" enctype="multipart/form-data"
        class="form-horizontal">

        @csrf
        @method('PUT')

        <div class="row">
            <div class="col-4">
                <img src="{{ $user->image ? $user->image->url() : '' }}" 
                    class="img-thumbnail avatar">

                <div class="card mt-4">
                    <div class="card-body">
                        <h6>@lang('Upload a different photo')</h6>
                        <input type="file" name="avatar" class="form-control-file">
                    </div>
                </div>
            </div>
            <div class="col-8">
                <div class="form-group">
                    <label>@lang('Name:')</label>
                    <input type="text" name="name" value="" class="form-control">
                </div>
                <div class="form-group">
                    <label>@lang('Language:')</label>
                    <select name="locale" class="form-control">
                        @foreach (App\Models\User::LOCALES as $locale => $label)
                            <option value="{{ $locale }}" {{ $user->locale !== $locale ?: 'selected' }}>
                                {{ $label }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <x-errors></x-errors>
                <div class="form-group">
                    <input type="submit" value="@lang('Save changes')" class="btn btn-primary">
                </div>
            </div>
        </div>

    </form>
@endsection