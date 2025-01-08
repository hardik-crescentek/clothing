@extends('layouts.master')
@section('title', 'Order')
@section('content')


<!-- Begin Row -->
<div class="row flex-row">
    <div class="col-xl-12 col-12">
        <div class="widget has-shadow">
            <div class="widget-header bordered no-actions d-flex align-items-center">
                <h4>View Order</h4>
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
                {{-- {!! Form::open(array('route' => ['order.update', $order->id],'method'=>'PATCH','id'=>'from_edit_order', 'class'=>"form-horizontal form-validate", 'novalidate')) !!} --}}
                {!! Form::model($order, ['route' => ['order.updatenew'],'method'=>'POST','id'=>'from_edit_order', 'class'=>"form-horizontal form-validate", 'novalidate']) !!}
                <div class="row">
                    <div class="col-lg-3">
                        <div class="form-group">
                            <label class="form-control-label">Date of purchase<span class="text-danger ml-2">*</span></label>
                            {!! Form::text('purchase_date', null, array('id' => 'purchase_date','class' => 'form-control', 'data-validation'=>"required","readonly")) !!}
                        </div>
                    </div>
                    <div class="col-lg-3">
                        <div class="form-group">
                            <label class="form-control-label">Status<span class="text-danger ml-2">*</span></label>
                            {!! Form::select('status', $order_status,$order->status, array('id'=>'order_status','class' => 'form-control custom-select', 'data-validation'=>"required","readonly")) !!}
                        </div>
                    </div>
                    <div class="col-lg-3">
                        <div class="form-group">
                            <label class="form-control-label">Role Cutter Person Name</label>
                            <input type="text" class="form-control" name="role_cutter_name" value="{{ $order->role_cutter_name }}" readonly>
                        </div>
                    </div>
                    <div class="col-lg-3">
                        <div class="form-group">
                            <label class="form-control-label">Customer<span class="text-danger ml-2">*</span></label>
                            {!! Form::select('customer_id', $users,$order->customer_id, array('id'=>'customer_id','class' => 'form-control custom-select', 'data-validation'=>"required","readonly")) !!}
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-3">
                        <div class="form-group">
                            <label class="form-control-label">Sales Person<span class="text-danger ml-2">*</span></label>
                            {!! Form::select('seller_id', $sales_person,$order->seller_id, array('id'=>'seller_id','class' => 'form-control custom-select', 'data-validation'=>"required","readonly")) !!}
                        </div>
                    </div>
                    <div class="col-lg-3">
                        <div class="form-group">
                            <label class="form-control-label">Article No</label>
                            {!! Form::text('search_article',null , array('class' => 'form-control','id'=>'search_article',"readonly")) !!}
                        </div>
                    </div>
                    <div class="col-lg-3">
                        <div class="form-group">
                            <label class="form-control-label">Color</label>
                            {!! Form::text('search_color',null , array('class' => 'form-control','id'=>'search_color',"readonly")) !!}
                        </div>
                    </div>
                    <div class="col-lg-3">
                        <div class="form-group">
                            <label class="form-control-label">Scan Barcode Number</label>
                            <div class="input-group">
                                <span class="input-group-addon addon-secondary"><i class="la la-barcode"></i></span>
                                {!! Form::text('input_search_barcode', null, array('id'=>'input_search_barcode','placeholder' => 'Barcode Number','class' => 'form-control',"readonly")) !!}
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-3">
                        <div class="form-group">
                            <label class="form-control-label">Note</label>
                            {!! Form::textarea('note', $order->note, ['class' => 'form-control','rows' => 3]); !!}
                        </div>
                    </div>
                    <div class="col-lg-3">
                        <div class="form-group">
                            <label class="form-control-label">Remark</label>
                            {!! Form::textarea('remark', $order->remark, ['class' => 'form-control','rows' => 3]); !!}
                        </div>
                    </div>
                </div>
                <div class="form-group row d-flex align-items-center">
                    <div id="search_error" class=" col-lg-12 alert alert-danger form-control" style="display: none;"></div>
                </div>
                <input type="hidden" name="id" value="{{$order->id}}">
                <div class="form-group row d-flex align-items-center mt-5">
                    <div class="col-lg-12 d-flex justify-content-center">
                        <button type="submit" class="btn btn-primary btn-lg">Update</button>
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
            <div class="widget-header bordered no-actions d-flex align-items-center">
                <h4>List Order Items</h4>
            </div>
            <div class="widget-body">
               
                
                <div class="table-responsive">
                    <table class="table table-hover mb-0 " id="tblEditOrderTable">
                        <thead>
                            <tr>
                                <th>Item Name</th>
                                <th>Barcode</th>
                                <th>Type Of Sale</th>
                                <th>Article No</th>
                                <th>Meter</th>
                                @role('super-admin')
                                <th>Price</th>
                                <th>Yard</th>
                                <th>Total Price</th>
                                @endrole
                                <th>Role Name</th>
                                <th>Status</th>
                                <th>Image</th>
                            </tr>
                        </thead>
                        <tbody>
                            @isset($items)
                            @foreach ($items as $key => $item)
                            <tr class="material-link" id="item-{{$item->id}}" data-item_id="{{$item->id}}">
                                <td class="td-material" data-value="{{ $item->item['fullName'] }}">{{ $item->item['fullName'] }}</td>
                                <td class="td-barcode" data-value="{{ $item->item['barcode'] }}">{!!   DNS1D::getBarcodeSVG($item->item['barcode'],config('app.BARCODE_TYPE'), 1, 40)  !!}</td>
                                <td class="td-type_of_sale" data-value="{{ $item->type_of_sale }}">{{ $item->type_of_sale }}</td>
                                @if($item->item->article_no != '')
                                <td class="td-type_of_sale">
                                    {{$item->item->article_no}}
                                </td>
                                @else
                                <td></td>
                                @endif
                                <td class="td-meter meter" data-value="{{ $item->meter }}">{{ $item->meter }}</td>
                                @role('super-admin')
                                <td class="td-price" data-value="{{ $item->price }}">{{ $item->price }}</td>
                                <td class="td-yard" data-value="{{ number_format((float)meter2yard($item->meter),2,'.','') }}">{{ number_format((float)meter2yard($item->meter),2,'.','') }}</td>
                                <td class="td-yard" data-value="{{ number_format($item->price * (float)meter2yard($item->meter),2,'.','') }}">{{ number_format($item->price * (float)meter2yard($item->meter),2,'.','') }}</td>
                                @endrole
                                <td>{{ $item->materials_name }}</td>
                                <td>{{ $item->status }}</td>
                                <td>
                                    @if($item->image)
                                        <a href="{{ url('public/'.$item->image) }}" data-fancybox="gallery" data-caption="Image Preview">
                                            <img src="{{ url('public/'.$item->image) }}" alt="Image" style="width: 50px; height: auto;">
                                        </a>
                                    @else
                                        <span>No Image</span>
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                            @endisset

                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>



