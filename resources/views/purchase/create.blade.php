@extends('layouts.master')
@section('title', 'Add Purchase')
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
                <h4>Add New Purchase</h4>
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


                {!! Form::open(array('route' => 'purchase.store','method'=>'POST','id'=>'from_add_purchase', 'class'=>"form-horizontal form-validate", 'novalidate','files' => true)) !!}
                <div class="row">
                    <div class="col-lg-6 row">
                        <div class="col-lg-3">
                            <div class="form-group">
                                <label class="form-control-label">Date of purchase<span class="text-danger ml-2">*</span></label>
                                {!! Form::text('purchase_date', null, array('id' => 'purchase_date','class' => 'form-control', 'data-validation'=>"required")) !!}
                            </div>
                        </div>
                        <div class="col-lg-3">
                            <div class="form-group">
                                <label class="form-control-label">Invoice No.<span class="text-danger ml-2">*</span></label>
                                {!! Form::text('invoice_no', null, array('id' => 'invoice_no','class' => 'form-control', 'data-validation'=>"required")) !!}
                            </div>
                        </div>

                        <div class="col-lg-6">
                            <div class="form-group">
                                <label class="form-control-label">Supplier<span class="text-danger ml-2">*</span></label>
                                <div class="input-group form-group">
                                    {!! Form::select('supplier_id', $suppliers, null, [
                                        'id' => 'supplier_id',
                                        'class' => 'form-control custom-select',
                                        'data-validation' => "required"
                                    ]) !!}
                                    <a href="{{ route('supplier.create', ['redirect' => base64_encode(route('purchase.create'))]) }}" class="col-6 btn btn-primary btn-square ml-2">Add Supplier</a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6 row">
                    <div class="col-lg-4">
                        <div class="form-group">
                            <label class="form-control-label">Purchase Type<span class="text-danger ml-2">*</span></label>
                            {!! Form::select('purchase_type', [
                                '' => 'Select Purchase Type',
                                'international' => 'International', 
                                'domestic' => 'Domestic'
                            ], null, [
                                'id' => 'purchase_type',
                                'class' => 'form-control custom-select',
                                'data-validation' => "required"
                            ]) !!}
                        </div>
                    </div>

                    <div class="col-lg-4">
                        <div class="form-group">
                            <label class="form-control-label">Currency Of Purchase<span class="text-danger ml-2">*</span></label>
                            {!! Form::select('currency_of_purchase', [''=>'Select Currency','USD'=>'USD','EUR'=>'EUR','CNY'=>'CNY','THB'=>'THB','INR'=>'INR'], null, [
                                'id' => 'currency_of_purchase',
                                'class' => 'form-control custom-select',
                                'data-validation' => "required"
                            ]) !!}
                        </div>
                    </div>
                        <div class="col-lg-4">
                            <div class="form-group">
                                <label class="form-control-label">EX RATE<span class="text-danger ml-2">*</span></label>
                                {!! Form::text('ex_rate', null, array('id' => 'ex_rate','class' => 'form-control', 'data-validation'=>"required")) !!}
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="row col-lg-6">
                        <div class="col-lg-3">
                            <div class="form-group">
                                <label class="form-control-label">Total Meter<span class="text-danger ml-2">*</span></label>
                                {!! Form::text('total_meter', null, array('placeholder' => 'Meters','id'=>'total_meter','class' => 'form-control','data-validation' => "required")) !!}
                            </div>
                        </div>
                        <div class="col-lg-3">
                            <div class="form-group">
                                <label class="form-control-label">Total Yards<span class="text-danger ml-2">*</span></label>
                                {!! Form::text('total_yard', null, array('placeholder' => 'Yards','id'=>'total_yard','class' => 'form-control','readonly'=>'true','data-validation' => "required")) !!}
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label class="form-control-label">Import Tax<span class="text-danger ml-2">*</span></label>
                                {!! Form::text('import_tax', null, array('placeholder' => 'THB','id'=>'import_tax','class' => 'form-control','data-validation' => "required")) !!}
                            </div>
                        </div>
                    </div>
                    <div class="row col-lg-6">
                        <div class="col-lg-4">
                            <div class="form-group">
                                <label class="form-control-label">Transport & Shipping Paid<span class="text-danger ml-2">*</span></label>
                                {!! Form::text('transport_shipping_paid', null, array('placeholder' => 'THB','id'=>'transport_shipping_paid','class' => 'form-control','data-validation' => "required")) !!}
                            </div>
                        </div>
                        <div class="col-lg-4">
                            <div class="form-group">
                                <label class="form-control-label">Discount</label>
                                {!! Form::text('discount', 0, array('placeholder' => 'discount','class' => 'form-control','id'=>'discount')) !!}
                            </div>
                        </div>
                        <div class="col-lg-4">
                            <div class="form-group">
                                <label class="form-control-label">Attach Documents</label>
                                {!! Form::file('attach_documents[]', [
                                    'class' => 'custom-file-input_0',
                                    'id' => 'attach_documents',
                                    'multiple' => true, // Enable multiple file selection
                                    'data-validation' => 'mime',
                                    'data-validation-allowing' => 'jpeg, jpg, png, pdf, doc, docx',
                                    'data-validation-error-msg-mime' => 'You can only upload image or document files'
                                ]) !!}
                            </div>
                        </div>

                    </div>
                </div>

                <div class="row">
                    <div class="col-lg-3">
                        <div class="form-group">
                            <label class="form-control-label">Transportation & Shipping Cost Per Meter<span class="text-danger ml-2">*</span></label>
                            {!! Form::text('transport_shippment_cost_per_meter', 0, array('placeholder' => 'Transport & Shipping Cost','class' => 'form-control','id'=>'transport_shippment_cost_per_meter','readonly'=>'readonly','data-validation' => "required")) !!}
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-lg-12">
                        <div class="form-group">
                            <label class="form-control-label">Note</label>
                            {!! Form::textarea('note', null, ['class' => 'form-control','rows' => 3]); !!}
                        </div>
                    </div>
                </div>
                <div class="row my-4">
                    <div class="col-md-12">

                        <div class="card">
                            <h4 class="card-header">Purchase Items <a href="javascript:;" class="btn btn-primary p-2 btn-square btn-sm ml-5" id="add_item_model_btn" data-toggle="modal" data-target="#addItemModal">Add Item</a>
                                <a href="javascript:;" style="display: none;" class="btn btn-primary btn-square btn-md float-right" id="add_single_row" ><i class="la la-plus p-0 m-0"></i></a>
                            </h4>
                            <div class="card-body">
                                <div class="table-responsive mt-3">
                                    <table id="tblPurchaseItems" class="table table-hover order-list">
                                        <thead>
                                            <tr>
                                                <th style="width:180px;">Material</th>
                                                <th style="width:160px;">Color</th>
                                                <th style="width:150px;">Article No</th>
                                                <th style="width:150px;">Color No</th>
                                                <th style="width:150px;">Batch No / Lot No</th>
                                                <th style="width:150px;">Width</th>
                                                <th style="width:150px;">Roll No</th>
                                                <th style="width:150px;">Meter</th>
                                                <th style="width:150px;">Yard</th>
                                                <th style="width:150px;">Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($items as $key => $item)
                                            <tr>
                                                <td>{!! Form::select('material_id[]', $materials,$item['material_id'], array('class' => 'material form-control custom-select' ,'data-validation'=>"required")) !!}</td>
                                                <td>{!! Form::text('color[]',$item['color'], array('class' => 'color form-control','readonly'=>'readonly')) !!}</td>
                                                <td>{!! Form::text('article_no[]', $item['article_no'], array('class' => 'article_no form-control', 'data-validation'=>"required")) !!}</td>
                                                <td>{!! Form::text('color_no[]',$item['color_no'], array('class' => 'color_no form-control','readonly'=>'readonly')) !!}</td>
                                                <td>{!! Form::text('batch_no[]', $item['batch_no'], array('class' => 'batch_no form-control', 'data-validation'=>"required")) !!}</td>
                                                <td>{!! Form::number('width[]', $item['width'], array('class' => 'width form-control', 'data-validation'=>"required")) !!}</td>
                                                <td>{!! Form::text('roll_no[]', $item['roll_no'], array('class' => 'roll_no form-control', 'data-validation'=>"required")) !!}</td>
                                                <td>{!! Form::number('meter[]', $item['meter'], array('class' => 'meter meter_val form-control', 'data-validation'=>"required", 'id' => 'meter_val')) !!}</td>
                                                <td><input class="yard form-control" readonly="readonly" value="{{ $item['yard'] }}" type="text"></td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
                <div class="form-group float-right">
                    <button type="submit" class="btn btn-primary btn-lg" id="from_add_purchase_btn">Save</button>
                </div>
                {!! Form::close() !!}
            </div>

        </div>
    </div>
