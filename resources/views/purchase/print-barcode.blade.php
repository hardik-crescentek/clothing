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
        /*height: 50px !important;*/
    }
</style>
<!-- Begin Row -->
<!-- <div class="row flex-row">
    <div class="col-xl-12 col-12">
        {!! Form::open(['method' => 'GET','route' => ['printbarcode']]) !!}
        <div class="widget has-shadow">
            <div class="widget-header bordered no-actions1 d-block align-items-center">
                Print Barcode
            </div>
            <div class="widget-body">
                <div class="form-group row d-flex align-items-center mb-0">
                    <input type="hidden" name="id" value="<?=$_REQUEST['id']?>">
                    <div class="col-lg-3">
                        <label class="form-control-label">Invoice No.</label>
                        {!! Form::text('invoice_no', $invoice_no, array('class' => 'form-control')) !!}
                    </div>
                    <div class="col-lg-3">
                        <label class="form-control-label">Quantity</label>
                        {!! Form::number('qty', $qty, array('class' => 'form-control', 'min' => 1)) !!}
                    </div>
                    <div class="col-lg-3">
                        <label class="form-control-label">Print</label>
                        <div class="form-check">
                            {!! Form::checkbox('printBarcode', 1, $printBarcode,array('class' => 'form-check-input', 'id' => 'printBarcode')) !!}
                            <label class="form-check-label" for="printBarcode">Barcode</label>
                        </div>
                        <div class="form-check">
                            {!! Form::checkbox('printQRCode', 1, $printQRCode ,array('class' => 'form-check-input', 'id' => 'printQRCode')) !!}
                            <label class="form-check-label" for="printQRCode">QRCode</label>
                        </div>
                        <div class="form-check">
                            {!! Form::checkbox('printInvoice', 1, $printInvoice,array('class' => 'form-check-input', 'id' => 'printInvoice')) !!}
                            <label class="form-check-label" for="printInvoice">INV</label>
                        </div>
                        <div class="form-check">
                            {!! Form::checkbox('printColor', 1, $printColor ,array('class' => 'form-check-input', 'id' => 'printColor')) !!}
                            <label class="form-check-label" for="printColor">Color</label>
                        </div>
                        <div class="form-check">
                            {!! Form::checkbox('printWidth', 1, $printWidth ,array('class' => 'form-check-input', 'id' => 'printWidth')) !!}
                            <label class="form-check-label" for="printWidth">Width</label>
                        </div>
                    </div>
                    <div class="col-lg-3">
                        <label class="form-control-label">Print With</label>
                        <div class="form-check">
                            {!! Form::checkbox('printWithBatchNo', 1, $printWithBatchNo,array('class' => 'form-check-input', 'id' => 'printWithBatchNo')) !!}
                            <label class="form-check-label" for="printWithBatchNo">Batch No.</label>
                        </div>
                        <div class="form-check">
                            {!! Form::checkbox('printWithArticleNo', 1, $printWithArticleNo,array('class' => 'form-check-input', 'id' => 'printWithArticleNo')) !!}
                            <label class="form-check-label" for="printWithArticleNo">Articel No.</label>
                        </div>
                        <div class="form-check">
                            {!! Form::checkbox('printWithRollNo', 1,$printWithRollNo, array('class' => 'form-check-input', 'id' => 'printWithRollNo')) !!}
                            <label class="form-check-label" for="printWithRollNo">Roll No.</label>
                        </div>
                    </div>
                </div>
            </div>
            <div class="widget-footer">
                <input type="submit" class="btn btn-primary btn-square" value="Apply">
            </div>
        </div>
        {!! Form::close() !!}
    </div>
