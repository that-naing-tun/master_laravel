@extends('layout')

@section('content')
    <h1>This is contact</h1>
    @can('home.secret')
        <p>
            <a href="{{ route('secret') }}">
                Special Contact Details
            </a>
        </p>
    @endcan
@endsection
