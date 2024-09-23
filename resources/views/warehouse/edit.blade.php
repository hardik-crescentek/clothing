@extends('layouts.master')
@section('title', 'Edit Warehouse')
@section('content')

@if ($message = Session::get('success'))
    <div class="alert alert-success">
        {{ $message }}
    </div>
@endif

<div class="row">
    <div class="col-xl-12 col-12">
        <div class="widget has-shadow">
            <div class="widget-header bordered no-actions d-flex align-items-center">
                <h4>Edit WareHouse</h4>
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
                <form action="{{ route('warehouse.update', $warehouse->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="row">
                        <div class="form-group col-lg-3">
                            <label for="name">Warehouse Name<span class="text-danger ml-2">*</span></label>
                            <input type="text" class="form-control" id="name" name="name" value="{{ old('name', $warehouse->name) }}" required>
                        </div>

                        <div class="form-group col-lg-3">
                            <label for="address">Address<span class="text-danger ml-2">*</span></label>
                            <input type="text" class="form-control" id="address" name="address" value="{{ old('address', $warehouse->address) }}" required>
                        </div>
                    </div>

                    <div class="form-group row d-flex align-items-center mt-5">
                        <div class="col-lg-12 d-flex justify-content-center">
                            <button type="submit" class="btn btn-primary btn-lg">Update</button>
                            <a class="btn btn-secondary btn-lg ml-1" href="{{ route('warehouse.index') }}"> Cancel</a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
