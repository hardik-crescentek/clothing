@extends('layouts.master')
@section('title', 'Inventory')
@section('content')
<!-- Begin Page Header-->
<div class="row">
    <div class="page-header">
        <div class="d-flex align-items-center">
            <h2 class="page-header-title">Inventory</h2>
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
                {!! Form::open(['method' => 'GET','route' => ['inventory']]) !!}
                <div class="form-group row d-flex align-items-center">
                    <!-- <div class="col-lg-3">
                        <label class="form-control-label">Search <div class="d-inline text-muted" style="font-size: 10px;">[Batch No/Barcode/QR Code]</div></label>
                        {!! Form::text('search', $search, array('class' => 'form-control')) !!}
                    </div> -->
                    <div class="col-lg-2">
                        <label class="form-control-label">Article No </label>
                        {!! Form::select('search_article',$article_no,$article , array('class' => 'form-control','id'=>'search_article')) !!}
                    </div>
                    <div class="col-lg-2">
                        <label class="form-control-label">Color </label>
                        {!! Form::select('color',$colors,$color , array('class' => 'form-control','id'=>'color')) !!}
                    </div>
                    <!-- <div class="col-lg-2">
                        <label class="form-control-label">Invoice No</label>
                        {!! Form::text('invoice_no', $invoice_no, array('class' => 'form-control')) !!}
                    </div> -->
                    <div class="col-lg-2">
                        <label class="form-control-label">Materials</label>
                        {!! Form::select('material_id', $materials, $material_id, array('class' => 'form-control custom-select')) !!}
                    </div>
                    <div class="col-lg-2">
                        <label class="form-control-label">&nbsp;</label>
                        <div class="form-action">
                            <a href="{{ url('/inventory') }}" class="btn btn-warning btn-square">Reset</a>
                            <input type="submit" class="btn btn-primary btn-square" value="Filter">
                        </div>
                    </div>
                </div>
                {!! Form::close() !!}
            </div>
        </div>
    </div>
