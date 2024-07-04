@extends('layouts.master')
@section('title', 'Invoice')
@section('content')


<!-- Begin Row -->
<div class="row flex-row">
    <div class="col-xl-12 col-12">
        <div class="widget has-shadow">
            <div class="widget-header bordered no-actions d-flex align-items-center">
                <h4>Generate Invoice</h4>
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
                {!! Form::open(array('route' => 'invoice.store2','method'=>'POST','id'=>'from_edit_order', 'class'=>"form-horizontal form-validate", 'novalidate')) !!}
                <div class="row">
                    <div class="form-group col-lg-3">
                        <label class="form-control-label d-flex">Invoic No<span class="text-danger ml-2">*</span></label>
                        {!! Form::text('invoice_no', null, array('id' => 'invoice_no','class' => 'form-control','data-validation-error-msg-required'=>'Please Select Customer', 'data-validation'=>"required","readonly"=>"readonly")) !!}
                    </div>
                    <div class="form-group col-lg-3">
                        <label class="form-control-label d-flex">Date of invoice<span class="text-danger ml-2">*</span></label>
                        {!! Form::text('generate_date', null, array('id' => 'generate_date','class' => 'form-control', 'data-validation'=>"required")) !!}
                    </div>
                    <div class="form-group col-lg-3">
                        <label class="form-control-label d-flex">Customer<span class="text-danger ml-2">*</span></label>
                        <div class="input-group">
                            {!! Form::select('customer_id',$users,null, array('id'=>'customer_id','class' => 'form-control custom-select', 'data-validation'=>"required")) !!}
                            <div class="input-group-append">
                                <span class="input-group-text">
                                    <a href="{{ route('users.create', ['redirect' =>  base64_encode(route('invoice.create2'))]) }}" title="Add Customer">
                                        <span><i class="fa fa-plus"></i></span>
                                    </a>
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="form-group col-lg-3">
                        <label class="form-control-label d-flex">Sales Person<span class="text-danger ml-2">*</span></label>
                        <div class="input-group">
                            {!! Form::select('seller_id', $sales_person,null, array('id'=>'seller_id','class' => 'form-control custom-select', 'data-validation'=>"required")) !!}
                            <div class="input-group-append">
                                <span class="input-group-text">
                                    <a href="{{ route('users.create', ['redirect' =>  base64_encode(route('invoice.create2'))]) }}" title="Add Sales Person">
                                        <span><i class="fa fa-plus"></i></span>
                                    </a>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="form-group col-lg-3">
                        <label class="form-control-label d-flex">Payment Terms<span class="text-danger ml-2">*</span></label>
                        {!! Form::select('payment_terms', ['cash' => 'Cash', 'credit' => 'Credit by Days'],null, array('id'=>'payment_terms','class' => 'form-control custom-select', 'data-validation'=>"required")) !!}
                    </div>
                    <div class="form-group col-lg-3">
                        <label class="form-control-label d-flex">Payment Receiver<span class="text-danger ml-2">*</span></label>
                        <div class="input-group">
                            {!! Form::select('payment_receiver_id', $payment_receiver,null, array('id'=>'seller_id','class' => 'form-control custom-select', 'data-validation'=>"required")) !!}
                            <div class="input-group-append">
                                <span class="input-group-text">
                                    <a href="{{ route('users.create', ['redirect' =>  base64_encode(route('invoice.create2'))]) }}" title="Add Payment Receiver">
                                        <span><i class="fa fa-plus"></i></span>
                                    </a>
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="form-group col-lg-3">
                        <label class="form-control-label d-flex">Sales Type<span class="text-danger ml-2">*</span></label>
                        {!! Form::select('sales_type', ['','Local', 'Out of state', 'International'],null, array('id'=>'sales_type','class' => 'form-control custom-select', 'data-validation'=>"required")) !!}
                    </div>
                    <div class="form-group col-lg-3">
                        <label class="form-control-label d-flex">Charge in Unit</label>
                        <div class="d-flex align-items-center">
                            <div class="styled-radio mr-3">
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
                <div class="row">
                    <div class="form-group col-lg-3">
                        <label class="form-control-label d-flex">Sales Commision</label>
                        <div class="d-flex align-items-center">
                            <div class="styled-radio mr-3">
                                {!! Form::radio('sales_commision', 'No', true, array('class' => 'custom-control-input', 'id'=>"sales_commision_no")) !!}
                                <label for="sales_commision_no">No</label>
                            </div>
                            <div class="styled-radio">
                                {!! Form::radio('sales_commision', 'Yes', false, array('class' => 'custom-control-input', 'id'=>"sales_commision_yes")) !!}
                                <label for="sales_commision_yes">Yes</label>
                            </div>
                        </div>
                    </div>
                    <div class="form-group col-lg-3 commision_part" style="display: none;">
                        <label class="form-control-label d-flex">Commision Type</label>
                        <div class="d-flex align-items-center">
                            <div class="styled-radio mr-3">
                                {!! Form::radio('commision_type', 'on_unit', true, array('class' => 'custom-control-input', 'id'=>"commision_type_unit")) !!}
                                <label for="commision_type_unit">On Unit Sold</label>
                            </div>
                            <div class="styled-radio">
                                {!! Form::radio('commision_type', 'on_subtotal', false, array('class' => 'custom-control-input', 'id'=>"commision_type_subtotal")) !!}
                                <label for="commision_type_subtotal">On Sub Total Amount Of Sale</label>
                            </div>
                        </div>
                    </div>
                    <div class="form-group col-lg-3 commision_part" style="display: none;">
                        <label class="form-control-label d-flex">Commision Amount THB/Unit</label>
                        <div class="input-group">
                            {!! Form::text('commision_amount_thb', 0, array('id' => 'commision_amount_thb','class' => 'form-control', 'data-validation'=>"required")) !!}
                            <div class="input-group-append">
                                <span class="input-group-addon addon-secondary">THB</span>
                            </div>
                        </div>
                    </div>
                    <div class="form-group col-lg-3 commision_part" style="display: none;">
                        <label class="form-control-label d-flex">Commision Amount on Sale</label>
                        <div class="input-group">
                            {!! Form::text('commision_amount_sale', 0, array('id' => 'commision_amount_sale','class' => 'form-control', 'data-validation'=>"required")) !!}
                            <div class="input-group-append">
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

                <div class="row">
                    <div class="form-group col-lg-3">
                        <label class="form-control-label d-flex">Article No</label>
                        {!! Form::select('search_article',$article_no, '' , array('class' => 'form-control','id'=>'search_article')) !!}
                    </div>
                    <div class="form-group col-lg-3">
                        <label class="form-control-label d-flex">Color</label>
                        {!! Form::select('search_color',$colors,'' , array('class' => 'form-control','id'=>'search_color')) !!}
                    </div>
                    <div class="form-group col-lg-3">
                        <div class="form-group">
                            <label class="form-control-label">Scan Barcode Number</label>
                            <div class="input-group form-group">
                                <span class="input-group-addon addon-secondary"><i class="la la-barcode"></i></span>
                                {!! Form::text('input_search_barcode', null, array('id'=>'input_search_barcode','placeholder' => 'Barcode Number','class' => 'form-control')) !!}
                            </div>
                        </div>
                    </div>      
                </div>

                <div class="form-group row d-flex align-items-center">
                    <div id="search_error" class=" col-lg-12 alert alert-danger form-control" style="display: none;"></div>
                </div>
                <div class="card">
                    <h4 class="card-header"> Invoice Items <label class="float-right" id="Price_warning"></label><a href="#"  id="price_book" class="btn btn-primary float-right">Price Book</a> </h4>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover mb-0 table-striped" id="tblOrderTable">
                                <thead>
                                    <tr>
                                        <th>Item Name</th>
                                        <th>Barcode</th>
                                        <th>Type Of Sale</th>
                                        <th>Price</th>
                                        <th>Meter</th>
                                        <th>Yard</th>
                                        <th>Weight</th>
                                        <th>Total Price</th>
                                        <th>Selected Meter</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @isset($items)
                                    @foreach ($items as $key => $item)
                                    <tr class="material-link accordion-toggle" data-toggle="collapse" data-target="#row-{{$item->id}}" id="item-{{$item->id}}" data-item_id="{{$item->id}}">

                                        <td class="td-material" data-value="{{ $item->item['fullName'] }}">{{ $item->item['fullName'] }}</td>
                                        <td class="td-barcode" data-value="{{ $item->item['barcode'] }}">{!! DNS1D::getBarcodeSVG($item->item['barcode'],config('app.BARCODE_TYPE'), 1, 40) !!}</td>
                                        <td class="td-type_of_sale" data-value="{{ $item->type_of_sale }}">{{ $item->type_of_sale }}</td>
                                        <td class="td-price price" data-value="{{ $item->price }}">{!! Form::text("price[$item->id]",$item->price,["class"=>"form-control",'id'=>"price",'data-validation'=>"required"]) !!}</td>
                                        <td class="td-meter" data-value="{{ $item->meter }}">{{ $item->meter }}</td>
                                        <input type="hidden" name="meter[]" value="{{ $item->meter }}">
                                        <td class="td-yard" data-value="{{ number_format((float)meter2yard($item->meter),2,'.','') }}">{{ number_format((float)meter2yard($item->meter),2,'.','') }}</td>
                                        <td class="td-weight" data-value="{{ $item->item['weight'] * $item->meter  }}">{{ ($item->item['weight'] * $item->meter) }}</td>
                                        <td class="td-total-price" id="total_price" data-value="{{ number_format((float)($item->price)*(meter2yard($item->meter)),2,'.','') }}">{{ number_format((float)($item->price)*(meter2yard($item->meter)),2,'.','') }}</td>
                                        <td class="td-selected-meter">
                                            <div class="data">0</div>
                                            {!! Form::hidden("selected_meter[".$item->id."]", null, ["class"=>"selected_meter_".$item->id,"id"=>"selected_meter_".$item->id]) !!}
                                        </td>
                                        <td>
                                            <button type="button" class="btn btn-sm btn-primary btn-square btn-roll-select" data-item_id="{{$item->id}}" data-material_id="{{$item->item['id']}}" data-toggle="modal" data-target="#rollSelectModel">Select Roll</button>
                                            <div id='item-rolls-{{$item->id }}' class="hidden_div"></div>
                                        </td>
                                    </tr>

                                    {!! Form::hidden("order_id[]", $item->order_id, ["class"=>"order_id","id"=>"order_id"]) !!}
                                    @endforeach

                                    @endisset
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <div class=" mt-5">
                    <div class="row">
                        <div class="col-7">
                            <div class="form-group row d-flex align-items-center mb-5">
                                <label class="col-lg-5 form-control-label ">Note </label>
                                <div class="col-lg-12">
                                    <div class="input-group">
                                        {!! Form::textarea('note', null, ['class'=>'form-control note','placeholder'=>'Note']) !!}
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-5">
                            <table class="table" Cellspacing="0">
                                <tbody>
                                    <tr>
                                        <td><label class="form-control-label">Total Items</label></td>
                                        <td>
                                            <div id="totalItem" class="">0</div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><label class="form-control-label">Total Meter</label></td>
                                        <td>
                                            <div id="totalMeter" class="">0</div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><label class="form-control-label">Selected Total Meter</label></td>
                                        <td>
                                            <div id="SelectedTotalMeter" class="">0</div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><label class="form-control-label">Sub Total</label></td>
                                        <td>
                                            <div id="sub-total" class="input-group">{!! Form::text("sub_total", null, ['class'=>'form-control sub-total','id'=>'sub_total','data-validation'=>"required",'placeholder'=>'Sub Total','readonly'=>'readonly']) !!}</div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><label class="form-control-label">Tax</label></td>
                                        <td>
                                            <div id="tax" class="input-group">
                                                {!! Form::text('tax', 0, array('placeholder' => 'Tax','id'=>'tax','class' => 'form-control tax')) !!}
                                                <span class="input-group-addon addon-secondary">%</span>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><label class="form-control-label">Discount</label></td>
                                        <td>
                                            <div id="discount" class="input-group">
                                                {!! Form::text("discount", 0, ['class'=>'form-control discount','id'=>'discount','placeholder'=>'Discount']) !!}
                                                {!! Form::select("discount_type", ["%","$"], 0, ['class'=>"form-control",'id'=>'discount_type']) !!}
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><label class="form-control-label">Grand Total</label></td>
                                        <td>
                                            <div id="grand-total" class="input-group">
                                                {!! Form::text("grand_total", 0, ['class'=>'form-control grand_total','id'=>'grand_total','data-validation'=>"required",'placeholder'=>'Grand Total']) !!}
                                            </div>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <div class="form-group row d-flex align-items-center mt-5">
                    <div class="col-lg-12 d-flex justify-content-center">
                        <button type="submit" class="btn btn-primary btn-lg" id="final_save_btn">Save</button>
                    </div>
                </div>
                {!! Form::close() !!}
            </div>
        </div>
    </div>
