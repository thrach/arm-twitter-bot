<x-layout bodyClass="g-sidenav-show  bg-gray-200">
    <x-navbars.sidebar activePage='tweets'></x-navbars.sidebar>
    <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg ">
        <x-navbars.navs.auth titlePage="Dashboard"></x-navbars.navs.auth>
        <a href="{{ route('tweets.index') }}"><i class="fas fa-arrow-left"></i></a>
        <div class="card">
            <div class="card-body text-center">
                <h5 class="font-weight-normal mt-3">
                    <a href="javascript:;">{{ $tweet->twitterUser->name }}</a>
                </h5>
                <p class="mb-0">
                    {{ $tweet->tweet }}
                </p>
            </div>
            <hr class="dark horizontal my-0">
                <div class="row align-items-center">
                    @if(! $tweet->replied)
                        <div class="mb-4 col-lg-6 p-4">
                            <form method="POST" action="{{ route('tweets.reply', ['tweet' => $tweet->id]) }}">
                                @csrf
                                <div class="input-group mb-4">
                                    <label class="form-label">Reply As</label>
                                    <select class="select2 form-control">
                                        <option value="{{ null }}">Reply as (default:random)</option>
                                        @foreach($authUsers as $authUser)
                                            <option value="{{ $authUser->id }}">{{ $authUser->name }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="input-group input-group-static mb-4">
                                    <label>Reply</label>
                                    <textarea class="form-control">{{ $tweet->reply }}</textarea>
                                </div>

                                <button type="submit" class="btn btn-success">Post a reply</button>
                            </form>
                        </div>
                    @else
                    <div class="mb-4 col-lg-6">
                        <div class="card shadow-lg">
                            <span class="badge rounded-pill bg-light text-dark w-30 mt-n2 mx-auto">Reply details</span>
                            <div class="card-header text-center pt-4 pb-3">
                                <h1 class="font-weight-bold mt-2">
                                    <small class="text-lg mb-auto"></small>
                                </h1>
                            </div>
                            <div class="card-body text-lg-start text-center pt-0">
                                <div class="d-flex flex-column">
                                    <span class="mb-2 text-xs">Replied As: <span class="text-dark font-weight-bold ms-sm-2">{{ $tweet->tweetReply->repliedAs->name }}</span></span>
                                    <span class="mb-2 text-xs">Reply Content: <span class="text-dark ms-sm-2 font-weight-bold">{{ $tweet->reply }}</span></span>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif
                    <div class="mb-4 col-lg-6">
                        <div class="card shadow-lg">
                            <span class="badge rounded-pill bg-light text-dark w-30 mt-n2 mx-auto">Sentiment Analyse</span>
                            <div class="card-header text-center pt-4 pb-3">
                                <h1 class="font-weight-bold mt-2">
                                    <small class="text-lg mb-auto"></small>
                                </h1>
                            </div>
                            <div class="card-body text-lg-start text-center pt-0">
                                <div class="d-flex flex-column">
                                    <span class="mb-2 text-xs">Magnitude: <span class="text-dark font-weight-bold ms-sm-2">{{ $tweet->sentiment->analysis['documentSentiment']['magnitude'] }}</span></span>
                                    <span class="mb-2 text-xs">Score: <span class="text-dark ms-sm-2 font-weight-bold">{{ $tweet->sentiment->analysis['documentSentiment']['score'] }}</span></span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
        </div>
    </main>
    @push('js')
        <script type="text/javascript">
            $(document).ready(function() {
                $('.select2').select2();
            });
        </script>
    @endpush
</x-layout>
