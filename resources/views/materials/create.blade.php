@extends('layouts.master')
@section('title', 'Add Material')
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
                <h4>Add Material</h4>
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


                {!! Form::open(array('route' => 'materials.store','method'=>'POST','id'=>'from_add_material', 'class'=>"form-horizontal form-validate", 'novalidate', 'files' => true)) !!}    
                <div class="row">
                    <div class="col-lg-2">
                        <div class="form-group">
                            <label class="form-control-label">Brand<span class="text-danger ml-2">*</span></label>
                            {!! Form::text('name', null, ['placeholder' => 'New Item', 'class' => 'form-control', 'data-validation' => 'required']) !!}
                        </div>
                    </div>
                    <div class="col-lg-2">
                        <div class="form-group">
                            <label class="form-control-label">Category<span class="text-danger ml-2">*</span></label>
                            {!! Form::select('category_id', $categories, null, ['class' => 'form-control custom-select', 'data-validation' => 'required']) !!}
                        </div>
                    </div>
                    <div class="col-lg-2">
                        <div class="form-group">
                            <label class="form-control-label">Vendor<span class="text-danger ml-2">*</span></label>
                            {!! Form::select('supplier_id', $suppliers, null, ['class' => 'form-control custom-select', 'data-validation' => 'required']) !!}
                        </div>
                    </div>
                    <div class="col-lg-2">
                        <div class="form-group">
                            <label class="form-control-label">Made In<span class="text-danger ml-2">*</span></label>
                            {!! Form::select('made_in', [''=>'Select Country','india'=>'India','china'=>'China','thailand'=>'Thailand'], null, ['class' => 'form-control custom-select', 'data-validation' => 'required']) !!}
                        </div>
                    </div>
                    <div class="col-lg-2">
                        <div class="form-group">
                            <label class="form-control-label">Price Currency<span class="text-danger ml-2">*</span></label>
                            {!! Form::select('currency', [''=>'Select Currency','USD'=>'USD','EUR'=>'EUR','CNY'=>'CNY','THB'=>'THB','INR'=>'INR'], null, ['id'=>'currency_of_purchase', 'class' => 'form-control custom-select', 'data-validation' => 'required']) !!}
                        </div>
                    </div>
                    <div class="col-lg-2">
                        <div class="form-group">
                            <label class="form-control-label">Unit Purchased In<span class="text-danger ml-2">*</span></label>
                            {!! Form::select('unit_purchased_in', ['meter'=>'Meter','yard'=>'Yard'], null, ['class' => 'form-control custom-select', 'data-validation' => 'required']) !!}
                        </div>
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-lg-2">
                        <div class="form-group">
                            <label class="form-control-label">Price<span class="text-danger ml-2">*</span></label>
                            {!! Form::text('price', null, ['placeholder' => 'Amount', 'class' => 'form-control', 'data-validation' => 'required']) !!}
                        </div>
                    </div>
                    <div class="col-lg-2">
                        <div class="form-group">
                            <label class="form-control-label">Article<span class="text-danger ml-2">*</span></label>
                            {!! Form::text('article_no', null, ['id'=>'article_no','placeholder' => 'Give Article Name', 'class' => 'form-control', 'data-validation' => 'required']) !!}
                        </div>
                    </div>
                </div>
                <div class="row">
                    <!-- First Column with lg-2 width -->
                    <div class="col-lg-2">
                        <div class="form-group">
                            <label class="form-control-label">Selling Price:</label>
                        </div>
                    </div>

                    <!-- Second Column with lg-5 width (to account for lg-7 used in remaining columns) -->
                    <div class="col-lg-5">
                        <div class="row">
                            <div class="col-lg-4">
                                <div class="form-group">
                                    <label class="form-control-label">ROLL (per yrd)</label>
                                    {!! Form::text('roll', 0, ['class' => 'form-control', 'id' => 'sample', 'data-validation' => 'number', 'data-validation-allowing' => 'float', 'placeholder' => 'Sample Price']) !!}
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <div class="form-group">
                                    <label class="form-control-label">WHOLESALE (per yrd)</label>
                                    {!! Form::text('cut_wholesale', 0, ['class' => 'form-control', 'id' => 'wholesale', 'data-validation' => 'number', 'data-validation-allowing' => 'float', 'placeholder' => 'WholeSale Price']) !!}
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <div class="form-group">
                                    <label class="form-control-label">RETAIL (per yrd)</label>
                                    {!! Form::text('retail', 0, ['class' => 'form-control', 'id' => 'retail', 'data-validation' => 'number', 'data-validation-allowing' => 'float', 'placeholder' => 'Retail Price']) !!}
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Third Column with lg-5 width (to account for lg-7 used in remaining columns) -->
                    <div class="col-lg-5">
                        <div class="row">
                            <div class="col-lg-4">
                                <div class="form-group">
                                    <label class="form-control-label">ROLL (per mtr)</label>
                                    {!! Form::text('roll_per_mtr', 0, ['class' => 'form-control', 'id' => 'sample', 'data-validation' => 'number', 'data-validation-allowing' => 'float', 'placeholder' => 'Sample Price']) !!}
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <div class="form-group">
                                    <label class="form-control-label">WHOLESALE (per mtr)</label>
                                    {!! Form::text('cut_wholesale_per_mtr', 0, ['class' => 'form-control', 'id' => 'wholesale', 'data-validation' => 'number', 'data-validation-allowing' => 'float', 'placeholder' => 'WholeSale Price']) !!}
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <div class="form-group">
                                    <label class="form-control-label">RETAIL (per mtr)</label>
                                    {!! Form::text('retail_per_mtr', 0, ['class' => 'form-control', 'id' => 'retail', 'data-validation' => 'number', 'data-validation-allowing' => 'float', 'placeholder' => 'Retail Price']) !!}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                {{-- <div class="form-group row d-flex align-items-center mb-5">
                    <label class="col-lg-3 form-control-label d-flex justify-content-lg-end">Barcode Number</label>
                    <div class="col-lg-5">
                        <div class="input-group">
                            <span class="input-group-addon addon-secondary"><i class="la la-barcode"></i></span> --}}
                {{-- {!! Form::hidden('barcode', null, array('id'=>'input_barcode','placeholder' => 'Barcode Number','class' => 'form-control', 'data-validation'=>"required")) !!} --}}
                {{-- <span class="input-group-btn">
                                <button type="button" id="genrate_code" class="btn btn-secondary ripple">
                                    Genrate Code
                                </button>
                            </span>
                        </div>
                    </div>
                </div> --}}
                <div class="row">
                    <div class="col-lg-2">
                        <label class="form-control-label">Width(inch)<span class="text-danger ml-2">*</span></label>
                        <div class="input-group form-group">
                            <input type="text" name="width_inch" class="form-control width-inch" id="widthInch" placeholder="Width in inches" data-validation="required">
                            <span class="input-group-text">INCH</span>
                        </div>
                    </div>
                    <div class="col-lg-2">
                        <label class="form-control-label">Width(cm)<span class="text-danger ml-2">*</span></label>
                        <div class="input-group form-group">
                            <input type="text" name="width_cm" class="form-control width-cm" id="widthCm" placeholder="Width in centimeters" readonly>
                            <span class="input-group-text">CM</span>
                        </div>
                    </div>
                    <div class="col-lg-2">
                        <label class="form-control-label">Weight(gsm)<span class="text-danger ml-2">*</span></label>
                        <div class="input-group form-group">
                            {!! Form::text('weight_gsm', null, ['class' => 'form-control weight-gsm', 'id' => 'weightGsm', 'placeholder' => 'Weight', 'data-validation' => 'required']) !!}
                            <span class="input-group-text">GSM</span>
                        </div>
                    </div>
                    <div class="col-lg-2">
                        <label class="form-control-label">Weight(per mtr)<span class="text-danger ml-2">*</span></label>
                        <div class="input-group form-group">
                            {!! Form::text('weight_per_mtr', null, ['class' => 'form-control weight-per-mtr', 'id' => 'weightPerMtr', 'placeholder' => 'Weight', 'data-validation' => 'required','readonly' => 'readonly']) !!}
                            <span class="input-group-text">PER MTR</span>
                        </div>
                    </div>
                    <div class="col-lg-2">
                        <label class="form-control-label">Weight(per yard)<span class="text-danger ml-2">*</span></label>
                        <div class="input-group form-group">
                            {!! Form::text('weight_per_yard', null, ['class' => 'form-control weight-per-yard', 'id' => 'weightPerYard', 'placeholder' => 'Weight', 'data-validation' => 'required','readonly' => 'readonly']) !!}
                            <span class="input-group-text">PER YARD</span>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-lg-3">
                        <div class="form-group">
                            <label class="form-control-label">Selvage</label>
                            {!! Form::text('selvage',null, array('class' => 'form-control selvage','id'=>"selvage",'placeholder' => 'Selvage')) !!}
                        </div>
                    </div>
                    <div class="col-lg-3">
                        <div class="form-group">
                            <label class="form-control-label">Construction</label>
                            {!! Form::text('construction', null, array('placeholder' => 'Construction','class' => 'form-control','id'=>"construction")) !!}
                        </div>
                    </div>
                    <div class="col-lg-3">
                        <div class="form-group">
                            <label class="form-control-label">Description</label>
                            {!! Form::textarea('description', null, ['class' => 'form-control','rows' => 3]); !!}
                        </div>
                    </div>
                    <div class="col-lg-3">
                        <div class="form-group">
                            <label class="form-control-label">No of colors</label>
                            <div class="input-group">
                                {!! Form::number('no_of_color', 1, ["class"=>" form-control no_of_color",'id'=>"no_of_color"]) !!}
                                <div class="input-group-append">
                                    <span class="input-group-text">
                                        <a type="button" id="add_color_item" title="Add Color Item">
                                            <span><i class="fa fa-plus"></i></span>
                                        </a>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                {{-- <div class="form-group row d-flex align-items-center mb-5">
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
                    <div class="col-lg-5">
                        {!! Form::file('image', ['id' => 'upload_image', 'accept' => 'image/*',
                        'data-validation'=>"required mime",
                        'data-validation-allowing'=>"jpeg, jpg, png, gif",
                        'data-validation-error-msg-mime'=>"You can only upload images"]); !!}
                        <small>
                            <p class="help-block">Only .jpeg, .jpg, .png, .gif file can be uploaded. Maximum image size 5MB</p>
                        </small>
                    </div>
                    <div class="col-lg-4">

                    </div>

                </div> --}}
                <div class="row my-4">
                    <div class="col-md-12">

                        <div class="card">
                            <h4 class="card-header">
                                <div class="col-xs-2 float-left pt-2">Color Items</div>
                            </h4>
                            <div class="card-body">
                                <div class="table-responsive mt-3">
                                    <table id="tblMaterialItems" class="table table-hover order-list">
                                        <thead>
                                            <tr>
                                                <th>Article No</th>
                                                <th>Color No</th>
                                                <th>Color</th>
                                                <th>Re Order Alert</th>
                                                <th>Image</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>

                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>

                <div class="form-group row d-flex align-items-center mt-5">
                    <div class="col-lg-12 d-flex justify-content-center">
                        <button type="submit" class="btn btn-primary btn-lg">Save</button>
                    </div>
                </div>
                {!! Form::close() !!}
            </div>

        </div>
    </div>