</div>
<div id="rollSelectModel" tabindex="-1" role="dialog" aria-labelledby="Edit" aria-hidden="true" class="modal fade lg text-left">
    <div role="document" class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 id="modal-header" class="modal-title">Select roll for <span class="span-modal-header"> </span></h5>
                <button type="button" data-dismiss="modal" aria-label="Close" class="close"><span aria-hidden="true">×</span></button>
            </div>
            <div class="modal-body">
                <input type="hidden" id="material_item_id" name="materialitemid" value="" />
                <table class="table" id="tblRoll">
                    <thead>
                        <tr>
                            <th>Select</th>
                            <th>Roll No</th>
                            <th>PIC No</th>
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
            </div>
            <div class="modal-footer">
                <div class="form-action float-right ">
                    <h4 class="d-inline">Meter : <div id="remember_meter" class="d-inline mr-3"></div>
                    </h4>
                    <h4 class="d-inline">Total Selected Meter : <div id="total_selected_meter" class="d-inline mr-3"></div>
                    </h4>
                    <h4 class="d-inline">Show +/- : <div id="show_extra" class="d-inline mr-3"></div>
                    </h4>
                    <button type="submit" id="model_save_btn" class="btn btn-primary">Save</button>
                </div>
            </div>
        </div>
    </div>