</div> -->
<div class="row flex-row">
    <div class="col-xl-12 col-12">
        <div class="widget has-shadow">
            <div class="widget-header bordered no-actions d-flex align-items-center">
                <h4 class="mr-3">Barcode List</h4>
                <button class="btn btn-secondary btn-square" onclick="print_barcode()">Print</button>
            </div>
            <div class="widget-body" id="print-barcodes">
                <div class="table-responsive">
                    <table class="barcodelist" style="width: 120mm;">
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
                            <?php //echo "<pre>"; print_r($purchase); ?>
                            @foreach($purchase->purchase_items as $item)
                            <tr  class="page-break" id="print_single_barcode">
                                <td style="text-align: center; padding:10px 0px;width: 100%;" colspan="7">

                                    <!-- old code -->
                                    <!-- <ul>
                                        <li style="list-style:none;margin:0;padding:0 3px;font-weight: lighter;float: left;width: 100%;clear: both;">
                                            @if($printBarcode)
                                            {!! DNS1D::getBarcodeSVG($item->barcode,config('app.BARCODE_TYPE'), 2, 45) !!}
                                            @endif
                                        </li>
                                    </ul> -->
                                    <!-- old code -->

                                    <!-- new code -->
                                    <ul style="list-style: none; margin: 0; padding: 0; display: flex; justify-content: space-between; align-items: center;">
                                        <li style="margin: 0 3px; font-weight: lighter; flex: 1; text-align: left;">
                                            @if($printBarcode)
                                                {!! DNS1D::getBarcodeSVG($item->barcode, config('app.BARCODE_TYPE'), 2, 45) !!}
                                            @endif
                                        </li>
                                        <li style="margin: 0 3px; font-weight: lighter; flex: 1; text-align: right;">
                                            @if(isset($item->qrcode))
                                                {!! DNS2D::getBarcodeSVG($item->qrcode, 'QRCODE') !!}
                                            @endif
                                        </li>
                                    </ul>
                                    <ul style="list-style:none;width: 100%; font-family: verdana; margin-top:3px;margin:10px 0px;padding: 0;display: inline-block; color:#000;margin-left:10px;">
                                        <li style="list-style:none;margin:0;padding:0 3px;font-weight: lighter;float: left;width: 100%;text-align: left;">Piece No : {{$item->piece_no}}</li>
                                        <li style="list-style:none;margin:0;padding:0 3px;font-weight: lighter;float: left;width: 45%;text-align: left;">ART No : {{$item->article_no}}</li>
                                        <li style="list-style:none;margin:0;padding:0 3px;font-weight: lighter;float: left;width: 45%;text-align: left;">COL : {{$item->color_no}}</li>
                                        <li style="list-style:none;margin:0;padding:0 3px;font-weight: lighter;float: left;width: 45%;text-align: left;">QTY MTR : {{$item->qty}}</li>                                        
                                        <li style="list-style:none;margin:0;padding:0 3px;font-weight: lighter;float: left;width: 45%;text-align: left;">QTY YARDS : {{ decimal_format(meter2yard($item->qty)) }}</li>
                                    </ul>
                                    <!-- new code -->

                                    <!-- <ul>
                                        <li style="list-style:none;margin:0;padding:0 3px;font-weight: lighter;float: left;width: 45%;">
                                            @if($printBarcode)
                                            <img src="data:image/png;base64,{{DNS1D::getBarcodePNG($item->barcode,config('app.BARCODE_TYPE'),1,40,array(0,0,0), true)}}" alt="barcode" /><br><br>
                                            @endif
                                        </li>
                                    </ul> -->
                                    <!-- old code -->
                                    <!-- <ul style="list-style:none;width: 100%; font-family: verdana; margin-top:3px;margin:10px 0px;padding: 0;display: inline-block; color:#000;margin-left:10px;">
                                        <li style="list-style:none;margin:0;padding:0 3px;font-weight: lighter;float: left;width: 45%;text-align: left;">ART No : {{$item->article_no}}</li>
                                        <li style="list-style:none;margin:0;padding:0 3px;font-weight: lighter;float: left;width: 45%;text-align: left;">Lot NO : {{$purchase->totel_lot}}</li>

                                        <li style="list-style:none;margin:0;padding:0 3px;font-weight: lighter;float: left;width: 45%;text-align: left;">MTRS # {{$purchase->total_meter}}</li>
                                        @if($purchase->yards != '')
                                        <li style="list-style:none;margin:0;padding:0 3px;font-weight: lighter;float: left;width: 45%;text-align: left;">YARD# {{ $purchase->yards }}</li>
                                        @else
                                        <li style="list-style:none;margin:0;padding:0 3px;font-weight: lighter;float: left;width: 45%;text-align: left;">YARD# {{ decimal_format(meter2yard($item->qty)) }}</li>
                                        @endif

                                        <li style="list-style:none;margin:0;padding:0 3px;font-weight: lighter;float: left;width: 45%;text-align: left;">COL# {{$item->color_no}}</li>
                                        <li style="list-style:none;margin:0;padding:0 3px;font-weight: lighter;float: left;width: 45%;text-align: left;">Pcs No#{{$purchase->pcs_no}}</li>

                                        <li style="list-style:none;margin:0;padding:0 3px;font-weight: lighter;float: left;width: 45%;text-align: left;">BK# 1234</li>
                                        <li style="list-style:none;margin:0;padding:0 3px;font-weight: lighter;float: left;width: 45%;text-align: left;">CT/NO 1234</li>

                                        <li style="list-style:none;margin:0;padding:0 3px;font-weight: lighter;float: left;width: 40%;text-align: left;">PO# {{$purchase->po}}</li>
                                        <li style="list-style:none;margin:0;padding:0 3px;font-weight: lighter;float: left;width: 50%;text-align: left;">#OF CARTON {{$purchase->bale}} BL</li>

                                        <li style="list-style:none;margin:0;padding:0 3px;font-weight: lighter;float: left;width: 45%;text-align: left;">TOTAL QTY : {{$item->total_qty}}</li>
                                                    
                                        <li style="list-style:none;margin:0;padding:0 3px;font-weight: lighter;float: left;width: 45%;text-align: left;">AVA. QTY : {{$item->available_qty}}</li>
                                        
                                    </ul> -->
                                    <!-- old code -->
                                    
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
</div>
<script>
    function print_barcode() {
        $('.print_single_barcode').hide();
        var divToPrint = document.getElementById('print-barcodes');
        var newWin = window.open('', 'Print-Window');
        newWin.document.open(); 
        newWin.document.write('<html></head><style>@media print { @page{ size:120mm 50mm;margin:0;padding:0; } tr{ page-break-after: always;font-size:12px; } }</style></head><body onload="window.print()">' + divToPrint.innerHTML + '</body></html>');
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
        newWin.document.write('<html></head><style>@media print { @page{ size:120mm 50mm;margin:0;padding:0; } tr{ page-break-after: always;font-size:12px; } }</style></head><body onload="window.print()">' + divToPrint.innerHTML + '</body></html>');
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
