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
            <div class="widget-header bordered no-actions1 d-block align-items-center">
                Filter
            </div>
            <div class="widget-body">
                {!! Form::open(['method' => 'GET','route' => ['supplier.index']]) !!}
                <div class="form-group row d-flex align-items-center mt-3">
                    <div class="col-lg-3">
                        <label class="form-control-label">Search <div class="d-inline text-muted" style="font-size: 10px;">[User Name/Mobile/E-mail]</div></label>
                        {!! Form::text('search', '', array('class' => 'form-control')) !!}
                    </div>
                    <div class="col-lg-3">
                        <label class="form-control-label">&nbsp;</label>
                        <div class="form-action">
                            <input type="submit" class="btn btn-primary btn-square" value="Filter">
                        </div>
                    </div>
                </div>
                {!! Form::close() !!}
            </div>
        </div>
    </div>
</div>
<!-- Begin Row -->
<div class="row flex-row">
    <div class="col-xl-12 col-12">
        <div class="widget has-shadow">
            <div class="widget-body">
                <div class="table-responsive">
                    <table class="table table-hover mb-0 " id="supplier_tbl">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Mobile</th>
                                <th data-sorter="false">GST</th>
                                <th>Office No.</th>
                                <th data-sorter="false">Address</th>
                                <th>City</th>
                                <th data-sorter="false" width="130px">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @isset($suppliers)
                            @foreach ($suppliers as $key => $supplier)
                            <tr>
                                <td>{{ $supplier->name }}</td>
                                <td>{{ $supplier->email }}</td>
                                <td>{{ $supplier->mobile_no }}</td>
                                <td>{{ $supplier->gst_no }}</td>
                                <td>{{ $supplier->office_no }}</td>
                                <td>{{ $supplier->address }}</td>
                                <td>{{ $supplier->city }}</td>
                                <td class="td-actions">
                                    <a class="btn btn-primary btn-sm btn-square col-sm-7" href="{{ route('supplier.edit',$supplier->id) }}">Edit</a>
                                    {!! Form::open(['method' => 'DELETE','route' => ['supplier.destroy', $supplier->id],'style'=>'display:inline', 'onsubmit'=>'return delete_confirm()']) !!}
                                    {!! Form::submit('Delete', ['class' => 'btn btn-danger btn-sm btn-square col-sm-7 mt-1']) !!}
                                    {!! Form::close() !!}
                                </td>
                            </tr>
                            @endforeach
                            @endisset
                        </tbody>
                    </table>
                    @isset($suppliers)
                    {{ $suppliers->render() }}
                    @endisset

                </div>
            </div>
        </div>
    </div>
</div>
<script>
    function delete_confirm() {
        return confirm("Are you sure want to delete?");
    }
</script>
<!-- End Row -->
@endsection
@push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.tablesorter/2.31.3/js/jquery.tablesorter.min.js" integrity="sha512-qzgd5cYSZcosqpzpn7zF2ZId8f/8CHmFKZ8j7mU4OUXTNRd5g+ZHBPsgKEwoqxCtdQvExE5LprwwPAgoicguNg==" crossorigin="anonymous"></script>
    <script>
        $(document).ready(function(){
            $('#supplier_tbl').tablesorter({
                cssAsc: 'up',
		        cssDesc: 'down',
                cssNone: 'both'
            });
        })
    </script>
@endpush
