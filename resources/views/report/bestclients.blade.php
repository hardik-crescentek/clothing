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
            <div class="widget-body">
                <div class="table-responsive">
                    <table class="table table-hover mb-0 " id="received_payments_tbl">
                        <thead>
                            <tr>
                                    <th>
                                        @isset($orderDetails) Total Order Cost @endisset
                                        @isset($orderDetailsByTopOrder) Total Orders @endisset
                                    </th>
                                    <th>Customer Name</th>
                                    <th>Customer Email</th>
                                    <th>Customer Phone</th>
                                    <th>Customer Address</th>
                            </tr>
                        </thead>
                        <tbody>
                            @isset($orderDetails)
                                @foreach ($orderDetails as $byPrice)
                                    <tr>
                                        <td >{!! number_format($byPrice->total_price,2,".","") !!}</td>
                                        <td>{!! $byPrice->order->customer->fullname !!}</td>
                                        <td>{!! $byPrice->order->customer->email !!}</td>
                                        <td>{!! $byPrice->order->customer->phone."<br>".$byPrice->order->customer->phone2 !!}</td>
                                        <td>{!! $byPrice->order->customer->address."<br>".$byPrice->order->customer->city."<br>".$byPrice->order->customer->state."<br>".$byPrice->order->customer->country !!}</td>
                                    </tr>
                                @endforeach
                            @endisset
                            @isset($orderDetailsByTopOrder)
                                @foreach ($orderDetailsByTopOrder as $byOrder)
                                <tr>
                                    <td >{!! $byOrder->total_order !!}</td>
                                    <td>{!! $byOrder->customer->fullname !!}</td>
                                    <td>{!! $byOrder->customer->email !!}</td>
                                    <td>{!! $byOrder->customer->phone."<br>".$byOrder->customer->phone2 !!}</td>
                                    <td>{!! $byOrder->customer->address."<br>".$byOrder->customer->city."<br>".$byOrder->customer->state."<br>".$byOrder->customer->country !!}</td>
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
            $("#total_cost").text().toFixed(3);
            
        })
    </script>
@endpush