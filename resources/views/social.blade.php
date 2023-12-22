@extends('layouts.guest')

@section('content')
    <div class="container mt-4">
        <div class="px-4 text-center">
            <h1 class="fw-bold">Defend Armenia: Amplifying Truth on Social Media</h1>
            <h2>Join our mission to combat misinformation and champion Armenia's story through the power of Twitter</h2>
        </div>
    </div>
    <div class="container mt-4">
        <div class="px-4 text-center">
            <h1 class="fw-bold">Harnessing Twitter for Positive Impact</h1>
            <p>
                In times when misinformation can spread as swiftly as the truth, the need for accurate, respectful, and
                timely communication is more crucial than ever. Defend Armenia leverages the expansive reach of Twitter
                to counteract false narratives and disparaging content about Armenia. By authorizing us to use your
                Twitter account, you become a part of a collective effort to shape a more informed and fair social dialogue.
                Our approach prioritizes facts and respectful engagement, ensuring that the conversation around Armenia is
                rooted in truth and dignity.
            </p>
        </div>
    </div>
    <div class="container mt-4">
        <div class="px-4 text-center">
            <h1 class="fw-bold">Our Commitment: Responsible and Ethical Engagement</h1>
            <p>
                Transparency and integrity are the cornerstones of our operations. The bot deployed on your behalf will
                strictly adhere to these principles. It will actively identify and respond to tweets spreading
                misinformation or demeaning content about Armenia, offering factual and respectful counter-narratives.
                The bot will not engage in personal attacks, spread false information, or violate Twitter's guidelines.
                Our goal is not just to defend but to educate and enlighten, fostering a digital space where discourse
                about Armenia is grounded in authenticity and respect.
            </p>
        </div>
    </div>
    <div class="container mt-4">
        <div class="px-4 text-center">
            <h1 class="fw-bold">How to Help</h1>
            <p>
                First, make sure you are logged in to Twitter in a separate tab on your browser
            </p>
            <p>
                Second, click the link below to authorize posting on your behalf. The link will take you to a page that
                looks like this →
            </p>
            <p>
                <img src="{{ asset('/assets/img/twitter_app_auth_example.png') }}" />
            </p>
        </div>
    </div>
    <div class="container mt-4">
        <div class="px-4 text-center">
            <h1 class="fw-bold">Questions?</h1>
            <a>
                Please email <a href="mailto:support@defendarmenia.org">support@defendarmenia.org</a> with any questions or to request more information on how your
                Twitter account may be used
            </p>
        </div>
    </div>
@endsection
