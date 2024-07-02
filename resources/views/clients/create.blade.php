@extends('layouts.master')
@section('title', 'Clients')
@section('content')
<!-- Begin Page Header-->
<!-- <div class="row">
    <div class="page-header">
        <div class="d-flex align-items-center">
            <h2 class="page-header-title">Clients</h2>
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
                <h4>Add Client</h4>
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


                {!! Form::open(array('route' => 'clients.store','method'=>'POST', 'class'=>"form-validate", 'novalidate')) !!}
                <input type="hidden" name="redirectTo" value="{{$redirect}}">
                <div class="row">
                    <div class="form-group col-lg-3">
                        <label class="form-control-label d-flex">Company Name / Shop Name<span class="text-danger ml-2">*</span></label>
                        {!! Form::text('company_name', null, array('id'=>'company_name','class' => 'form-control' , 'data-validation'=>"required")) !!}
                    </div>
                    <div class="form-group col-lg-3">
                        <label class="form-control-label d-flex">Owner FirstName<span class="text-danger ml-2">*</span></label>
                        {!! Form::text('firstname', null, array('placeholder' => 'FirstName','class' => 'form-control', 'data-validation'=>"required")) !!}
                    </div>
                    <div class="form-group col-lg-3">
                        <label class="form-control-label d-flex">LastName<span class="text-danger ml-2">*</span></label>
                        {!! Form::text('lastname', null, array('placeholder' => 'LastName','class' => 'form-control', 'data-validation'=>"required")) !!}
                    </div>
                    <div class="form-group col-lg-3">
                        <label class="form-control-label d-flex">Address<span class="text-danger ml-2">*</span></label>
                        {!! Form::text('address', null, array('placeholder' => 'Address','class' => 'form-control', 'data-validation'=>"required")) !!}
                    </div>
                </div>

                <div class="row">
                    <div class="form-group col-lg-3">
                        <label class="form-control-label d-flex">City<span class="text-danger ml-2">*</span></label>
                        {!! Form::text('city', null, array('placeholder' => 'City','class' => 'form-control', 'data-validation'=>"required")) !!}
                    </div>
                    <div class="form-group col-lg-3">
                        <label class="form-control-label d-flex">State<span class="text-danger ml-2">*</span></label>
                        {!! Form::text('state', null, array('placeholder' => 'State','class' => 'form-control', 'data-validation'=>"required")) !!}
                    </div>
                    <div class="form-group col-lg-3">
                        <label class="form-control-label d-flex">Country<span class="text-danger ml-2">*</span></label>
                        @include('countries', ['default' => null, 'attributes' => array('class' => 'form-control', 'data-validation'=>"required")])
                    </div>
                    <div class="form-group col-lg-3">
                        <label class="form-control-label d-flex">Zip<span class="text-danger ml-2">*</span></label>
                        {!! Form::text('zip', null, array('placeholder' => 'Zip','class' => 'form-control', 'data-validation'=>"required")) !!}
                    </div>
                </div>

                <div class="row">
                    <div class="form-group col-lg-3">
                        <label class="form-control-label d-flex">Nature Of Business</label>
                        {!! Form::select('business_nature', $business_nature,null, array('class' => 'form-control' , 'id' => 'input_business_nature')) !!}
                    </div>
                    <div class="form-group col-lg-3 row_business_nature_other" style="display: none">
                        <label class="form-control-label d-flex">Other Nature Of Business</label>
                        {!! Form::text('business_nature_other', null, array('id'=>'business_nature_other','placeholder' => 'Other Nature Of Business','class' => 'form-control')) !!}
                    </div>
                    <div class="form-group col-lg-3">
                        <label class="form-control-label d-flex">Date Of Birth<span class="text-danger ml-2">*</span></label>
                        {!! Form::text('dob', null, array('id'=>'dob','placeholder' => 'Date of Birth','class' => 'form-control', 'data-validation'=>"required")) !!}
                    </div>
                    <div class="form-group col-lg-3">
                        <label class="form-control-label d-flex">Phone<span class="text-danger ml-2">*</span></label>
                        {!! Form::text('phone', null, array('placeholder' => 'Phone','class' => 'form-control', 'data-validation'=>"required")) !!}
                    </div>
                </div>

                <div class="row">
                    <div class="form-group col-lg-3">
                        <label class="form-control-label d-flex">Email<span class="text-danger ml-2">*</span></label>
                        {!! Form::text('email', null, array('placeholder' => 'Email','class' => 'form-control', 'data-validation'=>"required")) !!}
                    </div>
                    <div class="form-group col-lg-3">
                        <label class="form-control-label d-flex">Password<span class="text-danger ml-2">*</span></label>
                        {!! Form::password('password', array('placeholder' => 'Password','class' => 'form-control', 'data-validation'=>"required")) !!}
                    </div>
                    <div class="form-group col-lg-3">
                        <label class="form-control-label d-flex">Confirm Password<span class="text-danger ml-2">*</span></label>
                        {!! Form::password('password_confirmation', array('placeholder' => 'Confirm Password','class' => 'form-control', 'data-validation'=>"required")) !!}
                    </div>
                    <div class="form-group col-lg-3 d-flex align-items-center">
                        <div class="form-check">
                            {!! Form::checkbox('newsletter', 1, null ,array('class' => 'form-check-input', 'id' => 'newsletter')) !!}
                            <label for="newsletter" class="form-check-label" for="printWidth">Recive update on new arrivals</label>
                        </div>
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
        $(document).on('change','.roll',function(){
            if($(this).val()=="sales-person"){
                $('#commission_div').css('display','block');
                $('#commission_div').addClass('d-flex');
                $('#commission_div #salesman_commission').attr('data-validation','required');
            }
            else{
                $('#commission_div').css('display','none');
                $('#commission_div').removeClass('d-flex');
                $('#commission_div #salesman_commission').attr('data-validation','none');
            }
        })
        $(document).on('change', '#input_business_nature', function() {
                if ($(this).val() == "other") {
                    $('.row_business_nature_other').show();
                } else {
                    $('.row_business_nature_other').hide();
                }
            })
    })(jQuery);
</script>
@endpush