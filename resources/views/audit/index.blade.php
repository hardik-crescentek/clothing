@extends('layouts.master')
@section('title', 'Audit')
@section('content')

<!-- Begin Page Header-->
<div class="row">
    <div class="page-header">
        <div class="d-flex align-items-center">
            <h2 class="page-header-title">Audit</h2>
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
<!-- <div class="row flex-row">
    <div class="col-xl-12 col-12">
        <div class="widget has-shadow">
            <div class="widget-header bordered no-actions1 d-block align-items-center">
                Filter
            </div>
            <div class="widget-body">
                {!! Form::open(['method' => 'GET','route' => ['audit']]) !!}
                <div class="form-group row d-flex align-items-center mt-3">
                    <div class="col-lg-2">
                        <label class="form-control-label col-lg-12">Search <div class="d-inline text-muted" style="font-size: 10px;">[barcode]</div></label>
                        {!! Form::text('search', '', array('class' => 'form-control')) !!}
                    </div>
                    <div class="col-lg-2">
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
</div> -->
<!-- Begin Row -->
<!-- Begin Row -->
<div class="row flex-row">
    <div class="col-xl-12 col-12">
        <div class="widget has-shadow">
            <div class="widget-body">
                <div class="table-responsive">
                    <table class="table table-hover mb-0 " id="order_tbl">
                        <thead>
                            <tr>
                                <th>User Name</th>
                                <th>Barcode</th>
                                <th>total_qty</th>
                                <th>available_qty</th>
                                <th>Date & Time</th>
                                <th>Remark</th>
                            </tr>
                        </thead>
                        <tbody>
                            @isset($audit)

                            @foreach($audit as $val)
                            <tr class="purchase-link" data-id="{{$val->id}}">
                                <td> {{$val->customer->firstname}} {{$val->customer->lastname}} </td>
                                <td> {{$val->barcode}} </td>
                                <td> {{$val->total_qty}} </td>
                                <td> {{$val->available_qty}} </td>
                                <td> {{ date('d-m-Y h:i A',strtotime($val->created_at))}} </td>
                                <td> {{$val->remark}} </td>
                            </tr>
                            @endforeach
                            @endisset
                        </tbody>
                    </table>
                    <!-- @isset($audit)
                    {{ $audit->render() }}
                    @endisset -->

                </div>
            </div>
        </div>
    </div>
</div>
<!-- End Row -->
<script>
    function delete_confirm() {
        return confirm("Are you sure want to delete?");
    }
</script>
@endsection
@push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.tablesorter/2.31.3/js/jquery.tablesorter.min.js" integrity="sha512-qzgd5cYSZcosqpzpn7zF2ZId8f/8CHmFKZ8j7mU4OUXTNRd5g+ZHBPsgKEwoqxCtdQvExE5LprwwPAgoicguNg==" crossorigin="anonymous"></script>
    <script>
        $(document).ready(function(){
            $('#order_tbl').tablesorter({
                // cssAsc: 'up',
		        // cssDesc: 'down',
                // cssNone: 'both'
                lengthMenu: [
                    [10, 25, 50,100,500,1000,'All'],
                    [10, 25, 50,100,500,1000,'All'],
                ],
                "aaSorting": []
            });
        })
    </script>
@endpush
