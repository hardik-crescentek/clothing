@extends('layouts.master')
@section('title', 'Add Order')
@section('content')
<!-- Begin Row -->
<div class="row flex-row">
    <div class="col-xl-12 col-12">
        <div class="widget has-shadow">
            <div class="widget-header bordered no-actions d-flex align-items-center">
                <h4>Add Order</h4>
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
                <div class="form-group row hide">
                    <div class="col-xl-4 ">
                        <label class="form-control-label">Date of purchase<span class="text-danger ml-2">*</span></label>
                        {!! Form::text('purchase_date', null, array('id' => 'purchase_date','class' => 'form-control', 'data-validation'=>"required")) !!}
                    </div>
                </div>
                <div class="form-group row mb-3">
                    <div class="col-xl-6 mb-3">
                        <label class="form-control-label">Customer<span class="text-danger ml-2">*</span></label>
                        <div class="row">
                            <div class="col-8">
                                {!! Form::select('user_id', $users,null, array('id'=>'user_id','class' => 'form-control custom-select', 'data-validation'=>"required")) !!}
                            </div>
                            <div class="col-4">
                                <a href="{{ route('users.create', ['redirect' =>  base64_encode(route('order.create'))]) }}" class="btn btn-primary btn-square">Add Customer</a>
                            </div>
                        </div>
                    </div>
                    {{-- <div class="col-xl-6 mb-3">
                        <label class="form-control-label">Sales Person<span class="text-danger ml-2">*</span></label>
                        <div class="row">
                            <div class="col-8">
                                {!! Form::select('selse_person_id', $sales_person,null, array('id'=>'sales_person_id','class' => 'form-control custom-select', 'data-validation'=>"required")) !!}
                            </div>
                            <div class="col-4">
                                <a href="{{ route('users.create', ['redirect' =>  base64_encode(route('order.create'))]) }}" class="btn btn-primary btn-square">Add Sales Person</a>

                            </div>
                        </div>
                    </div> --}}
                </div>

                <div class="form-group row d-flex align-items-center mb-5">
                    <div class="col-lg-6">
                        <div class="row">
                            <div class="col">
                                <label class="form-control-label">Article No </label>
                                <!-- {!! Form::text('search_article',null , array('class' => 'form-control','id'=>'search_article')) !!} -->
                                {!! Form::select('search_article',$article_no, '' , array('class' => 'form-control','id'=>'search_article')) !!}
                            </div>
                            <div class="col">
                                <label class="form-control-label">Color </label>
                                <!-- {!! Form::text('search_color',null , array('class' => 'form-control','id'=>'search_color')) !!} -->
                                {!! Form::select('search_color',$colors,'' , array('class' => 'form-control','id'=>'search_color')) !!}
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <label class=" form-control-label ">Scan Barcode Number</label>
                        <div class="input-group">
                            <span class="input-group-addon addon-secondary"><i class="la la-barcode"></i></span>
                            {!! Form::text('input_search_barcode', null, array('id'=>'input_search_barcode','placeholder' => 'Barcode Number','class' => 'form-control')) !!}
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
                                <th>Item Name</th>
                                <th>Barcode</th>
                                <th>Type Of Sale</th>
                                <th>Price</th>
                                <th>Meter</th>
                                <th>Yard</th>
                                <th>Total Price</th>
                                <th>Action</th>
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
                                <td><input name="yard[]" class="yard form-control" readonly="readonly" value="{{ number_format((float)$item['yard'],2,'.','') }}" type="text"></td>
                                <td><input name="total_price_table[]" class="total_price_table form-control" readonly="readonly" id="total_price_table" type="text"></td>
                                <td><a class="btn btn-danger btn-sm btn-square">Delete</a></td>
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
                            <div class="float-right mt-3">
                                <a class="btn btn-secondary btn-square" href="{{route('order.index')}}">Cancel</a>
                                <button type="submit" class="btn btn-primary  btn-square">Save Order</button>
                            </div>
                        </div>
                    </div>
                </div>

                {!! Form::close() !!}
            </div>
        </div>
    </div>
</div>