</div>
<div id="price_book_model" tabindex="-1" role="dialog" aria-labelledby="Edit" aria-hidden="true" class="modal fade xl text-left">
    <div role="document" class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 id="modal-header" class="modal-title"><span class="span-modal-header text-capitalize" id="customer-name"> </span></h5>
                <button type="button" data-dismiss="modal" aria-label="Close" class="close"><span aria-hidden="true">×</span></button>
            </div>
            <div class="modal-body">
                <div class="table-responsive">
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
            </div>
            {{-- <div class="modal-footer">

            </div> --}}
        </div>
    </div>
</div>
<script type="text/template" id="templateAddItem_invoice">
    <td class="td-material" data-value="">{!! Form::text('name[]', null, array('class' => 'inv_name form-control', 'data-validation'=>"required",'readonly'=>'readonly')) !!}</td>
    {!! Form::hidden('item_id[]',null,array('id'=>'inv_item_id')) !!}
    <td class="td-barcode" data-value="">{!! Form::text('barcode[]', null, array('class' => 'inv_barcode form-control', 'data-validation'=>"required",'readonly'=>'readonly')) !!}</td>
    <td class="td-type_of_sale" data-value="">{!! Form::select("type_of_sale[]", ["W"=>"Wholsale","R"=>"Retail","P"=>"Sample Price"], null, ['class'=>'form-control type_of_sale','data-validation'=>"required"]) !!}</td>
    <td class="td-price price" data-value="">{!! Form::text('price[]' , 0 , array('class' => 'inv_price form-control', 'data-validation'=>"required",'placeholder'=>"Price" )) !!}</td>
    <td class="td-meter" data-value="">{!! Form::text('meter[]', 0, array('class' => 'inv_meter form-control', 'data-validation'=>"required",'placeholder'=>"Meter")) !!}</td>
    <td class="td-yard" data-value="">{!! Form::text('yard[]',0 , ['class'=>'inv_yard form-control','readonly'=>'readonly','placeholder'=>"Yard"]) !!}</td>
    <td class="td-weight" data-value="">{!! Form::text('weight[]',0 , ['class'=>'inv_weight form-control','readonly'=>'readonly','placeholder'=>"Weight"]) !!}</td>
    <td  class="td-total-price" id="inv_total_price" data-value="">0</td>
    <td class="td-selected-meter"><div class="data">0</div>
        {!! Form::hidden("selected_meter[]", null, ["class"=>"","id"=>"selected_meter"]) !!}</td>
    <td>
        <a class="btn btn-danger btn-sm btn-square inv_delete my-1 text-light">Delete</a>
        <button type="button" class="btn btn-sm btn-primary btn-square my-1 btn-roll-select" data-material_id="" data-toggle="modal" data-target="#rollSelectModel">Select Roll</button>
        <div id='' class="hidden_div"></div>
    </td>

