@extends('layouts.app')


@section('content')
    <div class="faq-section">
        <h1>Frequently Asked Questions</h1>
    </div>

    <div class="faq-row">
        <div class="faq-column">
            <div class="about-card">
                <img src="{{url('storage/images/register.png')}}" alt="Register" style="width:100%">
                <div class="about-container">
                    <h2 style="text-align: center">How to Register</h2>
                    <ol>
                        <li>Fill in the name field.</li>
                        <li>Fill in the e-mail address field.</li>
                        <li>Fill in the password field and confirm it bellow.</li>
                        <li>Fill in your birthdate (Must be over 13 years old).</li>
                        <li>Select your privacy status (Either Public or Private).</li>
                        <li>Upload both your profile and cover pictures.</li>
                        <li>Finally, click on the 'Sign-up' button to finish the register process.</li>
                    </ol>
                    <a href="{{ route('register') }}">
                        <button class="about-button">Register</button>
                    </a>
                </div>
            </div>
        </div>

        <div class="faq-column">
            <div class="faq-card">
                <img src="{{url('storage/images/create_post.png')}}" alt="Create Post" style="width:100%">
                <div class="about-container">
                    <h2 style="text-align: center">How to Create a Post</h2>
                    <ol>
                        <li>Login in to your account (if you already are, skip this step). <strong>*</strong></li>
                        <li>Go to your profile page and click the 'Create Post' button.<strong>*</strong></li>
                        <li>To create the post, you'll need to fill in the fields.</li>
                        <li>Fill in the text field with the content you desire.<strong>*</strong></li>
                        <li>Select your visibility (Either Public or Private).<strong>*</strong></li>
                        <li>Upload up to 5 images.</li>
                        <li>Tag someone you want to.</li>
                        <li>Finally, Click the 'Create' button to post it.<strong>*</strong></li>
                    </ol>
                    <p><strong>*</strong> Required fields.</p>
                    <a href="{{ route('posts.create') }}">
                        <button class="about-button">Create Post</button>
                    </a>
                </div>
            </div>
        </div>


    </div>

@endsection
