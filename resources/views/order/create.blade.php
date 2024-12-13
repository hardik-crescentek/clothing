@extends('layouts.master')
@section('title', 'Add Order')
@section('content')


<style type="text/css">
    .role-model{
        height: 750px !important;
        overflow-y: auto !important ;
    }

    .main-content {
        padding: 20px; 
        transition: all 0.3s ease; 
    }

    .sidebar-container {
        overflow: hidden; 
    }

    .sidebar {
        display: block; 
        transform: translateX(0); 
        transition: transform 0.3s ease; 
    }

    .sidebar-hidden {
        transform: translateX(100%); 
        transition: transform 0.3s ease; 
    }
</style>

<!-- Begin Row -->
<div class="row flex-row">
    <div class="col-xl-8 col-12 main-content">
        <div class="widget has-shadow">
            <div class="widget-header bordered no-actions d-flex align-items-center">
                <h4>New Order</h4>
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
                {!! Form::open(array('route' => 'order.store','method'=>'POST','id'=>'from_add_order', 'class'=>"form-horizontal form-validate", 'novalidate')) !!}
                <div class="row">
                    <div class="col-lg-3">
                        <div class="form-group">
                            <label class="form-control-label">Customer<span class="text-danger ml-2">*</span></label>
                            <div class="input-group">
                                {!! Form::select('user_id', $users,null, array('id'=>'user_id','class' => 'form-control custom-select', 'data-validation'=>"required")) !!}
                                <div class="input-group-append">
                                    <span class="input-group-text">
                                        <a href="{{ route('users.create', ['redirect' =>  base64_encode(route('order.create'))]) }}" title="Add Customer">
                                            <span><i class="fa fa-plus"></i></span>
                                        </a>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3">
                        <br>
                        <button type="button" id="btn-show-orders" class="btn btn-primary btn-square mt-2">Show Past Orders</button>
                    </div>
                    <div class="col-lg-3">
                        <div class="form-group">
                            <label class="form-control-label">Date<span class="text-danger ml-2">*</span></label>
                            {!! Form::text('purchase_date', null, array('id' => 'purchase_date','class' => 'form-control', 'data-validation'=>"required")) !!}
                        </div>
                    </div>
                    <div class="col-lg-3">
                        <div class="form-group">
                            <label class="form-control-label">Sales Person</label>
                            <div class="input-group form-group">
                                {!! Form::select('selse_person_id', $sales_person,null, array('id'=>'sales_person_id','class' => 'form-control custom-select')) !!}
                                <div class="input-group-append">
                                    <span class="input-group-text">
                                        <a href="{{ route('users.create', ['redirect' =>  base64_encode(route('order.create'))]) }}" title="Add Sales Person">
                                            <span><i class="fa fa-plus"></i></span>
                                        </a>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-3">
                        <div class="form-group">
                            <label class="form-control-label">Price VAT<span class="text-danger ml-2">*</span></label>
                            <div class="input-group">
                                {!! Form::select('price_vat', ['price_include_vat' => 'Price Include VAT', 'price_exclude_vat' => 'Price Exclude VAT'], 'price_exclude_vat', ['id' => 'price_vat', 'class' => 'form-control custom-select', 'data-validation' => "required"]) !!}
                            </div>
                        </div>
                    </div>
                    
                    <!-- New Payment Term field -->
                    <div class="col-lg-3">
                        <div class="form-group">
                            <label class="form-control-label">Payment Term<span class="text-danger ml-2">*</span></label>
                            <div class="input-group">
                                {!! Form::select('payment_term', ['cash' => 'Cash', 'credit' => 'Credit'], 'cash', array('id' => 'payment_term', 'class' => 'form-control custom-select', 'data-validation' => "required")) !!}
                                <div class="input-group-append">
                                    <span class="input-group-text">
                                        <a href="#" title="Add Payment Term">
                                            <span><i class="fa fa-plus"></i></span>
                                        </a>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div id="credit-days" class="form-group d-none col-lg-3">
                        <label class="form-control-label">Credit Days<span class="text-danger ml-2">*</span></label>
                        {!! Form::number('credit_day', null, array('id' => 'credit_days', 'class' => 'form-control', 'placeholder' => 'Enter days', 'min' => 1)) !!}
                    </div>

                    <div class="col-lg-3">
                        <div class="form-group">
                            <label class="form-control-label">Scan Barcode Number</label>
                            <div class="input-group form-group">
                                <span class="input-group-addon addon-secondary"><i class="la la-barcode"></i></span>
                                {!! Form::text('input_search_barcode', null, array('id'=>'input_search_barcode','placeholder' => 'Barcode Number','class' => 'form-control')) !!}
                            </div>
                        </div>
                    </div>     
                </div>

                <div class="row">
                    <div class="col-lg-3">
                        <div class="form-group">
                            <label class="form-control-label">Article No</label>
                            <br>
                            {!! Form::select('search_article',$article_no, '' , array('class' => 'form-control','id'=>'search_article')) !!}
                        </div>
                    </div>
                    <div class="col-lg-3">
                        <div class="form-group">
                            <label class="form-control-label">Color</label>
                            <br>
                            {!! Form::select('search_color',$colors,'' , array('class' => 'form-control','id'=>'search_color')) !!}
                        </div>
                    </div>
                </div>

                <div class="form-group row d-flex align-items-center">
                    <div id="search_error" class=" col-lg-12 alert alert-danger form-control" style="display: none;"></div>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover mb-0 " id="tblOrderTable">
                        <thead>
                            <tr>
                                {{-- <th style="width:9%;">Brand Name</th>
                                <th style="width:9%;">Barcode</th>
                                <th style="width:9%;">Type Of Sale</th>
                                <th style="width:9%;">Unit Of Sale</th>
                                <th style="width:9%;">Price</th>
                                <th style="width:9%;">Meter</th>
                                <th style="width:9%;">Yard</th>
                                <!-- <th>Price</th> -->
                                <th style="width:9%;">Discount Type</th>
                                <th style="width:9%;">Discount Value</th>
                                <th style="width:9%;">Total Amount</th>
                                <th style="width:9%;">Select Role</th>
                                <th style="width:9%;">Action</th> --}}
                                <th style="width:5%;">Sr. No.</th>
                                <th style="width:14%;">Description</th>
                                <th style="width:9%;">Qty Mtrs</th>
                                <th style="width:9%;">Qty Yrds</th>
                                <th style="width:9%;">Unit Sold</th>
                                <th style="width:9%;">Sales Type</th>
                                <th style="width:9%;">Price</th>
                                <th style="width:9%;">Discount Type</th>
                                <th style="width:9%;">Discount Value</th>
                                <th style="width:9%;">Amount</th>
                                <th style="width:9%;">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @isset($items)
                                @foreach ($items as $key => $item)
                                    <tr class="material-link">
                                        {{-- <td>{!! Form::text('name[]', $item['name'], array('class' => 'name form-control', 'data-validation'=>"required")) !!}</td>
                                        {!! Form::hidden('item_id[]',$item['id'],array('class'=>'item_id')) !!}
                                        {!! Form::hidden('color_id[]',$item['color'],array('class'=>'color_id')) !!}
                                        <td>{!! Form::text('barcode[]', $item['barcode'], array('class' => 'barcode form-control', 'data-validation'=>"required")) !!}</td>
                                        <td>{!! Form::select("type_of_sale[]", ["W"=>"Wholsale","R"=>"Retail","P"=>"Sample Price"],null, ['class'=>'form-control type_of_sale','data-validation'=>"required",'id' => 'selected_price']) !!}</td>
                                        <td>{!! Form::select("unit_of_sale[]", ["meter"=>"meter","yard"=>"yard"],null, ['class'=>'form-control unit_of_sale','data-validation'=>"required",'id' => 'unit_price']) !!}</td>
                                        <td>{!! Form::number('price[]' , $item['price'] , array('class' => 'price form-control', 'data-validation'=>"required" )) !!}</td>
                                        <td>{!! Form::number('meter[]', $item['meter'], array('class' => 'meter form-control', 'data-validation'=>"required")) !!}</td>
                                        <td class="td-selected-role">
                                            <div id="selectedrole-" class="selectedrole"></div>
                                        </td>
                                        <td><input name="yard[]" class="yard form-control" readonly="readonly" value="{{ number_format((float)$item['yard'],2,'.','') }}" type="text"></td>
                                        <td>{!! Form::select("discount_type[]", ["percentage"=>"Percentage","amount"=>"Amount"],null, ['class'=>'form-control discount_type','data-validation'=>"required",'id' => 'discount_type']) !!}</td>
                                        <td>{!! Form::number('discount_value[]' , $item['discount_value'] , array('class' => 'discount_value form-control', 'data-validation'=>"required" )) !!}</td>
                                        <td><input name="total_price_table[]" class="total_price_table form-control" readonly="readonly" id="total_price_table" type="text"></td>
                                        <td>
                                            <a class="btn btn-danger btn-sm btn-square">Delete</a>
                                            
                                        </td> --}}
                                        <td>{{ $key + 1 }}</td>
                                        <td>{!! Form::text('name[]', $item['name'], ['class' => 'name form-control', 'data-validation' => "required"]) !!}</td>
                                        <td>{!! Form::number('meter[]', $item['meter'], ['class' => 'meter form-control', 'data-validation' => "required"]) !!}</td>
                                        <td><input name="yard[]" class="yard form-control" readonly="readonly" value="{{ number_format((float)$item['yard'], 2, '.', '') }}" type="text"></td>
                                        <td>{!! Form::select("unit_of_sale[]", ["meter" => "meter", "yard" => "yard"], null, ['class' => 'form-control unit_of_sale', 'data-validation' => "required", 'id' => 'unit_price']) !!}</td>
                                        <td>{!! Form::select("type_of_sale[]", ["W" => "Wholesale", "R" => "Retail", "P" => "Sample Price"], null, ['class' => 'form-control type_of_sale', 'data-validation' => "required", 'id' => 'selected_price']) !!}</td>
                                        <td>{!! Form::number('price[]', $item['price'], ['class' => 'price form-control', 'data-validation' => "required"]) !!}</td>
                                        <td>{!! Form::select("discount_type[]", ["percentage" => "Percentage", "amount" => "Amount"], null, ['class' => 'form-control discount_type', 'data-validation' => "required", 'id' => 'discount_type']) !!}</td>
                                        <td>{!! Form::number('discount_value[]', $item['discount_value'], ['class' => 'discount_value form-control', 'data-validation' => "required"]) !!}</td>
                                        <td><input name="total_price_table[]" class="total_price_table form-control" readonly="readonly" id="total_price_table" type="text"></td>
                                        <td>
                                            <a class="btn btn-danger btn-sm btn-square">Delete</a>
                                        </td>
                                    </tr>
                                @endforeach
                            @endisset

                        </tbody>
                    </table>
                </div>
                <div class=" mt-5">
                    <div class="row">
                        <div class="col-9">
                            <div class="form-group">
                                <label class="form-control-label">Note</label>
                                {!! Form::textarea('note', null, ['class' => 'form-control','rows' => 3]); !!}
                            </div>
                        </div>
                        <div class="col-3">
                            <h4 class="mb-3">
                                <div id="total_sum"> Total Amount (Before Disc.) : </div>
                            </h4>
                            <h4 class="mb-3">
                                <div id="total_discount"> Total Discount : </div>
                            </h4>
                            <h4 class="mb-3">
                                <div id="totalMeter"> Total Meter : </div>
                            </h4>
                            <h4 class="mb-3" id="total_amount_info" style="display: none;">
                                <div id="total_amount_before_vat"> Total Amount (Before VAT.) : </div>
                            </h4>
                            <div class="form-group mb-3=" id="gst_info" style="display: none;">
                                <label for="gst_checkbox">GST/VAT ({{ $vat }}%) : </label>
                                <h4 id="vat_amount" style="display: inline;"></h4>
                            </div>
                            <h4 class="mb-3">
                                <div id="grand_total" name="grand_total"> Grand Total : </div>
                            </h4>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-lg-2">
                        <div class="form-group">
                            <label class="form-control-label">Entered By</label>
                            {!! Form::text('entered_by', null, array('id'=>'enterd_by','placeholder' => 'Entered By','class' => 'form-control')) !!}
                        </div>
                    </div> 
                    <div class="col-lg-2">
                        <div class="form-group">
                            <label class="form-control-label">Arranged By</label>
                            {!! Form::text('arranged_by', null, array('id'=>'enterd_by','placeholder' => 'Arranged By','class' => 'form-control')) !!}
                        </div>
                    </div> 
                    <div class="col-lg-2">
                        <div class="form-group">
                            <label class="form-control-label">Inspected By</label>
                            {!! Form::text('inspected_by', null, array('id'=>'inspected_by','placeholder' => 'Inspected By','class' => 'form-control')) !!}
                        </div>
                    </div> 
                    <div class="col-lg-2">
                        <div class="form-group">
                            <label class="form-control-label">Delivered By</label>
                            {!! Form::text('delivered_by', null, array('id'=>'delivered_by','placeholder' => 'Delivered By','class' => 'form-control')) !!}
                        </div>
                    </div>  
                    <div class="col-lg-2">
                        <div class="form-group">
                            <label class="form-control-label">Delivered Date</label>
                            {!! Form::text('delivered_date', null, array('id'=>'delivered_date','placeholder' => 'Delivered Date','class' => 'form-control')) !!}
                        </div>
                    </div>  
                    <div class="col-lg-2">
                        <div class="form-group">
                            <label class="form-control-label">Total Number of Items.</label>
                            {!! Form::text('total_number_of_items', null, array('id'=>'total_number_of_items','placeholder' => 'Total Number of Items','class' => 'form-control')) !!}
                        </div>
                    </div>  
                    <div class="col-lg-2">
                        <div class="form-group">
                            <label class="form-control-label">Approximate weight</label>
                            {!! Form::text('approximate_weight', null, array('id'=>'approximate_weight','placeholder' => 'Approximate Weight','class' => 'form-control')) !!}
                        </div>
                    </div>  
                    <div class="col-lg-2">
                        <div class="form-group">
                            <label class="form-control-label">Total Profit</label>
                            {!! Form::text('total_profit', null, array('id'=>'total_profit',0,'class' => 'form-control','readonly' => 'readonly')) !!}
                        </div>
                    </div>  
                </div>
                
                <div class="form-group row d-flex align-items-center mt-5">
                    <div class="col-lg-12 d-flex justify-content-center">
                        <button type="submit" class="btn btn-primary btn-lg">Process Now</button>
                        <button type="submit" name="action" value="generate_invoice" class="btn btn-primary btn-lg ml-2">Process and Generate Invoice</button>
                        <button type="submit" class="btn btn-primary btn-lg ml-2">VAT Bill</button>
                    </div>
                </div>
                {!! Form::close() !!}
            </div>
        </div>
    </div>
    <div class="col-xl-4 col-12 sidebar-container sidebar">
        <div class="widget has-shadow">
            <div class="widget-header bordered no-actions d-flex align-items-center">
                <h4>Customer Past Invoices <span class=".custom-selected-name"></span></h4>
            </div>
            <div class="custom-selected-invoices">
                
            </div>
        </div>
    </div>
    <div id="rollSelectModel" tabindex="-1" role="dialog" aria-labelledby="Edit" aria-hidden="true" class="modal fade lg text-left">
        <div role="document" class="modal-dialog modal-lg" style="max-width: 60%;">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 id="modal-header" class="modal-title">Select roll for <span class="span-modal-header"> </span></h5>
                    <span class="d-inline-block ml-3">Total Meter: <span id="total_meters" class="d-inline"></span></span>
                    <span class="d-inline-block ml-3">Total Rolls: <span id="total_rolls" class="d-inline"></span></span>
                    <span class="d-inline-block ml-3">Total Selected Meter : <div id="total_select_mtr" class="d-inline mr-3"></div>
                    </span>
                    <span class="d-inline-block ml-3">Total Selected Yard : <div id="total_select_yrd" class="d-inline mr-3"></div>
                    </span>
                    <button type="button" data-dismiss="modal" aria-label="Close" class="close"><span aria-hidden="true">×</span></button>
                </div>
                <div class="modal-body role-model">
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
    <!-- Modal for Displaying Selected Rolls -->
    <div id="selectedRollModal" class="modal fade" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Selected Rolls</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <table class="table" id="selectedRollTable">
                        <thead>
                            <tr>
                                <th>Roll ID</th>
                                <th>Meter</th>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- Selected rolls will be populated here -->
                        </tbody>
                    </table>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- <script type="text/template" id="templateAddItem_invoice">
    <td class="td-material" data-value="">{!! Form::text('name[]', null, array('class' => 'inv_name form-control', 'data-validation'=>"required",'readonly'=>'readonly')) !!}</td>
    {!! Form::hidden('item_id[]',null,array('id'=>'inv_item_id')) !!}
    <td class="td-barcode" data-value="">{!! Form::text('barcode[]', null, array('class' => 'inv_barcode form-control', 'data-validation'=>"required",'readonly'=>'readonly')) !!}</td>
    <td class="td-type_of_sale" data-value="">{!! Form::select("type_of_sale[]", ["W"=>"Wholsale","R"=>"Retail","P"=>"Sample Price"], null, ['class'=>'form-control type_of_sale','data-validation'=>"required"]) !!}</td>
    <td class="td-unit_of_sale" data-value="">{!! Form::select("unit_of_sale[]", ["meter"=>"meter","yard"=>"yard"], null, ['class'=>'form-control unit_of_sale','data-validation'=>"required"]) !!}</td>
    <td class="td-price price" data-value="">{!! Form::text('price[]' , 0 , array('class' => 'inv_price form-control', 'data-validation'=>"required",'placeholder'=>"Price" )) !!}</td>
    <td class="td-meter" data-value="">{!! Form::text('meter[]', 0, array('class' => 'inv_meter form-control', 'data-validation'=>"required",'placeholder'=>"Meter")) !!}</td>
    <td class="td-yard" data-value="">{!! Form::text('yard[]',0 , ['class'=>'inv_yard form-control','placeholder'=>"Yard"]) !!}</td>
    <!-- <td class="td-weight" data-value="">{!! Form::text('weight[]',0 , ['class'=>'inv_weight form-control','readonly'=>'readonly','placeholder'=>"Weight"]) !!}</td> -->
    <td class="td-discount_type" data-value="">{!! Form::select("discount_type[]", ["percentage"=>"Percentage","amount"=>"Amount"], null, ['class'=>'form-control discount_type','data-validation'=>"required"]) !!}</td>
    <td class="td-discount_value" data-value="">{!! Form::text('discount_value[]' , 0 , array('class' => 'discount_value form-control', 'data-validation'=>"required",'placeholder'=>"Discount Value" )) !!}</td>
    <!-- <td  class="td-total-price" id="inv_total_price" data-value="">0</td> -->
    <td class="td-total-price" data-value="">{!! Form::text('total-price[]' , 0 , array('class' => 'total-price form-control', 'data-validation'=>"required",'readonly'=>'readonly' )) !!}</td>
    <!-- <td class="td-selected-meter">
        <div class="data">0</div>
        {!! Form::hidden("selected_meter[]", null, ["class"=>"inv_total_selected_roll","id"=>"selected_meter"]) !!}
    </td> -->
    <td class="td-selected-meter" data-value="">{!! Form::hidden('selected_meter[]', 0, array('class' => 'inv_selected_roll form-control', 'data-validation'=>"required")) !!}</td>
    {!! Form::hidden('inv_weight_gsm[]', null, ['class' => 'td-weight_gsm', 'data-gsm' => '']) !!}
    {!! Form::hidden('inv_weight_per_mtr[]', null, ['class' => 'td-weight_per_mtr', 'data-weight_per_mtr' => '']) !!}
    {!! Form::hidden('inv_weight_per_yard[]', null, ['class' => 'td-weight_per_yard', 'data-weight_per_yard' => '']) !!}
    {!! Form::hidden('grand_total', 0, array('class' => 'grand_total form-control', 'data-validation'=>"required")) !!}
    {!! Form::hidden('vat_percentage', 0, array('class' => 'vat_percentage form-control', 'data-validation'=>"required")) !!}
    {!! Form::hidden('vat_amount', 0, array('class' => 'vat_amount form-control', 'data-validation'=>"required")) !!}
    <td>
        <a class="btn btn-danger btn-sm btn-square inv_delete my-1 text-light">Delete</a>
        <button type="button" class="btn btn-sm btn-primary btn-square my-1 btn-roll-select" data-material_id="" data-toggle="modal" data-target="#rollSelectModel">Select Roll</button>
        <div id='' class="hidden_div"></div>
    </td>

