@extends('layouts.master')
@section('title', 'Warehouses')
@section('content')
<!-- Begin Page Header -->
<div class="row">
    <div class="page-header">
        <div class="d-flex align-items-center">
            <h2 class="page-header-title">Warehouses</h2>
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
            <div class="widget-body">
                <div class="table-responsive">
                    <table class="table table-hover mb-0" id="warehouse_tbl">
                        <thead>
                            <tr>
                                <th>Warehouse Name</th>
                                <th>Address</th>
                                <th data-sorter="false" width="280px">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @isset($warehouses)
                                @foreach ($warehouses as $warehouse)
                                <tr>
                                    <td>{{ $warehouse->name }}</td>
                                    <td>{{ $warehouse->address }}</td>
                                    <td class="td-actions">
                                        <a class="btn fa fa-edit btn-sm btn-primary ml-1" href="{{ route('warehouse.edit', $warehouse->id) }}" data-toggle="tooltip" data-placement="top" title="Edit Warehouse"></a>
                                        {!! Form::open(['method' => 'DELETE', 'route' => ['warehouse.destroy', $warehouse->id], 'style' => 'display:inline', 'onsubmit' => 'return delete_confirm()']) !!}
                                        <button type="submit" class="btn-action btn fa fa-trash btn-sm btn-danger ml-1" data-toggle="tooltip" data-placement="top" title="Delete Warehouse"></button>
                                        {!! Form::close() !!}
                                    </td>
                                </tr>
                                @endforeach
                            @endisset
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    function delete_confirm() {
        return confirm("Are you sure want to delete this warehouse?");
    }
</script>
<!-- End Row -->
@endsection
@push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.tablesorter/2.31.3/js/jquery.tablesorter.min.js" integrity="sha512-qzgd5cYSZcosqpzpn7zF2ZId8f/8CHmFKZ8j7mU4OUXTNRd5g+ZHBPsgKEwoqxCtdQvExE5LprwwPAgoicguNg==" crossorigin="anonymous"></script>
    <script>
        $(document).ready(function(){
            $('#warehouse_tbl').DataTable({
                lengthMenu: [
                    [10, 25, 50,100,500,1000,'All'],
                    [10, 25, 50,100,500,1000,'All'],
                ],
                "aaSorting": []
            });
        })
    </script>
@endpush
