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
                <div class="form-group row d-flex align-items-center mb-5">
                    <label class="col-lg-3 form-control-label d-flex justify-content-lg-end">Name</label>
                    <div class="col-lg-5">
                        {!! Form::text('name', null, array('placeholder' => 'Name','class' => 'form-control', 'data-validation'=>"required")) !!}
                    </div>
                </div>
                <div class="form-group row d-flex align-items-center mb-5">
                    <label class="col-lg-3 form-control-label d-flex justify-content-lg-end">Barcode Number</label>
                    <div class="col-lg-5">
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
                </div>
                <div class="form-group row d-flex align-items-center mb-5">
                    <label class="col-lg-3 form-control-label d-flex justify-content-lg-end">Article No</label>
                    <div class="col-lg-5">
                        {!! Form::text('article_no', null, array('placeholder' => 'Article_no','class' => 'form-control', 'data-validation'=>"required",'id'=>"article_no")) !!}
                    </div>
                </div>
                <div class="form-group row d-flex align-items-center mb-5">
                    <label class="col-lg-3 form-control-label d-flex justify-content-lg-end">Price</label>
                    <div class="col-lg-2">
                        <label for="col-lg-2 form-control-label d-flex justify-content-lg-end">Wholesale Price</label>
                        {!! Form::text('wholesale_price',null, array('class' => 'form-control col-lg-12','id'=>"wholesale",'data-validation' => "number",'data-validation-allowing'=>"float",'placeholder' => 'WholeSale Price')) !!}
                    </div>
                    <div class="col-lg-2">
                        <label for="col-lg-2 form-control-label d-flex justify-content-lg-end">Retail Price</label>
                        {!! Form::text('retail_price',null, array('class' => 'form-control col-lg-12','id'=>"retail",'data-validation' => "number",'data-validation-allowing'=>"float",'placeholder' => 'Retail Price')) !!}
                    </div>
                    <div class="col-lg-2">
                        <label for="col-lg-2 form-control-label d-flex justify-content-lg-end">Sample Price</label>
                        {!! Form::text('sample_price',null, array('class' => 'form-control col-lg-12','id'=>"sample",'data-validation' => "number",'data-validation-allowing'=>"float",'placeholder' => 'Sample Price')) !!}
                    </div>
                </div>
                <div class="form-group row d-flex align-items-center mb-5">
                    <label class="col-lg-3 form-control-label d-flex justify-content-lg-end">Category</label>
                    <div class="col-lg-5">
                        {!! Form::select('category_id', $categories,null, array('class' => 'form-control custom-select')) !!}
                    </div>
                </div>
                <div class="form-group row d-flex align-items-center mb-5">
                    <label class="col-lg-3 form-control-label d-flex justify-content-lg-end">Color</label>
                    <div class="col-lg-5">
                        {!! Form::text('color',null, array('class' => 'form-control color','id'=>"color")) !!}
                    </div>
                </div>
                <div class="form-group row d-flex align-items-center mb-5">
                    <label class="col-lg-3 form-control-label d-flex justify-content-lg-end">Selvage</label>
                    <div class="col-lg-5">
                        {!! Form::text('selvage',null, array('class' => 'form-control selvage','id'=>"selvage",'data-validation'=>"required",'placeholder' => 'Selvage')) !!}
                    </div>
                </div>
                <div class="form-group row d-flex align-items-center mb-5">
                    <label class="col-lg-3 form-control-label d-flex justify-content-lg-end">Construction</label>
                    <div class="col-lg-5">
                        {!! Form::text('construction', null, array('placeholder' => 'Construction','class' => 'form-control', 'data-validation'=>"required",'id'=>"construction")) !!}
                    </div>
                </div>
                <div class="form-group row d-flex align-items-center mb-5">
                    <label class="col-lg-3 form-control-label d-flex justify-content-lg-end">Width</label>
                    <div class="col-lg-5">
                        {!! Form::text('width', null, array('placeholder' => 'width','class' => 'form-control','id'=>"width")) !!}
                    </div>
                </div>
                <div class="form-group row d-flex align-items-center mb-5">
                    <label class="col-lg-3 form-control-label d-flex justify-content-lg-end">Weight</label>
                    <div class="col-lg-5">
                        {!! Form::text('weight', null, array('placeholder' => 'weight','class' => 'form-control','id'=>"weight")) !!}
                        <small>
                            <p class="help-block">Gram per meters</p>
                        </small>
                    </div>
                </div>
                <div class="form-group row d-flex align-items-center mb-5">
                    <label class="col-lg-3 form-control-label d-flex justify-content-lg-end">Re Order Alert</label>
                    <div class="col-lg-5">
                        {!! Form::text('min_alert_qty', null, array('placeholder' => 'Re Order Alert','class' => 'form-control','id'=>"min_alert_qty")) !!}
                    </div>
                </div>
                <div class="form-group row d-flex align-items-center mb-5">
                    <label class="col-lg-3 form-control-label d-flex justify-content-lg-end">Description</label>
                    <div class="col-lg-5">
                        {!! Form::textarea('description', null, ['class' => 'form-control','rows' => 3]); !!}
                    </div>
                </div>
                <div class="form-group row d-flex align-items-center mb-5">
                    <label class="col-lg-3 form-control-label d-flex justify-content-lg-end">Status</label>
                    <div class="col-lg-1">
                        <div class="styled-radio">
                            {!! Form::radio('status', 1, true, array('class' => 'custom-control-input', 'id'=>"status-active")) !!}
                            <label for="status-active">Active</label>
                        </div>
                    </div>
                    <div class="col-lg-1">
                        <div class="styled-radio">
                            {!! Form::radio('status', 0, false, array('class' => 'custom-control-input', 'id'=>"status-inactive")) !!}
                            <label for="status-inactive">Inactive</label>
                        </div>
                    </div>
                </div>
                <div class="form-group row d-flex align-items-center mb-5">
                    <label class="col-lg-3 form-control-label d-flex justify-content-lg-end">Image</label>
                    <div class="col-lg-4">
                        {!! Form::file('image', ['id' => 'upload_image', 'accept' => 'image/*',
                        'data-validation'=>"mime",
                        'data-validation-allowing'=>"jpeg, jpg, png, gif",
                        'data-validation-error-msg-mime'=>"You can only upload images"]); !!}
                        <small>
                            <p class="help-block">Only .jpeg, .jpg, .png, .gif file can be uploaded. Maximum image size 5MB</p>
                        </small>
                        <button type=button class="btn btn-info btn-lg"  data-toggle="modal" onclick="showComara(this)"  data-target="#myModal" >Capture</button>
                        <input type="hidden" name="image_binary" class="image_binary"/> 
                    </div>
                    <div class="col-lg-4">
                        <img src="{{ img_url($material->image) }}" class="listing-thumb image-load img-thumbnail " alt="" />
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
@endpush
