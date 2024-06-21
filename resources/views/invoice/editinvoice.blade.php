@extends('layouts.master')
@section('title', 'Invoice')
@section('content')


<!-- Begin Row -->
<div class="row flex-row">
    <div class="col-xl-12 col-12">
        <div class="widget has-shadow">
            <div class="widget-header bordered no-actions d-flex align-items-center">
                <h4>Edit Invoice</h4>
            </div>
            <div class="widget-body">
                @if (count($errors) > 0)
                <div class="alert alert-danger">
                    <strong>Whoops!</strong> There were some problems with your input.<br><br>
                    <ul>
                        @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
                @endif
                {!! Form::open(array('route' => ['invoice.update', $invoice->id],'method'=>'PATCH','id'=>'from_edit_invoice', 'class'=>"form-horizontal form-validate", 'novalidate')) !!}

                <div class="form-group row">
                    <div class="col-xl-3 mb-3 ">
                        <label class="form-control-label">Invoic No<span class="text-danger ml-2">*</span></label>
                        <div class="row">
                            <div class="col-12">
                                {!! Form::text('invoice_no', $invoice->invoice_no, array('id' => 'invoice_no','class' => 'form-control', 'data-validation'=>"required","readonly"=>"readonly")) !!}
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-3 mb-3 ">
                        <label class="form-control-label">Order No<span class="text-danger ml-2">*</span></label>
                        <div class="row">
                            <div class="col-12">
                                {!! Form::text('order_no', $invoice->order_id, array('id' => 'invoice_no','class' => 'form-control', 'data-validation'=>"required","readonly"=>"readonly")) !!}
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-6 mb-3 ">
                        <label class="form-control-label">Date of invoice<span class="text-danger ml-2">*</span></label>
                        <div class="row">
                            <div class="col-12">
                                {!! Form::text('generate_date', null, array('id' => 'generate_date','class' => 'form-control', 'data-validation'=>"required")) !!}
                            </div>
                        </div>
                    </div>
                </div>
                <div class="form-group row mb-3">
                    <div class="col-xl-6 mb-3">
                        <label class="form-control-label">Customer<span class="text-danger ml-2">*</span></label>
                        <div class="row">
                            <div class="col-12">
                                <div class="form-control" readonly="readonly" id="customer_id">{{$invoice->customer->fullName}}</div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-6 mb-3">
                        <label class="form-control-label">Sales Person<span class="text-danger ml-2">*</span></label>
                        <div class="row">
                            <div class="col-12">

                                <div class="form-control" readonly="readonly" id="seller_id">{{$invoice->seller->fullName}}</div>
                            </div>
                        </div>
                    </div>

                </div>
                <div class="form-group row mb-3">
                    <div class="col-xl-6 mb-3">
                        <div class="row">
                            <div class="col-6">
                                <label class="form-control-label">Payment Terms<span class="text-danger ml-2">*</span></label>
                                {!! Form::select('payment_terms', ['cash' => 'Cash', 'credit' => 'Credit by Days'],$invoice->payment_terms, array('id'=>'payment_terms','class' => 'form-control custom-select', 'data-validation'=>"required")) !!}
                            </div>
                            <div class="col-6" id="row-credit-days">
                                <label class="form-control-label">Credit Days</label>
                                {!! Form::text('credit_days', $invoice->credit_days, array('id'=>'credit_days','class' => 'form-control')) !!}
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-6 mb-3">
                        <label class="form-control-label">Payment Receiver<span class="text-danger ml-2">*</span></label>
                        <div class="row">
                            <div class="col-7">
                                {!! Form::select('payment_receiver_id', $payment_receiver,$invoice->payment_receiver_id, array('id'=>'seller_id','class' => 'form-control custom-select', 'data-validation'=>"required")) !!}
                            </div>
                            <div class="col-5">
                                <a href="{{ route('users.create', ['redirect' =>  base64_encode(route('invoice.edit',$invoice->id))]) }}" class="btn btn-primary btn-square">Add Payment Receiver</a>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="form-group row mb-3">
                    <div class="col-xl-6 mb-3">
                        <div class="row">
                            <div class="col-6">
                                <label class="form-control-label">Sales Type<span class="text-danger ml-2">*</span></label>
                                {!! Form::select('sales_type', ['','Local', 'Out of state', 'International'],null, array('id'=>'sales_type','class' => 'form-control custom-select', 'data-validation'=>"required")) !!}
                            </div>
                            <div class="col-6">
                                <label class="form-control-label">Charge in Unit</label>
                                <div class="mt-2 d-flex">
                                    <div class="styled-radio mr-4">
                                        {!! Form::radio('chareg_in_unit', 'Yards', true, array('class' => 'custom-control-input', 'id'=>"chareg_in_unit_yards")) !!}
                                        <label for="chareg_in_unit_yards">Yards</label>
                                    </div>
                                    <div class="styled-radio">
                                        {!! Form::radio('chareg_in_unit', 'Mtrs', false, array('class' => 'custom-control-input', 'id'=>"chareg_in_unit_mtrs")) !!}
                                        <label for="chareg_in_unit_mtrs">Mtrs</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-6 mb-3">
                        <div class="row">
                            <div class="col-4">
                                <label class="form-control-label">Sales Commision</label>
                                <div class="mt-2 d-flex">
                                    <div class="styled-radio mr-4">
                                        {!! Form::radio('sales_commision', 'No', true, array('class' => 'custom-control-input', 'id'=>"sales_commision_no")) !!}
                                        <label for="sales_commision_no">No</label>
                                    </div>
                                    <div class="styled-radio">
                                        {!! Form::radio('sales_commision', 'Yes', false, array('class' => 'custom-control-input', 'id'=>"sales_commision_yes")) !!}
                                        <label for="sales_commision_yes">Yes</label>
                                    </div>
                                </div>
                            </div>
                            <div class="col-8 commision_part" style="display: none;">
                                <label class="form-control-label">Commision Type</label>
                                <div class="mt-2 d-flex">
                                    <div class="styled-radio mr-4">
                                        {!! Form::radio('commision_type', 'on_unit', true, array('class' => 'custom-control-input', 'id'=>"commision_type_unit")) !!}
                                        <label for="commision_type_unit">On Unit Sold</label>
                                    </div>
                                    <div class="styled-radio">
                                        {!! Form::radio('commision_type', 'on_subtotal', false, array('class' => 'custom-control-input', 'id'=>"commision_type_subtotal")) !!}
                                        <label for="commision_type_subtotal">On Sub Total Amount Of Sale</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="form-group row mb-3 commision_part" style="display: none;">
                    <div class="col-xl-6 mb-3">
                        <div class="row">
                            <div class="col-4">
                                <label class="form-control-label">Commision Amount THB/Unit</label>
                                <div class="form-group">
                                    <div class="input-group">
                                        {!! Form::text('commision_amount_thb', 0, array('id' => 'commision_amount_thb','class' => 'form-control', 'data-validation'=>"required")) !!}
                                        <span class="input-group-addon addon-secondary">THB</span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-8">
                                <label class="form-control-label">Commision Amount on Sale</label>
                                <div class="form-group">
                                    <div class="input-group">
                                        {!! Form::text('commision_amount_sale', 0, array('id' => 'commision_amount_sale','class' => 'form-control', 'data-validation'=>"required")) !!}
                                        @php
                                            $commision_type =   [
                                                                    "percentage" => '%',
                                                                    "thb"        => 'THB',
                                                                ];
                                       @endphp
                                        {!!Form::select('subtotal_commision_type', $commision_type, null, ['class' => 'form-control'])!!}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>


                {{-- <div class="form-group row d-flex align-items-center mb-5">
                    <label class="col-lg-3 form-control-label ">Barcode Number</label>
                    <div class="col-lg-12">
                        <div class="input-group">
                            <span class="input-group-addon addon-secondary"><i class="la la-barcode"></i></span>
                            {!! Form::text('input_search_barcode', null, array('id'=>'input_search_barcode','placeholder' => 'Barcode Number','class' => 'form-control')) !!}

                        </div>
                        <div id='searchlist'></div>
                    </div>
                </div> --}}
                <div class="card">
                    <h4 class="card-header"> <a href="#"  id="price_book" class="btn btn-primary float-right">Price Book</a></h4>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover mb-0 table-striped" id="tblOrderTable">
                                <thead>
                                    <tr>
                                        <th>Item Name</th>
                                        <th>Barcode</th>
                                        <th>Price</th>
                                        <th>Meter</th>
                                        <th>Yard</th>
                                        <th>Weigth</th>
                                        <th>Total Price</th>
                                        <th>Selected Meter</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @isset($items)
                                    @foreach ($items as $key => $item)
                                    {{-- {{ dd($item->item['article_no']) }} --}}
                                    <tr class="material-link accordion-toggle" data-toggle="collapse" data-target="#row-{{$item->id}}" id="item-{{$item->id}}" data-item_id="{{$item->id}}">

                                        <td class="td-material" data-value="{{ $item->item['fullName'] }}">{{ $item->item['fullName'] }}<br>{{ $item->item['article_no'] }}</td>
                                        <td class="td-barcode" data-value="{{ $item->item['barcode'] }}">{!! DNS1D::getBarcodeSVG($item->item['barcode'],config('app.BARCODE_TYPE'), 1, 40) !!}</td>
                                        <td class="td-price price" data-value="{{ $item->price }}">{!! Form::text("price[$item->id]",$item->price,["class"=>"form-control",'id'=>"price",'data-validation'=>"required"]) !!}</td>
                                        <td class="td-meter" data-value="{{ $item->total_meter }}">{{ $item->total_meter }}</td>
                                        <input type="hidden" name="meter[]" value="{{ $item->total_meter }}">
                                        <td class="td-yard" data-value="{{ number_format((float)meter2yard($item->total_meter),2,'.','') }}">{{ number_format((float)meter2yard($item->total_meter),2,'.','') }}</td>
                                        <td class="td-weight" data-value="{{ $item->item['weight'] * $item->total_meter  }}">{{ ($item->item['weight'] * $item->total_meter) }}</td>
                                        <td class="td-total-price" id="total_price" data-value="{{ number_format((float)($item->price)*(meter2yard($item->total_meter)),2,'.','') }}">{{ number_format((float)($item->price)*(meter2yard($item->total_meter)),2,'.','') }}</td>
                                        <td class="td-selected-meter">
                                            <div class="data">{{$item->total_meter}}</div>
                                            {!! Form::hidden("selected_meter[".$item->id."]", $item->total_meter, ["class"=>"selected_meter_".$item->id,"id"=>"selected_meter_".$item->id]) !!}
                                        </td>
                                        <td>
                                            <button type="button" class="btn btn-sm btn-primary btn-square btn-roll-select" data-item_id="{{$item->id}}" data-material_id="{{$item->item['id']}}" data-toggle="modal" data-target="#rollSelectModel">Select Roll</button>
                                            <div id='item-rolls-{{$item->id }}' class="hidden_div">
                                                @foreach ($item->invoice_item_roll as $key=> $value)
                                                <?php $roll_id = $value->roll->id; ?>
                                                {!! Form::hidden("item_roll[$item->id][$roll_id]", $value->meter, ["id"=>"item_roll_$item->id"."_"."$roll_id"]) !!}
                                                @endforeach
                                            </div>
                                        </td>
                                    </tr>
                                    {!! Form::hidden("order_id", $item->order_id, ["class"=>"order_id","id"=>"order_id"]) !!}
                                    @endforeach
                                    @endisset
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <div class=" mt-5">
                    <div class="row">
                        <div class="col-4">
                            <div class="row responsive">
                                <table>
                                    <tr>
                                        &nbsp;&nbsp;&nbsp;&nbsp;
                                        <td>
                                            Total Item :- {{ count($items) }}
                                        </td>
                                        <td>
                                            &nbsp;&nbsp;
                                        </td>
                                        <td>
                                            Total Weight :-
                                            <?php
                                                $total = 0;
                                                foreach ($items as $item){
                                                    $total += ($item->item['weight'] ? $item->item['weight'] : 0) * $item->total_meter;
                                                }
                                            ?>
                                            {{$total}}
                                        </td>
                                    </tr>
                                    {{-- <tr>
                                        <td>
                                            @foreach ($items as $key => $item)
                                            {{ $item->item['fullname'] }} 
                                            <br>    
                                        @endforeach
                                        </td>
                                    </tr> --}}
                                </table>
                            </div>
                        </div>
                        <div class="col-2">

                        </div>
                        <div class="col-6">
                            <table class="table responsive" Cellspacing="0">
                                <tbody>
                                    <tr>
                                        <td><label class="form-control-label">Sub Total</label></td>
                                        <td>
                                            <div id="sub-total" class="input-group">{!! Form::text("sub_total", $invoice->sub_total, ['class'=>'form-control sub-total','id'=>'sub_total','data-validation'=>"required",'placeholder'=>'Sub Total','readonly'=>'readonly']) !!}</div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><label class="form-control-label">Tax</label></td>
                                        <td>
                                            <div id="tax" class="input-group">
                                                {!! Form::text('tax', $invoice->tax, array('placeholder' => 'Tax','id'=>'tax','class' => 'form-control tax')) !!}
                                                <span class="input-group-addon addon-secondary">%</span>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><label class="form-control-label">Discount</label></td>
                                        <td>
                                            <div id="discount" class="input-group">
                                                {!! Form::text("discount", $invoice->discount, ['class'=>'form-control discount','id'=>'discount','placeholder'=>'Discount']) !!}
                                                {!! Form::select("discount_type", ["%","$"], $invoice->discount_type, ['class'=>"form-control",'id'=>'discount_type']) !!}
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><label class="form-control-label">Grand Total</label></td>
                                        <td>
                                            <div id="grand-total" class="input-group">
                                                {!! Form::text("grand_total", $invoice->grand_total, ['class'=>'form-control grand_total','id'=>'grand_total','data-validation'=>"required",'placeholder'=>'Grand Total']) !!}
                                            </div>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                            <div class="float-right mt-3">
                                <button type="submit" class="btn btn-primary btn-lg btn-square" id="final_save_btn">Save Invoice</button>
                            </div>
                        </div>
                    </div>
                </div>

                {!! Form::close() !!}
            </div>
        </div>
    </div>