<div id="editItemModal" tabindex="-1" role="dialog" aria-labelledby="Edit" aria-hidden="true" class="modal fade text-left">
    <div role="document" class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 id="modal-header" class="modal-title">Edit Order Item</h5>
                <button type="button" data-dismiss="modal" aria-label="Close" class="close"><span aria-hidden="true">×</span></button>
            </div>
            <div class="modal-body">
                {!! Form::open(array('route' => 'order.update-order-item','method'=>'PATCH','id'=>'edit_item_form', 'class'=>"form-horizontal form-validate", 'novalidate')) !!}
                <input type="hidden" id="edit_item_id" name="orderItemId" value="" />
                <div class="form-group">
                {!! Form::hidden('customer_id',$order->customer_id) !!}
                    <label class="form-control-label">Item Name<span class="text-danger ml-2">*</span></label>
                    {!! Form::text('name',null, array('id'=>'edit_name','class' => 'form-control', 'data-validation'=>"required",'readonly'=>"readonly")) !!}
                </div>
                {{-- <div class="form-group">
                    <label class="form-control-label">Color<span class="text-danger ml-2">*</span></label>
                    {!! Form::select('color', $colors,null, array('id'=>'edit_color','class' => 'form-control custom-select', 'data-validation'=>"required")) !!}
                </div> --}}
                <div class="form-group">
                    <label class="form-control-label">Barcode<span class="text-danger ml-2">*</span></label>
                    {!! Form::text('barcode',null, array('id'=>'edit_barcode','class' => 'form-control', 'data-validation'=>"required",'readonly'=>"readonly")) !!}
                </div>
                <div class="form-group">
                    <label class="form-control-label">Type Of Sale<span class="text-danger ml-2">*</span></label>
                    <td>{!! Form::select("type_of_sale", ["W"=>"Wholsale","R"=>"Retail","P"=>"Sample Poh"], null, ['class'=>'form-control edit_type_of_sale','data-validation'=>"required"]) !!}</td>
                </div>
                <div class="form-group">
                    <label class="form-control-label">Price<span class="text-danger ml-2">*</span></label>
                    {!! Form::text('price',0, array('id'=>'edit_price','class' => 'form-control', 'data-validation'=>"required")) !!}
                </div>
                <div class="form-group">
                    <label class="form-control-label">Meter<span class="text-danger ml-2">*</span></label>
                    {!! Form::text('meter',0, array('id'=>'edit_meter','class' => 'form-control', 'data-validation'=>"required")) !!}
                </div>
                <div class="form-group">
                    <label class="form-control-label">Yard<span class="text-danger ml-2">*</span></label>
                    <input name="yard" class="yard form-control" id="edit_yard" readonly="readonly" value="" type="text">                
                </div>

                <div class="form-action float-right">
                    <button type="submit" name="update_btn" class="btn btn-primary">Update</button>
                </div>
                {!! Form::close() !!}
            </div>
        </div>
    </div>
