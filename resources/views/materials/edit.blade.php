@extends('layouts.master')
@section('title', 'Edit Material')
@section('content')

@if ($message = Session::get('success'))
<div class="alert alert-success">
    {{ $message }}
</div>
@endif

<!-- Begin Row -->
<div class="row flex-row">
    <div class="col-xl-12 col-12">
        <div class="widget has-shadow">
            <div class="widget-header bordered no-actions d-flex align-items-center">
                <h4>Edit Material</h4>
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

                {!! Form::model($material, ['route' => ['materials.update', $material->id],'method'=>'PATCH','id'=>'from_add_material', 'class'=>"form-horizontal form-validate", 'novalidate', 'files' => true]) !!}
                <div class="row">
                    <div class="form-group row d-flex col-lg-2">
                        <label class="form-control-label d-flex">Brand<span class="text-danger ml-2">*</span></label>
                        {!! Form::text('name', null, array('placeholder' => 'Name','class' => 'form-control', 'data-validation'=>"required")) !!}
                    </div>
                    <div class="form-group row d-flex col-lg-2 ml-2">
                        <label class="form-control-label d-flex">Category<span class="text-danger ml-2">*</span></label>
                        {!! Form::select('category_id', $categories,null, array('class' => 'form-control custom-select')) !!}
                    </div>
                    <div class="form-group row d-flex col-lg-2 ml-2">
                        <label class="form-control-label d-flex">Vendor<span class="text-danger ml-2">*</span></label>
                        {!! Form::select('supplier_id', $suppliers,null, array('class' => 'form-control custom-select')) !!}
                    </div>
                    <div class="form-group row d-flex col-lg-2 ml-2">
                        <label class="form-control-label d-flex">Made IN<span class="text-danger ml-2">*</span></label>
                        {!! Form::select('made_in',[''=>'Select Country','india'=>'India','china'=>'China','thailand'=>'Thailand'],null, array('class' => 'form-control custom-select')) !!}
                    </div>
                    <div class="form-group row d-flex col-lg-2 ml-2">
                        <label class="form-control-label d-flex">Price Currency<span class="text-danger ml-2">*</span></label>
                        {!! Form::select('currency',[''=>'Select Currency','USD'=>'USD','EUR'=>'EUR','CNY'=>'CNY','THB'=>'THB','INR'=>'INR'],null, array('class' => 'form-control custom-select')) !!}
                    </div>
                    <div class="form-group row d-flex col-lg-2 ml-2">
                        <label class="form-control-label d-flex">Price<span class="text-danger ml-2">*</span></label>
                        {!! Form::text('price', null, array('placeholder' => 'Price','class' => 'form-control', 'data-validation'=>"required")) !!}
                    </div>
                </div>

                <div class="row">
                    <div class="form-group row d-flex col-lg-2">
                        <label class="form-control-label d-flex">Article No<span class="text-danger ml-2">*</span></label>
                        {!! Form::text('article_no', null, array('placeholder' => 'Article_no','class' => 'form-control', 'data-validation'=>"required",'id'=>"article_no")) !!}
                    </div>
                    <div class="form-group row d-flex col-lg-2 ml-2">
                        <label class="form-control-label d-flex">Barcode Number</label>
                        <div class="input-group">
                            <span class="input-group-addon addon-secondary"><i class="la la-barcode"></i></span>
                            {!! Form::text('barcode', null, array('id'=>'input_barcode','placeholder' => 'Barcode Number','class' => 'form-control', 'data-validation'=>"required")) !!}
                            <span class="input-group-btn">
                                <button type="button" id="genrate_code" class="btn btn-secondary ripple">
                                    Genrate Code
                                </button>
                            </span>
                        </div>
                    </div>
                    <div class="form-group row d-flex col-lg-1 ml-2 justify-content-center align-items-center">
                        <label class="form-control-label">Selling Price:</label>
                    </div>
                    <div class="form-group row d-flex col-lg-2 ml-2">
                        <!-- <label class="form-control-label">Sample Price</label> -->
                        <label class="form-control-label">ROLL</label>
                        {!! Form::text('roll',null, array('class' => 'form-control col-lg-12','id'=>"sample",'data-validation' => "number",'data-validation-allowing'=>"float",'placeholder' => 'Sample Price')) !!}
                    </div>
                    <div class="form-group row d-flex col-lg-2 ml-2">
                        <!-- <label class="form-control-label">Wholesale Price</label> -->
                        <label class="form-control-label">CUT WHOLESALE</label>
                        {!! Form::text('cut_wholesale',null, array('class' => 'form-control col-lg-12','id'=>"wholesale",'data-validation' => "number",'data-validation-allowing'=>"float",'placeholder' => 'WholeSale Price')) !!}
                    </div>
                    <div class="form-group row d-flex col-lg-2 ml-2">
                        <!-- <label class="form-control-label">Retail Price</label> -->
                        <label class="form-control-label">RETAIL</label>
                        {!! Form::text('retail',null, array('class' => 'form-control col-lg-12','id'=>"retail",'data-validation' => "number",'data-validation-allowing'=>"float",'placeholder' => 'Retail Price')) !!}
                    </div>
                </div>

                <div class="row">
                    <div class="form-group row col-lg-2">
                        <label class="form-control-label">Width (inch)<span class="text-danger ml-2">*</span></label>
                        <div class="input-group-append">
                            {!! Form::text('width_inch',null, array('class' => 'form-control width-inch','id'=>"widthInch",'placeholder' => '"Width in inches', 'data-validation'=>"required")) !!}
                            <span class="input-group-text">INCH</span>
                        </div>
                    </div>
                    <div class="form-group row col-lg-2 ml-2">
                        <label class="form-control-label">Width(cm) = inch*2.54</label>
                        <div class="input-group-append">
                            <!-- <input type="text" name="width_cm" class="form-control width-cm" id="widthCm" placeholder="Width in centimeters" readonly> -->
                            {!! Form::text('width_cm',null, array('class' => 'form-control width-cm','id'=>"widthCm",'placeholder' => '"Width in centimeters', 'data-validation'=>"required",'readonly'=>"readonly")) !!}
                            <span class="input-group-text">CM</span>
                        </div>
                    </div>
                    <div class="form-group row col-lg-2 ml-2">
                        <label class="form-control-label">Weight(gsm)<span class="text-danger ml-2">*</span></label>
                        <div class="input-group-append">
                            {!! Form::text('weight_gsm',null, array('class' => 'form-control width','id'=>"width",'placeholder' => 'Width', 'data-validation'=>"required")) !!}
                            <span class="input-group-text">GSM</span>
                        </div>
                    </div>
                    <div class="form-group row col-lg-2 ml-2">
                        <label class="form-control-label">Weight(per mtr)<span class="text-danger ml-2">*</span></label>
                        <div class="input-group-append">
                            {!! Form::text('weight_per_mtr',null, array('class' => 'form-control weight','id'=>"weight",'placeholder' => 'Weight', 'data-validation'=>"required")) !!}
                            <span class="input-group-text">PER MTR</span>
                        </div>
                    </div>
                    <div class="form-group row col-lg-2 ml-2">
                        <label class="form-control-label">Weight(per yard)<span class="text-danger ml-2">*</span></label>
                        <div class="input-group-append">
                            {!! Form::text('weight_per_yard',null, array('class' => 'form-control weight','id'=>"weight",'placeholder' => 'Weight', 'data-validation'=>"required")) !!}
                            <span class="input-group-text">PER YARD</span>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="form-group row col-lg-3">
                        <label class="form-control-label">Selvage</label>
                        {!! Form::text('selvage',null, array('class' => 'form-control selvage','id'=>"selvage",'placeholder' => 'Selvage')) !!}
                    </div>
                    <div class="form-group row col-lg-3 ml-2">
                        <label class="form-control-label">Construction</label>
                        {!! Form::text('construction', null, array('placeholder' => 'Construction','class' => 'form-control','id'=>"construction")) !!}
                    </div>
                    <div class="form-group row col-lg-3 ml-2">
                        <label class="form-control-label">Description</label>
                        {!! Form::textarea('description', null, ['class' => 'form-control','rows' => 3]); !!}
                    </div>
                    <div class="form-group row col-lg-3 ml-2">
                        <label class="form-control-label">Color</label>
                        {!! Form::text('color',null, array('class' => 'form-control color','id'=>"color")) !!}
                    </div>
                </div>
                <div class="row">
                    <div class="form-group row col-lg-2 ml-2">
                        <label class="form-control-label">Re Order Alert</label>
                        {!! Form::text('min_alert_qty', null, array('placeholder' => 'Re Order Alert','class' => 'form-control','id'=>"min_alert_qty")) !!}
                    </div>
                    <div class="form-group col-lg-2 ml-2">
                        <label class="form-control-label">Status</label>
                        <br>
                        <div class="styled-radio">
                            {!! Form::radio('status', 1, true, array('class' => 'custom-control-input', 'id'=>"status-active")) !!}
                            <label for="status-active">Active</label>
                        </div>
                        <div class="styled-radio">
                            {!! Form::radio('status', 0, false, array('class' => 'custom-control-input', 'id'=>"status-inactive")) !!}
                            <label for="status-inactive">Inactive</label>
                        </div>
                    </div>
                    <div class="form-group col-lg-6">
                        <label class="form-control-label">Image</label>
                        <div class="">
                            <div style="display: flex; align-items: center;">
                                {!! Form::file('image', ['id' => 'upload_image', 'accept' => 'image/*',
                                'data-validation'=>"mime",
                                'data-validation-allowing'=>"jpeg, jpg, png, gif",
                                'data-validation-error-msg-mime'=>"You can only upload images"]); !!}
                                <button type=button class="btn btn-info btn-sl"  data-toggle="modal" onclick="showComara(this)"  data-target="#myModal" >Capture</button>
                                <img src="{{ img_url($material->image) }}" class="listing-thumb image-load img-thumbnail " alt="" />
                            </div>
                            <small>
                                <p class="help-block">Only .jpeg, .jpg, .png, .gif file can be uploaded. Maximum image size 5MB</p>
                            </small>
                            <input type="hidden" name="image_binary" class="image_binary"/> 
                        </div>
                    </div>
                </div>

                <div class="form-group row d-flex align-items-center mb-5">
                    <div class="col-lg-5 offset-lg-3">
                        <button type="submit" class="btn btn-primary btn-lg">Save</button>
                    </div>
                </div>
                {!! Form::close() !!}
            </div>
            <div id="myModal" class="modal fade" role="dialog">
                <div class="modal-dialog modal-lg">

                    <!-- Modal content-->
                    <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">Modal Header</h4>
                        <button type="button" class="close"  onclick="closeWebcame()"  data-dismiss="modal">&times;</button>        
                    </div>
                    <div class="modal-body modal-body-cus">
                        
                        <div class="col-md-6 pull-left">
                            <div id="my_camera"></div>
                            <input type="hidden" value="" id="row_id" />    
                            <input type="button" value="Capture Image" class="btn btn-success mt-2" onClick="take_snapshot()">
                        </div>
                        <div id="results" class="col-md-6 pull-right">                            
                            <img src=""  class="img-thumbnail min-size-image"/>        
                        </div>    
                        
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-primary" onclick="closeWebcame()" data-dismiss="modal">Ok</button>
                    </div>
                    </div>

                </div>
            </div>
            
        </div>
    </div>
