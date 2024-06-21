@extends('layouts.auth')
@section('title', 'Register')
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
                        Be {{ config('app.name') }}!
                    </h1>
                    <span class="description">
                        Etiam consequat urna at magna bibendum, in tempor arcu fermentum vitae mi massa egestas.
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
                    <a href="{{ url('/') }}"><img src="{{ asset('assets/img/logo.png') }}" alt="logo"></a>
                </div>
                <h3>{{ __('Register') }}</h3>
                <form method="POST" action="{{ route('register') }}" class="form-validate" novalidate>
                    @csrf
                    <div class="group material-input">
                        <input id="firstname" type="text" class="form-control @error('firstname') is-invalid @enderror" name="firstname" value="{{ old('firstname') }}" required autocomplete="firstname" autofocus data-validation="required">
                        <span class="highlight"></span>
                        <span class="bar"></span>
                        <label for="firstname" class="">{{ __('First Name') }}</label>
                        @error('firstname')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                    </div>
                    <div class="group material-input">
                        <input id="lastname" type="text" class="form-control @error('lastname') is-invalid @enderror" name="lastname" value="{{ old('lastname') }}" required autocomplete="lastname" autofocus data-validation="required">
                        <span class="highlight"></span>
                        <span class="bar"></span>
                        <label for="lastname" class="">{{ __('Last Name') }}</label>
                        @error('lastname')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                    </div>
                    <div class="group material-input">
                        <input id="mobile" type="text" class="form-control @error('mobile') is-invalid @enderror" name="mobile" value="{{ old('mobile') }}" required autocomplete="mobile" autofocus data-validation="required">
                        <span class="highlight"></span>
                        <span class="bar"></span>
                        <label for="mobile" class="">{{ __('Mobile') }}</label>
                        @error('mobile')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                    </div>
                    <div class="group material-input">
                        <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" data-validation="required email">
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
                    <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="new-password" data-validation="required">
                        <span class="highlight"></span>
                        <span class="bar"></span>
                        <label for="password" class="">{{ __('Password') }}</label>
                        @error('password')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                    </div>
                    <div class="group material-input">
                    <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required autocomplete="new-password" data-validation="required">
                        <span class="highlight"></span>
                        <span class="bar"></span>
                        <label for="password-confirm" class="">{{ __('Confirm Password') }}</label>
                    </div>
                
                <div class="row">
                    <div class="col text-left">
                        <div class="styled-checkbox">
                            <input type="checkbox" name="checkbox" id="agree" data-validation="required">
                            <label for="agree">I Accept <a href="#">Terms and Conditions</a></label>
                        </div>
                    </div>
                </div>
                <div class="sign-btn text-center">
                    <button type="submit" class="btn btn-lg btn-gradient-01">{{ __('Create an account') }}</button>
                </div>
            </form>
                <div class="register">
                    Already have an account?
                    <br>
                    <a class="" href="{{ route('login') }}">{{ __('Login') }}</a>
                </div>
            </div>
            <!-- End Form -->
        </div>
        <!-- End Right Content -->
    </div>
    <!-- End Row -->
</div>

@endsection