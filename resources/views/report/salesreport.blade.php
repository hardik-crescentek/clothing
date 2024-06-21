@extends('layouts.master')
@section('title', 'Payment')
@section('content')

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
                {!! Form::open(['method' => 'GET','route' => ['report.sales']]) !!}
                <div class="form-group row d-flex align-items-center mt-3">
                    <div class="col-lg-3">
                        <label class="form-control-label">From Date &nbsp;</label>
                        {!! Form::text('from_date','', array('class' => 'form-control','id'=>"from_date")) !!}
                    </div>
                    <div class="col-lg-3">
                        <label class="form-control-label">To Date &nbsp;</label>
                        {!! Form::text('to_date','', array('class' => 'form-control','id'=>"to_date")) !!}
                    </div>

                    <div class="col-lg-3">
                        <label class="form-control-label">&nbsp;</label>
                        <div class="form-action">
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
                    <table class="table table-hover mb-0 " id="sales_report_tbl">
                        <thead>
                            <tr>
                                <th>Order Number</th>
                                <th>Customer Name</th>
                                <th>Saller Name </th>
                                <th class="sorter-shortDate dateFormat-ddmmyyyy">Order Date</th>
                                <th data-sorter="false">Type Of Sale</th>
                                <th>Meter</th>
                                <th>Total Price</th>
                                <th data-sorter="false">Order From</th>
                            </tr>
                        </thead>
                        <tbody>
                            @isset($orderDetails)
                                @foreach ($orderDetails as $order)
                                    <tr>
                                        <td>{!! $order->id !!}</td>
                                        <td>{!! $order->order->customer->fullname !!}</td>
                                        <td>{!! $order->order->seller->fullName !!}</td>
                                        <td>{!! $order->order->order_date!!}</td>
                                        <td>{!! $order->type_of_sale ==='W' ? 'WholeSale' : ($order->type_of_sale === "R" ? 'Retails' : ($order->type_of_sale === "P" ? 'Sample' : ''))!!}</td>
                                        <td>{!! $order->meter!!}</td>
                                        <td>{!! $order->price * $order->meter !!}</td>
                                        <td>{!! $order->is_api == true ? '<i class="la la-mobile" aria-hidden="true"></i>' : '<i class="la la-laptop" aria-hidden="true">' !!}</td>
                                        @if ($order->is_api == true)
                                        
                                    @else
                                        </i>
                                    @endif
                                    </tr>
                                @endforeach
                            @endisset
                        </tbody>
                    </table>
                    @isset($orderDetails)
                    {{ $orderDetails->render() }}   
                    @endisset
                </div>
            </div>
        </div>
    </div>
</div>
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
            $('#sales_report_tbl').tablesorter({
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