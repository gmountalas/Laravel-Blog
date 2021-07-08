@extends('layouts.app')

@section('title', $post->title)

@section('content')
    <div class="row">
        <div class="col-8">
            <h1>
                {{ $post->title }}
                <x-badge show="{{ now()->diffInMinutes($post->created_at) < 5  }}" type="primary">
                    Brand New BlogPost
                </x-badge>
            </h1>
            <p>{{ $post->content }} </p> 
            
            <x-updated :date="$post->created_at" :name="$post->user->name" >
            </x-updated>
            <x-updated :date="$post->updated_at" >
                Updated
            </x-updated>

            <x-tags :tags="$post->tags"></x-tags>
            
            <p>Currently read by {{ $counter }} people</p>

            {{-- Implement the comments list --}}
            <h4>Comments</h4>

            @include('comments.partials.form')

            @forelse ($post->comments as $comment)
                <p>
                    {{ $comment->content }} 
                </p>
                <x-updated :date="$comment->created_at" :name="$comment->user->name">
                </x-updated>
            @empty
                <p>No comments yet!</p>
            @endforelse
            {{-- @isset($post['has_comments'])
                <div>The post has some comments... using isset</div>
            @endisset --}}
        </div>
        <div class="col-4">
            @include('posts.partials.activity')
        </div>
    </div>
@endsection