</div>

{{-- <div id="addColorModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" class="modal fade text-left">
    <div role="document" class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 id="modal-header" class="modal-title">Add Purchase Item</h5>
                <button type="button" data-dismiss="modal" aria-label="Close" class="close"><span aria-hidden="true">×</span></button>
            </div>
            <div class="modal-body">
                <form id="add_color_form" class="">
                    <div class="form-group">
                        <label class="form-control-label">Number Of Rolls<span class="text-danger ml-2">*</span></label>
                        {!! Form::number('number_of_rows', 1, array('id'=>'add_number_of_rolls', 'class' => 'form-control', 'data-validation'=>"required")) !!}
                    </div>
                    <div class="form-action float-right">
                        <button type="submit" name="update_btn" class="btn btn-primary">Add</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div> --}}

<!-- Modal -->
<div id="myModal" class="modal fade" role="dialog">
  <div class="modal-dialog  modal-lg">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title">Modal Header</h4>
        <button type="button" class="close" onclick="closeWebcame()" data-dismiss="modal">&times;</button>        
      </div>
      <div class="modal-body modal-body-cus">
        <div class="col-md-6 pull-left"> 
            <div id="my_camera"></div>
            <input type="hidden" value="" id="row_id" />    
            <input type="button" value="Capture Image" class="btn btn-success mt-2" onClick="take_snapshot()">
        </div>
        <div id="results" class="col-md-6 pull-right">
            
            <img src="" class="img-thumbnail min-size-image" />        
        </div>    
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-primary" onclick="closeWebcame()" data-dismiss="modal">Ok</button>
      </div>
    </div>

  </div>
