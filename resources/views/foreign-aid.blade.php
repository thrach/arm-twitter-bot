@extends('layouts.guest')
@push('header-scripts')
    <style>
        .iframe-container {
            position: relative;
            width: 100%;
            padding-bottom: 200%; /* Aspect ratio */
            height: 0;
        }

        .iframe-container iframe {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
        }
    </style>
@endpush
@section('content')
    <div class="iframe-container">
        <iframe src="https://docs.google.com/document/d/e/2PACX-1vT2OTAucYDHukUqwSFYu1zZj7a7k6EP1BLhYAj8YR0YOpY7F1oVyowcbY2R-q47BcXIkkGSI9JnOn3t/pub?embedded=true"></iframe>
    </div>
@endsection