</div>
<div id="rollSelectModel" tabindex="-1" role="dialog" aria-labelledby="Edit" aria-hidden="true" class="modal fade lg text-left">
    <div role="document" class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 id="modal-header" class="modal-title">Select roll for <span class="span-modal-header"> </span></h5>
                <button type="button" data-dismiss="modal" aria-label="Close" class="close"><span aria-hidden="true">×</span></button>
            </div>
            <div class="modal-body">
                {{-- {!! Form::open(array('method'=>'POST','id'=>'roll_select_form', 'class'=>"form-horizontal form-validate", 'novalidate')) !!} --}}
                <input type="hidden" id="order_item_id" name="orderItemId" value="" />
                {{-- <div class="form-group">
                    <label class="form-control-label">Roll No<span class="text-danger ml-2">*</span></label>
                    {!! Form::select('roll',[],null, array('id'=>'roll','class' => ' roll form-control', 'data-validation'=>"required",)) !!}
                </div> --}}
                <table class="table" id="tblRoll">
                    <thead>
                        <tr>
                            <th>Select</th>
                            <th>Roll No</th>
                            <th>Article No</th>
                            <th>Color No</th>
                            <th>Batch No</th>
                            <th>Available Meter</th>
                            <th>Option</th>
                            <th>Meter</th>
                        </tr>
                    </thead>
                    <tbody>

                    </tbody>
                </table>
                <div class="error1 alert alert-danger" style="display: none"></div>
                <div class="error2 alert alert-danger" style="display: none"></div>
                <div class="form-action float-right ">
                    <h4 class="d-inline">Meter : <div id="remember_meter" class="d-inline mr-3"></div>
                    </h4>
                    <h4 class="d-inline">Total Selected Meter : <div id="total_selected_meter" class="d-inline mr-3"></div>
                    </h4>
                    <h4 class="d-inline">Show +/- : <div id="show_extra" class="d-inline mr-3"></div>
                    </h4>
                    <button type="submit" id="model_save_btn" class="btn btn-primary" disabled="true">Save</button>
                </div>
                {{-- {!! Form::close() !!} --}}
            </div>
        </div>
    </div>