</div>
<input type="hidden" id="material_min_alert_qty" value="{{ isset($settings->material_min_alert_qty) ? $settings->material_min_alert_qty : 0 }}">
<script type="text/template" id="templateAddItem">


    <td><div readonly="readonly" class="form-control article_no" id="article_no"></div></td>
    <td>{!! Form::text('color_no[]', null, ['class'=>'form-control color_no','id'=>"color_no"]) !!}</td>
    <td>{!! Form::text('color[]', null, ['class'=>'form-control color','id'=>'color']) !!}</td>
    <td>{!! Form::text('min_alert_qty[]',null, ['class'=>'form-control min_alert_qty','id'=>'min_alert_qty']) !!}</td>
    <td>
        <div style="display: flex; align-items: center;">
            {!! Form::file('image[]', ['id' => 'upload_image', 'accept' => 'image/*',
            'data-validation'=>"mime",
            'data-validation-allowing'=>"jpeg, jpg, png, gif",
            'data-validation-error-msg-mime'=>"You can only upload images",
            "capture"=>"camera"]); !!}
            <button type="button" class="btn btn-info btn-sl" data-toggle="modal" onclick="showComara(this)" data-target="#myModal">Capture</button>
        </div>
        <small>
            <p class="help-block">Only .jpeg, .jpg, .png, .gif file can be uploaded. Maximum image size 5MB</p>
        </small>
        <input type="hidden" name="image_binary[]" class="image_binary"/>    
    </td>
    <td><a class="btn btn-danger btn-square btn-sm item-delete" >Delete</a></td>
    {!! Form::hidden('barcode[]', null, array('id'=>'input_barcode','placeholder' => 'Barcode Number','class' => 'form-control input_barcode')) !!}