</div>
<div class="row flex-row">
    <div class="col-xl-12 col-12">
        <div class="widget has-shadow">
            <!-- <div class="widget-header bordered no-actions d-flex align-items-center">
                <h4>Inventory Items</h4>
            </div> -->
            <div class="widget-body">
                <div class="row">
                    <div class="col-md-12">

                        <div class="table-responsive">
                            <table id="tblPurchaseItems_edit" class="table table-hover order-list">
                                <thead>
                                    <tr>
                                        <th>Invoice No</th>
                                        <th class="sorter-shortDate dateFormat-ddmmyyyy">Date Of Purchase</th>
                                        <th>Material</th>
                                        <th>Article No</th>
                                        <th>Color NO</th>
                                        <th>Color</th>
                                        <th>Batch No</th>
                                        <th data-sorter="false" style="width:150px;">Roll No</th>
                                        <th style="width:150px;" class="meter">Available Meter / Total Meter</th>
                                        <th style="width:150px;" class="yard">Available Yard / Total Yard</th>
                                        <th data-sorter="false">Barcode</th>
                                        <th data-sorter="false">QRCode</th>
                                        <th>Cost</th>
                                        <th>Sold At</th>
                                        <th>Salesman Commision</th>
                                        <th>Discount</th>
                                        <th>Profit</th>
                                        <th>Vat</th>
                                        <th>Image</th>
                                        <th data-sorter="false" style="width:50px;">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @isset($items)
                                    @foreach ($items as $key => $item)
                                    <tr id="item-{{$item->id}}" class=@if ($item->qty==$item->available_qty) "table-success text-dark" @elseif($item->available_qty==0) "table-danger text-dark" @else "table-warning text-dark" @endif >
                                        <td>{{ $item->purchase->invoice_no }}</td>
                                        <td>{{ $item->purchase->purchase_date }}</td>
                                        <td>{{ $item->material?$item->material->name:'' }}</td>
                                        <td>
                                            <div id="print-article-{{$item->id}}">{{ $item->article_no }}</div>
                                        </td>
                                        <td>
                                            <div id="print-color-no-{{$item->id}}">{{$item->material?$item->material->color_no:''}}</div>
                                        </td>
                                        <td>
                                            <div id="print-color-{{$item->id}}">{{$item->material?$item->material->color:''}}</div>
                                        </td>
                                        <td>
                                            <div id="print-batch-{{$item->id}}">{{ $item->batch_no }}</div>
                                        </td>
                                        <td>
                                            <div id="print-roll-no-{{$item->id}}">{{ $item->roll_no }}</div>
                                        </td>
                                        <td>
                                            {{ $item->available_qty }} / {{ $item->qty }}
                                        </td>
                                        <td>
                                            {{ number_format((float)(meter2yard($item->available_qty)),2,'.','') }} / {{ number_format((float)(meter2yard($item->qty)),2,'.','') }}
                                        </td>
                                        <td>
                                            <div id="print-barcode-{{$item->id}}">{!! DNS1D::getBarcodeSVG($item->barcode,config('app.BARCODE_TYPE'), 1, 40)  !!}</div>
                                        </td>
                                        <td>
                                            <div id="print-qrcode-{{$item->id}}">{!!  DNS2D::getBarcodeSVG($item->article_no,'QRCODE')  !!}</div>
                                        </td>
                                        <td>
                                            {{ $item->purchase->price_thb }}
                                        </td>
                                        <td>
                                            {{ total_per_meter($item->purchase->price_thb,$item->purchase->shipping_cost_per_meter) }}
                                        </td>
                                        <td>

                                        </td>
                                        <td>
                                            {{ $item->purchase->discount }}
                                        </td>
                                        <td>

                                        </td>
                                        <td>

                                        </td>
                                        <td>
                                            <a href="{!! attechment_url($item->purchase->attachment)!!}" class="btn btn-primary btn-square btn-sm" target="_blank">Open Attechment</a>
                                        </td>
                                        <td>
                                            <button class="btn btn-secondary btn-sm btn-square mx-3" onclick="print_barcode('{{$item->id}}')">Print</button>
                                            <button type="button" class="btn btn-sm btn-primary btn-square mt-2 btn-roll-history" data-item_id="{{$item->id}}" data-total_roll="{{$item->qty}}" data-available_roll="{{$item->available_qty}}" data-name="{{$item->material?$item->material->name:''}}" data-article_no="{{$item->article_no}}" data-color_no="{{$item->color_no}}" data-color="{{$item->color}}" data-roll_no="{{$item->roll_no}}" data-toggle="modal" data-target="#rollHistoryModel">Roll History</button>
                                        </td>
                                    </tr>
                                    @endforeach
                                    @endisset
                                </tbody>
                            </table>
                            <!-- @isset($items)
                            {{ $items->render() }}
                            @endisset -->
                        </div>

                    </div>
                </div>

            </div>

        </div>
    </div>