</script> --}}
<script type="text/template" id="templateAddItem_invoice">
    <td class="td-sr-no">{!! Form::text('sr_no[]', null, array('class' => 'form-control sr_no', 'readonly'=>'readonly')) !!}</td>
    <td class="td-description">{!! Form::text('description[]', null, array('class' => 'form-control description', 'readonly'=>'readonly')) !!}</td>
    <td class="td-meter" data-value="">{!! Form::text('meter[]', 0, array('class' => 'inv_meter form-control', 'data-validation'=>"required",'placeholder'=>"Meter")) !!}</td>
    <td class="td-yard" data-value="">{!! Form::text('yard[]',0 , ['class'=>'inv_yard form-control','placeholder'=>"Yard"]) !!}</td>
    <td class="td-unit_of_sale" data-value="">{!! Form::select("unit_of_sale[]", [""=>"Select Unit","meter"=>"meter","yard"=>"yard"], null, ['class'=>'form-control unit_of_sale','data-validation'=>"required"]) !!}</td>
    <td class="td-type_of_sale" data-value="">{!! Form::select("type_of_sale[]", [""=>"Select Type","W"=>"Wholsale","R"=>"Retail","P"=>"Sample Price"], null, ['class'=>'form-control type_of_sale','data-validation'=>"required"]) !!}</td>
    <td class="td-price price" data-value="">{!! Form::text('price[]' , 0 , array('class' => 'inv_price form-control', 'data-validation'=>"required",'placeholder'=>"Price" )) !!}</td>
    <td class="td-discount_type" data-value="">{!! Form::select("discount_type[]", ["percentage"=>"Percentage","amount"=>"Amount"], null, ['class'=>'form-control discount_type','data-validation'=>"required"]) !!}</td>
    <td class="td-discount_value" data-value="">{!! Form::text('discount_value[]' , 0 , array('class' => 'discount_value form-control', 'data-validation'=>"required",'placeholder'=>"Discount Value" )) !!}</td>
    <td class="td-total-price" data-value="">{!! Form::text('total-price[]' , 0 , array('class' => 'total-price form-control', 'data-validation'=>"required",'readonly'=>'readonly' )) !!}</td>
    {!! Form::hidden('inv_weight_gsm[]', null, ['class' => 'td-weight_gsm', 'data-gsm' => '']) !!}
    {!! Form::hidden('inv_weight_per_mtr[]', null, ['class' => 'td-weight_per_mtr', 'data-weight_per_mtr' => '']) !!}
    {!! Form::hidden('inv_weight_per_yard[]', null, ['class' => 'td-weight_per_yard', 'data-weight_per_yard' => '']) !!}
    {!! Form::hidden('grand_total', 0, array('class' => 'grand_total form-control', 'data-validation'=>"required")) !!}
    {!! Form::hidden('vat_percentage', 0, array('class' => 'vat_percentage form-control', 'data-validation'=>"required")) !!}
    {!! Form::hidden('vat_amount', 0, array('class' => 'vat_amount form-control', 'data-validation'=>"required")) !!}
    {!! Form::hidden('discount_amount', 0, array('class' => 'discount_amount form-control')) !!}
    {!! Form::hidden('material_price', 0, array('class' => 'material_price form-control')) !!}
    {!! Form::hidden('cost_per_mtr', 0, array('class' => 'cost_per_mtr form-control')) !!}
    {!! Form::hidden('cost_per_yrd', 0, array('class' => 'cost_per_yrd form-control')) !!}
    {!! Form::hidden('ex_rate', 0, array('class' => 'ex_rate form-control')) !!}
    {!! Form::hidden('import_tax', 0, array('class' => 'import_tax form-control')) !!}
    {!! Form::hidden('transport_shipping_paid', 0, array('class' => 'transport_shipping_paid form-control')) !!}
    <td class="td-action">
        <a class="btn btn-danger btn-sm btn-square inv_delete my-1 text-light">Delete</a>
        <button type="button" class="btn btn-sm btn-primary btn-square my-1 btn-roll-select" data-material_id="" data-toggle="modal" data-target="#rollSelectModel">Select Roll</button>
        <div id='' class="hidden_div"></div>
    </td>
    {!! Form::hidden('item_id[]',null,array('id'=>'inv_item_id')) !!}