</div>

<div id="addItemModal" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" class="modal fade text-left">
    <div role="document" class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 id="modal-header" class="modal-title">Add Purchase Item</h5>
                <button type="button" data-dismiss="modal" aria-label="Close" class="close"><span aria-hidden="true">×</span></button>
            </div>
            <div class="modal-body">
                <form id="add_item_form" class="">
                    <div class="form-group">
                        <label class="form-control-label">Select Material<span class="text-danger ml-2">*</span></label>
                        {!! Form::select('material_id',$materials,null, array('id'=>'add_material_id','class' => 'form-control custom-select ',"placeholder"=>"--Select Material--", 'data-validation'=>"required",'style'=>"width:100%")) !!}
                    </div>
                    <div class="form-group">
                        <label class="form-control-label">Select Color<span class="text-danger ml-2">*</span></label>
                        {!! Form::select('color_id', [''=>'--Select Color--'],null, array('id'=>'add_color_id','class' => 'form-control custom-select', 'data-validation'=>"required",'style'=>"width:100%",'disabled'=>'disabled')) !!}
                    </div>
                    <div class="form-group">
                        <label class="form-control-label">Purchase Price<span class="text-danger ml-2">*</span></label>
                        {!! Form::text('add_purchase_price', null, array('id'=>'add_purchase_price','placeholder' => 'Purchase Price','class' => 'form-control ','readonly'=>'readonly')) !!}
                    </div>
                    <div class="form-group">
                        <label class="form-control-label">Article No.<span class="text-danger ml-2">*</span></label>
                        {!! Form::text('article_no', null, array('id'=>'add_article_no','placeholder' => 'Article No.','class' => 'form-control ', 'data-validation'=>"required",'readonly'=>'readonly')) !!}
                        {!! Form::hidden('width', null, array('class' => 'add_width form-control','id'=>"add_width")) !!}
                    </div>
                    <div class="form-group">
                        <label class="form-control-label">Color No.<span class="text-danger ml-2">*</span></label>
                        {!! Form::text('color_no', null, array('id'=>'add_color_no','placeholder' => 'Color No','class' => 'form-control ', 'data-validation'=>"required",'readonly'=>'readonly')) !!}
                    </div>
                    <div class="form-group">
                        <label class="form-control-label">Batch No. / Lot No.<span class="text-danger ml-2">*</span></label>
                        {!! Form::text('batch_no', null, array('id'=>'add_batch_no','placeholder' => 'Batch No.','class' => 'form-control', 'data-validation'=>"required")) !!}
                    </div>

                    <div class="form-group">
                        <label class="form-control-label">Number Of Rolls<span class="text-danger ml-2">*</span></label>
                        {!! Form::number('number_of_rows', 1, array('id'=>'add_number_of_rolls', 'class' => 'form-control', 'data-validation'=>"required")) !!}
                    </div>
                    <div class="form-action float-right">
                        <button name="cancel_btn" id="cancel_btn" class="btn btn-primary">Cancel</button>
                        <button type="submit" id="save_continue" name="save_continue" class="btn btn-primary">Save & Continue</button>
                        <button type="submit" id="save_close" name="save_close" class="btn btn-primary">Save & Close</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<script type="text/template" id="templateAddItem">

    <td>{!! Form::select('material_id[]',$materials,null, array('class' => 'material form-control valid')) !!}</td>
    <td>{!! Form::select('color[]',[], null, array('class' => 'color form-control valid','id'=>'color')) !!}</td>
    <td>{!! Form::text('article_no[]', null, array('class' => 'article_no form-control valid', 'data-validation'=>"required",'readonly'=>'readonly')) !!}</td>
    <td>{!! Form::text('color_no[]', null, array('class' => 'color_no form-control valid','readonly'=>'readonly','id'=>'color_no')) !!}</td>
    <td>{!! Form::text('batch_no[]', null, array('class' => 'batch_no form-control valid', 'data-validation'=>"required")) !!}</td>
    <td>{!! Form::text('width[]', null, array('class' => 'width form-control valid','readonly'=>'readonly')) !!}</td>
    <td>{!! Form::text('roll_no[]', null, array('class' => 'roll_no form-control valid', 'data-validation'=>"required")) !!}</td>
    <td>{!! Form::text('meter[]', null, array('class' => 'meter meter_val form-control valid','id' => 'meter_val', 'data-validation'=>"required")) !!}</td>
    <td><input class="yard form-control" name='yard[]' type="text"></td>
    <td><button type="button" class="btn btn-danger btn-sm btn-square" id="delete_row">Delete</button></td>

