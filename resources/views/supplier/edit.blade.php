@extends('layouts.master')
@section('title', 'Users')
@section('content')
<!-- Begin Page Header-->
<!-- <div class="row">
    <div class="page-header">
        <div class="d-flex align-items-center">
            <h2 class="page-header-title">Edit User</h2>
        </div>
    </div>
</div> -->
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
                <h4>Edit User</h4>                
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

                {!! Form::model($supplier, ['method' => 'PATCH','route' => ['supplier.update', $supplier->id], 'class'=>"form-validate", 'novalidate']) !!}
                <div class="row">
                    <div class="form-group col-lg-3">
                        <label class="form-control-label d-flex">Name<span class="text-danger ml-2">*</span></label>
                        {!! Form::text('name', null, array('placeholder' => 'Name','class' => 'form-control', 'data-validation'=>"required")) !!}
                    </div>
                    <div class="form-group col-lg-3">
                        <label class="form-control-label d-flex">Contact person</label>
                        {!! Form::text('contact_person', null, array('placeholder' => 'Contact person','class' => 'form-control')) !!}
                    </div>
                    <div class="form-group col-lg-3">
                        <label class="form-control-label d-flex">Email</label>
                        {!! Form::text('email', null, array('placeholder' => 'Email','class' => 'form-control')) !!}
                    </div>
                    <div class="form-group col-lg-3">
                        <label class="form-control-label d-flex">Mobile number</label>
                        {!! Form::text('mobile_no', null, array('placeholder' => 'Mobile number','class' => 'form-control')) !!}
                    </div>
                </div>
                <div class="row">
                    <div class="form-group col-lg-3">
                        <label class="form-control-label d-flex">Alternate number</label>
                        {!! Form::text('alternate_no', null, array('placeholder' => 'Alternate number','class' => 'form-control')) !!}
                    </div>
                    <div class="form-group col-lg-3">
                        <label class="form-control-label d-flex">Office number</label>
                        {!! Form::text('office_no', null, array('placeholder' => 'Office number','class' => 'form-control')) !!}
                    </div>
                    <div class="form-group col-lg-3">
                        <label class="form-control-label d-flex">Address</label>
                        {!! Form::text('address', null, array('placeholder' => 'Address','class' => 'form-control')) !!}
                    </div>
                    <div class="form-group col-lg-3">
                        <label class="form-control-label d-flex">City</label>
                        {!! Form::text('city', null, array('placeholder' => 'City','class' => 'form-control')) !!}
                    </div>
                </div>
                <div class="row">
                    <div class="form-group col-lg-3">
                        <label class="form-control-label d-flex">GST Number</label>
                        {!! Form::text('gst_no', null, array('placeholder' => 'GST Number','class' => 'form-control')) !!}
                    </div>
                    <div class="form-group col-lg-3">
                        <label class="form-control-label d-flex">Transport Name</label>
                        {!! Form::text('transport_name', null, array('placeholder' => 'Transport Name','class' => 'form-control')) !!}
                    </div>
                    <div class="form-group col-lg-3">
                        <label class="form-control-label d-flex">More information</label>
                        {!! Form::textarea('info', null, array('placeholder' => 'More information','class' => 'form-control', 'rows' => 3)) !!}
                    </div>
                    <div class="form-group col-lg-3">
                        <label class="form-control-label d-flex">Supplier Type<span class="text-danger ml-2">*</span></label>
                        {!! Form::select('supplier_type', ['domestic'=>'Domestic', 'international'=>'International'],null, array('id'=>'currency_type','class' => 'form-control custom-select', 'data-validation'=>"required")) !!}
                    </div>
                </div>
                <div class="row">
                    <div class="form-group col-lg-3">
                        <label class="form-control-label d-flex">Currency Type<span class="text-danger ml-2">*</span></label>
                        {!! Form::select('currency_type',[''=>'Select Currency','USD'=>'USD','EUR'=>'EUR','CNY'=>'CNY','THB'=>'THB','INR'=>'INR'],null, array('id'=>'currency_type','class' => 'form-control custom-select', 'data-validation'=>"required")) !!}
                    </div>
                </div>
                <div class="form-group row d-flex align-items-center mt-5">
                    <div class="col-lg-12 d-flex justify-content-center">
                        <button type="submit" class="btn btn-primary btn-lg">Update</button>
                        <a class="btn btn-secondary btn-lg ml-1" href="{{ route('supplier.index') }}"> Cancel</a>
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
<script src="{{ asset('assets/js/datepicker/moment.min.js') }}"></script>
<script src="{{ asset('assets/js/datepicker/daterangepicker.js') }}"></script>
<script>
    (function($) {
        $('#dob').daterangepicker({
            singleDatePicker: true,
            showDropdowns: true,
            locale: {
                format: 'DD/MM/YYYY'
            }
        });
        $('#joining_date').daterangepicker({
            singleDatePicker: true,
            showDropdowns: true,
            locale: {
                format: 'DD/MM/YYYY'
            }
        });
    })(jQuery);
</script>
@endpush