</div>
<!-- End Row -->
<script type="text/template" id="templateAddItem">
    <td>{!! Form::text('a_name[]', null, array('class' => 'name form-control', 'data-validation'=>"required",'readonly'=>'readonly')) !!}</td>
    {!! Form::hidden('a_item_id[]',null,array('class'=>'item_id')) !!}
    <td>{!! Form::text('a_barcode[]', null, array('class' => 'barcode form-control', 'data-validation'=>"required",'readonly'=>'readonly')) !!}</td>
    <td>{!! Form::select("a_type_of_sale[]", ["W"=>"Wholsale","R"=>"Retail","P"=>"Sample Poh"], null, ['class'=>'form-control type_of_sale','data-validation'=>"required"]) !!}</td>
    <td>{!! Form::text('a_price[]' , 0 , array('class' => 'price form-control', 'data-validation'=>"required" )) !!}</td>
    <td>{!! Form::text('a_meter[]', '0', array('class' => 'meter form-control', 'data-validation'=>"required")) !!}</td>
    <td><input name="a_yard[]" class="yard form-control" readonly="readonly" value="" type="text"></td>      
    <td><a class="btn btn-danger btn-sm btn-square delete">Delete</a></td>
</script>
@endsection
@push('after-styles')
    <!-- Fancybox CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fancyapps/fancybox@3.5.2/dist/jquery.fancybox.min.css" />
