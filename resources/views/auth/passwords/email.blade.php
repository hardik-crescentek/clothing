@extends('layouts.auth')
@section('title', 'Forgot Password')
@section('bodyclass', 'bg-fixed-02')
@section('content')
<div class="container-fluid h-100 overflow-y">
    <div class="row flex-row h-100">
        <div class="col-12 my-auto">
            <div class="password-form mx-auto">
                <div class="logo-centered">
                    <a href="{{ url('/') }}"><img src="{{ asset('assets/img/logo.png') }}" alt="logo"></a>
                </div>
                <h3>{{ __('Reset Password') }}</h3>
                @if (session('status'))
                <div class="alert alert-success" role="alert">
                    {{ session('status') }}
                </div>
                @endif

                <form method="POST" action="{{ route('password.email') }}">
                    @csrf
                    <div class="group material-input">
                        <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>
                        <span class="highlight"></span>
                        <span class="bar"></span>
                        <label for="email" class="">{{ __('E-Mail Address') }}</label>
                        @error('email')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                    </div>
                    <div class="button text-center">
                        <button type="submit" class="btn btn-lg btn-gradient-01">{{ __('Send Password Reset Link') }}</button>
                    </div>
                </form>
            </div>
        </div>
        <!-- End Col -->
    </div>
    <!-- End Row -->
</div>
@endsection