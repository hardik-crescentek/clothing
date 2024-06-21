@extends('layouts.master')
@section('title', 'Invoice')
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
                {!! Form::open(['method' => 'GET','route' => ['invoice.index']]) !!}
                <div class="form-group row d-flex align-items-center mt-3">
                    <div class="col-lg-2">
                        <label class="form-control-label">Search &nbsp;</label><div class="d-inline h6 text-muted">[Invoice No]</div>
                        {!! Form::text('search', $search, array('class' => 'form-control')) !!}
                    </div>
                    <div class="col-lg-2">
                        <label class="form-control-label">From Date &nbsp;</label>
                        {!! Form::text('from_date', $from_date, array('class' => 'form-control','id'=>"from_date")) !!}
                    </div>
                    <div class="col-lg-2">
                        <label class="form-control-label">To Date &nbsp;</label>
                        {!! Form::text('to_date', $to_date, array('class' => 'form-control','id'=>"to_date")) !!}
                    </div>
                    
                    <div class="col-lg-3">
                        <label class="form-control-label">Customer</label>
                        {!! Form::select('customer_id', $users, $selected_customer, array('class' => 'form-control customer_id','id'=>'customer_id','style'=>'width:100%')) !!}
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
</div>


<!-- Begin Row -->
<div class="row flex-row">
    <div class="col-xl-12 col-12">
        <div class="widget has-shadow">
            <div class="widget-body">
                <div class="table-responsive">
                    <table class="table table-hover mb-0 " id="invoice_tbl">
                        <thead>
                            <tr>
                                <th>Invoice Number </th>
                                <th>Customer Name</th>
                                <th>Sales Person</th>
                                <th class="sorter-shortDate dateFormat-ddmmyyyy">Date</th>
                                <th data-sorter="false">Materials</th>
                                <th data-sorter="false">Colors</th>
                                <th data-sorter="false">Meter</th>
                                <th data-sorter="false">Price</th>
                                <th data-sorter="false">Note</th>
                                <th data-sorter="false" width="180px">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @isset($invoices)
                                
                            
                            @foreach($invoices as $invoice)
                            <tr class="purchase-link" data-id="{{$invoice->id}}">
                                <td> {{$invoice->invoice_no}} </td>
                                <td> {{$invoice->customer['fullName']}} </td>
                                <td> {{$invoice->seller->fullName}} </td>
                                <td> {{$invoice->invoice_date}} </td>
                                <td>
                                    @if ($invoice->invoice_items->count())
                                    @foreach ($invoice->invoice_items as $key => $invoice_item)
                                    {{isset($invoice_item->item->name) ? $invoice_item->item->name : ''}} <br />
                                    @endforeach
                                    @endif
                                </td>
                                <td>
                                    @if ($invoice->invoice_items->count())
                                    @foreach ($invoice->invoice_items as $key => $invoice_item)
                                    {{ isset($invoice_item->item->color) ? $invoice_item->item->color : '' }} <br />
                                    @endforeach
                                    @endif
                                </td>
                                <td>
                                    @if ($invoice->invoice_items->count())
                                    @foreach ($invoice->invoice_items as $key => $invoice_item)
                                    {{$invoice_item->total_meter}} <br />
                                    @endforeach
                                    @endif
                                </td>
                                <td>
                                    @if ($invoice->invoice_items->count())
                                    @foreach ($invoice->invoice_items as $key => $invoice_item)
                                    {{$invoice_item->price}} <br />
                                    @endforeach
                                    @endif
                                </td>
                                <td> {{$invoice->note}} </td>
                                <td class="td-actions">
                                    {{-- {{print_r(sizeof($invoice->invoice))}} --}}
                                    {{-- @if (!$invoice->invoice)
                                        <a class="btn btn-secondary btn-sm btn-square" href="{{ route('invoice.create',$invoice->id) }}">Generate Invoice</a>
                                        <a class="btn btn-primary btn-sm btn-square" href="{{ route('invoice.edit',$invoice->id) }}">Edit</a>
                                        
                                    @else --}}
                                    @role("super-admin")
                                        <iframe src="{{route('invoice.print',$invoice->id)}}" name="print_frame_{{$invoice->id}}" class="d-none"></iframe>
                                        <a class="btn btn-secondary btn-sm btn-square print col-sm-6"  onclick="document.title='{{$invoice->invoice_no}}';frames['print_frame_{{$invoice->id}}'].print();document.title='Invoice - Premier Collection';return false;" >Print</a>
                                        <a class="btn btn-primary btn-sm btn-square edit col-sm-6 mt-1" href="{{ route('invoice.edit',$invoice->id) }}" >Edit</a>
                                        {{-- @endif --}}
                                        {!! Form::open(['method' => 'DELETE','route' => ['invoice.destroy', $invoice->id],'style'=>'display:inline', 'onsubmit'=>'return delete_confirm()']) !!}
                                        {!! Form::submit('Delete', ['class' => 'btn btn-danger btn-sm btn-square delete col-sm-6 mt-1']) !!}
                                        {!! Form::close() !!}
                                    @endrole
                                    <a class="btn btn-primary btn-sm btn-square payment col-sm-6 ml-0 mt-1" href="{{ route('invoice.add-payment',$invoice->id) }}" >Payments</a>
                                </td>
                            </tr>
                            @endforeach
                            @endisset
                        </tbody>
                    </table>
                    @isset($invoices)
                    {{ $invoices->render() }}
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
@push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.tablesorter/2.31.3/js/jquery.tablesorter.min.js" integrity="sha512-qzgd5cYSZcosqpzpn7zF2ZId8f/8CHmFKZ8j7mU4OUXTNRd5g+ZHBPsgKEwoqxCtdQvExE5LprwwPAgoicguNg==" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/js/select2.min.js"></script>
<script src="{{ asset('assets/js/datepicker/moment.min.js') }}"></script>
<script src="{{ asset('assets/js/datepicker/daterangepicker.js') }}"></script>
    <script>
        $(document).ready(function(){
            $('#invoice_tbl').tablesorter({
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
@push('after-styles')
<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/css/select2.min.css" rel="stylesheet" />

    <style>
        .selection{
           display:block !important;
        }
        .payment{
            margin-left: 25% ;
        }
        @media (max-width: 1364px){
            .payment{
                margin-left: 0% ;
            }
            .delete{
                margin-top: 3% ;
            }
        }
        @media (max-width: 1350px){
            .delete{
                margin-left: 10% ;
            }
            .payment{
                margin-left: 10% ;
            }
        }
        @media (max-width: 1304px){
            .print{
                margin-left: 10% ;
            }
            .edit{
                margin-left: 10% ;
                margin-top: 3%;
            }
            .delete{
                margin-left: 10% ;
            }
            .payment{
                margin-left: 10% ;
            }
        }
        @media (max-width: 1200px){
            .print{
                margin-left: 0%; 
            }
            .edit{
                margin-left: 0%; 
                margin-top: 0%; 
            }
            .delete{
                margin-left: 0%; 
                margin-top: 0%; 
            }
            .payment{
                margin-left: 20%; 
            }
        }
        @media (max-width: 1125px){
            .delete{
                margin-left: 10%; 
                margin-top: 3%; 
            }
            .payment{
                margin-left: 10%; 
            }
        }
        @media (max-width: 1064px){
            .print{
                margin-left: 10% ;
            }
            .edit{
                margin-left: 10% ;
                margin-top: 3%;
            }
            .delete{
                margin-left: 10% ;
            }
            .payment{
                margin-left: 10% ;
            }
        }
    </style>
@endpush