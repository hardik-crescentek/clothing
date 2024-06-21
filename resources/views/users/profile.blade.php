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
<div class="row">
    <div class="col-md-12 col-sm-12">
        <div class="widget has-shadow">
            <div class="widget-header bordered no-actions d-flex align-items-center">
                <h4>Profile</h4>
            </div>
            <div class="widget-body">
                @if (count($errors->profile) > 0)
                <div class="alert alert-danger">
                    <strong>Whoops!</strong> There were some problems with your input.<br><br>
                    <ul>
                        @foreach ($errors->profile->all() as $error)
                        <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
                @endif
                {!! Form::model($user, ['method' => 'PUT','route' => 'profile.update', 'class'=>"form-validate", 'novalidate', 'files' => true]) !!}
                <div class="form-group row d-flex align-items-center mb-5">
                    <label class="col-lg-3 form-control-label d-flex justify-content-lg-end">Email</label>
                    <div class="col-lg-8">
                        <div class="form-control" readonly>{{ $user->email }}</div>
                    </div>
                </div>
                <div class="form-group row d-flex align-items-center mb-5">
                    <label class="col-lg-3 form-control-label d-flex justify-content-lg-end">FirstName</label>
                    <div class="col-lg-8">
                        {!! Form::text('firstname', null, array('placeholder' => 'FirstName','class' => 'form-control', 'data-validation'=>"required")) !!}
                    </div>
                </div>
                <div class="form-group row d-flex align-items-center mb-5">
                    <label class="col-lg-3 form-control-label d-flex justify-content-lg-end">LastName</label>
                    <div class="col-lg-8">
                        {!! Form::text('lastname', null, array('placeholder' => 'LastName','class' => 'form-control', 'data-validation'=>"required")) !!}
                    </div>
                </div>
                <div class="form-group row d-flex align-items-center mb-5">
                    <label class="col-lg-3 form-control-label d-flex justify-content-lg-end">Address</label>
                    <div class="col-lg-8">
                        {!! Form::text('address', null, array('placeholder' => 'Address','class' => 'form-control', 'data-validation'=>"required")) !!}
                    </div>
                </div>
                <div class="form-group row d-flex align-items-center mb-5">
                    <label class="col-lg-3 form-control-label d-flex justify-content-lg-end">City</label>
                    <div class="col-lg-8">
                        {!! Form::text('city', null, array('placeholder' => 'City','class' => 'form-control', 'data-validation'=>"required")) !!}
                    </div>
                </div>
                <div class="form-group row d-flex align-items-center mb-5">
                    <label class="col-lg-3 form-control-label d-flex justify-content-lg-end">State</label>
                    <div class="col-lg-8">
                        {!! Form::text('state', null, array('placeholder' => 'State','class' => 'form-control', 'data-validation'=>"required")) !!}
                    </div>
                </div>
                <div class="form-group row d-flex align-items-center mb-5">
                    <label class="col-lg-3 form-control-label d-flex justify-content-lg-end">Zip</label>
                    <div class="col-lg-8">
                        {!! Form::text('zip', null, array('placeholder' => 'Zip','class' => 'form-control', 'data-validation'=>"required")) !!}
                    </div>
                </div>
                <div class="form-group row d-flex align-items-center mb-5">
                    <label class="col-lg-3 form-control-label d-flex justify-content-lg-end">Date Of Birth</label>
                    <div class="col-lg-8">
                        {!! Form::text('dob', null, array('id'=>'dob','placeholder' => 'Date of Birth','class' => 'form-control', 'data-validation'=>"required")) !!}
                    </div>
                </div>
                <div class="form-group row d-flex align-items-center mb-5">
                    <label class="col-lg-3 form-control-label d-flex justify-content-lg-end">Phone</label>
                    <div class="col-lg-8">
                        {!! Form::text('phone', null, array('placeholder' => 'Phone','class' => 'form-control', 'data-validation'=>"required")) !!}
                    </div>
                </div>
                <div class="form-group row d-flex align-items-center mb-5">
                    <label class="col-lg-3 form-control-label d-flex justify-content-lg-end">Image</label>
                    <div class="col-lg-6">
                        {!! Form::file('avatar', ['id' => 'upload_avatar', 'accept' => 'image/*',
                        'data-validation'=>"mime",
                        'data-validation-allowing'=>"jpeg, jpg, png, gif",
                        'data-validation-error-msg-mime'=>"You can only upload images"]); !!}
                        <small>
                            <p class="help-block">Only .jpeg, .jpg, .png, .gif file can be uploaded. Maximum image size 5MB</p>
                        </small>
                    </div>
                    <div class="col-lg-3">
                        <img src="{{ img_url($user->avatar) }}" class="listing-thumb img-thumbnail" alt="" />
                    </div>

                </div>
                <div class="form-group row d-flex align-items-center mb-5">
                    <div class="col-lg-8 offset-lg-3">
                        <button type="submit" class="btn btn-primary btn-lg">Save</button>
                    </div>
                </div>
                {!! Form::close() !!}
            </div>

        </div>
    </div>
    <div class="col-md-6 col-sm-12">
        <div class="widget has-shadow">
            <div class="widget-header bordered no-actions d-flex align-items-center">
                <h4>Change Password</h4>
            </div>
            <div class="widget-body">
                @if ($error = Session::get('error'))
                <div class="alert alert-danger">
                    {{ $error }}
                </div>
                @endif
                @if (count($errors->password) > 0)
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->password->all() as $error)
                        <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
                @endif

                {!! Form::open(['method' => 'PUT','route' => 'profile.changePassword', 'class'=>"form-validate", 'novalidate']) !!}
                <div class="form-group">
                    <label class="form-control-label">Current Password</label>
                    {!! Form::password('current_pass', array('class' => 'form-control', 'data-validation'=>"required")) !!}
                </div>
                <div class="form-group">
                    <label class="form-control-label">New Password</label>
                    {!! Form::password('newpassword', array('class' => 'form-control', 'data-validation'=>"required")) !!}
                </div>
                <div class="form-group">
                    <label class="form-control-label">Confirm Password</label>
                    {!! Form::password('newpassword_confirmation', array('class' => 'form-control', 'data-validation'=>"required")) !!}
                </div>
                <div class="form-group mt-5">
                    <button type="submit" class="btn btn-primary btn-lg">Change</button>
                </div>
                {!! Form::close() !!}
            </div>

        </div>
    </div>
    @role('super-admin')
    <div class="col-md-6 col-sm-12">
        <div class="widget has-shadow">
            <div class="widget-header bordered no-actions d-flex align-items-center">
                <h4>Settings</h4>
            </div>
            <div class="widget-body">
                @if ($error = Session::get('error'))
                <div class="alert alert-danger">
                    {{ $error }}
                </div>
                @endif
                @if (count($errors->password) > 0)
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->password->all() as $error)
                        <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
                @endif

                {!! Form::open(['method' => 'POST','route' => 'setting.update', 'class'=>"form-validate", 'novalidate']) !!}
                <div class="form-group">
                    <label class="form-control-label">Material Re Order Alert QTY</label>
                    <input type="text" name="material_min_alert_qty" class="form-control" data-validation="required" value="{{ $settings->material_min_alert_qty }}">
                </div>
                <div class="form-group mt-5">
                    <button type="submit" class="btn btn-primary btn-lg">Update</button>
                </div>
                {!! Form::close() !!}
            </div>
        </div>
    </div>
    @endrole
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
    })(jQuery);
</script>
@endpush