@endpush
@push('scripts')
<script src="{{ asset('assets/js/datepicker/moment.min.js') }}"></script>
<script src="{{ asset('assets/js/datepicker/daterangepicker.js') }}"></script>
<script src="https://cdn.jsdelivr.net/npm/@fancyapps/fancybox@3.5.2/dist/jquery.fancybox.min.js"></script>
<script type="text/javascript">
    function deleteConfirm() {
        return confirm("Are you sure want to delete?");
    };

    (function($) {
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
                                label: item.name + " - " + item.color + ' [' + item.barcode + ']',
                                value: item.id,
                                data: item
                            }
                        }));
                    }
                });
            },
            response: function(event, ui) {
                if (ui.content.length == 1) {
                    $(this).autocomplete("close");
                    addSearchMaterial(ui.content[0].data);
                };
            },
            select: function(event, ui) {
                addSearchMaterial(ui.item.data);
            },
            close: function(event, ui) {
                $('#input_search_barcode').val('');
            }
        });
        //for article_no
        var input_search_article=$('#search_article');
        input_search_article.autocomplete({
            source: function(request, response) {
                $.ajax({
                    url: '{{url("materials/autocomplete")}}',
                    dataType: "json",
                    data: {
                        article_no: request.term
                    },
                    success: function(data) {
                        response($.map(data, function(item) {
                            return {
                                label: item.name + " - " + item.color + ' ['+ item.article_no +']' + ' [' + item.barcode + ']',
                                value: item.id,
                                data: item
                            }
                        }));
                    },
                });
            },
            response: function(event, ui) {
                if (ui.content.length == 1) {
                    $(this).autocomplete("close");
                    addSearchMaterial(ui.content[0].data);
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
                $('#search_article').val('');
            }
        });
        //for color
        var input_search_color=$('#search_color');
        input_search_color.autocomplete({
            source: function(request, response) {
                $.ajax({
                    url: '{{url("materials/autocomplete")}}',
                    dataType: "json",
                    data: {
                        color: request.term
                    },
                    success: function(data) {
                        response($.map(data, function(item) {
                            return {
                                label: item.name + " - " + item.color + ' ['+ item.article_no +']' + ' [' + item.barcode + ']',
                                value: item.id,
                                data: item
                            }
                        }));
                    },
                });
            },
            response: function(event, ui) {
                if (ui.content.length == 1) {
                    $(this).autocomplete("close");
                    addSearchMaterial(ui.content[0].data);
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
                $('#search_color').val('');
            }
        });
        function addSearchMaterial(data) {

            $template = $('#templateAddItem').html();
            var $uniqueId = uuid();
            var $tr = $('<tr class="orderitem" id="' + $uniqueId + '">').append($template);
            $('#tblOrderTable tbody').append($tr);
            $('#' + $uniqueId).find('.item_id').val(data.id);
            $('#' + $uniqueId).find('.name').val(data.name + " - " + data.color);
            $('#' + $uniqueId).find('.barcode').val(data.barcode);
            $('#' + $uniqueId).find('.delete').data('id', $uniqueId);
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
            //         // var color = data[0].color.name;
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

        $(window).on('load',function(){
            totalrow();
            totalmeter();
        });
        // for add table
        $(document).on('keyup', '#tblOrderTable input.meter', function() {
            var meter = $(this).val();
            meter = parseFloat(meter);
            if (!isNaN(meter) && meter) {
                var $thisRow = $(this).closest('tr.orderitem');
                $('input.yard', $thisRow).val(meter2yard(meter).toFixed(2));
                totalmeter();
            }
        });

        $(document).on('change', '#tblOrderTable input.meter', function() {
            var meter = $(this).val();
            meter = parseFloat(meter);
            if (!isNaN(meter) && meter) {
                var $thisRow = $(this).closest('tr.orderitem');
                $('input.yard', $thisRow).val(meter2yard(meter).toFixed(2));
                totalmeter();
            }
        });
        $(document).on('click', '#tblOrderTable .orderitem .delete', function() {
            var rowid = $(this).data('id');
            $('#' + rowid).remove();
            totalrow();
            totalmeter();
        });
        // for edit table
        
        function totalrow() {
            var rowCount = $("#tblOrderTable td").closest("tr").length + $("#tblEditOrderTable td").closest("tr").length;
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
        
        $('#editItemModal').on('shown.bs.modal', function(e) {
            var item_id = $(e.relatedTarget).data('item_id');            
            var material = $('#item-' + item_id).find('.td-material').data('value');
            var barcode = $('#item-' + item_id).find('.td-barcode').data('value');
            var type_of_sale = $('#item-' + item_id).find('.td-type_of_sale').data('value');
            var price = $('#item-' + item_id).find('.td-price').data('value');
            var meter = $('#item-' + item_id).find('.td-meter').data('value');
            var yard = $('#item-' + item_id).find('.td-yard').data('value');

            $('#edit_item_id').val(item_id);
            $('#edit_name').val(material);
            $('#edit_barcode').val(barcode);
            $('#edit_type_of_sale').val(type_of_sale);
            $('#edit_price').val(price);
            $('#edit_meter').val(meter);
            $('#edit_yard').val(yard.toFixed(2));


        });
    })(jQuery);

    $(document).ready(function () {
        $("[data-fancybox='gallery']").fancybox({
            toolbar: true,
            buttons: [
                "zoom",
                "download",
                "close"
            ],
            loop: true, 
            animationEffect: "zoom-in-out", 
            transitionEffect: "fade" 
        });
    });
</script>
@endpush