</script>
<script type="text/template" id="templateAddItem_roll">
    <td>{!! Form::checkbox('select[]',0,false, array('id'=>'select_roll','class' => 'form-control input-sm select_roll')) !!}</td>
        <td>{!! Form::text('roll_no[]',null, array('id'=>'roll_no','class' => 'form-control input-sm roll_no', 'data-validation'=>"required",'readonly'=>'readonly')) !!}</td>
        {!! Form::hidden('roll_id[]',null, array('id'=>'roll_id','class' => 'form-control input-sm roll_id', 'data-validation'=>"required",)) !!}
        <td>{!! Form::text('pcs_no[]',null, array('id'=>'pcs_no','class' => 'form-control input-sm pcs_no', 'data-validation'=>"required",'readonly'=>'readonly')) !!}</td>
        <td>{!! Form::text('article_no[]',null, array('id'=>'article_no','class' => 'form-control input-sm article_no', 'data-validation'=>"required",'readonly'=>'readonly')) !!}</td>
        <td>{!! Form::text('color_no[]',null, array('id'=>'color_no','class' => 'form-control input-sm color_no', 'data-validation'=>"required",'readonly'=>'readonly')) !!}</td>
        <td>{!! Form::text('batch_no[]',null, array('id'=>'batch_no','class' => 'form-control input-sm batch_no', 'data-validation'=>"required",'readonly'=>'readonly')) !!}</td>
        <td>{!! Form::text('available_qty[]',null, array('id'=>'available_qty','class' => 'form-control input-sm available_qty', 'data-validation'=>"required",'readonly'=>'readonly')) !!}</td>
        <td>{!! Form::Select('option[]',["0"=>"Take All","1"=>"Cut As Per Order","2"=>"Type How Much To Cut"],null, array('id'=>'option','class' => 'form-control input-sm option', 'data-validation'=>"required",'disabled'=>'disabled')) !!}</td>
        <td>{!! Form::number('meter[]',null, array('id'=>'meter','class' => 'form-control input-sm meter', 'data-validation'=>"required",'readonly'=>'readonly')) !!}</td>

