@extends('layouts.app')

@section('title', 'Blog Posts')

@section('content')
        {{-- @each('posts.partials.post', $posts, 'post') --}}
    <div class="row">
        <div class="col-8">
            @forelse ($posts as $key => $post)
                @include('posts.partials.post')
            @empty
                No posts found! 
            @endforelse
        </div>
        <div class="col-4">
            <div class="container">
                <div class="row">
                    {{-- <div class="card" style="width: 100%;">
                        <div class="card-body">
                            <h5 class="card-title">Most Commented</h5>
                            <h6 class="card-subtitle mb-2 text-muted">
                                What people are currently talking about
                            </h6>
                        </div>
                        <ul class="list-group list-group-flush">
                            @foreach ($mostCommented as $post)
                                <li class="list-group-item">
                                    <a href="{{ route('posts.show', ['post' => $post->id]) }}">
                                        {{ $post->title }}
                                    </a>
                                </li>  
                            @endforeach
                        </ul>
                    </div> --}}
                    <x-card-comp 
                        title="Most Commented" 
                        subtitle="What people are currently talking about" 
                        :items="collect($mostCommented)->pluck('title')">
                    </x-card-comp>
                </div>
                <div class="row mt-4">
                    <x-card-comp 
                        title="Most Active" 
                        subtitle="Users with most posts written" 
                        :items="collect($mostActive)->pluck('name')">
                    </x-card-comp>
                </div>
                <div class="row mt-4">
                    <x-card-comp 
                        title="Most Active Last Month" 
                        subtitle="Users with most posts written in the last month" 
                        :items="collect($mostActiveLastMonth)->pluck('name')">
                    </x-card-comp>
                </div>
            </div>
        </div>
    </div>
@endsection