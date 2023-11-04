@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">{{ $tweet->twitterUser->username }}</h5>
                <p class="card-text">{{ $tweet->tweet }}</p>
                @if (! $tweet->replied)
                    <form method="POST" action="{{ route('tweets.reply', ['tweet' => $tweet->id]) }}">
                        <select class="select2" name="auth_user_id">
                            <option value="{{ null }}">Random</option>
                            @foreach($authUsers as $authUser)
                                <option value="{{ $authUser->id }}">{{ $authUser->name }}</option>
                            @endforeach
                        </select>
                        <textarea class="form-control" name="reply_text">{{ $tweet->reply }}</textarea>
                        @csrf
                        <button type="submit" class="btn btn-primary mb-2 mt-2">Reply</button>
                    </form>
                @endif
            </div>
        </div>
        <div class="card mt-5">
            <div class="card-body">
                <h5 class="card-title">Search Terms</h5>
                <a href="{{ route('search-terms.show', ['search_term' => $tweet->searchTerm->id]) }}" class="btn btn-primary" title="View">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-binoculars-fill" viewBox="0 0 16 16">
                        <path d="M4.5 1A1.5 1.5 0 0 0 3 2.5V3h4v-.5A1.5 1.5 0 0 0 5.5 1h-1zM7 4v1h2V4h4v.882a.5.5 0 0 0 .276.447l.895.447A1.5 1.5 0 0 1 15 7.118V13H9v-1.5a.5.5 0 0 1 .146-.354l.854-.853V9.5a.5.5 0 0 0-.5-.5h-3a.5.5 0 0 0-.5.5v.793l.854.853A.5.5 0 0 1 7 11.5V13H1V7.118a1.5 1.5 0 0 1 .83-1.342l.894-.447A.5.5 0 0 0 3 4.882V4h4zM1 14v.5A1.5 1.5 0 0 0 2.5 16h3A1.5 1.5 0 0 0 7 14.5V14H1zm8 0v.5a1.5 1.5 0 0 0 1.5 1.5h3a1.5 1.5 0 0 0 1.5-1.5V14H9zm4-11H9v-.5A1.5 1.5 0 0 1 10.5 1h1A1.5 1.5 0 0 1 13 2.5V3z"/>
                    </svg>
                </a>
            </div>
            <div class="card-body">
                <p class="card-text">{{ $tweet->searchTerm->tags->pluck('name')->join(',') }}</p>
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
                    <p class="card-text">{{ $tweet->tweetReply->repliedAs->name }} ({{ $tweet->tweetReply->repliedAs->username }})</p>
                </div>
            </div>
        @endif
    </div>
@endsection

@push('footer-scripts')
    <script type="text/javascript">
        $(document).ready(function() {
            $('.select2').select2();
        });
    </script>
@endpush
