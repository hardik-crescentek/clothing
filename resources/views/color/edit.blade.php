@extends('layouts.master')
@section('title', 'Edit Color')
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
                <h4>Edit Color</h4>
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

                {!! Form::model($color, ['method' => 'PATCH','route' => ['color.update', $color->id], 'class'=>"form-validate", 'novalidate']) !!}
                <div class="form-group row d-flex align-items-center mb-5">
                    <label class="col-lg-3 form-control-label d-flex justify-content-lg-end">Color Name</label>
                    <div class="col-lg-5">
                        {!! Form::text('name', null, array('placeholder' => 'Color Name','class' => 'form-control', 'data-validation'=>"required")) !!}
                    </div>
                </div>
                <div class="form-group row d-flex align-items-center mb-5">
                    <label class="col-lg-3 form-control-label d-flex justify-content-lg-end">Color Code</label>
                    <div class="col-lg-5">
                        {!! Form::text('code', null, array('placeholder' => '#000000','class' => 'form-control', 'data-validation'=>"required")) !!}
                    </div>
                </div>
                <div class="form-group row d-flex align-items-center mb-5">
                    <label class="col-lg-3 form-control-label d-flex justify-content-lg-end">Active</label>
                    <div class="col-lg-1">
                        <div class="styled-radio">
                            {!! Form::radio('status', 1, true, array('class' => 'custom-control-input', 'id'=>"status-active")) !!}
                            <label for="status-active">Active</label>
                        </div>
                    </div>
                    <div class="col-lg-1">
                        <div class="styled-radio">
                            {!! Form::radio('status', 0, false, array('class' => 'custom-control-input', 'id'=>"status-inactive")) !!}
                            <label for="status-inactive">Inactive</label>
                        </div>
                    </div>
                </div>

                <div class="form-group row d-flex align-items-center mb-5">
                    <div class="col-lg-5 offset-lg-3">
                        <button type="submit" class="btn btn-primary btn-lg">Save</button>
                        <a class="btn btn-secondary btn-lg" href="{{ route('color.index') }}"> Cancel</a>
                    </div>
                </div>
                {!! Form::close() !!}
            </div>



        </div>
    </div>
</div>

<!-- End Row -->
@endsection
