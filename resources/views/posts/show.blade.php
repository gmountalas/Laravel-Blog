@extends('layouts.app')

@section('title', $post->title)

@section('content')
    <div class="row">
        <div class="col-8">
            @if ($post->image)
                <div style="background-image: url('{{ $post->image->url() }}'); min-height: 500px; color:white; text-aling: center; background-attachment: fixed;">
                    <h1 style="padding-top: 100px; text-shadow: 1px 2px #000">
            @else
                <h1>
            @endif
                {{ $post->title }}
                <x-badge show="{{ now()->diffInMinutes($post->created_at) < 5  }}" type="primary">
                    @lang('Brand new Post!')
                </x-badge>
            @if ($post->image)
                    </h1>
                </div>
            @else
                </h1>
            @endif

            <p>{{ $post->content }} </p> 

            {{-- <img src="{{ Storage::url($post->image->path) }}" alt=""> --}}
            {{-- <img src="{{ $post->image->url() }}" alt=""> --}}
            
            <x-updated :date="$post->created_at" :name="$post->user->name" >
            </x-updated>
            <x-updated :date="$post->updated_at" >
                @lang('Updated')
            </x-updated>

            <x-tags :tags="$post->tags"></x-tags>
            
            {{-- <p>Currently read by {{ $counter }} people</p> --}}
            {{-- Replaces the above --}}
            <p>@choice('messages.people.reading', $counter)</p>
            
            {{-- Implement the comments list --}}
            <h4>@lang('Comments')</h4>

            <x-comment-form :route="route('posts.comments.store', ['post' => $post->id])">
            </x-comment-form>
            {{-- @include('comments.partials.form') --}}

            <x-comment-list :comments="$post->comments"></x-comment-list>
            
            {{-- @isset($post['has_comments'])
                <div>The post has some comments... using isset</div>
            @endisset --}}
        </div>
        <div class="col-4">
            @include('posts.partials.activity')
        </div>
    </div>
@endsection