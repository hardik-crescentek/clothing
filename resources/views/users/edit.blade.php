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

                {!! Form::model($user, ['method' => 'PATCH','route' => ['users.update', $user->id], 'class'=>"form-validate", 'novalidate']) !!}
                <div class="form-group row d-flex align-items-center mb-5">
                    <label class="col-lg-3 form-control-label d-flex justify-content-lg-end">FirstName</label>
                    <div class="col-lg-5">
                        {!! Form::text('firstname', null, array('placeholder' => 'FirstName','class' => 'form-control', 'data-validation'=>"required")) !!}
                    </div>
                </div>
                <div class="form-group row d-flex align-items-center mb-5">
                    <label class="col-lg-3 form-control-label d-flex justify-content-lg-end">LastName</label>
                    <div class="col-lg-5">
                        {!! Form::text('lastname', null, array('placeholder' => 'LastName','class' => 'form-control', 'data-validation'=>"required")) !!}
                    </div>
                </div>
                <div class="form-group row d-flex align-items-center mb-5">
                    <label class="col-lg-3 form-control-label d-flex justify-content-lg-end">Company Name</label>
                    <div class="col-lg-5">
                        {!! Form::text('company_name', null, array('placeholder' => 'Address','class' => 'form-control', 'data-validation'=>"required")) !!}
                    </div>
                </div>
                <div class="form-group row d-flex align-items-center mb-5">
                    <label class="col-lg-3 form-control-label d-flex justify-content-lg-end">Address</label>
                    <div class="col-lg-5">
                        {!! Form::text('address', null, array('placeholder' => 'Address','class' => 'form-control', 'data-validation'=>"required")) !!}
                    </div>
                </div>
                <div class="form-group row d-flex align-items-center mb-5">
                    <label class="col-lg-3 form-control-label d-flex justify-content-lg-end">City</label>
                    <div class="col-lg-5">
                        {!! Form::text('city', null, array('placeholder' => 'City','class' => 'form-control', 'data-validation'=>"required")) !!}
                    </div>
                </div>
                <div class="form-group row d-flex align-items-center mb-5">
                    <label class="col-lg-3 form-control-label d-flex justify-content-lg-end">State</label>
                    <div class="col-lg-5">
                        {!! Form::text('state', null, array('placeholder' => 'State','class' => 'form-control', 'data-validation'=>"required")) !!}
                    </div>
                </div>
                <div class="form-group row d-flex align-items-center mb-5">
                    <label class="col-lg-3 form-control-label d-flex justify-content-lg-end">Zip</label>
                    <div class="col-lg-5">
                        {!! Form::text('zip', null, array('placeholder' => 'Zip','class' => 'form-control', 'data-validation'=>"required")) !!}
                    </div>
                </div>
                <div class="form-group row d-flex align-items-center mb-5">
                    <label class="col-lg-3 form-control-label d-flex justify-content-lg-end">Nature Of Business</label>
                    <div class="col-lg-5">
                        {!! Form::select('business_nature', $business_nature, $user->business_nature, array('class' => 'form-control', 'id'=>'input_business_nature')) !!}
                    </div>
                </div>
                <div class="form-group row mb-5 row_business_nature_other" style="display: {{ $user->business_nature == 'other' ? 'flex' : 'none' }};">
                    <label class="col-lg-3 form-control-label d-flex justify-content-lg-end mt-2">Other Nature Of Business</label>
                    <div class="col-lg-5">
                        {!! Form::text('business_nature_other', null, array('id'=>'business_nature_other','placeholder' => 'Other Nature Of Business','class' => 'form-control')) !!}
                    </div>
                </div>
                <div class="form-group row d-flex align-items-center mb-5">
                    <label class="col-lg-3 form-control-label d-flex justify-content-lg-end">Date Of Birth</label>
                    <div class="col-lg-5">
                        {!! Form::text('dob', null, array('id'=>'dob','placeholder' => 'Date of Birth','class' => 'form-control', 'data-validation'=>"required")) !!}
                    </div>
                </div>
                <div class="form-group row d-flex align-items-center mb-5">
                    <label class="col-lg-3 form-control-label d-flex justify-content-lg-end">Phone</label>
                    <div class="col-lg-5">
                        {!! Form::text('phone', null, array('placeholder' => 'Phone','class' => 'form-control', 'data-validation'=>"required")) !!}
                    </div>
                </div>
                <div class="form-group row d-flex align-items-center mb-5">
                    <label class="col-lg-3 form-control-label d-flex justify-content-lg-end">Email</label>
                    <div class="col-lg-5">
                        {!! Form::text('email', null, array('placeholder' => 'Email','class' => 'form-control', 'data-validation'=>"required")) !!}
                    </div>
                </div>
                <div class="form-group row d-flex align-items-center mb-5">
                    <label class="col-lg-3 form-control-label d-flex justify-content-lg-end">Skype</label>
                    <div class="col-lg-5 input-group">
                        {!! Form::text('skype', null, array('placeholder' => 'Skype','class' => 'form-control')) !!}
                        <span class="input-group-addon addon-secondary"><i class="la la-skype" aria-hidden="true"></i></span>
                    </div>
                </div>
                <div class="form-group row d-flex align-items-center mb-5">
                    <label class="col-lg-3 form-control-label d-flex justify-content-lg-end">Facebook</label>
                    <div class="col-lg-5 input-group">
                        {!! Form::text('facebook', null, array('placeholder' => 'Facebook','class' => 'form-control')) !!}
                        <span class="input-group-addon addon-secondary"><i class="la la-facebook" aria-hidden="true"></i></span>
                    </div>
                </div>
                <div class="form-group row d-flex align-items-center mb-5">
                    <label class="col-lg-3 form-control-label d-flex justify-content-lg-end">Pinterest</label>
                    <div class="col-lg-5 input-group">
                        {!! Form::text('pinterest', null, array('placeholder' => 'Pinterest','class' => 'form-control')) !!}
                        <span class="input-group-addon addon-secondary"><i class="la la-pinterest" aria-hidden="true"></i></span>
                    </div>
                </div>
                <div class="form-group row d-flex align-items-center mb-5">
                    <label class="col-lg-3 form-control-label d-flex justify-content-lg-end">WeChat</label>
                    <div class="col-lg-5 input-group">
                        {!! Form::text('wechat', null, array('placeholder' => 'WeChat','class' => 'form-control')) !!}
                        <span class="input-group-addon addon-secondary"><i class="la la-wechat" aria-hidden="true"></i></span>
                    </div>
                </div>
                <div class="form-group row d-flex align-items-center mb-5">
                    <label class="col-lg-3 form-control-label d-flex justify-content-lg-end">Whatsapp</label>
                    <div class="col-lg-5 input-group">
                        {!! Form::text('whatsapp', null, array('placeholder' => 'Whatsapp','class' => 'form-control')) !!}
                        <span class="input-group-addon addon-secondary"><i class="la la-whatsapp" aria-hidden="true"></i></span>
                    </div>
                </div>
                <div class="form-group row d-flex align-items-center mb-5">
                    <label class="col-lg-3 form-control-label d-flex justify-content-lg-end">Line</label>
                    <div class="col-lg-5 input-group">
                        {!! Form::text('line', null, array('placeholder' => 'Line','class' => 'form-control')) !!}
                        {{-- <span class="input-group-addon addon-secondary"><i class="la la-line"></i></span> --}}
                    </div>
                </div>
                <div class="form-group row d-flex align-items-center mb-5">
                    <label class="col-lg-3 form-control-label d-flex justify-content-lg-end">Password</label>
                    <div class="col-lg-5">
                        {!! Form::password('password', array('placeholder' => 'Password','class' => 'form-control')) !!}
                        <small>Leave Empty if you don't want to change it.</small>
                    </div>
                </div>

                <div class="form-group row d-flex align-items-center mb-5">
                    <label class="col-lg-3 form-control-label d-flex justify-content-lg-end">Role</label>
                    <div class="col-lg-5">
                        {!! Form::select('role', $roles,$userRole, array('class' => 'form-control custom-select','data-validation'=>"required")) !!}
                    </div>
                </div>
                <div class="form-group row d-flex align-items-center mb-5" style="display: none !important;" id='commission_div'>
                    <label class="col-lg-3 form-control-label d-flex justify-content-lg-end">Salesman Commission</label>
                    <div class="col-lg-5">
                        {!! Form::text('salesman_commission', null, array('id'=>'salesman_commission','class' => 'form-control')) !!}
                    </div>
                </div>
                <div class="form-group row d-flex align-items-center mb-5">
                    <label class="col-lg-3 form-control-label d-flex justify-content-lg-end">Joining Date</label>
                    <div class="col-lg-5">
                        {!! Form::text('joining_date', null, array('id'=>'joining_date','class' => 'form-control')) !!}
                    </div>
                </div>
                <div class="form-group row d-flex align-items-center mb-5">
                    <label class="col-lg-3 form-control-label d-flex justify-content-lg-end"></label>
                    <div class="col-lg-5">
                        <div class="form-check">
                            {!! Form::checkbox('newsletter', 1, null ,array('class' => 'form-check-input', 'id' => 'newsletter')) !!}
                            <label for="newsletter" class="form-check-label" for="printWidth">Recive update on new arrivals</label>
                        </div>
                    </div>
                </div>
                <div class="form-group row d-flex align-items-center mb-5">
                    <div class="col-lg-5 offset-lg-3">
                        <button type="submit" class="btn btn-primary btn-lg">Save</button>
                        <a class="btn btn-secondary btn-lg" href="{{ route('users.index') }}"> Cancel</a>
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
        $(document).ready(function() {
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
            $(document).on('change', '.roll', function() {
                if ($(this).val() == "sales-person") {
                    $('#commission_div').css('display', 'block');
                    $('#commission_div').addClass('d-flex');
                    $('#commission_div #salesman_commission').attr('data-validation', 'required');
                } else {
                    $('#commission_div').css('display', 'none');
                    $('#commission_div').removeClass('d-flex');
                    $('#commission_div #salesman_commission').attr('data-validation', 'none');
                }
            })
            $(document).on('change', '#input_business_nature', function() {
                if ($(this).val() == "other") {
                    $('.row_business_nature_other').show();
                } else {
                    $('.row_business_nature_other').hide();
                }
            })
        })
    })(jQuery);
</script>
@endpush