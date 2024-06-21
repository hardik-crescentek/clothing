@extends('layouts.auth')
@section('title', 'Login')
@section('bodyclass', 'bg-white')
@section('content')
<div class="container-fluid no-padding h-100">
    <div class="row flex-row h-100 bg-white">
        <!-- Begin Left Content -->
        <div class="col-xl-8 col-lg-6 col-md-5 no-padding">
            <div class="elisyam-bg background-01">
                <div class="elisyam-overlay overlay-01"></div>
                <div class="authentication-col-content mx-auto">
                    <h1 class="gradient-text-01">
                        Welcome To {{ config('app.name') }}!
                    </h1>
                    <span class="description">
                    {{ config('app.name') }}
                    </span>
                </div>
            </div>
        </div>
        <!-- End Left Content -->
        <!-- Begin Right Content -->
        <div class="col-xl-4 col-lg-6 col-md-7 my-auto no-padding">
            <!-- Begin Form -->
            <div class="authentication-form mx-auto">
                <div class="logo-centered">
                <a href="{{ url('/') }}">
                        <img src="{{ asset('assets/img/logo.png') }}" alt="logo">
                    </a>
                </div>
                <h3>Sign In</h3>
                <form method="POST" action="{{ route('login') }}" class="form-validate" novalidate>
                    @csrf

                    <div class="group material-input">
                        <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus data-validation="required email">
                        <span class="highlight"></span>
                        <span class="bar"></span>
                        <label for="email" class="">{{ __('E-Mail Address') }}</label>
                        @error('email')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                    </div>
                    <div class="group material-input">
                        <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="current-password" data-validation="required">
                        <span class="highlight"></span>
                        <span class="bar"></span>
                        <label for="password" class="">{{ __('Password') }}</label>
                        @error('password')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                    </div>

                    <div class="row">
                        <div class="col text-left">
                            <div class="styled-checkbox">
                                <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                                <label class="form-check-label" for="remember">
                                    {{ __('Remember Me') }}
                                </label>
                            </div>
                        </div>
                        <div class="col text-right">
                            @if (Route::has('password.request'))
                            <a class="" href="{{ route('password.request') }}">{{ __('Forgot Your Password?') }}</a>
                            @endif
                        </div>
                    </div>
                    <div class="sign-btn text-center">
                        <button type="submit" class="btn btn-lg btn-gradient-01">{{ __('Login') }}</button>
                    </div>
                    <div class="register">
                        Don't have an account?
                        <br>
                        <a class="nav-link" href="{{ route('register') }}">{{ __('Create an account') }}</a>
                    </div>
                </form>

            </div>
            <!-- End Form -->
        </div>
        <!-- End Right Content -->
    </div>
    <!-- End Row -->
</div>

@endsection