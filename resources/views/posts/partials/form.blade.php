<div class="form-group">
    <label for="title">@lang('Title')</label>
    <input type="text" id="title" name="title" class="form-control" 
        value="{{ old('title', optional($post ?? null)->title) }}">
</div>
@error('title')
    <div class="alert alert-danger">{{ $message }}</div>
@enderror
<div class="form-group">
    <label for="content">@lang('Content')</label>
    <textarea class="form-control" id="content" name="content" >{{ old('content', optional($post ?? null)->content) }}</textarea>
</div>
@error('content')
    {{ $message }}
@enderror
{{-- @if ($errors->any())
    <div class="mb-3">
        <ul class="list-group">
            @foreach ($errors->all() as $error)
                <li class="list-group-item list-group-item-danger"> {{ $error }} </li>
            @endforeach
        </ul>
    </div>
@endif --}}
<div class="form-group">
    <label for="title">@lang('Thumbnail')</label>
    <input type="file" name="thumbnail" class="form-control-file">
</div>
<x-errors></x-errors>