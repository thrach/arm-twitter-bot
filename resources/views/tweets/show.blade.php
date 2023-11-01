@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">{{ $tweet->twitterUser->username }}</h5>
                <p class="card-text">{{ $tweet->tweet }}</p>
                @if (! $tweet->replied)
                    <a href="#" class="btn btn-primary">Reply</a>
                @endif
            </div>
        </div>
        <div class="card mt-5">
            <div class="card-body">
                <h5 class="card-title">Search Terms</h5>
            </div>
            <div class="card-body">
                <p class="card-text">{{ $tweet->keywordReply->tags->pluck('name')->join(',') }}</p>
            </div>
        </div>

        @if($tweet->replied)
            <div class="card mt-5">
                <div class="card-body">
                    <h5 class="card-title">Sent Reply</h5>
                </div>
                <div class="card-body">
                    <p class="card-text">{{ $tweet->reply }}</p>
                </div>
            </div>

            <div class="card mt-5">
                <div class="card-body">
                    <h5 class="card-title">Used Twitter Account</h5>
                </div>
                <div class="card-body">
                    <p class="card-text">{{ $tweet->tweetReply->repliedAs->name }} ($tweet->tweetReply->repliedAs->username)</p>
                </div>
            </div>
        @endif
    </div>
@endsection
