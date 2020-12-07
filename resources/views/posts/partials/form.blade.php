<div class="form-group">
    <label for="title">Title</label>
    <input type="text" id="title" name="title" class="form-control" value="{{ old('title', optional($post ?? null)->title) }}">
</div>
@error('title')
    {{ $message }}
@enderror
<div class="form-group">
    <label for="content">Content</label>
    <textarea class="form-control" id="content" name="content" >{{ old('content', optional($post ?? null)->content) }}</textarea>
</div>
@error('content')
    {{ $message }}
@enderror
@if ($errors->any())
    <div>
        <ul>
            @foreach ($errors->all() as $error)
                <li> {{ $error }} </li>
            @endforeach
        </ul>
    </div>
@endif