</script>

<!-- End Row -->
@endsection
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
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.tablesorter/2.31.3/js/jquery.tablesorter.min.js" integrity="sha512-qzgd5cYSZcosqpzpn7zF2ZId8f/8CHmFKZ8j7mU4OUXTNRd5g+ZHBPsgKEwoqxCtdQvExE5LprwwPAgoicguNg==" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.tablesorter/2.31.3/js/parsers/parser-input-select.min.js" integrity="sha512-1yWDRolEDA6z68VeUHdXNFZhWYteCOlutcPMPuDtX1f7/doKecWLx87shPRKx8zmxdWA0FV9mNRUr9NnSwzwyw==" crossorigin="anonymous"></script>
<script src="{{ asset('assets/js/datepicker/moment.min.js') }}"></script>
<script src="{{ asset('assets/js/datepicker/daterangepicker.js') }}"></script>

<script type="text/javascript">

    (function($) {

        function calculateTransportationShippingCostPerMeter() {
            var totalMeter = parseFloat($('#total_meter').val()) || 0;
            var importTax = parseFloat($('#import_tax').val()) || 0;
            var transportationPaid = parseFloat($('#transport_shipping_paid').val()) || 0;

            // Calculate the transportation & shipping cost per meter
            var transportationShippingCostPerMeter = (importTax + transportationPaid) / totalMeter;

            // Update the displayed value
            $('#transport_shippment_cost_per_meter').val(transportationShippingCostPerMeter.toFixed(2));
        }

        // Trigger calculation when any relevant input field changes
        $('#total_meter, #import_tax, #transport_shipping_paid').on('input', calculateTransportationShippingCostPerMeter);

        $(document).on('input','.meter_val',function(){
            getTotalMeters();
        });

        $(document).on('click','#delete_row',function(){
            var row_id=$(this).data('row_id');
            $('#' + row_id).remove();
            getTotalMeters();
        });
        function convertMetersToYards() {
            var meters = $('#total_meter').val();
            if (meters) {
                // Perform conversion assuming 1 meter = 1.09361 yards
                var yards = meters * 1.09361;
                // Update the Total Yards field
                $('#total_yard').val(yards.toFixed(2)); // Adjust to display 2 decimal places
            } else {
                $('#total_yard').val(''); // If no input, clear the Total Yards field
            }
        }

        function getTotalMeters() {
            var totalMeter = 0;
            $("input[name='meter[]']").each(function(){
                totalMeter += $(this).val() ? parseFloat($(this).val()) : 0;
            });

            $('#total_meter').val(totalMeter);
            $('#total_meter').trigger('change');

            var yards = totalMeter * 1.09361;
            $('#total_yard').val(yards.toFixed(2));
            $('#total_yard').trigger('change');

            calculateTransportationShippingCostPerMeter();
        }

        $(document).ready(function() {

            $('#supplier_id').change(function() {
                var supplierId = $(this).val();
                if (supplierId) {
                    $.ajax({
                        url: '{{ route('get.suppliers') }}',
                        type: 'GET',
                        data: {
                            supplier_id: supplierId
                        },
                        success: function(data) {
                            console.log('AJAX Response:', data); // Debugging: Log the response to console

                            // Set purchase type dropdown value
                            $('#purchase_type').val(data.purchase_type);
                            $('#purchase_type').trigger('change');

                            // Set currency type dropdown value
                            $('#currency_of_purchase').val(data.currency_type).change();
                            $('#currency_of_purchase').trigger('change');
                        },
                        error: function(xhr, status, error) {
                            console.error('AJAX Error:', error); // Log any AJAX errors for debugging
                        }
                    });
                } else {
                    // If no supplier selected, clear purchase type and currency type fields
                    $('#purchase_type').val('').change();
                    $('#currency_of_purchase').val('').change();
                }
            });

            // Assuming you have a change event listener on #purchase_type dropdown
            $('#purchase_type').change(function() {
                var selectedPurchaseType = $(this).val();
                var exRateInput = $('#ex_rate');
                console.log('test'+selectedPurchaseType);

                if (selectedPurchaseType === 'domestic') {
                    // Set ex_rate value to 1 and disable the input
                    exRateInput.val('1');
                    // exRateInput.prop('disabled', true); // Optionally disable the input
                } else {
                    // Allow the user to manually input ex_rate
                    exRateInput.val(''); // Clear previous value if any
                    // exRateInput.prop('disabled', false); // Enable the input
                }
            });

            $('#total_meter').on('input', function() {
                var meters = $(this).val();
                if (meters) {
                    // Perform conversion assuming 1 meter = 1.09361 yards
                    var yards = meters * 1.09361;
                    // Update the Total Yards field
                    $('#total_yard').val(yards.toFixed(2)); // Adjust to display 2 decimal places
                } else {
                    $('#total_yard').val(''); // If no input, clear the Total Yards field
                }
            });
        });
        $('#thb_ex_rate, #price').keyup(function() {
            var price_thb = 0;
            var thb_ex_rate = parseFloat($('#thb_ex_rate').val());
            var price = parseFloat($('#price').val());
            if (!isNaN(thb_ex_rate) && !isNaN(price)) {
                price_thb = (price * thb_ex_rate).toFixed(2);
            }

            $('#price_thb').val(price_thb);
        });
        var last_row_data=null;
        $('#tblPurchaseItems').tablesorter({
            headers:{
                6 : {sorter: "inputs"},
                7 : {sorter: "inputs"},
                8 : {sorter: "inputs"}
            },

        });

        $('.material').select2();
        $(document).on('change','.material',function(){
            var material_name=$(this).val();
            var row_id=$(this).data('row_id');
            var data={!! json_encode($materials2) !!};
            $.each(data,function(index,value){
                if(material_name==value.name){
                    // $('#'+row_id).find('.color').val(value.color);
                    // $('#'+row_id).find('.color_no').val(String(value.color_no).padStart(2,"0"));
                    // $('#'+row_id).find('.article_no').val(value.article_no);
                    // $('#'+row_id).find('.width').val(value.width);
                    $('#'+row_id).find('.color_no').val('');
                    $('#'+row_id).find('.article_no').val('');
                    $('#'+row_id).find('.width').val('');
                    $('#'+row_id).find('.batch_no').val('');
                    $('#'+row_id).find('.roll_no').val('');
                }
            });
            var color_list="<option value=''>--Select Color--</option>";
            $.each(data,function(i,v){
                if(material_name==v.name){
                  var taxt = (v.color_no) ? v.color_no + ' - ' : '';
                  taxt += v.color;
                    color_list+="<option value='"+v.id+"'>"+taxt+"</option>";
                }
            });
            $('#'+row_id).find('.color').html(color_list);
        });
        $(document).on('change','.color',function(){
            var id=$(this).val();
            var row_id=$(this).data('row_id');
            var data={!! json_encode($materials2) !!};
            $.each(data,function(index,value){
                if(id==value.id){
                    // $('#'+row_id).find('.color').val(value.color);
                    $('#'+row_id).find('.color_no').val(String(value.color_no).padStart(2,"0"));
                    $('#'+row_id).find('.article_no').val(value.article_no);
                    $('#'+row_id).find('.width').val(value.width);
                }
            });
        });

        var add_selected_material_name=null;
        var add_color=[];
        $('#add_material_id').select2({
            dropdownParent: $('#add_item_form'),
            width: 'resolve',
        });
        $(document).on('change','#add_material_id',function(){
            var name=$(this).val();
            var data={!! json_encode($materials2) !!};
            $.each(data,function(index,value){
                if(name==value.name){
                    add_selected_material_name=value.name;
                    $('#add_article_no').val(value.article_no);
                    $('#add_width').val(value.width);
                    // $('#add_color_no').val(value.color_no);
                    $('#add_color_id').attr('disabled',false);
                }
            });
            add_color.length=0;
            $.each(data,function(i,v){
                if(add_selected_material_name==v.name){
                    var taxt = (v.color_no) ? v.color_no + ' - ' : '';
                    taxt += v.color;
                    add_color.push({'id':v.id,'text':taxt});
                }
            });
            $('#add_color_id').html('');
            $('#add_color_id').html('<option value="" selected="selected">--Select Color--</option>');
            $('#add_color_id').select2({dropdownParent: $('#add_item_form'),width: 'resolve',data:add_color});


        });
        $('#add_color_id').select2({
            dropdownParent: $('#add_item_form'),
            width: 'resolve',
        });
        $(document).on('change','#add_color_id',function(){
            var id=$(this).val();
            var data={!! json_encode($materials2) !!};
            $.each(data,function(index,value){
                if(id==value.id){
                    $('#add_color_no').val(String(value.color_no).padStart(2,"0"));
                    // $('#add_material_id').val(value.id);
                }
            });
        });
        $(document).on('click','#add_item_model_btn',function(){
            $('#add_purchase_price').val($('#price').val());
        });

        $.validate({
            form: '#from_add_material',
            modules: 'file'
        });

        var save_continue = false;
        $(document).on('click','#save_continue',function(){
            save_continue = true;
        });

        function resetLastRowData(){
            last_row_data = {
                meter: [],
                color_no: [],
                width: [],
                article_no: [],
                batch_no: [],
                roll_no: []
            };

            $('.meter_val').each(function(){
                last_row_data.meter.push($(this).val());
            });

            $('.color_no').each(function(){
                last_row_data.color_no.push($(this).val());
            });

            $('.width').each(function(){
                last_row_data.width.push($(this).val());
            });

            $('.article_no').each(function(){
                last_row_data.article_no.push($(this).val());
            });

            $('.batch_no').each(function(){
                last_row_data.batch_no.push($(this).val());
            });

            $('.roll_no').each(function(){
                last_row_data.roll_no.push($(this).val());
            });
        }

        $(document).on('click','#save_close',function(){
            save_continue = false;
        });
        $.validate({
            form: '#add_item_form',
            onSuccess: function($form) {
                last_row_data=$form;
                addItem($form);
                $($form).trigger("reset");
                $('#add_material_id').val('').trigger('change');
                $('#add_color_id').val('').trigger('change');
                $('#add_color_id').attr('disabled',true);
                if(!save_continue){
                    $('#addItemModal').modal('hide');
                }
                return false; // Will stop the submission of the form
            },
        });


        $(document).on('click','#cancel_btn',function(){
            $('#add_item_form').trigger("reset");
            $('#addItemModal').modal('hide');
            $('#add_color_id').attr('disabled',true);
            $('#add_color_id').val(['','--Select Color--']);
            return false;
        });
        $(document).on('click','#add_single_row',function(){
            $('#add_material_id', last_row_data).val($('#tblPurchaseItems tbody tr:last').find('.material').val());
            $('#add_color_id', last_row_data).html($('#tblPurchaseItems tbody tr:last').find('.color').html());
            $('#add_color_id', last_row_data).val($('#tblPurchaseItems tbody tr:last').find('.color option:selected').val());
            $('#add_article_no', last_row_data).val($('#tblPurchaseItems tbody tr:last').find('.article_no').val());
            $('#add_color_no', last_row_data).val($('#tblPurchaseItems tbody tr:last').find('.color_no').val());
            $('#add_batch_no', last_row_data).val($('#tblPurchaseItems tbody tr:last').find('.batch_no').val());
            $('#add_width', last_row_data).val($('#tblPurchaseItems tbody tr:last').find('.width').val());
            $('#add_number_of_rolls', last_row_data).val(1);
            var roll_no=parseInt($('#tblPurchaseItems tbody tr:last').find('.roll_no').val());
            $('#add_number_of_rolls',last_row_data).data("roll_no",(roll_no+1));

            addItem(last_row_data);

        });

        $('#genrate_code').on("click", function() {
            $.get('{{url("genrate_code")}}', function(data) {
                $("#input_barcode").val(data);
            });
        });

        $(document).on('keyup', '#tblPurchaseItems input.yard', function() {
            var yard = $(this).val();
            meter = parseFloat(yard);
            if (!isNaN(yard) && yard) {
                var $thisRow = $(this).closest('tr.purchaseItem');
                $('input.meter', $thisRow).val((yard/1.094).toFixed(2));
            }
        });
        $(document).on('keyup', '#tblPurchaseItems input.meter', function() {
            var meter = $(this).val();
            meter = parseFloat(meter);
            if (!isNaN(meter) && meter) {
                var $thisRow = $(this).closest('tr.purchaseItem');
                $('input.yard', $thisRow).val(meter2yard(meter));
            }
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

        $('#purchase_date').daterangepicker({
            singleDatePicker: true,
            showDropdowns: true,
            locale: {
                format: 'DD/MM/YYYY'
            }
        });

        //changes

        $(window).on('load',function(){
            check_currency();
        });
        $(document).on('change','#purchase_type',function(){
            var p_type = $(this).val();

            if (p_type == "domestic") {
                $('#thb_ex_rate').val('1');
            }else{
                $('#thb_ex_rate').val('');
            }
            check_currency();
        });
        $(document).on('change','#currency_of_purchase',function(){
            var price_type=$(this).val();
            $('#price').attr('placeholder',price_type);
        });
        $(document).on('change','#thb_ex_rate',function(){
           shipping_cost();
        });
        $(document).on('change','#total_meter',function(){
            var toalMT = $(this).val();
            shipping_cost();
        });
        $(document).on('change','#shipping_paid',function(){
           shipping_cost();
        });
        $(document).on('change','#transportation',function(){
           shipping_cost();
        });
        $(document).on('change','#tax_per',function(){
           shipping_cost();
        });
        $(document).on('change','#gross_tax',function(){
           shipping_cost();
        });
        $(document).on('change','#discount',function(){
           shipping_cost();
        });
        $(document).on('submit','#from_add_purchase',function(){
            var checkClass =  $('#tblPurchaseItems tbody tr').find('.valid');
            if (checkClass.length > 0) {
                return true;
            }else{
                new Noty({
                            type: 'warning',
                            text: 'Please select item first',
                            timeout: 2500,
                        }).show();
                return false;
            }
        });

    })(jQuery);

    function check_currency(){
        var purchase_type=$('#purchase_type').val();
        if(purchase_type=="domestic"){
            $('#currency_of_purchase').val('THB');
            $('#price').attr('placeholder','THB');
        }
        else{
            $('#currency_of_purchase').val('');
        }
    }
    function shipping_cost(){
        var price_thb = $('#price_thb').val()=='' ? 0 : parseFloat($('#price_thb').val());
        var shipping_paid = $('#shipping_paid').val()=='' ? 0 : parseFloat($('#shipping_paid').val());
        var transportation = $('#transportation').val()==''? 0 : parseFloat($('#transportation').val());
        var tax_per = $('#tax_per').val()=='' ? 0 : parseFloat($('#tax_per').val());
        var tax_thb = (price_thb*tax_per)/100;
        var gross_tax = $('#gross_tax').val()=='' ? 0 : parseFloat($('#gross_tax').val());
        var discount = $('#discount').val()=='' ? 0 : parseFloat($('#discount').val());
        var total_meter = $('#total_meter').val()=='' ? 0 : parseFloat($('#total_meter').val());

        var shipping_cost = ((shipping_paid + transportation + tax_thb + gross_tax) - discount) / total_meter;
        if(shipping_cost === Infinity){
            shipping_cost = 0;
        }
        $('#shipping_cost').val(shipping_cost.toFixed(2));

    }
    function addItem($form) {
        var roll_no=0;
        if($('#add_number_of_rolls',$form).data('roll_no')){
            roll_no=$('#add_number_of_rolls',$form).data('roll_no');
        }
        var material_name = $('#add_material_id ', $form).val();
        var material_id=0;
        if(roll_no){
            material_id= $('#add_color_id', $form).val();
            // console.log(material_id);
        }
        else{
            material_id = $('#add_color_id option:selected', $form).val();
        }
        // var color_id = $('#add_color_id option:selected', $form).text();
        var number_of_rolls = $('#add_number_of_rolls', $form).val();
        var article_no = $('#add_article_no', $form).val();
        var color_no = $('#add_color_no', $form).val();
        var batch_no = $('#add_batch_no', $form).val();
        var width = $('#add_width', $form).val();
        $template = $('#templateAddItem').html();
        for (i = 0; i < number_of_rolls; i++) {
            var $uniqueId = uuid();
            var $tr = $('<tr class="purchaseItem" id="' + $uniqueId + '">').append($template);
            $('#tblPurchaseItems tbody').append($tr);
            $('#' + $uniqueId).find('.material').val(material_name);
            $('#' + $uniqueId).find('.material').attr('data-row_id',$uniqueId);

            var color_list="";
            $.each({!! json_encode($materials2) !!},function(i,v){
                if(material_name==v.name){
                    color_list+="<option value='"+v.id+"'>"+v.color+"</option>";
                }
            });

            $('#' + $uniqueId).find('.color').html(color_list);
            $('#' + $uniqueId).find('.color').val(material_id);
            $('#' + $uniqueId).find('.color').attr('data-row_id',$uniqueId);
            $('#' + $uniqueId).find('.article_no').val(article_no);
            $('#' + $uniqueId).find('.color_no').val(String(color_no).padStart(2,"0"));
            $('#' + $uniqueId).find('.batch_no').val(batch_no);
            $('#' + $uniqueId).find('.width').val(width);
            if(roll_no){
                $('#' + $uniqueId).find('.roll_no').val(roll_no);
            }
            else{
                $('#' + $uniqueId).find('.roll_no').val(i + 1);
            }
            $('#' + $uniqueId).find('#delete_row').attr("data-row_id",$uniqueId);
        }
        if(($('.purchaseItem').length)!=0){
            $('#add_single_row').css('display','block');
        }
    }
</script>
@endpush