</div>
<div id="price_book_model" tabindex="-1" role="dialog" aria-labelledby="Edit" aria-hidden="true" class="modal fade xl text-left">
    <div role="document" class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header flex-wrap">
                <h5 class="modal-title"><span class="span-modal-header text-capitalize" id="customer-name"></span></h5>
                <button type="button" data-dismiss="modal" aria-label="Close" class="close"><span aria-hidden="true">×</span></button>
            </div> 
            <div class="modal-body">
                <table class="table" >
                    <thead>
                        <tr>
                            <th>Material Name </th>
                            <th>Article Code</th>
                            <th>Wholesale</th>
                            <th>Retail </th>
                            <th>Sample </th>
                            <th>Remark Note </th>
                            {{-- <th>Order Date</th> --}}
                            <th>Edit Price</th>

                        </tr>
                    </thead>
                    <tbody id="tblpricebook">

                    </tbody>
                </table>
            </div>
            {{-- <div class="modal-footer">

            </div> --}}
        </div>
    </div>
</div>
<script type="text/template" id="templateAddItem">
    <td>{!! Form::checkbox('select[]',0,false, array('id'=>'select_roll','class' => 'form-control select_roll')) !!}</td>
        <td>{!! Form::text('roll_no[]',null, array('id'=>'roll_no','class' => 'form-control roll_no', 'data-validation'=>"required",'readonly'=>'readonly')) !!}</td>
        {!! Form::hidden('roll_id[]',null, array('id'=>'roll_id','class' => 'form-control roll_id', 'data-validation'=>"required",)) !!}
        <td>{!! Form::text('article_no[]',null, array('id'=>'article_no','class' => 'form-control article_no', 'data-validation'=>"required",'readonly'=>'readonly')) !!}</td>
        <td>{!! Form::text('color_no[]',null, array('id'=>'color_no','class' => 'form-control input-sm color_no', 'data-validation'=>"required",'readonly'=>'readonly')) !!}</td>
        <td>{!! Form::text('batch_no[]',null, array('id'=>'batch_no','class' => 'form-control batch_no', 'data-validation'=>"required",'readonly'=>'readonly')) !!}</td>
        <td>{!! Form::text('available_qty[]',null, array('id'=>'available_qty','class' => 'form-control available_qty', 'data-validation'=>"required",'readonly'=>'readonly')) !!}</td>
        <td>{!! Form::Select('option[]',["0"=>"Take All","1"=>"Cut As Per Order","2"=>"Type How Much To Cut"],null, array('id'=>'option','class' => 'form-control input-sm option', 'data-validation'=>"required",'disabled'=>'disabled')) !!}</td>
        <td>{!! Form::number('meter[]',null, array('id'=>'meter','class' => 'form-control meter', 'data-validation'=>"required",'readonly'=>'readonly')) !!}</td>
