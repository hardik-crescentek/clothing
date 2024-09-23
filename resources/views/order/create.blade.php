@extends('layouts.master')
@section('title', 'Add Order')
@section('content')


<style type="text/css">
    .role-model{
        height: 750px !important;
        overflow-y: auto !important ;
    }

    .main-content {
        padding: 20px; /* Add padding or other styling as needed */
        transition: all 0.3s ease; /* Add transitions for smooth effect */
    }

    .sidebar-container {
        overflow: hidden; /* Ensure sidebar doesn't affect layout when hidden */
    }

    .sidebar {
        display: block; /* Default display state */
        transform: translateX(0); /* Ensure sidebar starts in view */
        transition: transform 0.3s ease; /* Add transitions for smooth sliding effect */
    }

    .sidebar-hidden {
        transform: translateX(100%); /* Slide sidebar out of view */
        transition: transform 0.3s ease; /* Add transitions for smooth sliding effect */
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
                    <div class="col-lg-4">
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
                    <div class="col-lg-4">
                        <br>
                        <button type="button" id="btn-show-orders" class="btn btn-primary btn-square mt-2">Show Past Orders</button>
                    </div>
                    <div class="col-lg-4">
                        <div class="form-group">
                            <label class="form-control-label">Date<span class="text-danger ml-2">*</span></label>
                            {!! Form::text('purchase_date', null, array('id' => 'purchase_date','class' => 'form-control', 'data-validation'=>"required")) !!}
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-4">
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

                    <!-- New Payment Term field -->
                    <div class="col-lg-4">
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

                    <div id="credit-days" class="form-group d-none col-lg-4">
                        <label class="form-control-label">Credit Days<span class="text-danger ml-2">*</span></label>
                        {!! Form::number('credit_day', null, array('id' => 'credit_days', 'class' => 'form-control', 'placeholder' => 'Enter days', 'min' => 1)) !!}
                    </div>
                </div>

                <div class="row">
                    <div class="col-lg-4">
                        <div class="form-group">
                            <label class="form-control-label">Scan Barcode Number</label>
                            <div class="input-group form-group">
                                <span class="input-group-addon addon-secondary"><i class="la la-barcode"></i></span>
                                {!! Form::text('input_search_barcode', null, array('id'=>'input_search_barcode','placeholder' => 'Barcode Number','class' => 'form-control')) !!}
                            </div>
                        </div>
                    </div>                    
                    <div class="col-lg-4">
                        <div class="form-group">
                            <label class="form-control-label">Article No</label>
                            <br>
                            {!! Form::select('search_article',$article_no, '' , array('class' => 'form-control','id'=>'search_article')) !!}
                        </div>
                    </div>
                    <div class="col-lg-4">
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
                                <th style="width:9%;">Brand Name</th>
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
                                <th style="width:9%;">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @isset($items)
                            @foreach ($items as $key => $item)
                            <tr class="material-link">
                                <td>{!! Form::text('name[]', $item['name'], array('class' => 'name form-control', 'data-validation'=>"required")) !!}</td>
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
                            <!-- <h4 class="mb-3">
                                <div id="totalItem"> Total Items : </div>
                            </h4> -->
                            <h4 class="mb-3">
                                <div id="totalMeter"> Total Meter : </div>
                            </h4>
                            <div class="form-group mb-3">
                                <label for="gst_checkbox">Apply GST/VAT ({{ $vat }}%) ? :</label>
                                <input type="checkbox" class="mt-1" id="gst_checkbox" />
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
                </div>
                
                <div class="form-group row d-flex align-items-center mt-5">
                    <div class="col-lg-12 d-flex justify-content-center">
                        <button type="submit" class="btn btn-primary btn-lg">Process Now</button>
                        <button type="submit" name="action" value="generate_invoice" class="btn btn-primary btn-lg ml-2">Process and Generate Invoice</button>
                    </div>
                </div>
                {!! Form::close() !!}
            </div>
        </div>
    </div>
    <div class="col-xl-4 col-12 sidebar-container sidebar">
        <div class="widget has-shadow">
            <div class="widget-header bordered no-actions d-flex align-items-center">
                <h4>Customer past orders <span class=".custom-selected-name"></span></h4>
            </div>
            <div class="custom-selected-orders">
                
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
</div>

<script type="text/template" id="templateAddItem_invoice">
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
        <td>{!! Form::Select('option[]',["0"=>"Take All","1"=>"Cut As Per Order","2"=>"Type How Much To Cut"],null, array('id'=>'option','class' => 'form-control input-sm option', 'data-validation'=>"required",'disabled'=>'disabled')) !!}</td>
        <td>{!! Form::number('meter[]',null, array('id'=>'meter','class' => 'form-control input-sm meter', 'data-validation'=>"required",'readonly'=>'readonly')) !!}</td>

</script>

@endsection
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
                        // console.log(data);
                        $("#search_color").append(`<option value="">-- Select color --</option>`);
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
                        }
                    }
                });
            }      
        });

        $(document).on('change', '.unit_of_sale, .type_of_sale', function() {
            var $thisRow = $(this).closest('tr');
            var unit_of_sale = $thisRow.find('.unit_of_sale').val();
            var type_of_sale = $thisRow.find('.type_of_sale').val();
            var materialId = $thisRow.find('.inv_item_id').val();

            // Set the title attribute to display the selected unit of sale
            $(this).attr('title', `Unit Of Sale: ${unit_of_sale}`);

            // Set read-only attributes based on unit_of_sale
            $thisRow.find('.inv_meter').prop('readonly', unit_of_sale === 'yard');
            $thisRow.find('.inv_yard').prop('readonly', unit_of_sale === 'meter');

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
            console.log(data);
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
            $itemRow.find('.inv_barcode').val(data.barcode).attr('title', `Barcode: ${data.barcode}`);
            $itemRow.find('.td-weight_gsm').attr("data-gsm", data.weight_gsm);
            $itemRow.find('.td-weight_per_mtr').attr("data-weight_per_mtr", data.weight_per_mtr);
            $itemRow.find('.td-weight_per_yard').attr("data-weight_per_yard", data.weight_per_yard);
            $itemRow.find('.unit_of_sale').val(unit_purchased_in).attr('title', `Unit Of Sale: ${unit_purchased_in}`);
            $itemRow.find('.inv_weight').val(data.weight);
            $itemRow.find('.inv_weight').attr("data-value", data.weight);
            $itemRow.find('.hidden_div').attr('id', 'item-rolls-' + data.id);
            $itemRow.find('.btn-roll-select').data('material_id', data.id);
            $itemRow.find('#selected_meter').attr('class', 'selected_meter_' + data.id);
            $itemRow.find('.inv_delete').attr('data-id', 'item-' + data.id);
            
            // Enable/disable fields based on unit purchased in
            toggleUnitFields($itemRow, unit_purchased_in);
            
            var user_id = $('#customer_id option:selected').val();
            var material_id = data.id;
            var price_w = price_r = price_s = 0;
            console.log($('#item-' + data.id).find('.inv_price').val())
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
            console.log("price "+ cut_wholesale_price);
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
                console.log("this.value "+price);
                console.log("price "+price);

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

        function toggleUnitFields($itemRow, unitPurchasedIn) {
            if (unitPurchasedIn === 'meter') {
                $itemRow.find('.inv_meter').prop('readonly', false);
                $itemRow.find('.inv_yard').prop('readonly', true);
            } else if (unitPurchasedIn === 'yard') {
                $itemRow.find('.inv_meter').prop('readonly', true);
                $itemRow.find('.inv_yard').prop('readonly', false);
            }
        }

        // Event handler for changes in relevant fields
        $(document).on('input change', '.inv_meter, .inv_price, .discount_value, .discount_type', function() {
            var $thisRow = $(this).closest('tr');
            handleCalculation($thisRow);
        });

        // Function to convert yards to meters
        function yard2meter(yard) {
            return yard * 0.9144;
        }

        // Keyup event handler for .inv_yard field
        $(document).on('keyup', '.inv_yard', function() {
            var $thisRow = $(this).closest('tr');
            var yard = parseFloat($(this).val()).toFixed(2);
            var unit_of_sale = $thisRow.find('.unit_of_sale').val() || ''; 
            var meter = yard2meter(yard).toFixed(2);
            var price = parseFloat($(this).closest('tr').find('.inv_price').val()).toFixed(2);
            var discountType = $thisRow.find('.discount_type').val() || 0; // No need for parseFloat
            var discountValue = parseFloat($thisRow.find('.discount_value').val()) || 0;

            $thisRow.find('.inv_meter').attr('title',`Meter : ${meter}`);
            $thisRow.find('.inv_yard').attr('title',`Meter : ${yard}`);
            $thisRow.find('.discount_type').attr('title',`Discount Type: ${discountType}`);
            $thisRow.find('.discount_value').attr('title',`Discount Value: ${discountValue}`);
            $(this).attr('data-value', yard);
            if (!isNaN(yard) && yard) {
                var meter = yard2meter(yard).toFixed(2);
                $('.inv_meter', $thisRow).val(meter).attr('title',`Meter : ${meter}`);   
                var weight = $('.inv_weight', $thisRow).attr('data-value');
                $('.inv_weight', $thisRow).val(weight * yard);
                                    
                totalmeter();
                grandtotal();
            }
            if (!isNaN(price) && price) {
                if(unit_of_sale == 'yard'){
                    var total = parseFloat(price * yard).toFixed(2);
                } else {
                    var total = parseFloat(price * meter).toFixed(2);
                }
                 // Apply discount
                 if (discountType === 'percentage') {
                    total = parseFloat(total - (total * discountValue / 100));
                } else if (discountType === 'amount') {
                    total = parseFloat(total - discountValue).toFixed(2);
                }
                // $(this).closest('tr').find('.td-total-price').attr('data-value', total).html(total).attr('title', `Total Amount: ${total}`);
                $('.total-price', $thisRow).val(total).attr('title', `Total Amount: ${total}`);
                sub_total();
                grandtotal();
            }
        });

        // Convert meter to yard
        function meterToYard(meter) {
            return meter2yard(meter).toFixed(2);
        }

        // Calculate total price based on discount
        function calculateTotal(price, amount, discountType, discountValue) {
            let total = parseFloat(price * amount).toFixed(2);
            if (discountType === 'percentage') {
                total = parseFloat(total - (total * discountValue / 100)).toFixed(2);
            } else if (discountType === 'amount') {
                total = parseFloat(total - discountValue).toFixed(2);
            }
            return total;
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
                
                totalmeter();
                grandtotal();
            }

            if (!isNaN(price) && price) {
                var yard = meterToYard(meter);
                var total = unit_of_sale === 'yard' ? calculateTotal(price, yard, discountType, discountValue) : calculateTotal(price, meter, discountType, discountValue);
                // $('.td-total-price', $thisRow).attr('data-value', total).html(total).attr('title', `Total Amount: ${total}`);
                console.log("call total item price "+total);
                $('.total-price', $thisRow).val(total).attr('title', `Total Amount: ${total}`);

                sub_total();
                grandtotal();
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
                    console.log("call");
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
            // console.log(option_value);
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
             var selected_role = 0;
             var roll_id_values = [];
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
                    roll_id_values.push(roll_id);
                    $('#item-rolls-' + item_id).append(input_hidden);

                    selected_role += 1;
                }
            });

            $('#selectedrole-'+ item_id).html(selected_role);
            //toFixed(2)
            var new_role_id = roll_id_values.join(',');
            input_hidden = $('<input>').attr({
                name: "roll_id[]",
                id: "roll_id",
                type: "hidden",
                value: new_role_id,
            });
            $('#item-rolls-' + item_id).append(input_hidden);
            var total_selected_meter = $("#total_selected_meter").html();
            $('#item-' + item_id + ' .td-selected-meter').html(total_selected_meter).attr('title',`Selected Role: ${total_selected_meter}`);
            $('#item-' + item_id).find('.td-selected-meter #selected_meter_' + item_id).val(total_selected_meter);

            // Set the value of the inv_meter field to the total_selected_meter value
            $('#item-' + item_id).find('.td-meter .inv_meter').val(total_selected_meter).attr('title',`Meter : ${meter}`);
            // Trigger the calculation logic for the updated inv_meter value
            var $thisRow = $('#item-' + item_id);
            handleCalculation($thisRow);
            selectedtotalmeter();
            grand_total();
            totalmeter();
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
            var totalMeter = 0;
            var totalYard = 0;
            var totalWeight = 0;

            // Iterate over each row
            $('.invoiceitem').each(function() {
                console.log("Call");
                // Get the meter and yard values from the inputs
                var meter = Number($(this).find('.inv_meter').val()) || 0;
                var yard = Number($(this).find('.inv_yard').val()) || 0;
                var unit_of_sale = $(this).find('.unit_of_sale').val(); 

                // If yard is given, convert it to meter
                if (yard > 0 && meter === 0) {
                    meter = yard2meter(yard); // Convert yard to meter
                } else if (meter > 0 && yard === 0) {
                    yard = meter2yard(meter); // Convert meter to yard
                }

                var itemId = $(this).find('.inv_meter').data('item-id'); // Get the item ID

                // Find the corresponding GSM value using the data-gsm attribute
                var weight_per_mtr = Number($(this).closest('tr').find('.td-weight_per_mtr').data('weight_per_mtr')) || 0;
                var weight_per_yard = Number($(this).closest('tr').find('.td-weight_per_yard').data('weight_per_yard')) || 0;
                console.log("weight_per_mtr "+weight_per_mtr);
                console.log("weight_per_yard"+weight_per_yard);
                console.log("unit_of_sale"+unit_of_sale);

                // Calculate weight for this item
                if(unit_of_sale == 'yard'){
                    var weight = yard * weight_per_yard;
                } else {
                    var weight = meter * weight_per_mtr;
                }
                console.log('unit_of_sale '+unit_of_sale);
                console.log('weight '+weight);

                // Accumulate totals
                totalMeter += meter;
                totalYard += yard;
                totalWeight += weight;
            });

            // Update the total meter and yard values in the HTML
            $('#totalMeter').html("Total Meter: " + totalMeter.toFixed(2) + " / " + totalYard.toFixed(2));

            // Update the approximate weight
            $('#approximate_weight').val(totalWeight.toFixed(2));
        }

        function selectedtotalmeter(callback) {
            var total = 0;

            // Calculate total from selected meters
            $.each($('.td-selected-meter'), function(i, v) {
                var value = $(v).html().trim(); // Ensure there are no extra spaces
                var number = parseFloat(value); // Convert to float
                if (!isNaN(number)) {
                    total += number;
                } else {
                    console.warn('Invalid value in td-selected-meter:', value);
                }
            });

            // Set the total value with 2 decimal places
            $('#SelectedTotalMeter').html(total.toFixed(2));

            // Retrieve the value for further processing
            var sel_roll_total = total.toFixed(2);
            if (isNaN(sel_roll_total)) {
                console.warn('Invalid sel_roll_total:', $('#SelectedTotalMeter').html());
                sel_roll_total = 0; // Fallback to 0 if the value is not valid
            }

            // Use callback to return the result
            if (typeof callback === 'function') {
                callback(sel_roll_total);
            }
        }
        
        function totalrow() {
            var rowCount = $("#tblOrderTable tbody tr").length;
            // $('#totalItem').html("Total Items : " + rowCount);
            $('#total_number_of_items').val(rowCount);
        };
        $(document).on('click', '.inv_delete', function() {
            var rowid = $(this).data('id');
            $('#' + rowid).remove();
            totalrow();
            totalmeter();
            grandtotal();
        });

        function grandtotal() {
            var grand_total = 0;
            $('.total-price').each(function() {
                grand_total += Number($(this).val());
            });

            // Get VAT rate (can be passed from backend using Blade)
            var vatRate = {{ $vat }};

            // Call the common function to update the grand total with or without VAT
            updateGrandTotalWithVAT(grand_total, vatRate);
        };

        // Function to calculate VAT and update grand total
        function updateGrandTotalWithVAT(grandTotal, vatRate) {
            // Format the base grand total
            // var formattedGrandTotal = grandTotal;

            // Check if the GST/VAT checkbox is checked
            if ($('#gst_checkbox').is(':checked')) {
                // Calculate the VAT based on the grand total and VAT rate
                var vatAmount = (grandTotal * vatRate) / 100;
                grandTotal += vatAmount; // Add VAT to the grand total

                // Update the displayed grand total with VAT included
                $('#grand_total').html("Grand Total (incl. VAT): " + "฿" + grandTotal);
                $('.grand_total').val(grandTotal);
                $('.vat_percentage').val(vatRate);
                $('.vat_amount').val(vatAmount);
            } else {
                // Update the displayed grand total without VAT
                $('#grand_total').html("Grand Total: " + "฿" + grandTotal);
                $('.grand_total').val(grandTotal);
            }
        }

        // Use the 'change' event listener for the GST/VAT checkbox
        $('#gst_checkbox').on('change', function() {
            grandtotal(); 
        });
        
        function formatMoney(number) {
          return number.toLocaleString('th-TH', { style: 'currency', currency: 'THB' });
        }

        function grand_total() {
            console.log("call");
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

            // Get VAT rate (can be passed from backend using Blade)
            // var vatRate = {{ $vat }};

            // Call the common function to update the grand total with or without VAT
            // updateGrandTotalWithVAT(grand_total, vatRate);
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
    // on chage customer name show past orders right side
    var html = '<div class="text-center mt-5" style="font-size: 20px;">\
          No data found..!\
        </div>';
    $('.custom-selected-orders').html(html);

        var currentDate = moment().format('DD/MM/YYYY HH:mm');
        
        $('#purchase_date').daterangepicker({
            singleDatePicker: true,
            showDropdowns: true,
            timePicker: true,
            timePicker24Hour: false,  // Use 24-hour format, set to false for 12-hour format
            locale: {
                format: 'DD/MM/YYYY HH:mm'  // Format with date and time
            },
            autoApply: false,
            startDate: currentDate  // Set the current date and time as the default
        });
        $('#purchase_date').val(currentDate);

        $('#delivered_date').daterangepicker({
            singleDatePicker: true,
            showDropdowns: true,
            timePicker: true,
            timePicker24Hour: false,  // Use 24-hour format, set to false for 12-hour format
            locale: {
                format: 'DD/MM/YYYY HH:mm'  // Format with date and time
            },
            autoApply: false
        });

        $('#delivered_date').daterangepicker({
            singleDatePicker: true,
            showDropdowns: true,
            timePicker: true,
            timePicker24Hour: false,  // Use 24-hour format, set to false for 12-hour format
            locale: {
                format: 'DD/MM/YYYY HH:mm'  // Format with date and time
            },
            autoApply: false,
            startDate: currentDate  // Set the current date and time as the default
        });
        $('#delivered_date').val(currentDate);
        

    $(document).on('change', '#user_id', function() {
        var user_id = $(this).val();
        $.ajax({
            url: "{{ route('get_customer_orders') }}",
            dataType: "json",
            data: {
                user_id: user_id
            },
            success: function(data) {
                if (data.status === 200) {
                    $(".custom-selected-orders").html('');
                    var html = '<table class="table mt-3 table-order-history">\
                                    <thead>\
                                        <tr>\
                                            <th scope="col">INV#</th>\
                                            <th scope="col">DATE</th>\
                                            <th scope="col">Amount</th>\
                                            <th scope="col">Payment Status</th>\
                                            <th scope="col">Remark</th>\
                                        </tr>\
                                    </thead>\
                                    <tbody>';
                    var total_purchase = 0;
                    var monthly_purchase = 0;
                    var amount_to_receive = 0;
                    var currentMonth = new Date().getMonth() + 1;
                    var currentYear = new Date().getFullYear();

                    $.each(data.response, function(i, order_data) {
                        var order_total = 0;
                        $.each(order_data.order_item_data, function(i, item) {
                            order_total += parseFloat(item.price);
                        });

                        // Placeholder values for payment status and remark
                        var payment_status = 'Paid'; // Replace with actual data if available
                        var remark = order_data.note;

                        var orderDate = new Date(order_data.order_date);
                        var formattedDate = ("0" + orderDate.getDate()).slice(-2) + '/' +
                                        ("0" + (orderDate.getMonth() + 1)).slice(-2) + '/' +
                                        orderDate.getFullYear();
                        if (orderDate.getMonth() + 1 === currentMonth && orderDate.getFullYear() === currentYear) {
                            monthly_purchase += order_total;
                        }

                        total_purchase += order_total;
                        amount_to_receive += order_total; // Placeholder for actual amount to receive logic

                        html += '<tr>\
                                    <td> <a href="{{ url('order') }}/view/' + order_data.id + '" data-toggle="tooltip" data-placement="top" title="View Order">' + order_data.id + '</a></td>\
                                    <td>' + formattedDate + '</td>\
                                    <td>' + order_total.toFixed(2) + '</td>\
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

                    $('.custom-selected-orders').html(html);
                } else {
                    var html = '<div class="text-center mt-5" style="font-size: 20px;">\
                                No data found..!\
                                </div>';
                    $('.custom-selected-orders').html(html);
                }
            }
        });
    });




    document.addEventListener("DOMContentLoaded", function() {
        var sidebarContainer = document.querySelector('.sidebar-container');
        var mainContent = document.querySelector('.main-content');
        var sidebar = document.querySelector('.sidebar');

        // Function to toggle sidebar visibility
        function toggleSidebar() {
            sidebarContainer.classList.toggle('sidebar-hidden');
            // Check if sidebar is hidden
            if (sidebarContainer.classList.contains('sidebar-hidden')) {
                // Expand main content to col-xl-12 when sidebar is hidden
                mainContent.classList.remove('col-xl-8');
                mainContent.classList.add('col-xl-12');
                // Hide sidebar by reducing its width to 0
                sidebar.classList.remove('col-xl-4');
                sidebar.classList.add('col-xl-0');
            } else {
                // Restore main content to col-xl-8 when sidebar is shown
                mainContent.classList.remove('col-xl-12');
                mainContent.classList.add('col-xl-8');
                 // Show sidebar by restoring its width
                sidebar.classList.remove('col-xl-0');
                sidebar.classList.add('col-xl-4');
            }
        }

        // Initially hide sidebar
        toggleSidebar();

        // Toggle sidebar visibility when clicking on the 'Show Past Orders' button
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
