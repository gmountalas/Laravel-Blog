<h3>
    @if ($post->trashed())
        <del>
    @endif
    <a  class="{{ $post->trashed() ? 'text-muted' : '' }}"
        href="{{ route('posts.show', ['post' => $post->id]) }}">{{$post->title}}</a>
    @if ($post->trashed())
        </del>
    @endif
</h3>

<x-updated :date="$post->created_at" :name="$post->user->name" :userId="$post->user->id">
</x-updated>

<x-tags :tags="$post->tags"></x-tags>

{{-- @if ($post->comments_count)
    <p> {{ $post->comments_count }} comments</p>
@else
    <p>No comments yet!</p>
@endif --}}

{{-- Replaces the above --}}
{{-- {{ trans_choice('messages.comments', $post->comments_count) }} --}}
@choice('messages.comments', $post->comments_count)

<div class="mb-3">
    @can('update', $post)
        <a href="{{ route('posts.edit', ['post' => $post->id]) }}" class="btn btn-primary">@lang('Edit')</a> 
    @endcan
    @if (!$post->trashed())
        @can('delete', $post)
            <form class="d-inline" action="{{ route('posts.destroy', ['post' => $post->id]) }}" method="POST">
                @csrf
                @method('DELETE')
                <input type="submit" value="Delete!" class="btn btn-primary">
            </form>
        @endcan
    @endif
</div>
        