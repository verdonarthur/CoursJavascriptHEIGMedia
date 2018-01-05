@extends('layout')
@section('content')
    @if (old('email'))
        <div class="warning">Login failed</div>
    @endif
    <section id="auth_form">
        <form action="{{ action('AuthController@check') }}" method="post">
            <input type="hidden" name="_token" value="{{ csrf_token() }}">
            <label>Email</label>
            <input type="text" name="email" value="{{ old('email') }}" @unless (old('email')) autofocus @endunless/>
            <label>Password</label>
            <input type="password" name="password" @if (old('email')) autofocus @endif/>
            <input type="submit" value="Login"/>
        </form>
        <a href="{{ action('AuthController@google') }}" id="googleOauth">Or login with your Google account</a>
    </section>
@endsection
