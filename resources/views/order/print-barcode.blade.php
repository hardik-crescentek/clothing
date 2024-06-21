@extends('layouts.master')
@section('title', 'Print Barcode')
@section('content')

@if ($message = Session::get('success'))
<div class="alert alert-success">
    {{ $message }}
</div>
@endif

<style type="text/css">
    @media print{
        #print{
            display:none !important;
        }
    }
    .barcode-div svg
    {
        height: 50px !important;
    }
</style>
<!-- Begin Row -->
<div class="row flex-row">
    <div class="col-xl-6 col-12">
        <div class="widget has-shadow">
            <div class="widget-header bordered no-actions d-flex align-items-center">
                <h4 class="mr-3">Customer Barcode List</h4>
                <button class="btn btn-secondary btn-square" onclick="print_barcode()">Print</button>
            </div>
            <div class="widget-body" id="print-barcodes">
                <div class="table-responsive">
                    <table class="barcodelist" style="width: 100mm;">
                        <thead>
                            <tr>
                                <th style="width: 25mm"></th>
                                <th style="width: 25mm"></th>
                                <th style="width: 25mm"></th>
                                <th style="width: 25mm"></th>
                            </tr>
                        </thead>
                        <tbody>
                            @if($order_items)
                            @foreach($order_items as $item)
                            <tr  class="page-break" id="print_single_barcode">
                                <td style="text-align: center; padding:10px 0px;width: 100%;" colspan="3">
                                    <ul>
                                        <li style="list-style:none;margin:0;padding:0 3px;font-weight: lighter;float: left;width: 100%;clear: both;">
                                            {!!   DNS1D::getBarcodeSVG($item->item['barcode'],config('app.BARCODE_TYPE'), 2, 40)  !!}
                                        </li>
                                    </ul>
                                    <ul style="list-style:none;width: 100%; font-family: verdana; margin-top:3px;margin:10px 0px;padding: 0;display: inline-block; color:#000;margin-left:10px;">
                                        <li style="list-style:none;margin:0;padding:0 3px;font-weight: lighter;float: left;width: 45%;text-align: left;">Order no : {{ $item->id }}</li>
                                        <li style="list-style:none;margin:0;padding:0 3px;font-weight: lighter;float: left;width: 45%;text-align: left;">CN : {{ $order_data->customer['firstname'] }}</li>

                                        <li style="list-style:none;margin:0;padding:0 3px;font-weight: lighter;float: left;width: 45%;text-align: left;">ART No : {{ $item->item['article_no'] }}</li>
                                        <li style="list-style:none;margin:0;padding:0 3px;font-weight: lighter;float: left;width: 45%;text-align: left;">Qty  : 1</li>

                                        <li style="list-style:none;margin:0;padding:0 3px;font-weight: lighter;float: left;width: 45%;text-align: left;">MTRS # {{$item->meter}}</li>
                                        <!-- <li style="list-style:none;margin:0;padding:0 3px;font-weight: lighter;float: left;width: 45%;text-align: left;">COL # {{$item->item['color'] }}</li> -->

                                        <li style="list-style:none;margin:0;padding:0 3px;font-weight: lighter;float: left;width: 45%;text-align: left;">Pcs No# {{$item->purchase['pcs_no'] }}</li>
                                        <li style="list-style:none;margin:0;padding:0 3px;font-weight: lighter;float: left;width: 45%;text-align: left;">D # {{date('d-m-Y',strtotime($order_data->order_date))}}</li>
                                        <li style="list-style:none;margin:0;padding:0 3px;font-weight: lighter;float: left;width: 45%;text-align: left;">T # {{date('h:i A',strtotime($order_data->order_date))}}</li>
                                    </ul>
                                </td>
                                <td>
                                    <button class="btn btn-secondary btn-square print_single_barcode" onclick="print_single_barcode()">Print</button>
                                </td>
                                </tr>
                            @endforeach
                            @else
                                <div class="col-12 text-center">
                                    Invalid Invoice Number
                                </div>
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-6 col-12">
        <div class="widget has-shadow">
            <div class="widget-header bordered no-actions d-flex align-items-center">
                <h4 class="mr-3">Role Barcode</h4>
                <button class="btn btn-secondary btn-square" onclick="print_role_barcode()">Print</button>
            </div>
            <div class="widget-body" id="print-barcodes2">
                <div class="table-responsive">
                    <table class="barcodelist" style="width: 100mm;">
                        <thead>
                            <tr>
                                <th style="width: 25mm"></th>
                                <th style="width: 25mm"></th>
                                <th style="width: 25mm"></th>
                                <th style="width: 25mm"></th>
                            </tr>
                        </thead>
                        <tbody>
                           @if($purchase_items)
                            @foreach($purchase_items as $item)
                                @php $purchase_data = \App\Utils\Util::get_single_purchase_data($item->purchase_id); @endphp
                                @php $order_item_data = \App\Utils\Util::get_order_data($item->id); @endphp
                                <tr style="height:100%;" class="page-break" id="print_single_barcode_role">
                                    <td style="text-align: center; padding:10px 0px;width: 90%;" colspan="3">
                                        <ul>
                                            <li class="barcode-div" style="list-style:none;margin:0;padding:0 3px;font-weight: lighter;float: left;width: 100%;">
                                                {!! DNS1D::getBarcodeSVG($item->barcode,config('app.BARCODE_TYPE'), 2, 40) !!}
                                            </li>
                                        </ul>
                                        <ul style="list-style:none;width: 100%; font-family: verdana; margin-top:3px;margin:10px 0px;padding: 0;display: inline-block; color``:#000;">
                                            <li style="list-style:none;margin:0;padding:0 3px;font-weight: lighter;float: left;width: 45%;text-align: left;">CUTTER : {{$order_data->role_cutter_name}}</li>

                                            <li style="list-style:none;margin:0;padding:0 3px;font-weight: lighter;float: left;width: 45%;text-align: left;">CN # {{$order_data->customer['firstname']}}</li>
                                            <li style="list-style:none;margin:0;padding:0 3px;font-weight: lighter;float: left;width: 45%;text-align: left;">Order No# {{ $order_data->id }}</li>

                                            <li style="list-style:none;margin:0;padding:0 3px;font-weight: lighter;float: left;width: 45%;text-align: left;">ART No : {{$item->article_no}}</li>
                                            <li style="list-style:none;margin:0;padding:0 3px;font-weight: lighter;float: left;width: 45%;text-align: left;">Lot NO : {{$purchase_data->totel_lot}}</li>

                                            <li style="list-style:none;margin:0;padding:0 3px;font-weight: lighter;float: left;width: 45%;text-align: left;">MTRS # {{ isset($order_item_data->meter) ? $order_item_data->meter : ''}}</li>
                                            @if($purchase_data->yards != '')
                                            <li style="list-style:none;margin:0;padding:0 3px;font-weight: lighter;float: left;width: 45%;text-align: left;">YARD# {{ $purchase_data->yards }}</li>
                                            @else
                                            <li style="list-style:none;margin:0;padding:0 3px;font-weight: lighter;float: left;width: 45%;text-align: left;">YARD# {{ decimal_format(meter2yard($item->qty)) }}</li>
                                            @endif

                                            <li style="list-style:none;margin:0;padding:0 3px;font-weight: lighter;float: left;width: 45%;text-align: left;">COL# {{$item->color_no}}</li>
                                            <li style="list-style:none;margin:0;padding:0 3px;font-weight: lighter;float: left;width: 45%;text-align: left;">Pcs No#{{$purchase_data->pcs_no}}</li>

                                            <li style="list-style:none;margin:0;padding:0 3px;font-weight: lighter;float: left;width: 45%;text-align: left;">BK# 1234</li>
                                            <li style="list-style:none;margin:0;padding:0 3px;font-weight: lighter;float: left;width: 45%;text-align: left;">CT/NO 1234</li>

                                            <li style="list-style:none;margin:0;padding:0 3px;font-weight: lighter;float: left;width: 40%;text-align: left;">PO# {{$purchase_data->po}}</li>
                                            <li style="list-style:none;margin:0;padding:0 3px;font-weight: lighter;float: left;width: 45%;text-align: left;">#OFCARTON {{$purchase_data->bale}} BL</li>

                                            <li style="list-style:none;margin:0;padding:0 3px;font-weight: lighter;float: left;width: 45%;text-align: left;">Total QTY# {{ $item->total_qty }}</li>
                                            
                                            <li style="list-style:none;margin:0;padding:0 3px;font-weight: lighter;float: left;width: 45%;text-align: left;">Balance QTY # {{ $item->available_qty }}</li>
                                            
                                            <li style="list-style:none;margin:0;padding:0 3px;font-weight: lighter;float: left;width: 45%;text-align: left;">D # {{date('d-m-Y',strtotime($order_data->order_date))}}</li>
                                            <li style="list-style:none;margin:0;padding:0 3px;font-weight: lighter;float: left;width: 45%;text-align: left;">T # {{date('h:i A',strtotime($order_data->order_date))}}</li>

                                        </ul>
                                    </td>
                                    <td>
                                        <button class="btn btn-secondary btn-square print_single_barcode_role" onclick="print_single_barcode_role()">Print</button>
                                    </td>
                                </tr>
                            @endforeach
                            @else
                                <div class="col-12 text-center">
                                    Invalid Invoice Number
                                </div>
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    function print_barcode() {
        $('.print_single_barcode').hide();
        var divToPrint = document.getElementById('print-barcodes');
        var newWin = window.open('', 'Print-Window');
        newWin.document.open(); 
        newWin.document.write('<html></head><style>@media print { @page{ size:100mm 50mm;margin:0;padding:0; } tr{ page-break-after: always;font-size:12px; } }</style></head><body onload="window.print()">' + divToPrint.innerHTML + '</body></html>');
        setTimeout(function() {
            newWin.document.close();
            newWin.close();
            CallAfterWindowLoad();
        }, 10);
    }

    function print_single_barcode() {
        $('.print_single_barcode').hide();
        var divToPrint = document.getElementById('print_single_barcode');
        var newWin = window.open('', 'Print-Window');
        newWin.document.open(); 
        newWin.document.write('<html></head><style>@media print { @page{ size:100mm 50mm;margin:0;padding:0; } tr{ page-break-after: always;font-size:12px; } }</style></head><body onload="window.print()">' + divToPrint.innerHTML + '</body></html>');
        setTimeout(function() {
            newWin.document.close();
            newWin.close();
            CallAfterWindowLoad();
        }, 10);
    }

    // role barcode
    function print_role_barcode() {
        $('.print_single_barcode_role').hide();
        var divToPrint = document.getElementById('print-barcodes2');
        var newWin = window.open('', 'Print-Window');
        newWin.document.open(); 
        newWin.document.write('<html></head><style>@media print { @page{ size:100mm 70mm;margin:0;padding:0; } tr{ page-break-after: always;font-size:12px; } }</style></head><body onload="window.print()">' + divToPrint.innerHTML + '</body></html>');
        setTimeout(function() {
            newWin.document.close();
            newWin.close();
            CallAfterWindowLoad();
        }, 10);
    }

    function print_single_barcode_role() {
        $('.print_single_barcode_role').hide();
        var divToPrint = document.getElementById('print_single_barcode_role');
        var newWin = window.open('', 'Print-Window');
        newWin.document.open(); 
        newWin.document.write('<html></head><style>@media print { @page{ size:100mm 70mm;margin:0;padding:0; } tr{ page-break-after: always;font-size:12px; } }</style></head><body onload="window.print()">' + divToPrint.innerHTML + '</body></html>');
        setTimeout(function() {
            newWin.document.close();
            newWin.close();
            CallAfterWindowLoad();
        }, 10);
    }
    function CallAfterWindowLoad()
    {
        $('.print_single_barcode').show();
        $('.print_single_barcode_role').show();
    }
</script>
@endsection
