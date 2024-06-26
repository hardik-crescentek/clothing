@extends('layouts.master')
@section('title', 'Suppliers')
@section('content')
<!-- Begin Page Header-->
<div class="row">
    <div class="page-header">
        <div class="d-flex align-items-center">
            <h2 class="page-header-title">Suppliers</h2>
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
                <h4>Add Supplier</h4>
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


                {!! Form::open(array('route' => 'supplier.store','method'=>'POST', 'class'=>"form-validate", 'novalidate')) !!}
                <input type="hidden" name="redirectTo" value="{{$redirect}}">
                <div class="form-group row d-flex align-items-center mb-5">
                    <label class="col-lg-3 form-control-label d-flex justify-content-lg-end">Name</label>
                    <div class="col-lg-5">
                        {!! Form::text('name', null, array('placeholder' => 'Name','class' => 'form-control', 'data-validation'=>"required")) !!}
                    </div>
                </div>
                <div class="form-group row d-flex align-items-center mb-5">
                    <label class="col-lg-3 form-control-label d-flex justify-content-lg-end">Contact person</label>
                    <div class="col-lg-5">
                        {!! Form::text('contact_person', null, array('placeholder' => 'Contact person','class' => 'form-control')) !!}
                    </div>
                </div>
                <div class="form-group row d-flex align-items-center mb-5">
                    <label class="col-lg-3 form-control-label d-flex justify-content-lg-end">Email</label>
                    <div class="col-lg-5">
                        {!! Form::text('email', null, array('placeholder' => 'Email','class' => 'form-control')) !!}
                    </div>
                </div>
                <div class="form-group row d-flex align-items-center mb-5">
                    <label class="col-lg-3 form-control-label d-flex justify-content-lg-end">Mobile number</label>
                    <div class="col-lg-5">
                        {!! Form::text('mobile_no', null, array('placeholder' => 'Mobile number','class' => 'form-control')) !!}
                    </div>
                </div>
                <div class="form-group row d-flex align-items-center mb-5">
                    <label class="col-lg-3 form-control-label d-flex justify-content-lg-end">Alternate number</label>
                    <div class="col-lg-5">
                        {!! Form::text('alternate_no', null, array('placeholder' => 'Alternate number','class' => 'form-control')) !!}
                    </div>
                </div>
                <div class="form-group row d-flex align-items-center mb-5">
                    <label class="col-lg-3 form-control-label d-flex justify-content-lg-end">Office number</label>
                    <div class="col-lg-5">
                        {!! Form::text('office_no', null, array('placeholder' => 'Office number','class' => 'form-control')) !!}
                    </div>
                </div>
                <div class="form-group row d-flex align-items-center mb-5">
                    <label class="col-lg-3 form-control-label d-flex justify-content-lg-end">Address</label>
                    <div class="col-lg-5">
                        {!! Form::text('address', null, array('placeholder' => 'Address','class' => 'form-control')) !!}
                    </div>
                </div>
                <div class="form-group row d-flex align-items-center mb-5">
                    <label class="col-lg-3 form-control-label d-flex justify-content-lg-end">City</label>
                    <div class="col-lg-5">
                        {!! Form::text('city', null, array('placeholder' => 'City','class' => 'form-control')) !!}
                    </div>
                </div>
                <div class="form-group row d-flex align-items-center mb-5">
                    <label class="col-lg-3 form-control-label d-flex justify-content-lg-end">GST Number</label>
                    <div class="col-lg-5">
                        {!! Form::text('gst_no', null, array('placeholder' => 'GST Number','class' => 'form-control')) !!}
                    </div>
                </div>
                <div class="form-group row d-flex align-items-center mb-5">
                    <label class="col-lg-3 form-control-label d-flex justify-content-lg-end">Transport Name</label>
                    <div class="col-lg-5">
                        {!! Form::text('transport_name', null, array('placeholder' => 'Transport Name','class' => 'form-control')) !!}
                    </div>
                </div>
                <div class="form-group row d-flex align-items-center mb-5">
                    <label class="col-lg-3 form-control-label d-flex justify-content-lg-end">More information</label>
                    <div class="col-lg-5">
                        {!! Form::textarea('info', null, array('placeholder' => 'More information','class' => 'form-control', 'rows' => 3)) !!}
                    </div>
                </div>
                <div class="form-group row d-flex align-items-center mb-5">
                    <label class="col-lg-3 form-control-label d-flex justify-content-lg-end">Supplier Type</label>
                    <div class="col-lg-5">
                        {!! Form::select('supplier_type', ['domestic'=>'Domestic', 'international'=>'International'],null, array('id'=>'currency_type','class' => 'form-control custom-select', 'data-validation'=>"required")) !!}
                    </div>
                </div>
                <div class="form-group row d-flex align-items-center mb-5">
                    <label class="col-lg-3 form-control-label d-flex justify-content-lg-end">Currency Type</label>
                    <div class="col-lg-5">
                        {!! Form::select('currency_type',[''=>'Select Currency','USD'=>'USD','EUR'=>'EUR','CNY'=>'CNY','THB'=>'THB','INR'=>'INR'],null, array('id'=>'currency_type','class' => 'form-control custom-select', 'data-validation'=>"required")) !!}
                    </div>
                </div>
                <div class="form-group row d-flex align-items-center mb-5">
                    <div class="col-lg-5 offset-lg-3">
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