</script>
@endsection
@push('after-styles')
<style>
    .roll_item td {
        padding: 8px;
    }

    .input-sm {
        padding: 8px;
    }

    .selectpicker .input-group ul.dropdown-menu a {
        padding: .25rem 1.5rem;
    }
</style>
@endpush
@push('scripts')
<script src="{{ asset('assets/js/datepicker/moment.min.js') }}"></script>
<script src="{{ asset('assets/js/datepicker/daterangepicker.js') }}"></script>
<script type="text/javascript">
    $(document).ready(function() {

        $(document).on('change','#payment_terms',function(){
            var p_val = $(this).val();
            if (p_val == 'credit') {
                $('#row-credit-days').css('display','block');
            }else{
                $('#row-credit-days').css('display','none');
            }
        });

        var customer_item_price = {!!json_encode($customer_item_price) !!};
        var input_search_barcode = $('#input_search_barcode');
        //for barcode search
        input_search_barcode.autocomplete({
            source: function(request, response) {
                $.ajax({
                    url: '{{url("materials/autocomplete")}}',
                    dataType: "json",
                    data: {
                        term: request.term
                    },
                    success: function(data) {
                        response($.map(data, function(item) {
                            return {
                                label: item.name + " - " + item.color + ' [' + item.article_no + ']' + ' [' + item.barcode + ']',
                                value: item.id,
                                data: item
                            }
                        }));
                    }
                });
            },
            response: function(event, ui) {
                var item_id = $('.inv_item_id_' + ui.content[0].data.id).val() ? $('.inv_item_id_' + ui.content[0].data.id).val() : 0;
                if (ui.content.length == 1) {
                    $(this).autocomplete("close");
                    if (item_id) {
                        $('#search_error').fadeIn(300).css('display', 'block').html("This material allready selected").fadeOut(3000);
                    } else {
                        addSearchMaterial(ui.content[0].data);
                    }

                };
            },
            select: function(event, ui) {
                var item_id = $('.inv_item_id_' + ui.item.data.id).val() ? $('.inv_item_id_' + ui.item.data.id).val() : 0;
                if (item_id) {
                    $('#search_error').fadeIn(300).css('display', 'block').html("This material allready selected").fadeOut(3000);
                } else {
                    addSearchMaterial(ui.item.data);
                }
            },
            close: function(event, ui) {
                $('#input_search_barcode').val('');
            }
        });

        //for article_no
        var input_search_article = $('#search_article');
        input_search_article.select2();
        
        
        $(document).on('change','#search_article',function(){
                var article_no = $(this).val();
                $("#search_color").html('');
                $.ajax({
                    url: "{{ route('materials.index') }}",
                    dataType: "json",
                    data: {
                        article: article_no
                    },
                    success: function(data) {
                        console.log(data);
                        $.each(data,function(i){
                            $("#search_color").append(`<option value="${i}">${data[i]}</option>`); 
                        })
                    }
                });
            });
            $('#search_article').trigger('change');
        //for color
        var input_search_color = $('#search_color');
        input_search_color.select2();
        

        $(document).on('change','#search_color',function(){
                var color_no = $(this).val(); 
                if(color_no!=""){
                    $.ajax({
                        url: "{{ route('invoice.getMaterial') }}",
                        dataType: "json",
                        data: {
                            color: color_no,
                            artical:$("#search_article").val()
                        },
                        success: function(data) {
                            var item_id = $('.inv_item_id_' + data.id).val() ? $('.inv_item_id_' + data.id).val() : 0;
                            
                            if (item_id) {
                                $('#search_error').fadeIn(300).css('display', 'block').html("This material allready selected").fadeOut(3000);
                            } else {
                                addSearchMaterial(data);
                            }
                        }
                    });
                }      
            });

        function addSearchMaterial(data) {
            console.log(data);
            $template = $('#templateAddItem_invoice').html();
            // var $uniqueId = uuid();
            var $tr = $('<tr class="invoiceitem" id="item-' + data.id + '">').append($template);
            $('#tblOrderTable tbody').append($tr);
            $('#item-' + data.id).find('#inv_item_id').val(data.id);
            $('#item-' + data.id).find('#inv_item_id').attr('class', 'inv_item_id_' + data.id);
            $('#item-' + data.id).find('.inv_name').val(data.name + " - " + data.color);
            $('#item-' + data.id).find('.inv_price').attr('name', 'price[' + data.id + ']');
            $('#item-' + data.id).find('.inv_barcode').val(data.barcode);
            $('#item-' + data.id).find('.inv_weight').val(data.weight);
            $('#item-' + data.id).find('.inv_weight').attr("data-value", data.weight);
            $('#item-' + data.id).find('.hidden_div').attr('id', 'item-rolls-' + data.id);
            $('#item-' + data.id).find('.btn-roll-select').data('material_id', data.id);
            $('#item-' + data.id).find('#selected_meter').attr('class', 'selected_meter_' + data.id);
            $('#item-' + data.id).find('.inv_delete').attr('data-id', 'item-' + data.id);
            var user_id = $('#customer_id option:selected').val();
            var material_id = data.id;
            var price_w = price_r = price_s = 0;
            console.log($('#item-' + data.id).find('.inv_price').val())
            $.each(customer_item_price, function(i, v) {
                if (v.customer_id == user_id && v.material_id == material_id) {
                    $('#item-' + data.id).find('.inv_price').val(v.wholesale_price);
                    price_w = v.wholesale_price;
                    price_r = v.price;
                    price_s = v.sample_price;
                }
            });
            if(price_w==0){
                $('#item-' + data.id).find('.inv_price').val(data.wholesale_price);
                price_w=data.wholesale_price;
            }            
            if(price_r==0){
                price_r=data.retail_price;
            }
            if(price_s==0){
                price_s=data.sample_price;
            }

            $('#item-' + data.id).find('.inv_price').attr("data-wholesale", price_w);
            $('#item-' + data.id).find('.inv_price').attr("data-retail", price_r);
            $('#item-' + data.id).find('.inv_price').attr("data-sample", price_s);

            $('.type_of_sale').on('change', function() {
                var price_input = $(this).parents('tr').find('.inv_price');
                var price=0;
                if(this.value=="W"){
                    price = price_input.attr("data-wholesale");
                }
                if(this.value=="R"){
                    price = price_input.attr("data-retail");
                }
                if(this.value=="P"){
                    price = price_input.attr("data-sample");
                }
                price_input.val(price);
            });

            totalrow();

            var credit_days = $("#credit_days").val();
            if (credit_days == '') {
                var cust_id = $('#customer_id').val();
                var material_id = data.id;
                var url = "{{ route('invoice.getdays') }}";
                $.ajax({
                    url  : url,
                    type : 'POST',
                    data : 
                    {
                        'customer_id' : cust_id,
                        'material_id' : material_id,
                    },
                    success: function(res){
                        if (res) {
                            if (res.msg) {
                            $('#search_error').fadeIn(300).css('display', 'block').html(res.msg).fadeOut(3000);
                            }
                            console.log(res);
                            if (res.retail_credit_days !== null) {
                               
                                $("#credit_days").val(res.retail_credit_days);
                                if (res.retail_credit_days == 0 || res.retail_credit_days == "0") {
                                    $("#payment_terms").val('cash');
                                }else{
                                    $("#payment_terms").val('credit');
                                }
                            }
                        }
                    }
                });
            }
        }
       
        $(document).on('keyup', '.inv_meter', function() {
            var meter = parseFloat($(this).val()).toFixed(2);
            var price = parseFloat($(this).closest('tr').find('.inv_price').val()).toFixed(2);
            $(this).attr('data-value', meter);
            if (!isNaN(meter) && meter) {
                var $thisRow = $(this).closest('tr');
                $('.inv_yard', $thisRow).val(meter2yard(meter).toFixed(2));   
                var weight = $('.inv_weight', $thisRow).attr('data-value');
                $('.inv_weight', $thisRow).val(weight * meter);
                             
                totalmeter();
            }
            if (!isNaN(price) && price) {
                var total = parseFloat(price * meter).toFixed(2);
                $(this).closest('tr').find('.td-total-price').attr('data-value', total).html(total);
                sub_total();
            }
        });
        $(document).on('change', '.inv_meter', function() {
            var meter = parseFloat($(this).val()).toFixed(2);
            var price = parseFloat($(this).closest('tr').find('.inv_price').val()).toFixed(2);
            $(this).attr('data-value', meter);
            if (!isNaN(meter) && meter) {
                var $thisRow = $(this).closest('tr');
                $('.inv_yard', $thisRow).val(meter2yard(meter).toFixed(2));
                var weight = $('.inv_weight', $thisRow).attr('data-value');
                $('.inv_weight', $thisRow).val(weight * meter);
                
                totalmeter();
            }
            if (!isNaN(price) && price) {
                var total = parseFloat(price * meter).toFixed(2);
                $(this).closest('tr').find('.td-total-price').attr('data-value', total).html(total);
                sub_total();
            }
        });

        $(document).on('keyup', '.inv_price', function() {
            var price = parseFloat($(this).val());
            var yard = parseFloat(meter2yard($(this).closest('tr').find('.inv_meter').val()));
            $(this).attr('data-value', price);
            if (!isNaN(yard) && yard) {
                var total = parseFloat(price * yard).toFixed(2);
                $(this).closest('tr').find('.td-total-price').attr('data-value', total).html(total);
                sub_total();
            }
        });
        $(document).on('change', '.inv_price', function() {
            var price = parseFloat($(this).val());
            var yard = parseFloat(meter2yard($(this).closest('tr').find('.inv_meter').val()));
            $(this).attr('data-value', price);
            if (!isNaN(yard) && yard) {
                var total = parseFloat(price * yard).toFixed(2);
                $(this).closest('tr').find('.td-total-price').attr('data-value', total).html(total);
                sub_total();
            }
        });

        $('#generate_date').daterangepicker({
            singleDatePicker: true,
            showDropdowns: true,
            locale: {
                format: 'DD/MM/YYYY'
            }
        });
        var roll_data;
        var modal_item_id;
        $('#rollSelectModel').on('shown.bs.modal', function(e) {
            $('#tblRoll tbody').empty();
            modal_item_id = "";
            var item_id = $(e.relatedTarget).data('material_id');

            modal_item_id = item_id;
            $('#material_item_id').val(item_id);
            $.when(
                $.ajax({
                    url: '{{route("invoice.get-roll")}}',
                    data: {
                        'material_id': item_id
                    },
                    dataType: "json",
                    success: function(data) {
                        roll_data = data.roll;
                        // $('#roll').empty();
                        // $('#roll').append(new Option("--Select Roll--"));
                        $.each(roll_data, function(index, value) {
                            // $('#roll').append(new Option(value.roll_no + " [ Meter : " +value.available_qty+" ]", value.id));
                            addRoll(value);
                            // console.log(value);
                        })
                    }
                })
            ).then(function() {
                $('#remember_meter').html($('#item-' + item_id + ' .inv_meter').val());
                $('#total_selected_meter').html(0);
                if (!$('#item-rolls-' + item_id).is(':empty')) {
                    var meter = 0;
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
                                $(value).closest('tr').find('.meter').attr('readonly', false);
                            }
                        });
                        meter += parseFloat(v.value);
                        // addRoll(data);
                    });

                    $('#total_selected_meter').html(meter.toFixed(2));

                }
            });


        });

        function addRoll(data) {
            $template = $('#templateAddItem_roll').html();
            var $uniqueId = uuid();
            var $tr = $('<tr class="roll_item" id="' + $uniqueId + '">').append($template);
            $('#tblRoll tbody').append($tr);
            $('#' + $uniqueId).find('.roll_id').val(data.id);
            $('#' + $uniqueId).find('.select_roll').val(data.id);
            $('#' + $uniqueId).find('.roll_no').val(data.roll_no);
            $('#' + $uniqueId).find('.pcs_no').val(data.pcs_no);
            $('#' + $uniqueId).find('.article_no').val(data.article_no);
            $('#' + $uniqueId).find('.color_no').val(data.color_no);
            $('#' + $uniqueId).find('.batch_no').val(data.batch_no);
            $('#' + $uniqueId).find('.available_qty').val(data.available_qty);
            $('#' + $uniqueId).find('.meter').attr("max", data.available_qty);
            // $('#' + $uniqueId).find('.meter').val(data.available_qty);
            $('#' + $uniqueId).find('.roll_delete_btn').data('id', $uniqueId);
            if (data.meter) {
                $('#' + $uniqueId).find('.meter').val(data.meter);
            }

        }

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
                    $(v).closest('tr').addClass("table-danger");
                    $('.error1').css('display', 'block').html("Maximum Value Is : " + $(v).attr('max'));
                    $('#model_save_btn').attr('disabled', true);
                    // $('#total_selected_meter').html(v.value);
                    return false;
                } else {
                    $(v).closest('tr').removeClass('table-danger');
                    $('.error1').css('display', 'none').fadeOut();
                    $('#model_save_btn').attr('disabled', false);
                    // $('#total_selected_meter').html(v.value);
                    // console.log(v.value);
                }
                if (v.value != '') {
                    meter += parseFloat(v.value);
                }
                $('#total_selected_meter').html(meter.toFixed(2));
                user_enter_val = parseFloat($('#remember_meter').html());
                $('#show_extra').html((meter - user_enter_val).toFixed(2));
            })
            var total_meter = parseFloat($('#item-' + modal_item_id + ' .td-meter').data("value"));
        });
        $(document).on('keyup', '#tblRoll .meter', function() {
            var meter = 0;
            var user_enter_val = 0;
            $.each($('.meter'), function(i, v) {

                if (v.value > parseFloat($(v).attr('max'))) {
                    $('.error1').css('display', 'block').html("Maximum Value Is : " + $(v).attr('max'));
                    $(v).closest('tr').addClass("table-danger");
                    $('#model_save_btn').attr('disabled', true);
                    // $('#total_selected_meter').html(v.value);
                    return false;
                } else {
                    $(v).closest('tr').removeClass('table-danger');
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
            var total_meter = parseFloat($('#item-' + modal_item_id + ' .td-meter').data("value"));
        });
        $('#model_save_btn').on('click', function() {
            var item_id = $('#material_item_id').val();
            $('#item-rolls-' + item_id).empty();
            var input_hidden;
            $.each($('.select_roll'), function(index, value) {
                if (value.checked) {
                    var roll_id = $(value).closest('tr').find('.roll_id').val();
                    var meter = $(value).closest('tr').find('.meter').val();

                    // $.each($('.roll_id'),function(i,v){
                    //     var meter=$(v).closest('tr').find('.meter').val();
                    //    if(meter!='')
                    //     {
                    input_hidden = $('<input>').attr({
                        name: "item_roll[" + item_id + "][" + roll_id + "]",
                        id: "item_roll_" + item_id + "_" + roll_id,
                        type: "hidden",
                        value: meter,
                    });
                    $('#item-rolls-' + item_id).append(input_hidden);

                    //     }

                    //     // console.log(input_hidden);
                    // });
                }
            });
            $('#item-' + item_id + ' .td-selected-meter').html($("#total_selected_meter").html());
            $('#item-' + item_id).find('.td-selected-meter #selected_meter_' + item_id).val($("#total_selected_meter").html());
            selectedtotalmeter();
            grand_total();
            $('#rollSelectModel').modal('hide');
            $('#rollSelectModel #tblRoll tbody').html('');
            // }
        });

        function generate_invoice_no(fname, lname, last_invoice) {
            var c_fname = fname.substr(0, 2);
            var c_lname = lname.substr(0, 2);
            var d = new Date();
            var year = d.getFullYear();
            var month = ("0" + (d.getMonth() + 1)).slice(-2);
            var day = ("0" + d.getDate()).slice(-2);
            var last_invoice = pad(parseInt(last_invoice) + 1);

            function pad(num) {
                var s = num + "";
                while (s.length < 4) s = "0" + s;
                return s;
            }
            // console.log(c_fname+" "+c_lname+" "+year+" "+month+" "+day+" "+pad(last_invoice));
            $('#invoice_no').val(c_fname + c_lname + year + month + day + last_invoice);
        };
        $(document).on('change', '#customer_id', function() {
            var id = $(this).val();
            if (id != '') {
                $.ajax({
                    url: '{{route("invoice.last-invoice")}}',
                    data: {
                        'id': id
                    },
                    success: function(data) {
                        var f_name = data.firstname;
                        var l_name = data.lastname;
                        var last_invoice = data.last_invoice ? data.last_invoice : 1;
                        generate_invoice_no(f_name, l_name, last_invoice);
                    }
                });
            } else {
                $('#invoice_no').val('');
            }
        });

        function sub_total() {
            var total_price = 0.00;
            $.each($('.td-total-price'), function(i, v) {
                total_price += parseFloat($(v).html());
            });
            $('#sub_total').val(total_price.toFixed(2));
        };

        function totalmeter() {
            var total = 0;
            $.each($('.inv_meter'), function(i, v) {
                total += Number($(this).val());
            });
            $('#totalMeter').html(total.toFixed(2));
        };

        function selectedtotalmeter() {
            var total = 0;
            $.each($('.td-selected-meter'), function(i, v) {
                total += Number($(v).html());
            });
            $('#SelectedTotalMeter').html(total.toFixed(2));
        };

        function totalrow() {
            var rowCount = $("#tblOrderTable tbody tr").length;
            $('#totalItem').html(rowCount);
        };
        $(document).on('click', '.inv_delete', function() {
            var rowid = $(this).data('id');
            $('#' + rowid).remove();
            totalrow();
            totalmeter();
        });

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

            $('#grand_total').val(parseFloat(grand_total.toFixed(2)));
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
            $("#Price_warning").html('');
            $('#tblpricebook').html('');
            var user_id = $('#customer_id option:selected').val();
            if(user_id == ""){
                $("#customer_id").focus();
            }else{
                $("#customer_id").blur();
                getPriceBook(user_id);
            }
        });
        
        $(document).on('click','#edit_price_book',function(){
            var mode = $(this).text();
            var id = $(this).data('id');

            var saveBtn = $("button[data-id="+id+"]").html('Save')['0'].outerHTML;
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
            var note = $("#note_"+id).val();
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
                        

                        console.log(id);
                        ListDefault(id)
                        getPriceBook(customerId);

                        new Noty({
                            type    : 'success',
                            text    : data.msg,
                            timeout : 2500
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
                    url : "pricebook/"+user_id,
                    type: 'get',
                    datatype : 'json',
                    success : function(data){
                        $('#price_book_model').modal('show');
                        var trHTML = '';
                        $.each(data, function (i,item) {
                            // var str    = item.created_at;
                            // var substr = str.split('T');
                            // fname      = substr[0];
                            // dteSplit   = fname.split("-");
                            // yr         = dteSplit[0];
                            // month      = dteSplit[1];
                            // day        = dteSplit[2];
                            trHTML +=   '<tr>'+
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
    });
</script>
@endpush
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
@endpush