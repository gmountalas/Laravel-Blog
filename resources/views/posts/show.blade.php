@extends('layouts.app')

@section('title', $post->title)

@section('content')

    <h1>
        {{ $post->title }}
        <x-badge show="{{ now()->diffInMinutes($post->created_at) < 5  }}" type="primary">
            Brand New BlogPost
        </x-badge>
    </h1>
    <p>{{ $post->content }} </p> 
    <p> Added {{ $post->created_at->diffForHumans() }}</p>
    
   
    
    {{-- Implement the comments list --}}
    <h4>Comments</h4>
    @forelse ($post->comments as $comment)
        <p>
            {{ $comment->content }} 
        </p>
        <p class="text-muted">
            added {{ $comment->created_at->diffForHumans() }}
        </p>
    @empty
        <p>No comments yet!</p>
    @endforelse
    {{-- @isset($post['has_comments'])
        <div>The post has some comments... using isset</div>
    @endisset --}}

@endsection