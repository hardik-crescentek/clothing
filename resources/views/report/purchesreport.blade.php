@extends('layouts.master')
@section('title', 'Purchase')
@section('content')
<!-- Begin Page Header-->
<!-- <div class="row">
    <div class="page-header">
        <div class="d-flex align-items-center">
            <h2 class="page-header-title">Users</h2>
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
            <div class="widget-header bordered no-actions1 d-block align-items-center">
                Filter
            </div>
            <div class="widget-body">
                {!! Form::open(['method' => 'GET','route' => ['report.purches']]) !!}
                <div class="form-group row d-flex align-items-center mt-3">
                    <div class="col-lg-2">
                        <label class="form-control-label">From Date &nbsp;</label>
                        {!! Form::text('from_date','', array('class' => 'form-control','id'=>"from_date")) !!}
                    </div>
                    <div class="col-lg-2">
                        <label class="form-control-label">To Date &nbsp;</label>
                        {!! Form::text('to_date','', array('class' => 'form-control','id'=>"to_date")) !!}
                    </div>

                    <div class="col-lg-2">
                        <label class="form-control-label">&nbsp;</label>
                        <div class="form-action">
                            <a href="{{ route('report.purches') }}" class="btn btn-warning btn-square">Reset</a>
                            <input type="submit" name="filter" class="btn btn-primary btn-square" value="Filter">
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
                    <table class="table table-hover mb-0 " id="purchase_tbl">
                        <thead>
                            <tr>
                                <th>Invoice No</th>
                                <th class="sorter-shortDate dateFormat-ddmmyyyy">Date</th>
                                <th data-sorter="false">Qty</th>
                                <th data-sorter="false">Total Cost</th>
                                <th data-sorter="false">Cost/meter</th>
                                <th data-sorter="false">Supplier</th>
                                <th data-sorter="false">Payment Terms</th>
                                <th data-sorter="false">Purchase Type</th>
                            </tr>
                        </thead>
                        <tbody>
                            @isset($purches)
                            @foreach ($purches as $key => $purchase)
                            <tr class="purchase-link" data-id="{{$purchase->id}}">
                                <td>{{ $purchase->invoice_no }}</td>
                                <td>{{ $purchase->purchase_date }}</td>
                                <td>{{ $purchase->total_qty }}</td>
                                <td>
                                    {{$purchase->currency_of_purchase}}: {{ total_cost($purchase->price, $purchase->total_qty, $purchase->shipping_cost_per_meter) }}
                                    <br>
                                    THB: {{ total_cost($purchase->price_thb, $purchase->total_qty, $purchase->shipping_cost_per_meter) }}
                                </td>
                                <td>
                                    {{$purchase->currency_of_purchase}}: {{ total_per_meter($purchase->price, $purchase->shipping_cost_per_meter) }}
                                    <br>
                                    THB: {{ total_per_meter($purchase->price_thb, $purchase->shipping_cost_per_meter)}}
                                </td>
                                <td>{{ $purchase->supplier->name }}</td>
                                <td>{{ $purchase->payment_terms }}</td>
                                <td>{{ $purchase->purchase_type }}</td>
                            </tr>
                            @endforeach
                            @endisset
                        </tbody>
                    </table>
                    @isset($purches)
                    {{ $purches->render() }}
                    @endisset
                    
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
@push('after-styles')
<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/css/select2.min.css" rel="stylesheet" />
<style>
    .selection{
        display:block !important;
    }
</style>
@endpush
@push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/js/select2.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.tablesorter/2.31.3/js/jquery.tablesorter.min.js" integrity="sha512-qzgd5cYSZcosqpzpn7zF2ZId8f/8CHmFKZ8j7mU4OUXTNRd5g+ZHBPsgKEwoqxCtdQvExE5LprwwPAgoicguNg==" crossorigin="anonymous"></script>
<script src="{{ asset('assets/js/datepicker/moment.min.js') }}"></script>
<script src="{{ asset('assets/js/datepicker/daterangepicker.js') }}"></script>
    <script>
        $(document).ready(function(){
            $('#purchase_tbl').tablesorter({
                cssAsc: 'up',
		        cssDesc: 'down',
                cssNone: 'both'
            });
            $('#to_date').daterangepicker({
                // autoUpdateInput: false,
                singleDatePicker: true,
                showDropdowns: true,
                locale: {
                    format: 'DD/MM/YYYY',
                }
            });
            $('#from_date').daterangepicker({
                autoUpdateInput: false,
                singleDatePicker: true,
                showDropdowns: true,
                locale: {
                    format: 'DD/MM/YYYY',
                }
            },function(chosen_date) {
                $('#from_date').val(chosen_date.format('DD/MM/YYYY'));
            });
            
            
        })
    </script>
@endpush