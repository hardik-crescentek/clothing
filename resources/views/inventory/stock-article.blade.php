@extends('layouts.master')
@section('title', 'Stock Article')
@section('content')
<!-- Begin Page Header-->
<div class="row">
    <div class="page-header">
        <div class="d-flex align-items-center">
            <h2 class="page-header-title">Stock Article</h2>
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
                {!! Form::open(['method' => 'GET','route' => ['invoice.index']]) !!}
                <div class="form-group row d-flex align-items-center mt-3">                    
                    <div class="col-lg-2">
                        <label class="form-control-label">Article</label>
                        {!! Form::select('article_id', $articles ?? [], null, ['class' => 'form-control article_id', 'id' => 'article_id', 'style' => 'width:100%']) !!}
                    </div>
                    <div class="col-lg-2">
                        <label class="form-control-label">Color No</label>
                        {!! Form::select('color_no', $colors ?? [], null, ['class' => 'form-control color_no', 'id' => 'color_no', 'style' => 'width:100%']) !!}
                    </div>
                    <div class="col-lg-2">
                        <label class="form-control-label">&nbsp;</label>
                        <div class="form-action">
                            <a href="{{ url('/invoice') }}" class="btn btn-warning btn-square">Reset</a>
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
                                <th>Sr. No. </th>
                                <th>Article</th>
                                <th>Color</th>
                                <th>Color No</th>
                                <th>0-50</th>
                                <th>0-100</th>
                                <th>0-200</th>
                                <th>0-500</th>
                                <th>0-1000</th>
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
                                            <a class="btn fa fa-edit btn-sm btn-primary ml-1" href="{{ route('invoice.edit',$invoice->id) }}" data-toggle="tooltip" data-placement="top" title="Edit"></a>
                                            
                                        @else --}}
                                        @role("super-admin")
                                            <iframe src="{{route('invoice.print',$invoice->id)}}" name="print_frame_{{$invoice->id}}" class="d-none"></iframe>
                                            <a class="btn fa fa-print btn-sm btn-info"  onclick="document.title='{{$invoice->invoice_no}}';frames['print_frame_{{$invoice->id}}'].print();document.title='Invoice - Premier Collection';return false;" data-toggle="tooltip" data-placement="top" title="Print"></a>
                                            <a class="btn fa fa-edit btn-sm btn-primary ml-1" href="{{ route('invoice.edit',$invoice->id) }}" data-toggle="tooltip" data-placement="top" title="Edit"></a>
                                            {{-- @endif --}}
                                            {!! Form::open(['method' => 'DELETE','route' => ['invoice.destroy', $invoice->id],'style'=>'display:inline', 'onsubmit'=>'return delete_confirm()']) !!}
                                            <!-- {!! Form::submit('Delete', ['class' => 'btn btn-danger btn-sm btn-square delete col-sm-6 mt-1']) !!} -->
                                            <button type="submit" class="btn-action btn fa fa-trash  btn-sm btn-danger ml-1" data-toggle="tooltip" data-placement="top" title="Delete"></button>
                                            {!! Form::close() !!}
                                        @endrole
                                        <a class="btn fa fa-credit-card btn-sm btn-success ml-1" href="{{ route('invoice.add-payment',$invoice->id) }}" data-toggle="tooltip" data-placement="top" title="Payments"></a>
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
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.tablesorter/2.31.3/js/jquery.tablesorter.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/js/select2.min.js"></script>
    <script>
        $(document).ready(function(){
            $('#invoice_tbl').tablesorter({
                cssAsc: 'up',
		        cssDesc: 'down',
                cssNone: 'both'
            });
            $('#article_id').select2({
                width: 'resolve',
            });
            $('#color_no').select2({
                width: 'resolve',
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
    </style>
@endpush
@push('scripts')
    <script>
        $(document).ready(function(){
            $('#invoice_tbl').DataTable({
                lengthMenu: [
                    [10, 25, 50,100,500,1000,'All'],
                    [10, 25, 50,100,500,1000,'All'],
                ],
                "aaSorting": []
            });
        })
    </script>
@endpush