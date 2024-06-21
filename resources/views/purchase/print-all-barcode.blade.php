@extends('layouts.master')
@section('title', 'Print Barcode')
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
<div class="row flex-row">
    <div class="col-xl-12 col-12">
        <div class="widget has-shadow">
            <div class="widget-header bordered no-actions d-flex align-items-center">
                <h4 class="mr-3">Barcode List</h4>
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
                            @if($purchase)
                            @foreach($purchase as $val)
                                @if($val->purchase_items)
                                    @foreach($val->purchase_items as $item)
                                        <tr style="height:100%;" class="page-break" id="print_single_barcode">
                                            <td style="text-align: center; padding:10px 0px;width: 90%;" colspan="3">
                                                <ul>
                                                    <li class="barcode-div" style="list-style:none;margin:0;padding:0 3px;font-weight: lighter;float: left;width: 45%;">
                                                        {!! DNS1D::getBarcodeSVG($item->barcode,config('app.BARCODE_TYPE'), 1, 40) !!}
                                                    </li>
                                                </ul>
                                                <ul style="list-style:none;width: 100%; font-family: verdana; margin-top:3px;margin:10px 0px;padding: 0;display: inline-block; color``:#000;">
                                                    <li style="list-style:none;margin:0;padding:0 3px;font-weight: lighter;float: left;width: 45%;text-align: left;">ART No : {{$item->article_no}}</li>
                                                    <li style="list-style:none;margin:0;padding:0 3px;font-weight: lighter;float: left;width: 45%;text-align: left;">Lot NO : {{$val->totel_lot}}</li>

                                                    <li style="list-style:none;margin:0;padding:0 3px;font-weight: lighter;float: left;width: 45%;text-align: left;">MTRS # {{$val->total_meter}}</li>
                                                    <li style="list-style:none;margin:0;padding:0 3px;font-weight: lighter;float: left;width: 45%;text-align: left;">YARD# {{ $val->yards}}</li>

                                                    <li style="list-style:none;margin:0;padding:0 3px;font-weight: lighter;float: left;width: 45%;text-align: left;">COL# {{$item->color_no}}</li>
                                                    <li style="list-style:none;margin:0;padding:0 3px;font-weight: lighter;float: left;width: 45%;text-align: left;">Pcs No#{{$val->pcs_no}}</li>

                                                    <li style="list-style:none;margin:0;padding:0 3px;font-weight: lighter;float: left;width: 45%;text-align: left;">BK# 1234</li>
                                                    <li style="list-style:none;margin:0;padding:0 3px;font-weight: lighter;float: left;width: 45%;text-align: left;">CT/NO 1234</li>

                                                    <li style="list-style:none;margin:0;padding:0 3px;font-weight: lighter;float: left;width: 40%;text-align: left;">PO# {{$val->po}}</li>
                                                    <li style="list-style:none;margin:0;padding:0 3px;font-weight: lighter;float: left;width: 50%;text-align: left;">#OF CARTON {{$val->bale}} BL</li>

                                                    <li style="list-style:none;margin:0;padding:0 3px;font-weight: lighter;float: left;width: 45%;text-align: left;">TOTAL QTY : {{$item->total_qty}}</li>
                                                    
                                                    <li style="list-style:none;margin:0;padding:0 3px;font-weight: lighter;float: left;width: 45%;text-align: left;">AVA. QTY : {{$item->available_qty}}</li>
                                                </ul>
                                            </td>
                                            <td>
                                                <button class="btn btn-secondary btn-square print_single_barcode" onclick="print_single_barcode()">Print</button>
                                            </td>
                                        </tr>
                                    @endforeach
                                @endif
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
    function CallAfterWindowLoad()
    {
        $('.print_single_barcode').show();
    }
</script>
@endsection
