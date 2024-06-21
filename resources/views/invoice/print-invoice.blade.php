{{-- @extends('layouts.master') --}}
{{-- @section('title', 'Print Invoice') --}}
{{-- @section('content')

@if ($message = Session::get('success'))
<div class="alert alert-success">
    {{ $message }}
</div>
@endif --}}
<html>
<head>
    <link href="https://fonts.googleapis.com/css?family=Montserrat:400,500,600,700,800|Noto+Sans:400,700&display=swap" rel="stylesheet">  
    <link rel="stylesheet" href="{{ asset('assets/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/owl-carousel/owl.carousel.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/owl-carousel/owl.theme.min.css') }}">
    <link rel="stylesheet" href="{{asset('assets/css/style.css')}}">
</head>
<body>
<!-- Begin Row -->
<div class="row flex-row" id="print_invoice">
    <div class="col-xl-12 col-12">
        <div class="widget has-shadow">
            <div class="widget-body">
                <div class="container">
                    <div class="text-center logo">
                        <img src="{{asset('assets/img/invoice-logo/logo.png')}}" alt="Logo" width="100px" height="100px"  class="mb-4 ">
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <h2 class="text-center mb-3">Premier Collection Tel: (02)0550909 Mob: 087 9197389</h2>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <h4 style="text-align: center">ใบส่งสินค้า / ใบวางบิล</h4> 
                            <h4  style="float: right;">Original / ต้นฉบับ </h4>
                        </div>
                    </div>
                    <div class="row d-flex justify-content-around mb-3" style="color:black !important;">
                        <div class="col-5 border border-dark p-2">
                            รหัสลูกค้า / Code : <div style="border-bottom:1px dotted black;width: 58%" class="float-right mt-3" ></div> <br />
                            นามลูกค้า / Name : {!! $invoice->customer->fullName !!} <br />
                            ที่อยู่ / Address : TEL : {!! $invoice->customer->phone !!} <br /> {!! $invoice->customer->address !!} , {!! $invoice->customer->city !!} , {!! $invoice->customer->state !!} , {!! $invoice->customer->zip !!} <br />
                        </div>
                        <div class="col-5 border border-dark p-2">
                            วันที่ / Date : {!! Date('d M, Y') !!} <br />
                            เลขที่ / No. : <br />
                            เลขที่ใบกำกับ / Order No. : {!! $invoice->invoice_no !!} <br />
                            กำหนดชำระเงิน / Term: <br />
                            ครบกำหนด / Due : <br />
                        </div>
                    </div>
                </div>
                <div class="table-responsive">
                    <table class="table text-center border border-dark" id="data_tbl" style="color:black !important;">
                        <thead>
                            <tr>
                                <th width="10%" >
                                    รหัสสินค้า <br /> PRODUCT CODE
                                </th>
                                <th width="40%" >
                                    รายละเอียด <br /> DESCRIPTION
                                </th>
                                <th width="15%" >
                                    จำนวน <br /> QUANTITY
                                </th>
                                <th width="15%" >
                                    หน่วยละ <br /> UNIT
                                </th>
                                <th width="20%" >
                                    จำนวนเงิน <br /> AMOUNT
                                </th>
                            </tr>
                        </thead>
                        <tbody class="mb-5">
                            @if ($invoice->invoice_items->count())
                                @foreach ($invoice->invoice_items as $invoice_item)
                                    <tr>
                                        <td>
                                            {{$invoice_item->id}}
                                        </td>
                                        <td>
                                            {{strtoupper($invoice_item->item->name)}} &nbsp; {{$invoice_item->total_meter}} Meters
                                        </td>
                                        <td>
                                            {{number_format((float)meter2yard($invoice_item->total_meter),2,'.','')}} Yards
                                        </td>
                                        <td>
                                            {{$invoice_item->price}}
                                        </td>
                                        <td>
                                            {{number_format((float)($invoice_item->price)*(meter2yard($invoice_item->total_meter)),2,'.','')}}
                                        </td>
                                    </tr>
                                @endforeach
                            @endif
                        </tbody>
                        <tfoot>
                            <tr>
                                <td colspan="3" class="active amount-in-word"></td>
                                <td>ยอดเงินสุทธิ</td>
                                <td><b>{{$invoice->sub_total}}</b></td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
               
                <div class="table-responsive mt-5">
                    <table class="table border border-dark" id="confirm_tbl" style="color:black !important;">
                        <thead>
                            <tr class="text-center">
                                <th width="25%">
                                    <b>ผู้รับของ / Receiver <br /></b>
                                    ได้รับสินค้าตามรายการถูกต้องแล้ว
                                </th>
                                <th width="25%">
                                    <b>ผู้ส่งของ / Delivered By</b>
                                </th>
                                <th width="25%">
                                    <b>ผู้รับเงิน / Collector</b>
                                </th>
                                <th width="25%">
                                    <b>ผู้จัดการ / Manager</b>
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>
                                    ลงชื่อ <br />
                                <div style="border-bottom:1px dotted black;width:100%;" class="mt-4"> </div>
                                    <div class="mt-4">วันที่ <div style="border-bottom:1px dotted black;width:80%" class="float-right mt-4" > </div> </div>
                                </td>
                                <td>
                                    ลงชื่อ <br />
                                    <div style="border-bottom:1px dotted black;width:100%;" class="mt-4"> </div>
                                    <div class="mt-4">วันที่ <div style="border-bottom:1px dotted black;width:80%" class="float-right mt-4" > </div> </div>
                                </td>
                                <td>
                                    ลงชื่อ <br />
                                    <div style="border-bottom:1px dotted black;width:100%;" class="mt-4"> </div>
                                    <div class="mt-4">วันที่ <div style="border-bottom:1px dotted black;width:80%" class="float-right mt-4" > </div> </div>
                                </td>
                                <td>
                                    ลงชื่อ <br />
                                    <div style="border-bottom:1px dotted black;width:100%;" class="mt-4"> </div>
                                    <div class="mt-4">วันที่ <div style="border-bottom:1px dotted black;width:80%" class="float-right mt-4" > </div> </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
{{-- page break --}}
<div style="page-break-after: always;"></div>