</script>

<script type="text/template" id="templateAddItem_roll">
    <td>{!! Form::checkbox('select[]',0,false, array('id'=>'select_roll','class' => 'form-control input-sm select_roll')) !!}</td>
        <td>{!! Form::text('roll_no[]',null, array('id'=>'roll_no','class' => 'form-control input-sm roll_no', 'data-validation'=>"required",'readonly'=>'readonly')) !!}</td>
        {!! Form::hidden('roll_id[]',null, array('id'=>'roll_id','class' => 'form-control input-sm roll_id', 'data-validation'=>"required")) !!}
        <td>{!! Form::text('pcs_no[]',null, array('id'=>'pcs_no','class' => 'form-control input-sm pcs_no', 'data-validation'=>"required",'readonly'=>'readonly')) !!}</td>
        <td>{!! Form::text('article_no[]',null, array('id'=>'article_no','class' => 'form-control input-sm article_no', 'data-validation'=>"required",'readonly'=>'readonly')) !!}</td>
        <td>{!! Form::text('color_no[]',null, array('id'=>'color_no','class' => 'form-control input-sm color_no', 'data-validation'=>"required",'readonly'=>'readonly')) !!}</td>
        <td>{!! Form::text('batch_no[]',null, array('id'=>'batch_no','class' => 'form-control input-sm batch_no', 'data-validation'=>"required",'readonly'=>'readonly')) !!}</td>
        <td>{!! Form::text('available_qty[]',null, array('id'=>'available_qty','class' => 'form-control input-sm available_qty', 'data-validation'=>"required",'readonly'=>'readonly')) !!}</td>
        {{-- <td>{!! Form::Select('option[]',["0"=>"Take All","1"=>"Cut As Per Order","2"=>"Type How Much To Cut"],null, array('id'=>'option','class' => 'form-control input-sm option', 'data-validation'=>"required",'disabled'=>'disabled')) !!}</td> --}}
        <td>{!! Form::Select('option[]',["0"=>"Take All","2"=>"Type How Much To Cut"],null, array('id'=>'option','class' => 'form-control input-sm option', 'data-validation'=>"required",'disabled'=>'disabled')) !!}</td>
        <td>{!! Form::number('meter[]',null, array('id'=>'meter','class' => 'form-control input-sm meter', 'data-validation'=>"required",'readonly'=>'readonly')) !!}</td>

</script>

