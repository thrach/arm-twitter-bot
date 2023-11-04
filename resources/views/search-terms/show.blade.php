@extends('layouts.app')

@section('content')
    <div class="row">
        <a href="{{ route('search-terms.edit', ['search_term' => $searchTerm->id]) }}" class="btn btn-primary mb-2">Edit</a>
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">Search Terms</h5>
            </div>
            <div class="card-body">
                <h5 class="card-title">{{ $searchTerm->tags->pluck('name')->join(', ') }}</h5>
            </div>
        </div>

        @if ($searchTerm->keyword->searchTermExclusion)
            <div class="card mt-5">
                <div class="card-body">
                    <h5 class="card-title">Search term exclusions</h5>
                </div>
                <div class="card-body">
                    <p class="card-text">{{ $searchTerm->keyword->searchTermExclusion->tags->pluck('name')->join(', ') }}</p>
                </div>
            </div>
        @endif

        <h5 class="card-title mt-5">Replies</h5>
        @foreach($searchTerm->keyword->replies as $reply)
            <div class="card mb-2">
                <div class="card-body">
                </div>
                <div class="card-body">
                    <p class="card-text">{{ $reply->reply }}</p>
                </div>
            </div>
        @endforeach
    </div>
@endsection
