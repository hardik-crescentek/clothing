<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=Edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title') - {{ config('app.name') }}</title>

    <!-- Favicon -->
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('assets/img/apple-touch-icon.png') }}">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('assets/img/favicon-32x32.png') }}">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('assets/img/favicon-16x16.png') }}">
    <!-- Stylesheet -->

    <link href="https://fonts.googleapis.com/css?family=Montserrat:400,500,600,700,800|Noto+Sans:400,700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    @stack('before-styles')
    <link rel="stylesheet" href="{{ asset('assets/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/bootstrap-select/bootstrap-select.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/owl-carousel/owl.carousel.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/owl-carousel/owl.theme.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/animate/animate.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/custom.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/bootstrap.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/sweetalert/sweetalert2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/dataTables.bootstrap4.min.css') }}">
    {{-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery.tablesorter/2.31.3/css/theme.default.min.css"> --}}

    <!-- Tweaks for older IEs-->
    <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
        <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script><![endif]-->
    <link rel="stylesheet" href="{{asset('assets/css/style.css')}}">
    @stack('after-styles')
</head>

<body class="">
    <!-- Begin Preloader -->
    <div id="preloader">
        <div class="canvas">
            <img src="{{ asset('assets/img/logo.png') }}" alt="logo" class="loader-logo">
            <div class="spinner"></div>
        </div>
    </div>

    <div class="page">
        @include('layouts.header')

        <!-- Begin Page Content -->
        <div class="page-content d-flex align-items-stretch">
            @include('layouts.sidebar')
            <!-- Begin Content -->
            <div class="content-inner">
                <div class="container-fluid">
                    @yield('content')
                </div>

                @include('layouts.footer')

                {{-- @include('layouts.rightsidebar') --}}
            </div>
            <!-- End Content -->
        </div>
        <!-- End Page Content -->
</div>
        @yield('modal')
        <!-- Scripts -->
        <script src="{{ asset('assets/js/jquery.min.js') }}"></script>
        <script src="{{ asset('assets/js/jquery.ui.min.js') }}"></script>
        <script src="{{ asset('assets/js/core.min.js') }}"></script>
        <script src="{{ asset('assets/js/form-validator/jquery.form-validator.min.js') }}"></script>
        <script src="{{ asset('assets/js/bootstrap-select/bootstrap-select.min.js') }}"></script>
        <script src="{{ asset('assets/js/nicescroll/nicescroll.min.js') }}"></script>
        <script src="{{ asset('assets/js/noty/noty.min.js') }}"></script>
        <script src="{{ asset('assets/js/app.js') }}"></script>
        <script src="{{ asset('assets/js/jquery.dataTables.min.js') }}"></script>
        <script src="{{ asset('assets/js/dataTables.bootstrap4.min.js') }}"></script>
        <script src="{{ asset('assets/css/sweetalert/sweetalert2.min.js') }}"></script>        
        <script>
            function notifactionData(){
                $('#Notidata').html('');
                $.ajax({
                    url: '{{ route("header.notifaction") }}',
                    type: 'get',
                    success: function(response){
                        var liHTML = '';
                            $.each(response,function(i,data){
                                if (data.material != null) {
                                    liHTML +=   '<li>'+
                                                    '<a href="{{ url("") }}/materials/'+data.material.id+'/edit">'+
                                                        '<div class="message-icon">'+
                                                            '<i class="la la-cart-plus"></i>'+
                                                        '</div>'+
                                                        '<div class="message-body">'+
                                                            '<div class="message-body-heading">'+
                                                                'Following item has low quantity <br>'+
                                                            '</div>'+
                                                            '<span class="date">'+
                                                                data.material.name+'('+ data.material.article_no +')'+ 'material has low Qty, Re-order for maintain Qty' +
                                                            '</span>'+
                                                        '</div>'+
                                                    '</a>'+
                                                '</li>';
                                }
                            })
                            $('#Notidata').append(liHTML);


                    }
                });
            }

            $(document).ready(function(){
                notifactionData();
            setInterval(notifactionData,10000);
            });
        </script>
        @stack('scripts')
</body>

</html>