@endsection
@push('scripts')
<script src="{{ asset('assets/js/datepicker/moment.min.js') }}"></script>
<script src="{{ asset('assets/js/datepicker/daterangepicker.js') }}"></script>
<script type="text/javascript">
    $(document).ready(function() {

        if ($('#price_vat').val() === 'price_exclude_vat') {
            $('#gst_info').show(); 
            $('#total_amount_info').show(); 
        } else {
            $('#gst_info').hide(); 
            $('#total_amount_info').hide(); 
        }

        let selectedRolls = []; 
        var itemRolls = {};

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
                var isValid = true;

                $('.invoiceitem').each(function () {
                    var meter = parseFloat($(this).find('.inv_meter').val()) || 0;
                    var unitOfSale = $(this).find('.unit_of_sale').val();
                    var typeOfSale = $(this).find('.type_of_sale').val();
                    console.log("meter"+meter);
                    console.log("unitOfSale"+unitOfSale);
                    console.log("typeOfSale"+typeOfSale);
                    
                    if (meter >= 0 && (!unitOfSale || !typeOfSale)) {
                        isValid = false;
                        alert('Please select Unit of Sale and Type of Sale for all existing articles before adding a new article.');
                        return false;
                    }
                });

                if (!isValid) {
                    return;
                }

                $("#search_color").html('');
                $.ajax({
                    url: "{{ route('materials.index') }}",
                    dataType: "json",
                    data: {
                        article: article_no
                    },
                    success: function(data) {
                        $("#search_color").append(`<option value="">-- Select color --</option>`);
                        $.each(data,function(i){
                            $("#search_color").append(`<option value="${i}">${data[i]}</option>`); 
                        })
                    }
                });
            });
        $('#search_article').trigger('change');
        var input_search_color = $('#search_color');
        input_search_color.select2();
        

        $(document).on('change','#search_color',function(){
            var user_id = $('#user_id').val();
            var search_color = $(this).val();
            
            if (search_color && !user_id) {
                // If color is selected but no customer is selected
                alert('Please select a customer before selecting a color.');
                $(this).val(''); // Clear the color selection
                $('#user_id').focus(); // Focus on the customer select box
                $("#search_color").html('');
                return false;
            }
            var color_no = $(this).val(); 
            if(color_no!=""){
                $.ajax({
                    url: "{{ route('invoice.getMaterial') }}",
                    dataType: "json",
                    data: {
                        color: color_no,
                        artical:$("#search_article").val(),
                        cus_id: user_id
                    },
                    success: function(data) {
                        var item_id = $('.inv_item_id_' + data.id).val() ? $('.inv_item_id_' + data.id).val() : 0;
                        
                        if (item_id) {
                            $('#search_error').fadeIn(300).css('display', 'block').html("This material allready selected").fadeOut(3000);
                        } else {
                            addSearchMaterial(data);

                            openRollModal(data.id);
                        }
                    }
                });
            }      
        });

        // Function to open the modal and fetch roll data based on material (item) ID
        function openRollModal(item_id) {
            var roll_data;
            var modal_item_id;
            
            // Open the modal directly
            $('#rollSelectModel').modal('show');

            // When the modal is shown, trigger the AJAX request to fetch roll data
            $('#rollSelectModel').one('shown.bs.modal', function() {
                $('#tblRoll tbody').empty();  // Clear previous data in the table
                modal_item_id = item_id; // Store the item ID
                $('#material_item_id').val(item_id); // Set the material ID in the modal input
                
                // Make the AJAX request to fetch roll data based on the material ID
                $.ajax({
                    url: '{{route("invoice.get-roll")}}', // Your route to get roll data
                    data: {
                        'material_id': item_id // Pass the material ID to fetch corresponding roll data
                    },
                    dataType: "json",
                    success: function(data) {
                        roll_data = data.roll; // Assuming the response contains a 'roll' array
                        // Process the roll data and populate the modal table
                        $.each(roll_data, function(index, value) {
                            addRoll(value); // Add each roll to the table
                        });

                        // Calculate total meters if necessary
                        calculateTotalMeter(roll_data);
                    }
                }).then(function() {
                    // Update meter values after the AJAX call
                    $('#remember_meter').html($('#item-' + item_id + ' .inv_meter').val());
                    $('#total_selected_meter').html(0);
                    $('#total_select_mtr').html(0);
                    $('#total_select_yrd').html(0);
                    
                    // If any rolls are selected previously, process the selected rolls
                    if (!$('#item-rolls-' + item_id).is(':empty')) {
                        var meter = 0;
                        $.each($('#item-rolls-' + item_id + ' input'), function(i, v) {
                            var str = v.id;
                            var n = str.lastIndexOf("_");
                            var roll_id = str.substring(n + 1);
                            $.each($('.select_roll'), function(index, value) {
                                if (value.value == roll_id) {
                                    value.checked = true;
                                    $(value).closest('tr').addClass('table-success');
                                    $(value).closest('tr').find('.meter').val($('#item_roll_' + item_id + '_' + roll_id).val());
                                    $(value).closest('tr').find('.meter').attr('readonly', false);
                                    meter += parseFloat(v.value);
                                }
                            });
                        });

                        // Update the total selected meter
                        $('#total_selected_meter').html(meter);
                        $('#total_select_mtr').html(meter);
                        var yard = meterToYard(meter);
                        $('#total_select_yrd').html(yard);
                        totalmeter();
                    }
                });
            });
        }

        $(document).on('change', '.unit_of_sale, .type_of_sale', function() {
            
            var $thisRow = $(this).closest('tr');
            var unit_of_sale = $thisRow.find('.unit_of_sale').val();
            var type_of_sale = $thisRow.find('.type_of_sale').val();
            var materialId = $thisRow.find('.inv_item_id').val();

            // Set the title attribute to display the selected unit of sale
            $(this).attr('title', `Unit Of Sale: ${unit_of_sale}`);

            // Fetch price from server
            fetchMaterialPrice(materialId, unit_of_sale, type_of_sale, $thisRow);
        });

        function fetchMaterialPrice(materialId, unit_of_sale, type_of_sale, $thisRow) {
            $.ajax({
                url: "{{ route('invoice.getMaterial') }}",
                type: "GET",
                dataType: "json",
                data: {
                    artical: $("#search_article").val(),
                    color: $("#search_color").val(),
                    cus_id: $('#user_id').val(),
                    material_id: materialId
                },
                success: function(data) {
                    var price = 0;

                    // Determine price based on unit_of_sale and type_of_sale
                    if (unit_of_sale === 'yard') {
                        if (type_of_sale === 'W') {  // Wholesale
                            price = parseFloat(data.cut_wholesale) || 0;
                        } else if (type_of_sale === 'R') {  // Retail
                            price = parseFloat(data.retail) || 0;
                        } else if (type_of_sale === 'P') {  // Sample
                            price = parseFloat(data.roll) || 0;
                        }
                    } else if (unit_of_sale === 'meter') {
                        if (type_of_sale === 'W') {  // Wholesale
                            price = parseFloat(data.cut_wholesale_per_mtr) || 0;
                        } else if (type_of_sale === 'R') {  // Retail
                            price = parseFloat(data.retail_per_mtr) || 0;
                        } else if (type_of_sale === 'P') {  // Sample
                            price = parseFloat(data.roll_per_mtr) || 0;
                        }
                    }

                    // Update price input and title
                    $thisRow.find('.inv_price').val(price.toFixed(2)).attr('title', `Price: ${price.toFixed(2)}`);

                    // Perform additional calculations if needed
                    handleCalculation($thisRow);
                },
                error: function(xhr, status, error) {
                    console.error('AJAX error:', status, error);
                    // Handle error appropriately
                }
            });
        }

        function addSearchMaterial(data) {
            $template = $('#templateAddItem_invoice').html();
            var $tr = $('<tr class="invoiceitem" id="item-' + data.id + '">').append($template);
            var unit_purchased_in = data.unit_purchased_in;

            $('#tblOrderTable tbody').append($tr);
            const $itemRow = $('#item-' + data.id);
            
            // Set initial values
            $itemRow.find('#inv_item_id').val(data.id);
            $itemRow.find('#inv_item_id').attr('class', 'inv_item_id_' + data.id);
            $itemRow.find('.inv_name').val(data.name + " - " + data.color).attr('title', `Brand Name: ${data.name}`);
            $itemRow.find('.inv_price').attr('name', 'price[]');
            $itemRow.find('.type_of_sale').attr('title', 'Type Of Sale: WholSale');
            $itemRow.find('.discount_type').attr('title', 'Discount Type: Percentage');
            $itemRow.find('.discount_value').attr('title', 'Discount Value: 0');
            // $itemRow.find('.inv_barcode').val(data.barcode).attr('title', `Barcode: ${data.barcode}`);
            $itemRow.find('.td-weight_gsm').attr("data-gsm", data.weight_gsm);
            $itemRow.find('.td-weight_per_mtr').attr("data-weight_per_mtr", data.weight_per_mtr);
            $itemRow.find('.td-weight_per_yard').attr("data-weight_per_yard", data.weight_per_yard);
            // $itemRow.find('.unit_of_sale').val(unit_purchased_in).attr('title', `Unit Of Sale: ${unit_purchased_in}`);
            $itemRow.find('.inv_weight').val(data.weight);
            $itemRow.find('.inv_weight').attr("data-value", data.weight);
            $itemRow.find('.hidden_div').attr('id', 'item-rolls-' + data.id);
            $itemRow.find('.btn-roll-select').data('material_id', data.id);
            $itemRow.find('#selected_meter').attr('class', 'selected_meter_' + data.id);
            $itemRow.find('.inv_delete').attr('data-id', 'item-' + data.id);

            $itemRow.find('.sr_no').val($('#tblOrderTable tbody tr.invoiceitem').length);
            $itemRow.find('.description').val(`${data.article_no} - ${data.color} # ${data.piece_no} # ${data.barcode}`).attr('title', `Article: ${data.article_no}, Color: ${data.color}, # Piece: ${data.piece_no}, Barcode: ${data.barcode}`);;
            $itemRow.find('.unit_sold').attr('title', 'Unit Sold:');
            $itemRow.find('.material_price').val(data.price);

            
            // Enable/disable fields based on unit purchased in
            toggleUnitFields($itemRow, unit_purchased_in);
            
            var user_id = $('#customer_id option:selected').val();
            var material_id = data.id;
            var price_w = price_r = price_s = 0;
            $.each(customer_item_price, function(i, v) {
                if (v.customer_id == user_id && v.material_id == material_id) {
                    $('#item-' + data.id).find('.inv_price').val(v.cut_wholesale);
                    price_w = v.cut_wholesale;
                    price_r = v.price;
                    price_s = v.roll;
                }
            });
            if(unit_purchased_in == "meter"){
                var cut_wholesale_price = data.cut_wholesale_per_mtr === "" ? 0 : parseFloat(data.cut_wholesale_per_mtr).toFixed(3) || 0;
                var retail_price = data.retail_per_mtr === "" ? 0 : parseFloat(data.retail_per_mtr).toFixed(3) || 0;
                var roll_price = data.roll_per_mtr === "" ? 0 : parseFloat(data.roll_per_mtr).toFixed(3) || 0;

            } else {
                var cut_wholesale_price = data.cut_wholesale === "" ? 0 : parseFloat(data.cut_wholesale).toFixed(3) || 0;
                var retail_price = data.retail_price === "" ? 0 : parseFloat(data.retail_price).toFixed(3) || 0;
                var roll_price = data.roll_price === "" ? 0 : parseFloat(data.roll_price).toFixed(3) || 0;
            }
            if(price_w==0){
                $('#item-' + data.id).find('.inv_price').val(cut_wholesale_price).attr('Title',`Price : ${cut_wholesale_price}`);
                price_w=cut_wholesale_price;
            }            
            if(price_r==0){
                price_r= retail_price;
            }
            if(price_s==0){
                price_s= roll_price;
            }

            $('#item-' + data.id).find('.inv_price').attr("data-wholesale", price_w);
            $('#item-' + data.id).find('.inv_price').attr("data-retail", price_r);
            $('#item-' + data.id).find('.inv_price').attr("data-sample", price_s);

            $('.type_of_sale').on('change', function() {
                var price_input = $(this).parents('tr').find('.inv_price');
                var price = 0;
                var saleType = '';

                if (this.value == "W") {
                    price = price_input.attr("data-wholesale");
                    saleType = 'Wholesale';
                }
                if (this.value == "R") {
                    price = price_input.attr("data-retail");
                    saleType = 'Retail';
                }
                if (this.value == "P") {
                    price = price_input.attr("data-sample");
                    saleType = 'Sample';
                }

                // Set the title attribute to display the selected sale type
                $(this).prop('title', 'Type of Sale: ' + saleType);

                price_input.val(price);

                if (!isNaN(price) && price) {
                    price = parseFloat(price).toFixed(2);
                } else {
                    price = 0;
                }

                var $thisRow = $(this).closest('tr');
                var meter = parseFloat($thisRow.find('.inv_meter').val()).toFixed(2) || 0;
                var yard = meter2yard(meter).toFixed(2);
                var unit_of_sale = $thisRow.find('.unit_of_sale').val() || ''; 
                var discountType = $thisRow.find('.discount_type').val() || ''; 
                var discountValue = parseFloat($thisRow.find('.discount_value').val()) || 0;

                $(this).attr('data-value', yard);

                // Function to update prices based on unit of sale
                function updatePricesBasedOnUnit(unit_purchased_in, data) {
                    var cut_wholesale_price = 0;
                    var retail_price = 0;
                    var roll_price = 0;

                    if (unit_purchased_in === "meter") {
                        cut_wholesale_price = data.cut_wholesale_per_mtr === "" ? 0 : parseFloat(data.cut_wholesale_per_mtr).toFixed(3);
                        retail_price = data.retail_per_mtr === "" ? 0 : parseFloat(data.retail_per_mtr).toFixed(3);
                        roll_price = data.roll_per_mtr === "" ? 0 : parseFloat(data.roll_per_mtr).toFixed(3);
                    } else {
                        cut_wholesale_price = data.cut_wholesale === "" ? 0 : parseFloat(data.cut_wholesale).toFixed(3);
                        retail_price = data.retail_price === "" ? 0 : parseFloat(data.retail_price).toFixed(3);
                        roll_price = data.roll_price === "" ? 0 : parseFloat(data.roll_price).toFixed(3);
                    }

                    // Update the price based on unit_purchased_in
                    switch (saleType) {
                        case 'Wholesale':
                            price = cut_wholesale_price;
                            break;
                        case 'Retail':
                            price = retail_price;
                            break;
                        case 'Sample':
                            price = roll_price;
                            break;
                    }
                }

                // Assuming `data` is available and contains pricing info
                var data = {
                    cut_wholesale_per_mtr: price_input.attr("data-wholesale-per-mtr"),
                    retail_per_mtr: price_input.attr("data-retail-per-mtr"),
                    roll_per_mtr: price_input.attr("data-roll-per-mtr"),
                    cut_wholesale: price_input.attr("data-wholesale"),
                    retail_price: price_input.attr("data-retail"),
                    roll_price: price_input.attr("data-sample")
                };

                // updatePricesBasedOnUnit(unit_of_sale, data);

                if (!isNaN(yard) && yard) {
                    var meter = yard2meter(yard).toFixed(2);
                    $('.inv_meter', $thisRow).val(meter).attr('title', `Meter : ${meter}`);   
                    var weight = $('.inv_weight', $thisRow).attr('data-value');
                    $('.inv_weight', $thisRow).val(weight * yard);

                    totalmeter();
                    grandtotal();
                }

                var total = 0;
                if (price == 0) {
                    total = 0;
                    // $(this).closest('tr').find('.td-total-price').attr('data-value', total).html(total);
                    $('.total-price', $thisRow).val(total).attr('title', `Total Amount: ${total}`);
                    sub_total();
                    grandtotal();
                } else if (!isNaN(price) && price) {
                    if (unit_of_sale == 'yard') {
                        total = parseFloat(price * yard).toFixed(2);
                    } else {
                        total = parseFloat(price * meter).toFixed(2);
                    }
                    

                    // Apply discount
                    if (discountType === 'percentage') {
                        total = parseFloat(total - (total * discountValue / 100)).toFixed(2);
                    } else if (discountType === 'amount') {
                        total = parseFloat(total - discountValue).toFixed(2);
                    }

                    // $(this).closest('tr').find('.td-total-price').attr('data-value', total).html(total);
                    $('.total-price', $thisRow).val(total).attr('title', `Total Amount: ${total}`);
                    sub_total();
                    grandtotal();
                }
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

        function toggleUnitFields($itemRow, unitPurchasedIn) {
            // if (unitPurchasedIn === 'meter') {
            //     $itemRow.find('.inv_meter').prop('readonly', false);
            //     $itemRow.find('.inv_yard').prop('readonly', true);
            // } else if (unitPurchasedIn === 'yard') {
                $itemRow.find('.inv_meter').prop('readonly', true);
                $itemRow.find('.inv_yard').prop('readonly', true);
            // }
        }

        // Event handler for changes in relevant fields
        $(document).on('input change', ' .inv_price, .discount_value, .discount_type', function() {
            var $thisRow = $(this).closest('tr');
            handleCalculation($thisRow);
        });

        // Function to convert yards to meters
        function yard2meter(yard) {
            return yard * 0.9144;
        }

        // Convert meter to yard
        function meterToYard(meter) {
            return meter2yard(meter).toFixed(2);
        }

        // Calculate total price based on discount
        function calculateTotal(price, amount, discountType, discountValue) {
            // let total = parseFloat(price * amount).toFixed(2);
            let initialTotal = parseFloat(price * amount).toFixed(2);
            let discountAmount = 0;

            if (discountType === 'percentage') {
                discountAmount = parseFloat(initialTotal * (discountValue / 100)).toFixed(2);
            } else if (discountType === 'amount') {
                discountAmount = parseFloat(discountValue).toFixed(2);
            }

            let total = parseFloat(initialTotal - discountAmount).toFixed(2);

            return {
                total: total,
                discountAmount: discountAmount
            };
        }

        // Handle the calculation logic
        function handleCalculation($thisRow) {
            
            var meter = parseFloat($thisRow.find('.inv_meter').val()).toFixed(2);
            var price = parseFloat($thisRow.find('.inv_price').val()).toFixed(2);
            var unit_of_sale = $thisRow.find('.unit_of_sale').val() || '';
            var discountType = $thisRow.find('.discount_type').val() || 0;
            var discountValue = parseFloat($thisRow.find('.discount_value').val()) || 0;
            
            $thisRow.find('.inv_meter').attr('title',`Meter : ${meter}`);
            $thisRow.find('.discount_type').attr('title',`Discount Type: ${discountType}`);
            $thisRow.find('.discount_value').attr('title',`Discount Value: ${discountValue}`);
            $thisRow.find('.inv_price').attr('title',`Price : ${price}`);

            if (!isNaN(meter) && meter) {
                var yard = meterToYard(meter);
                $('.inv_yard', $thisRow).val(yard).attr('title',`Yard : ${yard}`);
                var weight = parseFloat($('.inv_weight', $thisRow).attr('data-value')) || 0;
                $('.inv_weight', $thisRow).val(weight * meter);                
            }

            if (!isNaN(price) && price && !isNaN(meter) && meter && unit_of_sale) {
                var yard = meterToYard(meter);
                
                // var total = unit_of_sale === 'yard' ? calculateTotal(price, yard, discountType, discountValue) : calculateTotal(price, meter, discountType, discountValue);
                var { total, discountAmount } = unit_of_sale === 'yard'? calculateTotal(price, yard, discountType, discountValue): calculateTotal(price, meter, discountType, discountValue);
                $('.total-price', $thisRow).val(total).attr('title', `Total Amount: ${total}`);
                $('.discount_amount', $thisRow).val(discountAmount);

                sub_total();
                grandtotal();
                totalmeter();
            }
        }

        // Event handler for 'change' and 'keyup' events
        $(document).on('change keyup', '.inv_meter, .inv_price', function() {
            var $thisRow = $(this).closest('tr');
            handleCalculation($thisRow);
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
                        })

                        calculateTotalMeter(roll_data);
                    }
                })
            ).then(function() {
                $('#remember_meter').html($('#item-' + item_id + ' .inv_meter').val());
                $('#total_selected_meter').html(0);
                $('#total_select_mtr').html(0);
                $('#total_select_yrd').html(0);
                if (!$('#item-rolls-' + item_id).is(':empty')) {
                    var meter = 0;
                    $.each($('#item-rolls-' + item_id + ' input'), function(i, v) {
                        var str = v.id;
                        var n = str.lastIndexOf("_");
                        var roll_id = str.substring(n + 1);
                        var data;
                        $.each($('.select_roll'), function(index, value) {
                            if (value.value == roll_id) {
                                value.checked = true;
                                $(value).closest('tr').addClass('table-success');
                                $(value).closest('tr').find('.meter').val($('#item_roll_' + item_id + '_' + roll_id).val());
                                $(value).closest('tr').find('.meter').attr('readonly', false);
                                meter += parseFloat(v.value);
                            }
                        });
                        // addRoll(data);
                    });

                    $('#total_selected_meter').html(meter);
                    $('#total_select_mtr').html(meter);
                    var yard = meterToYard(meter);
                    $('#total_select_yrd').html(yard);
                    totalmeter();
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
            $('#' + $uniqueId).find('.pcs_no').val(data.piece_no);
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

            var hiddenFields = [
                { name: 'color', class: 'color', value: data.color },
                { name: 'barcode_svg', class: 'barcode_svg', value: data.barcode_svg },
                { name: 'ex_rate', class: 'ex_rate', value: data.ex_rate },
                { name: 'import_tax', class: 'import_tax', value: data.import_tax },
                { name: 'transport_shipping_paid', class: 'transport_shipping_paid', value: data.transport_shipping_paid },
                { name: 'cost_per_mtr', class: 'cost_per_mtr', value: data.cost_per_mtr },
                { name: 'cost_per_yrd', class: 'cost_per_yrd', value: data.cost_per_yrd }
            ];

            hiddenFields.forEach(function(field) {
                var hiddenInput = $('<input>').attr({
                    type: 'hidden',
                    name: field.name,
                    class: field.class,
                    value: field.value
                });
                $tr.append(hiddenInput);
            });
            
        }

        function calculateTotalMeter(roll_data) {
            var totalMeter = 0;
            var totalRolls = roll_data.length;

            // Loop through each roll item and calculate the total meter
            $.each(roll_data, function (index, value) {
                totalMeter += parseFloat(value.available_qty); // Assuming available_qty is the meter value
            });

            // Update the total meter in the modal header
            $('#total_meters').html(totalMeter.toFixed(2));
            $('#total_rolls').html(totalRolls.toFixed(2));
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
                $('#total_select_mtr').html(selected_meter.toFixed(2));
                var selected_yrd = meterToYard(selected_meter);
                $('#total_select_yrd').html(selected_yrd);
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
                $('#total_select_mtr').html(selected_meter.toFixed(2));
                var selected_yrd = meterToYard(selected_meter);
                $('#total_select_yrd').html(selected_yrd);
                $('#total_select_mtr').html(selected_meter.toFixed(2));
                var selected_yrd = meterToYard(selected_meter);
                $('#total_select_yrd').html(selected_yrd);
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
                var available_qty = parseFloat($(this).closest('tr').find('#available_qty').val());
                var selected_mtr = available_qty.toFixed();
                $(this).closest('tr').find('#meter').val(selected_mtr)
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
                $('#total_select_mtr').html(selected_meter.toFixed(2));
                var selected_yrd = meterToYard(selected_meter);
                $('#total_select_yrd').html(selected_yrd);
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
                $('#total_select_mtr').html(meter.toFixed(2));
                var yard = meterToYard(meter.toFixed(2));
                $('#total_select_yrd').html(yard);
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
                }
                if (v.value != '') {
                    meter += parseFloat(v.value);
                }
                $('#total_selected_meter').html(meter);
                $('#total_select_mtr').html(meter);
                var yard = meterToYard(meter);
                $('#total_select_yrd').html(yard);
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
                $('#total_selected_meter').html(meter);
                $('#total_select_mtr').html(meter);
                var yard = meterToYard(meter);
                $('#total_select_yrd').html(yard);
                user_enter_val = parseFloat($('#remember_meter').html());
                $('#show_extra').html((meter - user_enter_val).toFixed(2));
            })
            var total_meter = parseFloat($('#item-' + modal_item_id + ' .td-meter').data("value"));
        });

        function updateSelectedRollModal() {
            const selectedRollContainer = $('#selectedRollTable'); // Assuming this is the container for displaying selected rolls
            selectedRollContainer.empty(); // Clear previous contents

            if (selectedRolls.length === 0) {
                selectedRollContainer.append('<p>No rolls selected.</p>');
                return;
            }

            $.each(selectedRolls, function(index, roll) {
                // Create a display for each selected roll
                const rollInfo = `<div>
                    Roll ID: ${roll.rollId}, 
                    Roll No: ${roll.roll_no}, 
                    Meter: ${roll.meter}, 
                    Pcs No: ${roll.pcs_no}, 
                    Article No: ${roll.article_no}, 
                    Color No: ${roll.color_no}, 
                    Batch No: ${roll.batch_no}, 
                    Available Qty: ${roll.available_qty}
                </div>`;
                selectedRollContainer.append(rollInfo);
            });
        }

        // Save selected rolls for the item
        var itemRolls = {};
        $('#model_save_btn').on('click', function() {
            var item_id = $('#material_item_id').val();
            var input_hidden;
            var selected_role = 0;
            var roll_id_values = [];

            // Initialize itemRolls[item_id] as an array if not already set
            if (!itemRolls[item_id]) {
                itemRolls[item_id] = [];
            }

            // Clear the existing roll selections for this item to prevent duplicates
            itemRolls[item_id] = [];

            // Loop through each roll and check if it is selected
            $('#item-rolls-' + item_id).empty();
            $.each($('.select_roll'), function(index, value) {
                
                // If roll is checked, add it to the selected rolls for the item
                if (value.checked) {
                    var roll_id = $(value).closest('tr').find('.roll_id').val();
                    var meter = $(value).closest('tr').find('.meter').val();
                    var roll_no = $(value).closest('tr').find('.roll_no').val();
                    var pcs_no = $(value).closest('tr').find('.pcs_no').val();
                    var article_no = $(value).closest('tr').find('.article_no').val();
                    var color_no = $(value).closest('tr').find('.color_no').val();
                    var color = $(value).closest('tr').find('.color').val();
                    var barcode_svg = $(value).closest('tr').find('.barcode_svg').val();
                    var ex_rate = $(value).closest('tr').find('.ex_rate').val();
                    var import_tax = $(value).closest('tr').find('.import_tax').val();
                    var transport_shipping_paid = $(value).closest('tr').find('.transport_shipping_paid').val();
                    var cost_per_mtr = $(value).closest('tr').find('.cost_per_mtr').val();
                    var cost_per_yrd = $(value).closest('tr').find('.cost_per_yrd').val();
                    
                    var batch_no = $(value).closest('tr').find('.batch_no').val();
                    var available_qty = $(value).closest('tr').find('.available_qty').val();

                    itemRolls[item_id].push({
                        roll_id: roll_id,
                        roll_no: roll_no,
                        pcs_no: pcs_no,
                        article_no: article_no,
                        color_no: color_no,
                        color: color,
                        batch_no: batch_no,
                        available_qty: available_qty,
                        meter: meter,
                        barcode_svg: barcode_svg,
                        ex_rate: ex_rate,
                        import_tax: import_tax,
                        transport_shipping_paid: transport_shipping_paid,
                        cost_per_mtr: cost_per_mtr,
                        cost_per_yrd: cost_per_yrd,
                    });

                    // Hidden input for form submission
                    var input_hidden = $('<input>').attr({
                        name: "item_roll[" + item_id + "][" + roll_id + "]",
                        id: "item_roll_" + item_id + "_" + roll_id,
                        type: "hidden",
                        value: meter,
                    });
                    $('#item-rolls-' + item_id).append(input_hidden);

                    roll_id_values.push(roll_id);

                     // Push roll data along with itemId to selectedRolls
                    const existingRoll = selectedRolls.find(roll => roll.itemId === item_id && roll.rollId === roll_id);
                    if (!existingRoll) {
                        selectedRolls.push({ itemId: item_id, rollId: roll_id, roll_no: roll_no, pcs_no: pcs_no, article_no: article_no, color_no: color_no, color: color, batch_no: batch_no,available_qty: available_qty , meter: meter , barcode_svg: barcode_svg, ex_rate: ex_rate, import_tax: import_tax, transport_shipping_paid: transport_shipping_paid,cost_per_mtr: cost_per_mtr, cost_per_yrd: cost_per_yrd});
                    }
                    $('#item-rolls-' + item_id).append(input_hidden);

                    selected_role += 1;
                } else {
                    // Logic for unselecting a roll, if applicable
                    var roll_id = $(value).closest('tr').find('.roll_id').val();
                    selectedRolls = selectedRolls.filter(roll => !(roll.itemId === item_id && roll.rollId === roll_id)); // Remove unselected roll
                }
            });

            // Update the selectedRollModal to reflect the current selection
            updateSelectedRollModal();

            // First, check if the item row already exists in the table
            var itemRow = $('#tblOrderTable tbody').find(`tr.item-row[id="item-${item_id}"]`);
            if (itemRow.length === 0) {
                // If the item doesn't exist, create a new row for the item with a heading column
                var newItemRow = `
                    <tr class="item-row" id="item-${item_id}">
                        <th>Roll No</th>
                        <th>PCS No</th>
                        <th>Article No</th>
                        <th>Color No</th>
                        <th>Batch No</th>
                        <th>Available Qty</th>
                        <th>Meter</th>
                        <th>Barcode</th>
                    </tr>`;
                $('#tblOrderTable tbody').append(newItemRow);
            }

            // Remove any existing roll rows for this specific item to prevent duplicates
            $('#tblOrderTable tbody').find(`tr.roll-row[id="item-${item_id}"]`).remove();

            // Now, append the rolls for the specific item under the item's row
            if (itemRolls[item_id].length > 0) {
                itemRolls[item_id].forEach(function(roll) {
                    var piece_no = roll.pcs_no;
                    var rollRow = `
                        <tr class="roll-row" id="item-${item_id}">
                            <td>${roll.roll_no}</td> 
                            <td>${roll.pcs_no}</td> 
                            <td>${roll.article_no}</td>
                            <td>${roll.color_no}</td>
                            <td>${roll.batch_no}</td>
                            <td>${roll.available_qty}</td>
                            <td>${roll.meter}</td>
                            <td>${roll.barcode_svg}</td>
                        </tr>`;
                    $('#tblOrderTable tbody').find(`tr.item-row[id="item-${item_id}"]`).after(rollRow);

                    const descriptionValue = `${roll.article_no} - ${roll.color} # ${roll.pcs_no}`;
                    const descriptionTitle = `Article: ${roll.article_no}, Color: ${roll.color}, Piece: ${roll.pcs_no}`;
                    $('#item-' + item_id).find('.td-description .description').val(descriptionValue).attr('title', descriptionTitle);
                    $('#item-' + item_id).find('.ex_rate').val(roll.ex_rate);
                    $('#item-' + item_id).find('.import_tax').val(roll.import_tax);
                    $('#item-' + item_id).find('.transport_shipping_paid').val(roll.transport_shipping_paid);
                    $('#item-' + item_id).find('.cost_per_mtr').val(roll.cost_per_mtr);
                    $('#item-' + item_id).find('.cost_per_yrd').val(roll.cost_per_yrd);
                });
            }

            $('#selectedrole-' + item_id).html(selected_role);

            // Hidden input for roll IDs
            var new_role_id = roll_id_values.join(',');
            var input_hidden = $('<input>').attr({
                name: "roll_id[]",
                id: "roll_id",
                type: "hidden",
                value: new_role_id,
            });
            $('#item-rolls-' + item_id).append(input_hidden);

            // Update the selected meter for the specific item
            var total_selected_meter = $("#total_selected_meter").html();
            $('#item-' + item_id + ' .td-selected-meter').html(total_selected_meter).attr('title', `Selected Role: ${total_selected_meter}`);
            $('#item-' + item_id + ' .td-selected-meter').append('<button type="button" class="btn btn-info btn-sm btn-display-info ml-1" data-item-id="' + item_id + '">Info</button>');
            $('#item-' + item_id).find('.td-meter .inv_meter').val(total_selected_meter);
            
            // Trigger recalculations
            handleCalculation($('#item-' + item_id));
            selectedtotalmeter();
            grand_total();
            totalmeter();

            // Hide modal and reset roll table content
            $('#rollSelectModel').modal('hide');
            $('#rollSelectModel #tblRoll tbody').html('');
        });

        $(document).on('click', '.btn-display-info', function() {
            var itemId = $(this).data('item-id'); // Get the associated item ID
            var rollsForItem = selectedRolls.filter(roll => roll.itemId.toString() === itemId.toString());
            var displayHtml = '<table class="table"><thead><tr><th>Roll No</th><th>PSC NO</th><th>Article No</th><th>Color No</th><th>Batch No</th><th>Available Meter</th><th>Meter</th><th>Barcode</th></tr></thead><tbody>';
            
            if (rollsForItem.length === 0) {
                displayHtml += '<tr><td colspan="7">No rolls selected for this item.</td></tr>';
            } else {
                rollsForItem.forEach(function(roll) {
                    displayHtml += '<tr>' +
                        '<td>' + roll.roll_no + '</td>' +
                        '<td>' + roll.pcs_no + '</td>' +
                        '<td>' + roll.article_no + '</td>' +
                        '<td>' + roll.color_no + '</td>' +
                        '<td>' + roll.batch_no + '</td>' +
                        '<td>' + roll.available_qty + '</td>' +
                        '<td>' + roll.meter + '</td>' +
                        '<td>' + roll.barcode_svg + '</td>' +
                        '</tr>';
                });
            }

            displayHtml += '</tbody></table>';
            
            // Display in a modal
            $('#selectedRollModal .modal-body').html(displayHtml);
            $('#selectedRollModal').modal('show');
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
            var totalMeter = 0;
            var totalYard = 0;
            var totalWeight = 0;
            var totalProfit = 0;

            $('.invoiceitem').each(function() {
                var meter = Number($(this).find('.inv_meter').val()) || 0;
                var yard = Number($(this).find('.inv_yard').val()) || 0;
                const sellingPrice = Number($(this).find('.total-price ').val()) || 0;
                const unit_of_sale = $(this).find('.unit_of_sale').val(); 
                var itemId = $(this).find('.inv_meter').data('item-id'); 
                const weight_per_mtr = Number($(this).closest('tr').find('.td-weight_per_mtr').data('weight_per_mtr')) || 0;
                const weight_per_yard = Number($(this).closest('tr').find('.td-weight_per_yard').data('weight_per_yard')) || 0;
                const actualPricePerUnit = unit_of_sale === 'yard' ?  Number($(this).find('.cost_per_yrd').val()) || 0 : Number($(this).find('.cost_per_mtr').val()) || 0;

                if (yard > 0 && meter === 0) {
                    meter = yard2meter(yard); 
                } else if (meter > 0 && yard === 0) {
                    yard = meter2yard(meter);
                }

                const weight = unit_of_sale === 'yard' ? yard * weight_per_yard : meter * weight_per_mtr;
                const qtySold = unit_of_sale === 'yard' ? yard : meter;
                const profit = (sellingPrice) - (actualPricePerUnit * qtySold);
                
                totalMeter += meter;
                totalYard += yard;
                totalWeight += weight;
                totalProfit += profit;
            });

            $('#totalMeter').html("Total Meter: " + totalMeter.toFixed(2) + " / " + totalYard.toFixed(2));

            $('#approximate_weight').val(totalWeight.toFixed(2));
            $('#total_profit').val(totalProfit.toFixed(2));
        }

        function selectedtotalmeter(callback) {
            var total = 0;

            $.each($('.td-selected-meter'), function(i, v) {
                var value = $(v).html().trim(); 
                var number = parseFloat(value); 
                if (!isNaN(number)) {
                    total += number;
                } else {
                    console.warn('Invalid value in td-selected-meter:', value);
                }
            });

            $('#SelectedTotalMeter').html(total.toFixed(2));

            var sel_roll_total = total.toFixed(2);
            if (isNaN(sel_roll_total)) {
                console.warn('Invalid sel_roll_total:', $('#SelectedTotalMeter').html());
                sel_roll_total = 0; 
            }

            if (typeof callback === 'function') {
                callback(sel_roll_total);
            }
        }
        
        function totalrow() {
            var rowCount = $("#tblOrderTable tbody tr").length;
            $('#total_number_of_items').val(rowCount);
        };
        $(document).on('click', '.inv_delete', function() {
            var rowid = $(this).data('id');
            // $('#' + rowid).remove();
            $('tr[id="' + rowid + '"]').remove();
            totalrow();
            totalmeter();
            grandtotal();

            $('.sr_no').each(function(index) {
                $(this).val(index + 1);
            });
        });

        function grandtotal() {
            var grand_total = 0;
            $('.total-price').each(function() {
                grand_total += Number($(this).val());
            });

            var vatRate = {{ $vat }};

            var discount_total = 0;
            $('.discount_amount').each(function() {
                discount_total += Number($(this).val() || 0);
            });

            updateGrandTotalWithVAT(grand_total, vatRate, discount_total);
        };

        function updateGrandTotalWithVAT(grandTotal, vatRate, discountTotal) {
            
            if ($('#price_vat').val() === 'price_exclude_vat') {
                $('#total_sum').html("Total Amount (Before Disc.) : " + "฿" + parseFloat(grandTotal+discountTotal).toFixed(2));

                $('#total_amount_before_vat').html("Total Amount (Before VAT.) : " + "฿" + parseFloat(grandTotal).toFixed(2));

                var vatAmount = (grandTotal * vatRate) / 100;

                grandTotal += vatAmount;

                $('#grand_total').html("Grand Total (incl. VAT): " + "฿" + parseFloat(grandTotal).toFixed(2));
                $('#vat_amount').text("฿"+parseFloat(vatAmount).toFixed(2));
                $('#gst_info').show();
                $('#total_amount_info').show();
                $('.grand_total').val(grandTotal);
                $('.vat_percentage').val(vatRate);
                $('#total_discount').html("Total Discount : " + "฿" + parseFloat(discountTotal).toFixed(2));

            } else {
                $('#grand_total').html("Grand Total: " + "฿" + parseFloat(grandTotal).toFixed(2));
                $('#gst_info').hide();
                $('#total_amount_info').hide();
                $('#total_sum').html("Total Amount (Before Disc.) : " + "฿" + parseFloat(grandTotal+discountTotal).toFixed(2));
                $('#total_amount_before_vat').html("Total Amount (Before VAT.) : " + "฿" + parseFloat(grandTotal-vatRate).toFixed(2));
                $('.grand_total').val(parseFloat(grandTotal).toFixed(2));
                $('#total_discount').html("Total Discount : " + "฿" + parseFloat(discountTotal).toFixed(2));

            }
        }

        $('#price_vat').on('change', function() {
            grandtotal(); 
        });
        
        function formatMoney(number) {
          return number.toLocaleString('th-TH', { style: 'currency', currency: 'THB' });
        }

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
        }
        
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
            $("#cut_wholesale_"+id).prop('type','text');
            $("#cut_wholesale__day"+id).prop('type','text');
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

            var wholeSale = $("#cut_wholesale_"+id).val();
            var wholeSale_day = $("#cut_wholesale_day"+id).val();
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
                            trHTML +=   '<tr>'+
                                            '<td>' + item.material.name + '</td><td>'+ item.material.article_no+ '</td>'+
                                            '<td>'+
                                                '<label class="show'+item.id+'" style="display:none">* Price :-</label>'+   
                                                '<input type="hidden" class="form-control" id="cut_wholesale_'+item.id+'" value="'+(item.cut_wholesale != null ? item.cut_wholesale : '0.00')+'">'+
                                                '<label class="show'+item.id+'" style="display:none">* Credit Days :-</label>'+   
                                                '<input type="hidden" class="form-control" id="cut_wholesale_day'+item.id+'" value="'+(item.wholesale_credit_days != null ? item.wholesale_credit_days : '0')+'">'+
                                                '<p id="wholesale_text_'+item.id+'">'+
                                                    '<label>* Price :- </label>'+   
                                                    (item.cut_wholesale != null ? item.cut_wholesale : '0.00')+ 
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
                                                '<input type="hidden" class="form-control" id="sampe_price_'+item.id+'" value="'+(item.roll != null ? item.roll : '0.00')+'">'+
                                                '<label class="show'+item.id+'" style="display:none">* Credit Days :-</label>'+   
                                                '<input type="hidden" class="form-control" id="sampe_price_day'+item.id+'" value="'+(item.sample_credit_days != null ? item.sample_credit_days : '0')+'">'+
                                                '<p id="sample_text_'+item.id+'">'+
                                                    '<label>* Price :- </label>'+   
                                                    (item.roll != null ? item.roll : '0.00')+ 
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
            $("#cut_wholesale_"+id).prop('type','hidden');
            $("#cut_wholesale_day"+id).prop('type','hidden');
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
<script type="text/javascript">
    var html = '<div class="text-center mt-5" style="font-size: 20px;">\
          No data found..!\
        </div>';
    $('.custom-selected-invoices').html(html);

        var currentDate = moment().format('DD/MM/YYYY HH:mm');
        
        $('#purchase_date').daterangepicker({
            singleDatePicker: true,
            showDropdowns: true,
            timePicker: true,
            timePicker24Hour: false,  
            locale: {
                format: 'DD/MM/YYYY HH:mm'  
            },
            autoApply: false,
            startDate: currentDate  
        });
        $('#purchase_date').val(currentDate);

        $('#delivered_date').daterangepicker({
            singleDatePicker: true,
            showDropdowns: true,
            timePicker: true,
            timePicker24Hour: false,  
            locale: {
                format: 'DD/MM/YYYY HH:mm' 
            },
            autoApply: false,
            startDate: currentDate 
        });
        $('#delivered_date').val(currentDate);
        
        $(document).on('change', '#user_id', function() {
            var user_id = $(this).val();
            $.ajax({
                url: "{{ route('get_customer_invoices') }}",
                dataType: "json",
                data: {
                    user_id: user_id
                },
                success: function(data) {
                    if (data.status === 200) {
                        $(".custom-selected-invoices").html('');
                        var html = '<table class="table mt-3 table-order-history">\
                                        <thead>\
                                            <tr>\
                                                <th scope="col">INV#</th>\
                                                <th scope="col">DATE</th>\
                                                <th scope="col">Amount</th>\
                                                <th scope="col">Payment Status</th>\
                                                <th scope="col">Note</th>\
                                            </tr>\
                                        </thead>\
                                        <tbody>';
                        var total_purchase = 0;
                        var monthly_purchase = 0;
                        var amount_to_receive = 0;

                        $.each(data.response, function(i, order_data) {
                            var payment_status = 'Paid';
                            var remark = order_data.note;

                            var orderDate = new Date(order_data.invoice_date.split("/").reverse().join("-")); 
                            var formattedDate = ("0" + orderDate.getDate()).slice(-2) + '/' +
                                                ("0" + (orderDate.getMonth() + 1)).slice(-2) + '/' +
                                                orderDate.getFullYear();

                            total_purchase += order_data.grand_total;
                            monthly_purchase += order_data.grand_total;
                            amount_to_receive += order_data.grand_total;

                            html += '<tr>\
                                        <td><a href="{{ url('invoice') }}/edit/' + order_data.id + '" data-toggle="tooltip" data-placement="top" title="View Invoices">' + order_data.inv_no + '</a></td>\
                                        <td>' + formattedDate + '</td>\
                                        <td>' + order_data.grand_total + '</td>\
                                        <td>' + payment_status + '</td>\
                                        <td>' + remark + '</td>\
                                    </tr>';
                        });

                        html += '</tbody>\
                                </table>';
                        
                        html += '<hr>';
                        
                        html += '<div class="row m-1">\
                                    <div class="col-md-4">\
                                        <h4>Total Purchase: ' + total_purchase.toFixed(2) + '</h4>\
                                    </div>\
                                    <div class="col-md-4">\
                                        <h4>Monthly Purchase: ' + monthly_purchase.toFixed(2) + '</h4>\
                                    </div>\
                                    <div class="col-md-4">\
                                        <h4>Amount to Receive: ' + amount_to_receive.toFixed(2) + '</h4>\
                                    </div>\
                                </div>';

                        $('.custom-selected-invoices').html(html);
                    } else {
                        var html = '<div class="text-center mt-5" style="font-size: 20px;">\
                                    No data found..!\
                                    </div>';
                        $('.custom-selected-invoices').html(html);
                    }
                }
            });
        });


    document.addEventListener("DOMContentLoaded", function() {
        var sidebarContainer = document.querySelector('.sidebar-container');
        var mainContent = document.querySelector('.main-content');
        var sidebar = document.querySelector('.sidebar');

        function toggleSidebar() {
            sidebarContainer.classList.toggle('sidebar-hidden');
            if (sidebarContainer.classList.contains('sidebar-hidden')) {
                mainContent.classList.remove('col-xl-8');
                mainContent.classList.add('col-xl-12');
                sidebar.classList.remove('col-xl-4');
                sidebar.classList.add('col-xl-0');
            } else {
                mainContent.classList.remove('col-xl-12');
                mainContent.classList.add('col-xl-8');
                sidebar.classList.remove('col-xl-0');
                sidebar.classList.add('col-xl-4');
            }
        }

        toggleSidebar();

        document.getElementById('btn-show-orders').addEventListener('click', function() {
            toggleSidebar();
        });
    });

    document.addEventListener('DOMContentLoaded', function () {
        const paymentTerm = document.getElementById('payment_term');
        const creditDays = document.getElementById('credit-days');

        paymentTerm.addEventListener('change', function () {
            if (paymentTerm.value === 'credit') {
                creditDays.classList.remove('d-none');
            } else {
                creditDays.classList.add('d-none');
            }
        });
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