</div>
<!-- End Row -->
@endsection
@push('scripts')
<script type="text/javascript" src="{{asset('/js/webcam.min.js')}}"></script>
<script type="text/javascript">
    (function($) {
        $.validate({
            form: '#from_add_material',
            modules: 'file'
        });

        $('#genrate_code').on("click", function() {
            $.get('{{url("genrate_code")}}', function(data) {
                $("#input_barcode").val(data);
            });
        });


        $(window).keydown(function(e) {
            if (e.which == 13) {
                var $targ = $(e.target);

                if (!$targ.is("textarea") && !$targ.is(":button,:submit")) {
                    var focusNext = false;
                    $(this).find(":input:visible:not([disabled],[readonly]), a").each(function() {
                        if (this === e.target) {
                            focusNext = true;
                        } else if (focusNext) {
                            $(this).focus();
                            return false;
                        }
                    });

                    return false;
                }
            }
        });
        Webcam.set({
			width: 320,
			height: 240,
			image_format: 'jpeg',
			jpeg_quality: 90
		});
    })(jQuery); 

    // Convert width in inches to centimeters
    $('#widthInch').on('input', function() {
        var inchValue = parseFloat($(this).val());
        if (!isNaN(inchValue)) {
            var cmValue = (inchValue * 2.54).toFixed(2);
            $('#widthCm').val(cmValue);
        } else {
            $('#widthCm').val('');
        }
    });
    
    function take_snapshot() {
        // take snapshot and get image data
        Webcam.snap( function(data_uri) {
            
            $("#results img").attr("src", data_uri);
            $('.image_binary').val(data_uri); 
            $(".image-load").attr("src", data_uri);                       
        } );
    }
    function showComara(ele){        
        Webcam.reset();
		Webcam.attach( '#my_camera' );         
    }
    function closeWebcame(){
        Webcam.reset();
    }
</script>
<style>
    .styled-radio {
        display: inline-block;
        margin-right: 10px;
    }
</style>
@endpush
