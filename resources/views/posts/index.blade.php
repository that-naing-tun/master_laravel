@extends('layout')

@section('content')
    {{-- @dd($posts) --}}
    <div class="row">
        <div class="col-8">
            @forelse ($posts as $post)
                <p>
                <h3>
                    @if ($post->trashed())
                        <del>
                    @endif
                    <a class="{{ $post->trashed() ? 'text-muted' : '' }}"
                        href="{{ route('posts.show', $post->id) }}">{{ $post->title }}</a>
                    @if ($post->trashed())
                        </del>
                    @endif
                </h3>
                {{-- <p class="text-muted">
                    Added {{ $post->created_at->diffForHumans() }}
                    By{{ $post->user->name }}
                </p> --}}
                @updated(['date' => $post->created_at, 'name' => $post->user->name])
                @endupdated

                @if ($post->comments_count)
                    <p>{{ $post->comments_count }} comments</p>
                @else
                    <p>No Comments yet!</p>
                @endif

                @auth
                    @can('update', $post)
                        <a href="{{ route('posts.edit', $post->id) }}" class="btn btn-primary">Edit</a>
                    @endcan
                @endauth


                @auth
                    @if (!$post->trashed())
                        @can('delete', $post)
                            <form method="POST" class="fm-inline" action="{{ route('posts.destroy', $post->id) }}">
                                @csrf
                                @method('DELETE')
                                <input type="submit" class="btn btn-secondary" value="Delete" />
                            </form>
                        @endcan
                    @endif
                @endauth


                </p>
            @empty
                <p>No Blog Post Yet!</p>
            @endforelse
        </div>
        <div class="col-4">
            <div class="container">
                <div class="row">
                    @card(['title' => 'Most commented'])
                        @slot('subtitle')
                            What people are currently talking about.
                        @endslot
                        @slot('items')
                            @foreach ($mostCommented as $comment)
                                <a href="{{ route('posts.show', [$post->id]) }}">
                                    <li class="list-group-item">{{ $comment->title }}</li>
                                </a>
                            @endforeach
                        @endslot
                    @endcard
                    {{-- <div class="card" style="width: 100%;">
                        <div class="card-body">
                            <h5 class="card-title">Most commented</h5>
                            <h6 class="card-subtitle mb-2 text-muted">What people are currently talking about.</h6>
                        </div>
                        <ul class="list-group list-group-flush">
                            @foreach ($mostCommented as $comment)
                                <a href="{{ route('posts.show', [$post->id]) }}">
                                    <li class="list-group-item">{{ $comment->title }}</li>
                                </a>
                            @endforeach
                        </ul>
                    </div> --}}
                </div>
                <div class="row mt-4">
                    {{-- <div class="card" style="width: 100%;">
                        <div class="card-body">
                            <h5 class="card-title">Most Active</h5>
                            <h6 class="card-subtitle mb-2 text-muted">
                                User with more posts written.
                            </h6>
                        </div>
                        <ul class="list-group list-group-flush">
                            @foreach ($mostActive as $user)
                                <li class="list-group-item">
                                    {{ $user->name }}
                                </li>
                            @endforeach
                        </ul>
                    </div> --}}
                    @card(['title' => 'Most Active'])
                        @slot('subtitle')
                            Writers with most posts written
                        @endslot
                        @slot('items', collect($mostActive)->pluck('name'))
                    @endcard
                </div>
                <div class="row mt-4">
                    {{-- <div class="card" style="width: 100%;">
                        <div class="card-body">
                            <h5 class="card-title">Most Active Last Month</h5>
                            <h6 class="card-subtitle mb-2 text-muted">User with more posts written in the last Month.</h6>
                        </div>
                        <ul class="list-group list-group-flush">
                            @foreach ($mostActiveLastMonth as $user)
                                <li class="list-group-item">
                                    {{ $user->name }}
                                </li>
                            @endforeach
                        </ul>
                    </div> --}}
                    @card(['title' => 'Most Active Last Month'])
                        @slot('subtitle')
                            User with more posts written in the last Month.
                        @endslot
                        @slot('items', collect($mostActiveLastMonth)->pluck('name'))
                    @endcard
                </div>
            </div>
        </div>
    </div>
@endsection