<div class="row flex-row" id="print_invoice_copy">
    <div class="col-xl-12 col-12">
        <div class="widget has-shadow">
            <div class="widget-body">
                <div class="container-fluid">
                    <div class="text-center logo">
                        <img src="{{asset('assets/img/invoice-logo/logo.png')}}" alt="Logo" width="100px" height="100px"  class="mb-4 ">
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <h2 class="text-center mb-3">Premier Collection Tel: (02)0550909 Mob: 087 9197389</h2>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <h4 style="text-align: center">ใบส่งสินค้า / ใบวางบิล</h4> 
                            <h4  style="float: right;">Copy / สำเนา </h4>
                        </div>
                    </div>
                    <div class="row d-flex justify-content-around mb-3" style="color:black !important;">
                        <div class="col-5 border border-dark p-2">
                            รหัสลูกค้า / Code : <div style="border-bottom:1px dotted black;width: 58%" class="float-right mt-3" ></div> <br />
                            นามลูกค้า / Name : {!! $invoice->customer->fullName !!} <br />
                            ที่อยู่ / Address : TEL : {!! $invoice->customer->phone !!} <br /> {!! $invoice->customer->address !!} , {!! $invoice->customer->city !!} , {!! $invoice->customer->state !!} , {!! $invoice->customer->zip !!} <br />
                        </div>
                        <div class="col-5 border border-dark p-2">
                            วันที่ / Date : {!! Date('d M, Y') !!} <br />
                            เลขที่ / No. : <br />
                            เลขที่ใบกำกับ / Order No. : {!! $invoice->invoice_no !!} <br />
                            กำหนดชำระเงิน / Term: <br />
                            ครบกำหนด / Due : <br />
                        </div>
                    </div>
                    
                </div>
                <div class="table-responsive">
                    <table class="table text-center border border-dark" id="data_tbl_copy" style="color:black !important;">
                        <thead>
                            <tr>
                                <th width="10%" >
                                    รหัสสินค้า <br /> PRODUCT CODE
                                </th>
                                <th width="40%" >
                                    รายละเอียด <br /> DESCRIPTION
                                </th>
                                <th width="15%" >
                                    จำนวน <br /> QUANTITY
                                </th>
                                <th width="15%" >
                                    หน่วยละ <br /> UNIT
                                </th>
                                <th width="20%" >
                                    จำนวนเงิน <br /> AMOUNT
                                </th>
                            </tr>
                        </thead>
                        <tbody class="mb-5">
                            @if ($invoice->invoice_items->count())
                                @foreach ($invoice->invoice_items as $invoice_item)
                                    <tr>
                                        <td>
                                            {{$invoice_item->id}}
                                        </td>
                                        <td>
                                            {{strtoupper($invoice_item->item->name)}} &nbsp; {{$invoice_item->total_meter}} Meters
                                        </td>
                                        <td>
                                            {{number_format((float)meter2yard($invoice_item->total_meter),2,'.','')}} Yards
                                        </td>
                                        <td>
                                            {{$invoice_item->price}}
                                        </td>
                                        <td>
                                            {{number_format((float)($invoice_item->price)*(meter2yard($invoice_item->total_meter)),2,'.','')}}
                                        </td>
                                    </tr>
                                @endforeach
                            @endif
                        </tbody>
                        <tfoot>
                            <tr>
                                <td colspan="3" class="active amount-in-word"></td>
                                <td>ยอดเงินสุทธิ</td>
                                <td><b>{{$invoice->sub_total}}</b></td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
                <div class="table-responsive mt-5">
                    <table class="table border border-dark" id="confirm_tbl_copy" style="color:black !important;">
                        <thead>
                            <tr class="text-center">
                                <th width="25%">
                                    <b>ผู้รับของ / Receiver <br /></b>
                                    ได้รับสินค้าตามรายการถูกต้องแล้ว
                                </th>
                                <th width="25%">
                                    <b>ผู้ส่งของ / Delivered By</b>
                                </th>
                                <th width="25%">
                                    <b>ผู้รับเงิน / Collector</b>
                                </th>
                                <th width="25%">
                                    <b>ผู้จัดการ / Manager</b>
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>
                                    ลงชื่อ <br />
                                <div style="border-bottom:1px dotted black;width:100%;" class="mt-4"> </div>
                                    <div class="mt-4">วันที่ <div style="border-bottom:1px dotted black;width:80%" class="float-right mt-4" > </div> </div>
                                </td>
                                <td>
                                    ลงชื่อ <br />
                                    <div style="border-bottom:1px dotted black;width:100%;" class="mt-4"> </div>
                                    <div class="mt-4">วันที่ <div style="border-bottom:1px dotted black;width:80%" class="float-right mt-4" > </div> </div>
                                </td>
                                <td>
                                    ลงชื่อ <br />
                                    <div style="border-bottom:1px dotted black;width:100%;" class="mt-4"> </div>
                                    <div class="mt-4">วันที่ <div style="border-bottom:1px dotted black;width:80%" class="float-right mt-4" > </div> </div>
                                </td>
                                <td>
                                    ลงชื่อ <br />
                                    <div style="border-bottom:1px dotted black;width:100%;" class="mt-4"> </div>
                                    <div class="mt-4">วันที่ <div style="border-bottom:1px dotted black;width:80%" class="float-right mt-4" > </div> </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
{{-- @endsection --}}
{{-- @push('scripts') --}}
<script src="{{ asset('assets/js/jquery.min.js') }}"></script>
        <script src="{{ asset('assets/js/jquery.ui.min.js') }}"></script>
        <script src="{{ asset('assets/js/core.min.js') }}"></script>
        <script src="{{ asset('assets/js/form-validator/jquery.form-validator.min.js') }}"></script>
        <script src="{{ asset('assets/js/nicescroll/nicescroll.min.js') }}"></script>
        <script src="{{ asset('assets/js/app.js') }}"></script>