</script>

<!-- End Row -->
@endsection
@push('scripts')
<script type="text/javascript" src="{{asset('/js/webcam.min.js')}}"></script>
<script type="text/javascript">
    (function($) {
        $.validate({
            form: '#from_add_material',
            modules: 'file',
        });
       
        $(document).on('click', '#add_color_item', function() {

            if(!$('#from_add_material').isValid()){
                return;
            }

            var article_no = $('#article_no').val();
            var no_of_color = $('#no_of_color').val();
            var data = {
                "article_no": article_no,
                "no_of_color": no_of_color
            };
            addItem(data);
        });

        function addItem(form) {
            var material_min_alert_qty = $('#material_min_alert_qty').val();
            var number_of_colors = form.no_of_color;
            var article_no = form.article_no;
            $template = $('#templateAddItem').html();
            for (i = 0; i < number_of_colors; i++) {
                var $uniqueId = uuid();
                var $tr = $('<tr class="colorItem" id="' + $uniqueId + '">').append($template);
                $('#tblMaterialItems tbody').append($tr);
                $('#' + $uniqueId).find('.article_no').text(article_no);
                $('#' + $uniqueId).find('.color_no').val(String(i + 1).padStart(2, '0'));
                $('#' + $uniqueId).find('.min_alert_qty').val(material_min_alert_qty);
                $('#' + $uniqueId).find('.item-delete').attr('id', $uniqueId);
            }
            $('#no_of_color').val(1);
        }

        $(document).on('click', '#tblMaterialItems tbody .item-delete', function() {
            var rowid = $(this).attr('id');
            $('#' + rowid).remove();
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
            var id = $('#row_id').val();
            $('#'+id+' .image_binary').val(data_uri);
            $("#results img").attr("src", data_uri);            
        } );
    }
    function showComara(ele){        
        Webcam.reset();        
		Webcam.attach( '#my_camera' );

        var id = $(ele).parents('tr').eq(0).attr('id');
        $("#row_id").val(id);
        var data_url = $('#'+id+' .image_binary').val();
        $("#results img").attr("src", data_url); 
        
    }
    function closeWebcame(){
        Webcam.reset();
    }

    // Function to convert inches to centimeters
    function inchesToCm(inches) {
        return inches * 2.54; // 1 inch = 2.54 cm
    }

    // Add event listener to detect input changes in width-inch field
    document.getElementById('widthInch').addEventListener('input', function() {
        // Get value of width in inches
        let inches = parseFloat(this.value);

        // Check if the input is a valid number
        if (!isNaN(inches)) {
            // Convert inches to centimeters
            let cm = inchesToCm(inches);

            // Update the value in width-cm field (rounded to 2 decimal places)
            document.getElementById('widthCm').value = cm.toFixed(2);
        } else {
            // If input is not a valid number, clear the width-cm field
            document.getElementById('widthCm').value = '';
        }
    });

    function calculateWeights() {
        let gsmWeight = parseFloat(document.getElementById('weightGsm').value);

        if (!isNaN(gsmWeight)) {
            let weightPerMtr = 1.5 * gsmWeight;
            let weightPerYard = gsmWeight * 1.3716;

            document.getElementById('weightPerMtr').value = weightPerMtr.toFixed(2);
            document.getElementById('weightPerYard').value = weightPerYard.toFixed(2);
        } else {
            document.getElementById('weightPerMtr').value = '';
            document.getElementById('weightPerYard').value = '';
        }
    }

    $(document).ready(function() {
        $('#from_add_material').on('submit', function(e) {
            var isValid = true;

            // Validate all Color fields
            $('input[name="color[]"]').each(function() {
                var color = $(this).val();
                if (color === '') {
                    isValid = false;
                    $(this).addClass('is-invalid');
                    alert('Each Color is required');
                    return false; // Break the loop if an invalid color is found
                } else {
                    $(this).removeClass('is-invalid');
                }
            });

            // Validate all Color fields
            $('input[name="color_no[]"]').each(function() {
                var color_no = $(this).val();
                if (color_no === '') {
                    isValid = false;
                    $(this).addClass('is-invalid');
                    alert('Each Color No is required');
                    return false; // Break the loop if an invalid color is found
                } else {
                    $(this).removeClass('is-invalid');
                }
            });

            // Prevent form submission if validation fails
            if (!isValid) {
                e.preventDefault();
            }
        });
    });

    document.getElementById('weightGsm').addEventListener('input', calculateWeights);
</script>
@endpush


