@extends('layouts.master')
@section('title', 'WareHouse')
@section('content')
<!-- Begin Page Header-->
<div class="row">
    <div class="page-header">
        <div class="d-flex align-items-center">
            <h2 class="page-header-title">WareHouse</h2>
        </div>
    </div>
</div>
<!-- End Page Header -->

@if ($message = Session::get('success'))
<div class="alert alert-success">
    {{ $message }}
</div>
@endif

<!-- Begin Row -->
<div class="row flex-row">
    <div class="col-xl-12 col-12">
        <div class="widget has-shadow">
            <div class="widget-header bordered no-actions d-flex align-items-center">
                <h4>Add WareHouse</h4>
            </div>
            <div class="widget-body">


                @if (count($errors) > 0)
                <div class="alert alert-danger">
                    <strong>Whoops!</strong> There were some problems with your input.<br><br>
                    <ul>
                        @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
                @endif


                {!! Form::open(array('route' => 'warehouse.store','method'=>'POST', 'class'=>"form-validate", 'novalidate')) !!}
                    <div class="row">
                        <div class="form-group col-lg-3">
                            <label class="form-control-label d-flex">Name<span class="text-danger ml-2">*</span></label>
                            {!! Form::text('name', null, array('placeholder' => 'Name','class' => 'form-control', 'data-validation'=>"required")) !!}
                        </div>
                        <div class="form-group col-lg-3">
                            <label class="form-control-label d-flex">Address<span class="text-danger ml-2">*</span></label>
                            {!! Form::text('address', null, array('placeholder' => 'Address','class' => 'form-control')) !!}
                        </div>
                    </div>
                    <div class="form-group row d-flex align-items-center mt-5">
                        <div class="col-lg-12 d-flex justify-content-center">
                            <button type="submit" class="btn btn-primary btn-lg">Save</button>
                        </div>
                    </div>
                {!! Form::close() !!}
            </div>

        </div>
    </div>
</div>
<!-- End Row -->
@endsection

@push('scripts')
    <script>
        (function($) {
            $('form').on('submit', function() {
                $('button[type="submit"]').prop('disabled', true).text('Submitting...');
            });
        })(jQuery);
    </script>
@endpush