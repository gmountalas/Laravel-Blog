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
            <div class="card" style="width: 18rem;">
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
            </div>
        </div>
    </div>
@endsection