</div>
<div id="rollHistoryModel" tabindex="-1" role="dialog" aria-labelledby="Edit" aria-hidden="true" class="modal fade">
    <div role="document" class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 id="modal-header" class="modal-title"><div id="header_info"></div></h5>
                <button type="button" data-dismiss="modal" aria-label="Close" class="close"><span aria-hidden="true">×</span></button>
            </div>
            <div class="modal-body">
                <div class="container">
                    <div class="row">
                        <div class="col-12 ">
                            <div style="float: left;" id="total_roll">
                            </div>
                            <div style="float: right;" id="available_roll">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="table-responsive">
                    <table class="table" id="rollhistorytbl">
                        <thead class="text-center">
                            <tr>
                                <th>Invoice Number</th>
                                <th>Customer Name</th>
                                <th>Price</th>
                                <th>Meter</th>
                                <th>Yard</th>
                                <th>Date</th>
                            </tr>
                            </thead>
                            <tbody>

                            </tbody>
                    </table>
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

    function print_barcode(id) {
        var color = document.getElementById('print-color-' + id).innerHTML;
        var article_no = document.getElementById('print-article-' + id).innerHTML;
        var batch_no = document.getElementById('print-batch-' + id).innerHTML;
        var roll_no = document.getElementById('print-roll-no-' + id).innerHTML;
        var barcode = document.getElementById('print-barcode-' + id).innerHTML;
        var qrcode = document.getElementById('print-qrcode-' + id).innerHTML;

        var html = `<body onload="window.print()">
                        <table style="width: 100%;"><tr>
                            <td style="text-align: center; padding:20px;">
                                    ` + barcode + `
                                <div style="width: 100%;font-family: verdana; margin-top:3px;">
                                    <h6 style="margin:0;font-weight: normal;line-height: 16px;">Batch No.: ` + batch_no + `</h6>
                                    <h6 style="margin:0;font-weight: normal;line-height: 16px;">Article No.: ` + article_no + `</h6>
                                    <h6 style="margin:0;font-weight: normal;line-height: 16px;">Roll No.: ` + roll_no + `</h6>
                                </div>
                            </td>
                            <td style="text-align: center; padding:20px;">
                                    ` + qrcode + `
                                <div style="width: 100%;font-family: verdana; margin-top:3px;">
                                    <h6 style="margin:0;font-weight: normal;line-height: 16px;">Batch No.: ` + batch_no + `</h6>
                                    <h6 style="margin:0;font-weight: normal;line-height: 16px;">Article No.: ` + article_no + `</h6>
                                    <h6 style="margin:0;font-weight: normal;line-height: 16px;">Roll No.: ` + roll_no + `</h6>
                                </div>
                            </td>
                            </tr></table></body>`;

        var newWin = window.open('', 'Print-Window');
        newWin.document.open();
        newWin.document.write(html);
        setTimeout(function() {
            newWin.document.close();
            newWin.close();
        }, 10);
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
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.tablesorter/2.31.3/js/jquery.tablesorter.min.js" integrity="sha512-qzgd5cYSZcosqpzpn7zF2ZId8f/8CHmFKZ8j7mU4OUXTNRd5g+ZHBPsgKEwoqxCtdQvExE5LprwwPAgoicguNg==" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/js/select2.min.js"></script>
<script type="text/javascript">

    (function($) {
        // $('#tblPurchaseItems_edit').tablesorter({
        //     cssAsc: 'up',
        //     cssDesc: 'down',
        //     cssNone: 'both'
        // });
        $('#tblPurchaseItems_edit').DataTable({
            lengthMenu: [
                [10, 25, 50,100,500,1000,'All'],
                [10, 25, 50,100,500,1000,'All'],
            ],
            "aaSorting": []
        });
        $('#search_article').select2();
        $('#color').select2();
        $('#rollHistoryModel').on('shown.bs.modal', function(e) {
            $('#rollhistorytbl tbody').empty();
            var item_id = $(e.relatedTarget).data('item_id');
            var total_roll=$(e.relatedTarget).data('total_roll');
            var available_roll=$(e.relatedTarget).data('available_roll');
            var name = $(e.relatedTarget).data('name');
            var article_no = $(e.relatedTarget).data('article_no');
            var color_no = $(e.relatedTarget).data('color_no');
            var color = $(e.relatedTarget).data('color');
            var roll_no = $(e.relatedTarget).data('roll_no');
            $('#total_roll').html("Total Roll : "+total_roll);
            $('#available_roll').html("Available Roll : "+available_roll);
            $('#header_info').html(name + " - " + article_no + " - " + color_no + " - " + color + " - " + roll_no);
            $.ajax({
                url: '{{route("purchase.roll-history")}}',
                data: {'item_id' : item_id},
                dataType: "json",
                success: function(data) {
                    var tr;
                    if(data.length){
                        $.each(data,function(i,v){
                                var date=new Date(v.created_at);
                                tr="<tr><td>"+v.invoice.invoice_no+"</td>";
                                tr+="<td>"+v.invoice.customer.firstname+" "+v.invoice.customer.lastname+"</td>";
                                tr+="<td>"+v.invoice_item.price+"</td>";
                                tr+="<td>"+v.meter+"</td>";
                                tr+="<td>"+meter2yard(v.meter).toFixed(2)+"</td>";
                                tr+="<td>"+date.getDate()+" / "+(date.getMonth()+1)+" / "+date.getFullYear()+"</td></tr>";
                        });
                    }
                    else{
                        tr="<tr class='text-center'><td colspan=6> No History For This Roll </td></tr>";
                    }

                    $('#rollhistorytbl tbody').append(tr);
                }
            });
        });

        // Article Wise Color Selection

        $(document).on('change','#search_article',function(){
                var article_no = $(this).val();
                $("#color").html('');
                $.ajax({
                    url: "{{ route('materials.index') }}",
                    dataType: "json",
                    data: {
                        article: article_no
                    },
                    success: function(data) {
                        console.log(data);
                        $.each(data,function(i){
                            $("#color").append(`<option value="${i}">${data[i]}</option>`); 
                        })
                    }
                });
            });
    })(jQuery);
</script>
@endpush
