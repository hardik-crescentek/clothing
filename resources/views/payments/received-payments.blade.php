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
                {!! Form::open(['method' => 'GET','route' => ['payments.received-payments']]) !!}
                <div class="form-group row d-flex align-items-center mt-3">
                    <div class="col-lg-2">
                        <label class="form-control-label">From Date &nbsp;</label>
                        {!! Form::text('from_date', $from_date, array('class' => 'form-control','id'=>"from_date")) !!}
                    </div>
                    <div class="col-lg-2">
                        <label class="form-control-label">To Date &nbsp;</label>
                        {!! Form::text('to_date', $to_date, array('class' => 'form-control','id'=>"to_date")) !!}
                    </div>
                    
                    <div class="col-lg-2">
                        <label class="form-control-label">Customer</label>
                        {!! Form::select('customer_id', $users, $selected_customer, array('class' => 'form-control customer_id','id'=>'customer_id','style'=>'width:100%')) !!}
                    </div>
                    <div class="col-lg-2">
                        <label class="form-control-label">&nbsp;</label>
                        <div class="form-action">
                            <a href="{{ route('payments.received-payments') }}" class="btn btn-warning btn-square">Reset</a>
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
                    <table class="table table-hover mb-0 " id="received_payments_tbl">
                        <thead>
                            <tr>
                                <th>Invoice No</th>
                                <th>Customer Name </th>
                                <th>Payment Receiver Name</th>
                                <th>Payment Type</th>
                                <th data-sorter="false">Amount</th>
                                <th class="sorter-shortDate dateFormat-ddmmyyyy">Payment date</th>
                                <th data-sorter="false">Note</th>
                            </tr>
                        </thead>
                        <tbody>
                            @isset($history)
                                @foreach ($history as $item)
                                    <tr>
                                        <td>{{ $item->invoice->invoice_no }}</td>
                                        <td>{{ $item->invoice->customer['fullName'] }}</td>
                                        <td>{{ $item->invoice->paymentReceiver->fullName }}</td>
                                        <td>{{ $item->payment_type }}</td>
                                        <td>{{ $item->amount }}</td>
                                        <td>{{ $item->payment_date }}</td>
                                        <td>{{ $item->note }}</td>
                                    </tr>
                                @endforeach
                            @endisset
                        </tbody>
                    </table>
                    @isset($history)
                    {{ $history->render() }}   
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
            $('#received_payments_tbl').tablesorter({
                cssAsc: 'up',
		        cssDesc: 'down',
                cssNone: 'both'
            });
            $('#customer_id').select2({
                width: 'resolve',
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