</script>
@endsection
@push('scripts')
<script src="{{ asset('assets/js/datepicker/moment.min.js') }}"></script>
<script src="{{ asset('assets/js/datepicker/daterangepicker.js') }}"></script>
<script type="text/javascript">
    (function($) {
        checkPaymentTermsval(); 
        function checkPaymentTermsval(){
            $(document).on('change','#payment_terms',function(){
                var p_val = $(this).val();
                if (p_val == 'credit') {
                    $('#row-credit-days').css('display','block');
                }else{
                    $('#row-credit-days').css('display','none');
                }
            });
        }

        $('#generate_date').daterangepicker({
            singleDatePicker: true,
            showDropdowns: true,
            locale: {
                format: 'DD/MM/YYYY'
            }
        });
        // $('#select-roll').on('click',function(e){
        //     var item_id = $('input.item_id').val();

        //     console.log(item_id);
        // });
        var roll_data;
        var modal_item_id;
        $('#rollSelectModel').on('shown.bs.modal', function(e) {
            $('#tblRoll tbody').empty();
            modal_item_id = "";
            var item_id = $(e.relatedTarget).data('item_id');
            modal_item_id = item_id;
            $('#order_item_id').val(item_id);
            var material_id = $(e.relatedTarget).data('material_id');
            $.when(
                $.ajax({
                    url: '{{route("invoice.get-roll")}}',
                    data: {
                        'material_id': material_id
                    },
                    dataType: "json",
                    success: function(data) {
                        roll_data = data.roll;
                        // $('#roll').empty();
                        // $('#roll').append(new Option("--Select Roll--"));
                        $.each(roll_data, function(index, value) {
                            // $('#roll').append(new Option(value.roll_no + " [ Meter : " +value.available_qty+" ]", value.id));
                            addRoll(value);
                        })
                    }
                })).then(function() {
                $('#remember_meter').html($('#item-' + item_id + ' .td-meter').data("value"));
                $('#total_selected_meter').html(0);
                if (!$('#item-rolls-' + item_id).is(':empty')) {
                    var meter = 0;
                    var user_enter_val = 0;
                    $.each($('#item-rolls-' + item_id + ' input'), function(i, v) {
                        var str = v.id;
                        var n = str.lastIndexOf("_");
                        var roll_id = str.substring(n + 1);
                        var data;
                        // $.each(roll_data,function(index,value){
                        //     if(value.id==roll_id){
                        //         data={
                        //             'id':value.id,
                        //             'roll_no':value.roll_no,
                        //             'article_no':value.article_no,
                        //             'batch_no':value.batch_no,
                        //             'available_qty':value.available_qty,
                        //             'meter':v.value,
                        //         };
                        //     }

                        // });
                        $.each($('.select_roll'), function(index, value) {
                            if (value.value == roll_id) {
                                value.checked = true;
                                $(value).closest('tr').addClass('table-success');
                                $(value).closest('tr').find('.meter').val($('#item_roll_' + item_id + '_' + roll_id).val());
                                // $(value).closest('tr').find('.meter').attr('readonly',false);
                                $(this).closest('tr').find('.option').attr('disabled', false);
                            }
                        });
                        meter += parseFloat(v.value);
                        // console.log(roll_id);
                        // addRoll(data);
                    });
                    $('#total_selected_meter').html(meter.toFixed(2));
                    user_enter_val = parseFloat($('#remember_meter').html());
                    $('#show_extra').html((meter - user_enter_val).toFixed(2));

                }
            });

        });

        function addRoll(data) {

            $template = $('#templateAddItem').html();
            var $uniqueId = uuid();
            var $tr = $('<tr class="roll_item " id="' + $uniqueId + '">').append($template);
            $('#tblRoll tbody').append($tr);
            $('#' + $uniqueId).find('.roll_id').val(data.id);
            $('#' + $uniqueId).find('.select_roll').val(data.id);
            $('#' + $uniqueId).find('.roll_no').val(data.roll_no);
            $('#' + $uniqueId).find('.article_no').val(data.article_no);
            $('#' + $uniqueId).find('.color_no').val(data.color_no);
            $('#' + $uniqueId).find('.batch_no').val(data.batch_no);
            $('#' + $uniqueId).find('.available_qty').val(data.available_qty);
            $('#' + $uniqueId).find('.meter').attr("max", data.available_qty);
            $('#' + $uniqueId).find('.roll_delete_btn').data('id', $uniqueId);
            if (data.meter) {
                $('#' + $uniqueId).find('.meter').val(data.meter);
            }

        }
        // $('#roll').on('change',function(){
        //     var id=$(this).children("option:selected").val();
        //     var data;
        //     $.each(roll_data,function(i,v){
        //         if(v.id==id){
        //             data={
        //                 'id':v.id,
        //                 'roll_no':v.roll_no,
        //                 'article_no':v.article_no,
        //                 'batch_no':v.batch_no,
        //                 'available_qty':v.available_qty,
        //             };
        //         }
        //     });
        //     addRoll(data);

        // });
        $(document).on('change', '#select_roll', function() {
            var selected_meter = 0;
            var user_enter_val = 0;
            if (this.checked) {
                $(this).closest('tr').addClass('table-success');
                // $(this).closest('tr').find('.meter').attr('readonly',false);
                $(this).closest('tr').find('.option').attr('disabled', false);
                $(this).closest('tr').find('.meter').val($(this).closest('tr').find('.meter').attr('max'));
                $.each($('.meter'), function(i, v) {
                    if (v.value != '') {
                        selected_meter += parseFloat(v.value);
                    }
                })
                // selected_meter+=parseFloat($(this).closest('tr').find('.meter').val());
                $('#total_selected_meter').html(selected_meter.toFixed(2));
                user_enter_val = parseFloat($('#remember_meter').html());
                $('#show_extra').html((selected_meter - user_enter_val).toFixed(2));

            } else {
                $(this).closest('tr').removeClass('table-success').removeClass('table-danger');
                // $(this).closest('tr').find('.meter').attr('readonly',true);
                $(this).closest('tr').find('.option').attr('disabled', true);
                $(this).closest('tr').find('.meter').val('');
                $.each($('.meter'), function(i, v) {
                    if (v.value != '') {
                        selected_meter += parseFloat(v.value);
                    }
                })
                // selected_meter-=parseFloat($(this).closest('tr').find('.meter').val());
                $('.error1').css('display', 'none').fadeOut();
                $('#model_save_btn').attr('disabled', false);
                $('#total_selected_meter').html(selected_meter.toFixed(2));
                user_enter_val = parseFloat($('#remember_meter').html());
                if (selected_meter) {
                    $('#show_extra').html((selected_meter - user_enter_val).toFixed(2));
                } else {
                    $('#show_extra').html(selected_meter.toFixed(2));
                }
            }
        });
        $(document).on('change', '.option', function() {
            var option_value = $(this).val();
            var selected_meter = 0;
            var user_enter_val = 0;
            if (option_value == '0') {
                $(this).closest('tr').find('.meter').attr('readonly', true);
                $.each($('.meter'), function(i, v) {
                    if (v.value != '') {
                        selected_meter += parseFloat(v.value);
                    }
                });

                $('#total_selected_meter').html(selected_meter.toFixed(2));
                user_enter_val = parseFloat($('#remember_meter').html());
                $('#show_extra').html((selected_meter - user_enter_val).toFixed(2));
            }
            if (option_value == '1') {
                var available_qty = parseFloat($(this).closest('tr').find('#available_qty').val());
                var user_enter_val = parseFloat($('#remember_meter').html());
                var meter = user_enter_val > available_qty ? available_qty : user_enter_val;
                $(this).closest('tr').find('.meter').val(meter);
                $.each($('.meter'), function(i, v) {
                    if (v.value != '') {
                        selected_meter += parseFloat(v.value);
                    }
                });

                $('#total_selected_meter').html(selected_meter.toFixed(2));
                user_enter_val = parseFloat($('#remember_meter').html());
                $('#show_extra').html((selected_meter - user_enter_val).toFixed(2));
            }
            if (option_value == '2') {
                var available_qty = parseFloat($(this).closest('tr').find('#available_qty').val());
                $(this).closest('tr').find('.meter').attr('readonly', false).val(available_qty);
                $.each($('.meter'), function(i, v) {
                    if (v.value != '') {
                        selected_meter += parseFloat(v.value);
                    }
                });

                $('#total_selected_meter').html(selected_meter.toFixed(2));
                user_enter_val = parseFloat($('#remember_meter').html());
                $('#show_extra').html((selected_meter - user_enter_val).toFixed(2));
            }
        });
        $(document).on('change', '#tblRoll .meter', function() {
            var meter = 0;
            var user_enter_val = 0
            $.each($('.meter'), function(i, v) {
                if (v.value > parseInt($(v).attr('max'))) {
                    $('.error1').css('display', 'block').html("Maximum Value Is : " + $(v).attr('max'));
                    $(v).closest('tr').addClass("table-danger");
                    $('#model_save_btn').attr('disabled', true);
                    // $('#total_selected_meter').html(v.value);
                    return false;
                } else {
                    $(v).closest('tr').removeClass('table-danger').addClass("table-success");
                    $('.error1').css('display', 'none').fadeOut();
                    $('#model_save_btn').attr('disabled', false);
                    // $('#total_selected_meter').html(v.value);
                }
                if (v.value != '') {
                    meter += parseFloat(v.value);
                }
                $('#total_selected_meter').html(meter.toFixed(2));
                user_enter_val = parseFloat($('#remember_meter').html());
                $('#show_extra').html((meter - user_enter_val).toFixed(2));
            })
            var total_meter = parseInt($('#item-' + modal_item_id + ' .td-meter').data("value"));
            // if((total_meter-meter)<0){
            //         $('.error2').css('display','block').html("Please Check Your Meter's Value");
            //         // $(this).closest('tr').addClass("table-danger");
            //         $('#remember_meter').html(0);
            //         $('#model_save_btn').attr('disabled',true);
            //     }
            //     else{
            //         // $(this).closest('tr').removeClass("table-danger").addClass("table-success");
            //         $('.error2').css('display','none').fadeOut();
            //         $('#remember_meter').html(total_meter-meter);
            //         $('#model_save_btn').attr('disabled',false);
            //     }
            // console.log(total_meter-meter);
            // $('#remember_meter').html(total_meter-meter);
        });
        $(document).on('keyup', '#tblRoll .meter', function() {
            var meter = 0;
            var user_enter_val = 0;
            $.each($('.meter'), function(i, v) {
                if (v.value > parseInt($(v).attr('max'))) {
                    $('.error1').css('display', 'block').html("Maximum Value Is : " + $(v).attr('max'));
                    $(v).closest('tr').addClass("table-danger");
                    $('#model_save_btn').attr('disabled', true);
                    $('#total_selected_meter').html(v.value);
                    return false;
                } else {
                    $(v).closest('tr').removeClass('table-danger').addClass("table-success");
                    $('.error1').css('display', 'none').fadeOut();
                    $('#model_save_btn').attr('disabled', false);
                    $('#total_selected_meter').html(v.value);
                }
                if (v.value != '') {
                    meter += parseFloat(v.value);
                }
                $('#total_selected_meter').html(meter.toFixed(2));
                user_enter_val = parseFloat($('#remember_meter').html());
                $('#show_extra').html((meter - user_enter_val).toFixed(2));
            })
            var total_meter = parseInt($('#item-' + modal_item_id + ' .td-meter').data("value"));
            // console.log(total_meter-meter);
            // if((total_meter-meter)<0){
            //     $('.error2').css('display','block').html("Please Check Your Meter's Value");
            //     $('#remember_meter').html(0);
            //     // $(this).closest('tr').addClass("table-danger");
            //     $('#model_save_btn').attr('disabled',true);
            // }
            // else{
            //     // $(this).closest('tr').removeClass("table-danger").addClass("table-success");
            //     $('.error2').css('display','none').fadeOut();
            //     $('#remember_meter').html(total_meter-meter);
            //     $('#model_save_btn').attr('disabled',false);
            // }

        });
        $(document).on('change', '#price', function() {
            var new_price = $(this).val();
            var yard = $(this).closest('tr').find('.td-yard').data('value');
            $(this).closest('tr').find('#total_price').html((new_price * yard).toFixed(2)).data('value', (new_price * yard).toFixed(2));
            sub_total();
            grand_total();
        });
        $(document).on('keyup', '#price', function() {
            var new_price = $(this).val();
            var yard = $(this).closest('tr').find('.td-yard').data('value');
            $(this).closest('tr').find('#total_price').html((new_price * yard).toFixed(2)).data('value', (new_price * yard).toFixed(2));
            sub_total();
            grand_total();
        });
        $('#model_save_btn').on('click', function() {
            // var status;
            // var meter=0;
            // var total_meter=parseInt($('.td-meter').data("value"));
            // $.each($('.meter'),function(i,v){
            //     if(v.value > $(v).attr('max'))
            //     {
            //         meter+=parseInt(v.value);
            //         console.log(v.value);
            //         status = false;
            //         $('.error').removeClass('d-none').html("Maximum Value Of Meter Is : "+$(v).attr('max')).delay(2000).fadeOut();
            //         v.value=$(v).attr('max');
            //     }
            //     else{
            //         status=true;
            //     }
            // });

            // $('#remember_meter').html(total_meter-meter);
            // if(status){
            var item_id = $('#order_item_id').val();
            $('#item-rolls-' + item_id).empty();
            var input_hidden;
            // $.each($('.roll_id'),function(i,v){
            //     var meter=$(v).closest('tr').find('.meter').val();
            //     input_hidden=$('<input>').attr({
            //         name:"item_roll["+item_id+"]["+v.value+"]",
            //         id:"item_roll_"+item_id+"_"+v.value,
            //         type:"hidden",
            //         value:meter,
            //         });

            //     $('#item-rolls-'+item_id).append(input_hidden);
            //     // console.log(input_hidden);
            // });
            $.each($('.select_roll'), function(index, value) {
                if (value.checked) {
                    var roll_id = $(value).closest('tr').find('.roll_id').val();
                    var meter = $(value).closest('tr').find('.meter').val();
                    input_hidden = $('<input>').attr({
                        name: "item_roll[" + item_id + "][" + roll_id + "]",
                        id: "item_roll_" + item_id + "_" + roll_id,
                        type: "hidden",
                        value: meter,
                    });
                    $('#item-rolls-' + item_id).append(input_hidden);
                }
            });
            $('#item-' + item_id + ' .td-selected-meter .data').html($("#total_selected_meter").html());
            $('#item-' + item_id).find('.td-selected-meter #selected_meter_' + item_id).val($("#total_selected_meter").html());
            grand_total();
            $('#rollSelectModel').modal('hide');
            $('#rollSelectModel #tblRoll tbody').html('');
            // }
        });

        $(window).on('load', function() {
            sub_total();

        });

        function sub_total() {
            var total_price = 0.00;
            $.each($('.td-total-price'), function(i, v) {
                total_price += parseFloat($(v).data('value'));
            });
            $('#sub_total').val(total_price.toFixed(2));
        };

        function grand_total() {
            var sub_total = parseFloat($('#sub_total').val());
            var tax_val = $('.tax').val() == '' ? 0 : $('.tax').val();
            var tax = (sub_total * parseFloat(tax_val)) / 100;
            var discount_val = $('.discount').val() == '' ? 0 : $('.discount').val()
            var discount = 0;

            if ($('#discount_type').val() == 0) {
                discount = (sub_total * parseFloat(discount_val)) / 100;
            } else if ($('#discount_type').val() == 1) {
                discount = parseFloat(discount_val);
            }
            var grand_total = sub_total + tax - discount;
            // console.log(grand_total);
            $('#grand_total').val(grand_total.toFixed(2));
        }
        $('#tax').on('keyup', function() {
            grand_total();
        });
        $('#discount').on('keyup', function() {
            grand_total();
        });
        $('#discount_type').on('change', function() {
            grand_total();
        });
        $('#final_save_btn').on('click', function() {
            if ($('.hidden_div').is(':empty')) {
                alert("Please Select Roll");
                return false;
            }
            return true;
        });
        $("#sales_commision_yes").change(function(){
            if( $(this).is(":checked") ){
                $(".commision_part").css("display", "block");
            }
        });
         $("#sales_commision_no").change(function(){
            if( $(this).is(":checked") ){
                $(".commision_part").css("display", "none");
            }
        });
        $(document).on('click','#price_book',function(){
            $('#tblpricebook').html('');
            var user_id = "{{$invoice->customer->id}}";
            getPriceBook(user_id);
        });
        
        $(document).on('click','#edit_price_book',function(){
            var mode = $(this).text();
            var id   = $(this).data('id');

            var saveBtn   = $("button[data-id="+id+"]").html('Save')['0'].outerHTML;
            var cancelBtn = '<button class="btn btn-danger btn-sm btn-square" id="cancle_price_book" data-id="cancle_'+id+'">Cancle</button>';
            
            $("button[data-id="+id+"]").parent().html(saveBtn+cancelBtn);
            $("#wholesale_price_"+id).prop('type','text');
            $("#wholesale_price_day"+id).prop('type','text');
            $("#price_"+id).prop('type','text');
            $("#price_day"+id).prop('type','text');
            $("#sampe_price_"+id).prop('type','text');
            $("#sampe_price_day"+id).prop('type','text');
            $("#note_"+id).css('display','block');
            $("#note_text_"+id).css('display','none')
            $("#wholesale_text_"+id).css('display','none');
            $("#price_text_"+id).css('display','none');
            $("#sample_text_"+id).css('display','none');
            $(".show"+id).css('display','block');

            var wholeSale = $("#wholesale_price_"+id).val();
            var wholeSale_day = $("#wholesale_price_day"+id).val();
            var price = $("#price_"+id).val();
            var price_day = $("#price_day"+id).val();
            var sample = $("#sampe_price_"+id).val();
            var sample_day = $("#sampe_price_day"+id).val();
            var note       = $("#note_"+id).val();
            var materialId = $(this).attr('data-material-id');
            var customerId = $(this).attr('data-client-id');
            if (mode == 'Save') {
                var url = "{{ route('client.update','') }}"+"/"+customerId;
                $.ajax({
                    url : url,
                    type : 'POST',
                    data: {
                        'wholeprice'   : wholeSale,
                        'w_credit_day' : wholeSale_day,
                        'retailprice'  : price,
                        'r_credit_day' : price_day,
                        'sampleprice'  : sample,
                        's_credit_day' : sample_day,
                        'materialid'   : materialId,
                        'note'         : note
                    },
                    datatype: 'JSON',
                    success : function(data){
                        ListDefault(id)
                        getPriceBook(customerId);

                        new Noty({
                            type: 'success',
                            text: data.msg,
                            timeout: 2500
                        }).show();
                    }
                });
            } 
        });

        $(document).on('click','#cancle_price_book',function(){
            var cancleBtnAttr = $(this).data('id');
            var id = cancleBtnAttr.split("_").pop();
           ListDefault(id);
        });

        function getPriceBook(user_id) {
            $('#tblpricebook').html('');
            $.ajax({
                    url : "{{ route('invoice.pricebook','') }}"+"/"+user_id,
                    type: 'get',
                    datatype : 'json',
                    success : function(data){
                        $('#price_book_model').modal('show');
                        var trHTML = '';
                        var totalItem = '';
                        var totalWeight = '';
                        $.each(data, function (i,item) {
                            // var str    = item.created_at;
                            // var substr = str.split('T');
                            // fname      = substr[0];
                            // dteSplit   = fname.split("-");
                            // yr         = dteSplit[0];
                            // month      = dteSplit[1];
                            // day        = dteSplit[2];

                            trHTML  +=  '<tr>'+
                                            '<td>' + item.material.name + '</td><td>'+ item.material.article_no+ '</td>'+
                                            '<td>'+
                                                '<label class="show'+item.id+'" style="display:none">* Price :-</label>'+   
                                                '<input type="hidden" class="form-control" id="wholesale_price_'+item.id+'" value="'+(item.wholesale_price != null ? item.wholesale_price : '0.00')+'">'+
                                                '<label class="show'+item.id+'" style="display:none">* Credit Days :-</label>'+   
                                                '<input type="hidden" class="form-control" id="wholesale_price_day'+item.id+'" value="'+(item.wholesale_credit_days != null ? item.wholesale_credit_days : '0')+'">'+
                                                '<p id="wholesale_text_'+item.id+'">'+
                                                    '<label>* Price :- </label>'+   
                                                    (item.wholesale_price != null ? item.wholesale_price : '0.00')+ 
                                                    '<br>' +
                                                    '<label>* Credit Days :- </label>'+ 
                                                    (item.wholesale_credit_days != null ? item.wholesale_credit_days : '0') +
                                                '</p>'+
                                            '</td>'+
                                            '<td>'+
                                                '<label class="show'+item.id+'" style="display:none">* Price :-</label>'+   
                                                '<input type="hidden" class="form-control" id="price_'+item.id+'" value="'+(item.price != null ? item.price : '0.00')+'">'+
                                                '<label class="show'+item.id+'" style="display:none">* Credit Days :-</label>'+   
                                                '<input type="hidden" class="form-control" id="price_day'+item.id+'" value="'+(item.retail_credit_days != null ? item.retail_credit_days : '0')+'">'+
                                                '<p id="price_text_'+item.id+'">' + 
                                                    '<label>* Price :- </label>'+   
                                                    (item.price != null ? item.price : '0.00') + 
                                                    '<br>' + 
                                                    '<label>* Credit Days :- </label>'+ 
                                                    (item.retail_credit_days != null ? item.retail_credit_days : '0') +
                                                '</p>'+
                                            '</td>'+
                                            '<td>'+
                                                '<label class="show'+item.id+'" style="display:none">* Price :-</label>'+   
                                                '<input type="hidden" class="form-control" id="sampe_price_'+item.id+'" value="'+(item.sample_price != null ? item.sample_price : '0.00')+'">'+
                                                '<label class="show'+item.id+'" style="display:none">* Credit Days :-</label>'+   
                                                '<input type="hidden" class="form-control" id="sampe_price_day'+item.id+'" value="'+(item.sample_credit_days != null ? item.sample_credit_days : '0')+'">'+
                                                '<p id="sample_text_'+item.id+'">'+
                                                    '<label>* Price :- </label>'+   
                                                    (item.sample_price != null ? item.sample_price : '0.00')+ 
                                                    '<br>' + 
                                                    '<label>* Credit Days :- </label>'+ 
                                                    (item.sample_credit_days != null ? item.sample_credit_days : '0') +
                                                '</p>'+
                                            '</td>'+
                                            '<td>'+
                                                '<textarea class="form-control" style="display:none;" id="note_'+item.id+'" rows="3">'+
                                                    (item.remark_note != null ? item.remark_note : "")+
                                                '</textarea>'+
                                                '<p id="note_text_'+item.id+'">'+
                                                    (item.remark_note != null ? item.remark_note : '---')+
                                                '</p>'+
                                            '</td>'+
                                            // '<td>' 
                                            //     + day+'-'+month+'-'+yr + 
                                            // '</td>'+
                                            '<td>'+
                                                '<button class="btn btn-primary btn-sm btn-square" id="edit_price_book" data-id="'+item.id +'" data-client-id="'+item.customer_id +'" data-material-id="'+item.material_id +'">Edit</button>'+
                                            '</td>'+
                                        '</tr>';
                        });
                        $('#tblpricebook').append(trHTML);
                        var customer_name = $('#customer_id option:selected').text();

                        $('#customer-name').text("Customer Price List :- " + customer_name);



                    }
                });
        }
        function ListDefault(id) {
            var saveBtn = $("button[data-id="+id+"]").html('Edit')['0'].outerHTML;
            
            $("button[data-id="+id+"]").parent().html(saveBtn);
            $("#wholesale_price_"+id).prop('type','hidden');
            $("#wholesale_price_day"+id).prop('type','hidden');
            $("#price_"+id).prop('type','hidden');
            $("#price_day"+id).prop('type','hidden');
            $("#sampe_price_"+id).prop('type','hidden');
            $("#sampe_price_day"+id).prop('type','hidden');
            $("#note_"+id).css('display','none');
            $("#note_text_"+id).css('display','block');
            $("#wholesale_text_"+id).css('display','block');
            $("#price_text_"+id).css('display','block');
            $("#sample_text_"+id).css('display','block');
            $(".show"+id).css('display','none');

        }
    })(jQuery);
</script>
@endpush
