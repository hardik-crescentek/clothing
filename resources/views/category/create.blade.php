@extends('layouts.master')
@section('title', 'Create Category')
@section('content')
<!-- Begin Page Header-->
<div class="row">
    <div class="page-header">
        <div class="d-flex align-items-center">
            <h2 class="page-header-title">Categories</h2>
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
                <h4>Create Category</h4>
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


                {!! Form::open(array('route' => 'category.store','method'=>'POST', 'class'=>"form-validate", 'novalidate')) !!}
                <div class="row">
                    <div class="col-lg-4">
                        <div class="form-group">
                            <label class="form-control-label">Name<span class="text-danger ml-2">*</span></label>
                            {!! Form::text('name', null, array('placeholder' => 'Name','class' => 'form-control', 'data-validation'=>"required")) !!}
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <div class="form-group">
                            <label class="form-control-label">Parent Category</label>
                            {!! Form::select('parent_id', $parent_categories,null, array('class' => 'form-control custom-select')) !!}
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <div class="form-group">
                            <label class="form-control-label">Active</label>
                            <div class="d-flex align-items-center">
                                <div class="styled-radio mr-3">
                                    {!! Form::radio('status', 1, true, ['class' => 'custom-control-input', 'id' => 'status-active']) !!}
                                    <label for="status-active">Active</label>
                                </div>
                                <div class="styled-radio">
                                    {!! Form::radio('status', 0, false, ['class' => 'custom-control-input', 'id' => 'status-inactive']) !!}
                                    <label for="status-inactive">Inactive</label>
                                </div>
                            </div>
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