<script type="text/template" id="templateAddItem">
    <td>{!! Form::text('name[]', null, array('class' => 'name form-control', 'data-validation'=>"required",'readonly'=>'readonly')) !!}</td>
    {!! Form::hidden('item_id[]',null,array('id'=>'item_id')) !!}
    <td>{!! Form::text('barcode[]', null, array('class' => 'barcode form-control', 'data-validation'=>"required",'readonly'=>'readonly')) !!}</td>
    <td>{!! Form::select("type_of_sale[]", ["W"=>"Wholsale","R"=>"Retail","P"=>"Sample Price"], null, ['class'=>'form-control type_of_sale','data-validation'=>"required"]) !!}</td>
    <td>{!! Form::number('price[]' , 0 , array('class' => 'price form-control', 'data-validation'=>"required",'id' => 'selected_price' )) !!}</td>
    <td>{!! Form::number('meter[]', '0', array('class' => 'meter form-control', 'data-validation'=>"required")) !!}</td>
    <td><input name="yard[]" class="yard form-control" readonly="readonly" value="" type="text"></td>
    <td><input name="total_price_table[]" readonly="readonly" type="text" id="total_price_table"></td>
    <td><a class="btn btn-danger btn-sm btn-square delete">Delete</a></td>
</script>

@endsection
@push('scripts')
<script src="{{ asset('assets/js/datepicker/moment.min.js') }}"></script>
<script src="{{ asset('assets/js/datepicker/daterangepicker.js') }}"></script>
<script type="text/javascript">
    $(document).ready(function() {

        var customer_item_price={!! json_encode($customer_item_price) !!};
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
                                label: item.name + " - " + item.color + ' ['+ item.article_no +']' + ' [' + item.barcode + ']',
                                value: item.id,
                                data: item
                            }
                        }));
                    }
                });
            },
            response: function(event, ui) {
                var item_id=$('.item_id_'+ui.content[0].data.id).val()?$('.item_id_'+ui.content[0].data.id).val():0;
                if (ui.content.length == 1) {
                    $(this).autocomplete("close");
                    if(item_id){
                        $('#search_error').fadeIn(300).css('display','block').html("This material allready selected").fadeOut(3000);
                    }
                    else{
                        addSearchMaterial(ui.content[0].data);
                    }

                };
            },
            select: function(event, ui) {
                var item_id=$('.item_id_'+ui.item.data.id).val()?$('.item_id_'+ui.item.data.id).val():0;
                if(item_id){
                    $('#search_error').fadeIn(300).css('display','block').html("This material allready selected").fadeOut(3000);
                }
                else{
                    addSearchMaterial(ui.item.data);
                }
            },
            close: function(event, ui) {
                $('#input_search_barcode').val('');
            }
        });
        //for article_no
        var input_search_article=$('#search_article');
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
                        $.each(data,function(i){
                            $("#search_color").append(`<option value="${i}">${data[i]}</option>`);
                        })
                    }
                });
            });
            $('#search_article').trigger('change');

        //for color
        var input_search_color=$('#search_color');
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
            $template = $('#templateAddItem').html();
            var $uniqueId = uuid();
            var $tr = $('<tr class="orderitem" id="' + $uniqueId + '">').append($template);
            $('#tblOrderTable tbody').append($tr);
            $('#' + $uniqueId).find('#item_id').val(data.id);
            $('#' + $uniqueId).find('#item_id').attr('class','item_id_'+data.id);
            $('#' + $uniqueId).find('#total_price_table').attr('class','form-control tpt total_price_table_'+data.id);
            $('#' + $uniqueId).find('.name').val(data.name + " - " + data.color);
            $('#' + $uniqueId).find('.barcode').val(data.barcode);
            $('#' + $uniqueId).find('.delete').data('id', $uniqueId);
            $('#' + $uniqueId).find('.type_of_sale').data('id',data.id);
            let price_input = $('#' + $uniqueId).find('.price');
            price_input.val(data.wholesale_price);

            var user_id=$('#user_id option:selected').val();
            var material_id=data.id;
            var price_w = price_r = price_s = 0;

            $.each(customer_item_price,function(i,v){
                if(v.customer_id==user_id && v.material_id==material_id)
                {
                    price_input.val(v.wholesale_price);
                    price_w = v.wholesale_price;
                    price_r = v.price;
                    price_s = v.sample_price;
                }
            });
            if(price_w==0){
                price_w=data.wholesale_price;
            }
            if(price_r==0){
                price_r=data.retail_price;
            }
            if(price_s==0){
                price_s=data.sample_price;
            }

            price_input.attr("data-wholesale", price_w);
            price_input.attr("data-retail", price_r);
            price_input.attr("data-sample", price_s);

            totalrow();

            // $.ajax({
            //     url: '{{url("materials/getItem")}}/' + id,
            //     method: 'get',
            //     dataType: 'json',
            //     success: function(data) {

            //         console.log(data);

            //         // var item_id = data[0].id;
            //         // var color_id = data[0].color.id;
            //         // var name = data[0].name;
            //         // var barcode = data[0].barcode;
            //         // var meter = null;
            //         // $template = $('#templateAddItem').html();
            //         // var $uniqueId = uuid();
            //         // var $tr = $('<tr class="orderitem" id="' + $uniqueId + '">').append($template);
            //         // $('#tblOrderTable tbody').append($tr);
            //         // $('#' + $uniqueId).find('.item_id').val(item_id);
            //         // $('#' + $uniqueId).find('.color_id').val(color_id);
            //         // $('#' + $uniqueId).find('.name').val(name);
            //         // $('#' + $uniqueId).find('.color').val(color);
            //         // $('#' + $uniqueId).find('.barcode').val(barcode);
            //         // $('#' + $uniqueId).find('.meter').val(meter);
            //         // $('#' + $uniqueId).find('.delete').attr('id', $uniqueId);
            //         // totalrow();
            //     }
            // });
        }

        $(document).on('keyup', '#tblOrderTable input.meter', function() {
            var meter = $(this).val();
            meter = parseFloat(meter);
            if (!isNaN(meter) && meter) {
                var $thisRow = $(this).closest('tr.orderitem');
                $('input.yard', $thisRow).val(meter2yard(meter).toFixed(2));
                totalmeter();
                grandtotal();
            }
        });

        $(document).on('change', '#tblOrderTable input.meter', function() {
            var meter = $(this).val();
            meter = parseFloat(meter);
            if (!isNaN(meter) && meter) {
                var $thisRow = $(this).closest('tr.orderitem');
                $('input.yard', $thisRow).val(meter2yard(meter).toFixed(2));
                totalmeter();
                grandtotal();
            }
        });
        
        // table shhow total price for PRICE * Yard == Total price
        $(document).on('keyup', '#tblOrderTable input.meter', function() {
            var meter = $(this).val();
            meter = parseFloat(meter);
            if (!isNaN(meter) && meter) {
                var $thisRow = $(this).closest('tr.orderitem');
                var price = $('input.price', $thisRow).val();
                var yard = $('input.yard', $thisRow).val();
                var total_pr = price  * yard;
                $('input.tpt', $thisRow).val(parseFloat(total_pr).toFixed(2));
                totalmeter();
                grandtotal();
            }
        });
        
        $(document).on('click', '#tblOrderTable .orderitem .delete', function() {
            var rowid = $(this).data('id');
            $('#' + rowid).remove();
            totalrow();
            totalmeter();
            grandtotal();
        });

        function totalrow() {
            var rowCount = $("#tblOrderTable td").closest("tr").length;
            $('#totalItem').html("Total Items : " + rowCount);
        };

        function totalmeter() {
            var total = 0;
            $('.meter').each(function() {
                total += Number($(this).val());
            });
            $('#totalMeter').html("Total Meter : " + total);
        };
        $('#purchase_date').daterangepicker({
            singleDatePicker: true,
            showDropdowns: true,
            locale: {
                format: 'DD/MM/YYYY'
            }
        });
    
        function grandtotal() {
            var grand_total = 0;
            $('.tpt').each(function() {
                grand_total += Number($(this).val());
            });
            $('#grand_total').html("Grand Total : " + formatMoney(grand_total));
        };
        
        function formatMoney(number) {
          return number.toLocaleString('en-US', { style: 'currency', currency: 'INR' });
        }
        
        $(document).on('change','#tblOrderTable .type_of_sale',function(){
            var price_input = $(this).closest('tr').find('.price');
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
        })
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
