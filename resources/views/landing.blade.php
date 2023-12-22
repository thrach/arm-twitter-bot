@extends('layouts.guest')

@section('content')
    <div class="container text-center mt-5">
        <h2>Welcome to the Defend Armenia Initiative.</h2>
        <p>To view the contents of this page, please enter the password below or email <a href="mailto:support@defendarmenia.org">support@defendarmenia.org</a></p>
        <form method="POST" action="{{ url('/check-password') }}" class="form-inline justify-content-center">
            @csrf
            <div class="form-group mx-sm-3 mb-2">
                <input type="password" name="password" class="form-control" placeholder="Password">
            </div>
            <button type="submit" class="btn btn-primary mb-2">Enter</button>
        </form>
    </div>
@endsection
