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
                                <th style="width:10%;">Item Name</th>
                                <th style="width:10%;">Barcode</th>
                                <th style="width:10%;">Type Of Sale</th>
                                <th style="width:10%;">Price</th>
                                <th style="width:10%;">Meter</th>
                                <th style="width:10%;">Yard</th>
                                <!-- <th>Price</th> -->
                                <th style="width:10%;">Total Price</th>
                                <th style="width:10%;">Select Role</th>
                                <th style="width:10%;">Action</th>
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
                                <td>{!! Form::number('price[]' , $item['price'] , array('class' => 'price form-control', 'data-validation'=>"required" )) !!}</td>
                                <td>{!! Form::number('meter[]', $item['meter'], array('class' => 'meter form-control', 'data-validation'=>"required")) !!}</td>
                                <td class="td-selected-role">
                                    <div id="selectedrole-" class="selectedrole"></div>
                                </td>
                                <td><input name="yard[]" class="yard form-control" readonly="readonly" value="{{ number_format((float)$item['yard'],2,'.','') }}" type="text"></td>
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
                                <div id="totalItem"> Total Items : </div>
                            </h4>
                            <h4 class="mb-3">
                                <div id="totalMeter"> Total Meter : </div>
                            </h4>
                            <h4 class="mb-3">
                                <div id="grand_total"> Grand Total : </div>
                            </h4>
                            <!-- <div class="float-right mt-3">
                                <button type="submit" class="btn btn-primary  btn-square">Save Order</button>
                                <a class="btn btn-secondary btn-square" href="{{route('order.index')}}">Cancel</a>
                            </div> -->
                        </div>
                    </div>
                </div>
                
                <div class="form-group row d-flex align-items-center mt-5">
                    <div class="col-lg-12 d-flex justify-content-center">
                        <button type="submit" class="btn btn-primary btn-lg">Save</button>
                        <button type="submit" name="action" value="generate_invoice" class="btn btn-primary btn-lg ml-2">Save and Generate Invoice</button>
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
    <td class="td-price price" data-value="">{!! Form::text('price[]' , 0 , array('class' => 'inv_price form-control', 'data-validation'=>"required",'placeholder'=>"Price" )) !!}</td>
    <td class="td-meter" data-value="">{!! Form::text('meter[]', 0, array('class' => 'inv_meter form-control', 'data-validation'=>"required",'placeholder'=>"Meter")) !!}</td>
    <td class="td-yard" data-value="">{!! Form::text('yard[]',0 , ['class'=>'inv_yard form-control','placeholder'=>"Yard"]) !!}</td>
    <!-- <td class="td-weight" data-value="">{!! Form::text('weight[]',0 , ['class'=>'inv_weight form-control','readonly'=>'readonly','placeholder'=>"Weight"]) !!}</td> -->
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
            $('#item-' + data.id).find('.inv_price').attr('name', 'price[]');
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
                grandtotal();
            }
            if (!isNaN(price) && price) {
                var total = parseFloat(price * meter).toFixed(2);
                $(this).closest('tr').find('.td-total-price').attr('data-value', total).html(total);
                sub_total();
                grandtotal();
            }
        });

        // Function to convert yards to meters
        function yard2meter(yard) {
            return yard * 0.9144;
        }

        // Keyup event handler for .inv_yard field
        $(document).on('keyup', '.inv_yard', function() {
            var yard = parseFloat($(this).val()).toFixed(2);
            var price = parseFloat($(this).closest('tr').find('.inv_price').val()).toFixed(2);
            $(this).attr('data-value', yard);
            if (!isNaN(yard) && yard) {
                var $thisRow = $(this).closest('tr');
                $('.inv_meter', $thisRow).val(yard2meter(yard).toFixed(2));   
                var weight = $('.inv_weight', $thisRow).attr('data-value');
                $('.inv_weight', $thisRow).val(weight * yard);
                                    
                totalmeter();
                grandtotal();
            }
            if (!isNaN(price) && price) {
                var total = parseFloat(price * yard).toFixed(2);
                $(this).closest('tr').find('.td-total-price').attr('data-value', total).html(total);
                sub_total();
                grandtotal();
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
                grandtotal();
            }
            if (!isNaN(price) && price) {
                var total = parseFloat(price * meter).toFixed(2);
                $(this).closest('tr').find('.td-total-price').attr('data-value', total).html(total);
                sub_total();
                grandtotal();
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
                grandtotal();
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
                grandtotal();
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
                    roll_id_values.push(roll_id);
                    $('#item-rolls-' + item_id).append(input_hidden);

                    //     }

                    //     // console.log(input_hidden);
                    // });
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
            $('#totalMeter').html("Total Meter : " + total);
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
            $('#totalItem').html("Total Items : " + rowCount);
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
            $('.td-total-price').each(function() {
                grand_total += Number($(this).attr('data-value'));
            });
            $('#grand_total').html("Grand Total : "  + formatMoney(grand_total));
        };
        
        function formatMoney(number) {
          return number.toLocaleString('en-US', { style: 'currency', currency: 'INR' });
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

            $('#grand_total').val(parseFloat(grand_total.toFixed(2)));
        }
        // $('#tax').on('keyup', function() {
        //     grand_total();
        // });
        // $('#discount').on('keyup', function() {
        //     grand_total();
        // });
        // $('#discount_type').on('change', function() {
        //     grand_total();
        // });
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
<script type="text/javascript">
    // on chage customer name show past orders right side
    var html = '<div class="text-center mt-5" style="font-size: 20px;">\
          No data found..!\
        </div>';
    $('.custom-selected-orders').html(html);

        // $('#purchase_date').daterangepicker({
        //     singleDatePicker: true,
        //     showDropdowns: true,
        //     locale: {
        //         format: 'DD/MM/YYYY'
        //     }
        // });
        
        $('#purchase_date').daterangepicker({
            singleDatePicker: true,
            showDropdowns: true,
            timePicker: true,
            timePicker24Hour: false,  // Use 24-hour format, set to false for 12-hour format
            locale: {
                format: 'DD/MM/YYYY HH:mm'  // Format with date and time
            },
            autoApply: false
        });
    // $(document).on('change','#user_id',function(){
    //     var user_id = $(this).val();
    //     $.ajax({
    //         url: "{{ route('get_customer_orders') }}",
    //         dataType: "json",
    //         data: {
    //             user_id: user_id
    //         },
    //         success: function(data) {

    //             // console.log(data);
    //             if (data.status === 200) 
    //             {
    //                 $(".custom-selected-orders").html('');
    //                 var html = '';
    //                 $.each(data.response,function(i,order_data){

    //                     // console.log(order_data.order_date);
    //                     html += '<div class="row col-md-12 mt-3">\
    //                             <div class="form-group col-md-3">\
    //                                 <h5>Order ID</h5>\
    //                                 <span>'+order_data.id+'</span>\
    //                             </div>\
    //                              <div class="form-group col-md-4">\
    //                                 <h5>Order Date</h5>\
    //                                 <span>'+order_data.order_date+'</span>\
    //                             </div>\
    //                             <div class="form-group col-md-5">\
    //                                 <h5>Order Note</h5>\
    //                                 <span>'+order_data.note+'</span>\
    //                             </div>\
    //                         </div>';
    //                         var html_item = '';
    //                         var total_item_id = 0;
    //                         var total_meater = 0;
    //                         var grand_total = 0;
    //                         if (order_data.order_item_data.length > 0) {
    //                         html_item += '<table class="table mt-3 table-order-history">\
    //                             <thead>\
    //                                 <tr>\
    //                                     <th scope="col">Order Type</th>\
    //                                     <th scope="col">Meater</th>\
    //                                     <th scope="col">Price</th>\
    //                                 </tr>\
    //                             </thead>\
    //                             <tbody>';
    //                             $.each(order_data.order_item_data,function(i,order_item_data){
    //                                 html_item += '<tr>\
    //                                     <td>'+order_item_data.type_of_sale+'</td>\
    //                                     <td>'+order_item_data.meter+'</td>\
    //                                     <td>'+order_item_data.price+'</td>\
    //                                 </tr>';
    //                                 total_item_id += 1;
    //                                 total_meater += order_item_data.meter;
    //                                 grand_total += parseInt(order_item_data.price);
    //                                 })
    //                             html_item += '</tbody>\
    //                         </table>';
    //                         }
    //                         html += html_item;
    //                         html += '<div class="">\
    //                                 <div class="row">\
    //                                     <div class="col-4">\
    //                                         <h4 class="mb-2 ml-2">\
    //                                             <div id="totalItem"> Total Items : '+total_item_id+'</div>\
    //                                         </h4>\
    //                                     </div>\
    //                                     <div class="col-4">\
    //                                         <h4 class="mb-2">\
    //                                             <div id="totalMeter"> Total Meter : '+total_meater+'</div>\
    //                                         </h4>\
    //                                     </div>\
    //                                     <div class="col-4">\
    //                                         <h4 class="mb-2">\
    //                                             <div id="grand_total"> Grand Total : '+grand_total+'</div>\
    //                                         </h4>\
    //                                     </div>\
    //                                 </div>\
    //                             </div>';
    //                         html += '<hr>';

    //                     $('.custom-selected-orders').html(html);
    //                     // $("#search_color").append(`<option value="${i}">${data[i]}</option>`);
    //                 })
    //             }
    //             else
    //             {
    //                 var html = '<div class="text-center mt-5" style="font-size: 20px;">\
    //                       No data found..!\
    //                     </div>';
    //                 $('.custom-selected-orders').html(html);
    //             }
    //         }
    //     });
    // });

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
                                    <td>' + order_data.id + '</td>\
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
