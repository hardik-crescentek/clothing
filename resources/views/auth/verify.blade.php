@extends('layouts.auth')
@section('title', 'Verify Your Email Address')
@section('bodyclass', 'bg-fixed-02')
@section('content')
<div class="container-fluid h-100 overflow-y">
    <div class="row flex-row h-100">
        <div class="col-12 my-auto">
            <div class="password-form mx-auto">
                <div class="logo-centered">
                    <a href="{{ url('/') }}"><img src="{{ asset('assets/img/logo.png') }}" alt="logo"></a>
                </div>
                <h3>{{ __('Verify Your Email Address') }}</h3>
                @if (session('resent'))
                <div class="alert alert-success" role="alert">
                    {{ __('A fresh verification link has been sent to your email address.') }}
                </div>
                @endif


                {{ __('Before proceeding, please check your email for a verification link.') }}
                {{ __('If you did not receive the email') }},
                <form class="d-inline" method="POST" action="{{ route('verification.resend') }}">
                    @csrf
                    <div class="button text-center">
                        <button type="submit" class="btn btn-link p-0 m-0 align-baseline">{{ __('click here to request another') }}</button>.
                    </div>
                </form>
            </div>
        </div>
        <!-- End Col -->
    </div>
    <!-- End Row -->
</div>
@endsection