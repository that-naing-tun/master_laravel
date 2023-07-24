@extends('layout')

@section('content')
    <h3>
        {{ $post->title }}

        @badge(['show' => now()->diffInMinutes($post->created_at) < 20])
            Brand New Post
        @endbadge

    </h3>
    <p>{{ $post->content }}</p>

    @updated(['date' => $post->created_at, 'name' => $post->user->name])
    @endupdated

    @updated(['date' => $post->updated_at])
        Updated
    @endupdated

    <p>Currently read by {{ $counter }} people</p>

    <h4>Comments </h4>

    @forelse ($post->comments as $comment)
        <p>
            {{ $comment->content }},
        </p>
        @updated(['date' => $comment->created_at])
        @endupdated
    @empty
        <p>No Comments Yet</p>
    @endforelse
@endsection