<script type="text/javascript">
    (function($) {
        $(window).on('load',function(){
            
            function numberToWords(number) {  
                var digit = ['zero', 'one', 'two', 'three', 'four', 'five', 'six', 'seven', 'eight', 'nine'];  
                var elevenSeries = ['ten', 'eleven', 'twelve', 'thirteen', 'fourteen', 'fifteen', 'sixteen', 'seventeen', 'eighteen', 'nineteen'];  
                var countingByTens = ['twenty', 'thirty', 'forty', 'fifty', 'sixty', 'seventy', 'eighty', 'ninety'];  
                var shortScale = ['', 'thousand', 'million', 'billion', 'trillion'];  
        
                number = number.toString(); number = number.replace(/[\, ]/g, ''); if (number != parseFloat(number)) return 'not a number'; var x = number.indexOf('.'); if (x == -1) x = number.length; if (x > 15) return 'too big'; var n = number.split(''); var str = ''; var sk = 0; for (var i = 0; i < x; i++) { if ((x - i) % 3 == 2) { if (n[i] == '1') { str += elevenSeries[Number(n[i + 1])] + ' '; i++; sk = 1; } else if (n[i] != 0) { str += countingByTens[n[i] - 2] + ' '; sk = 1; } } else if (n[i] != 0) { str += digit[n[i]] + ' '; if ((x - i) % 3 == 0) str += 'hundred '; sk = 1; } if ((x - i) % 3 == 1) { if (sk) str += shortScale[(x - i - 1) / 3] + ' '; sk = 0; } } if (x != number.length) { var y = number.length; str += 'point '; for (var i = x + 1; i < y; i++) str += digit[n[i]] + ' '; } str = str.replace(/\number+/g, ' '); return str.trim() + ".";  
        
            } 
            $('.amount-in-word').html("<b>"+numberToWords("{{$invoice->sub_total}}")+"</b>");
            // for orignal
            var total_tr=parseInt($('#data_tbl tbody td').closest('tr').length);
            if(total_tr<=10)
            {
                var add_tr=20-total_tr;
                var tr="<tr><td></td><td></td><td></td><td></td><td></td></tr>";
                for (let index = 1; index < add_tr; index++) {
                    $('#data_tbl tbody').append(tr);
                }
            }

            // for copy
            var total_tr=parseInt($('#data_tbl_copy tbody td').closest('tr').length);
            if(total_tr<=10)
            {
                var add_tr=20-total_tr;
                var tr="<tr><td></td><td></td><td></td><td></td><td></td></tr>";
                for (let index = 1; index < add_tr; index++) {
                    $('#data_tbl_copy tbody').append(tr);
                }
            }
            
            // for orignal
            $('#data_tbl thead tr th').addClass("border border-dark");
            $('#data_tbl tbody tr td').addClass("border border-dark border-top-0 border-bottom-0");
            $('#data_tbl tfoot tr td').addClass("active border border-dark");

            $('#confirm_tbl thead tr th').addClass("border border-dark border-bottom-0");
            $('#confirm_tbl tbody tr td').addClass("border border-dark border-top-0 pt-3 pb-4");

            // for copy
            $('#data_tbl_copy thead tr th').addClass("border border-dark");
            $('#data_tbl_copy tbody tr td').addClass("border border-dark border-top-0 border-bottom-0");
            $('#data_tbl_copy tfoot tr td').addClass("active border border-dark");

            $('#confirm_tbl_copy thead tr th').addClass("border border-dark border-bottom-0");
            $('#confirm_tbl_copy tbody tr td').addClass("border border-dark border-top-0 pt-3 pb-4");
        });

    })(jQuery);
</script>
{{-- @endpush --}}
</body>
</html>
