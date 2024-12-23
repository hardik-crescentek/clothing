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
                                    <div class="input-group">
                                        {!! Form::select('supplier_id', $suppliers, null, ['id' => 'supplier_id','class' => 'form-control custom-select','data-validation' => "required"]) !!}
                                        <div class="input-group-append">
                                            <span class="input-group-text">
                                                <a href="{{ route('supplier.create', ['redirect' =>  base64_encode(route('purchase.create'))]) }}" title="Add Supplier">
                                                    <span><i class="fa fa-plus"></i></span>
                                                </a>
                                            </span>
                                        </div>
                                    </div>
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
                                    'data-validation' => 'required mime',
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
                    <div class="col-lg-3">
                        <div class="form-group">
                            <label class="form-control-label">Total No Of Rolls<span class="text-danger ml-2">*</span></label>
                            {!! Form::text('no_of_rolls', null, array('placeholder' => 'Total No Of Rolls','id'=>'no_of_rolls','class' => 'form-control','data-validation' => "required")) !!}
                        </div>
                    </div>
                    <div class="col-lg-3">
                        <div class="form-group">
                            <label class="form-control-label">Total No Of Bales Arrived<span class="text-danger ml-2">*</span></label>
                            {!! Form::text('no_of_bales', null, array('placeholder' => 'Total No Of Bales Arrived','class' => 'form-control','id'=>'no_of_bales','data-validation' => "required")) !!}
                        </div>
                    </div>
                    <div class="col-lg-3">
                        <div class="form-group">
                            <label class="form-control-label">WareHouse<span class="text-danger ml-2">*</span></label>
                            {!! Form::select('warehouse_id', [''=>' --Select WareHouse-- ']+$wareHouse, null, [
                                'id' => 'warehouse',
                                'class' => 'form-control custom-select',
                                'data-validation' => "required"
                            ]) !!}
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
                
                <hr class="mt-5;">
                    <div class="widget-container" style="background-color: #f8f9fa; border: 1px solid #dee2e6; padding: 15px;">
                        <div class="bordered no-actions d-flex align-items-center justify-content-between" id="toggle-form" style="cursor: pointer;">
                            <h4 class="mb-0">Add Article & Color</h4>
                            <button class="btn btn-outline-gray btn-sm" id="toggle-icon" style="font-size: 24px;">+</button>
                        </div>

                        <div id="additional-section" style="display: none; margin-top: 20px;margin-left:1%">
                            <div class="form-group row" id="form-rows">
                                <div class="col-md-4">
                                    <label for="article">Article:</label>
                                    <select class="form-control-label select2 article-select" name="articles[0][article]" data-article-id="1">
                                        <option value="">Select Article</option>
                                        <!-- Options will be dynamically populated -->
                                    </select>
                                    <input type="hidden" name="articles[0][article_id]" class="article-id" value="1">
                                </div>

                                <div class="col-md-4">
                                    <label for="color">Color:</label>
                                    <select class="form-control-label select2 color-select" name="articles[0][colors][]" multiple="multiple">
                                        <option value="">Select Color</option>
                                        <!-- Options will be dynamically populated -->
                                    </select>
                                </div>

                                <div class="col-md-2">
                                    <button type="button" id="add-row" class="btn btn-success mt-4">+</button>
                                </div>
                            </div>
                        </div>
                    </div>
                <hr>

                <div class="form-group row d-flex align-items-center mt-5">
                    <div class="col-lg-12 d-flex justify-content-center">
                        <button type="submit" class="btn btn-primary btn-lg" id="from_add_purchase_btn">Save</button>
                    </div>
                </div>
                {!! Form::close() !!}
            </div>

        </div>
    </div>
</div>
<!-- End Row -->
@endsection
@push('after-styles')
<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/css/select2.min.css" rel="stylesheet" />
<style>
    .selection{
        display:block !important;
    }
    .select2-container {
        width: 100% !important; /* Ensures Select2 takes the full width of its container */
    }
    
    /* Custom styles for select2 */
    .select2-container--default .select2-selection--multiple .select2-selection__choice {
        border: none !important;
    }
    .select2-container .select2-selection--multiple .select2-selection__rendered {
        display: ruby !important;
    }
    .select2-container--default .select2-search--inline .select2-search__field {
        width: 100% !important; /* Set full width */
        min-width: 100px; /* Ensure there's a minimum width for better display */
    }

    #additional-section {
        margin: 20px 0;
    }

    .highlighted-row {
        background-color: #e2f0d9; /* Light green background */
        border-radius: 5px;
        padding: 15px; /* Padding for the entire row */
        margin-bottom: 15px;
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1); /* Optional shadow for depth */
    }

    .highlighted-row label {
        font-weight: bold; /* Make labels bold for emphasis */
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

        // $('form').on('submit', function() {
        //     $('button[type="submit"]').prop('disabled', true).text('Submitting...');
        // });

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

        $(document).ready(function() {

            // Toggle the additional form section
            $('#toggle-form').on('click', function(event) {
                // Prevent form submission on button click
                event.preventDefault();

                // Toggle the visibility of the section
                $('#additional-section').toggle();
                
                // Toggle the + to - and vice versa
                var icon = $('#toggle-icon');
                icon.text(icon.text() === '+' ? '-' : '+');
            });

            // Initialize Select2
            function initializeSelect2() {
                $('.article-select').select2({
                    placeholder: "-- Select Article --",
                    allowClear: true
                });
                
                $('.color-select').select2({
                    placeholder: "-- Select Colors --",
                    allowClear: true,
                    tags: true
                });
            }

            // Get a list of all selected articles
            function getSelectedArticles() {
                var selectedArticles = [];
                $('.article-select').each(function() {
                    var article = $(this).val();
                    if (article) {
                        selectedArticles.push(article);
                    }
                });
                return selectedArticles;
            }

            // Filter the article options to remove already selected articles
            function filterArticles($articleSelect, data) {
                var selectedArticles = getSelectedArticles(); // Get all currently selected articles
                var selectedValue = $articleSelect.val(); // Get the currently selected value for the current row
                
                $articleSelect.empty();
                
                // Append default placeholder
                $articleSelect.append(new Option("-- Select Article --", "", true, false));

                // Append only the articles that haven't been selected yet, plus the currently selected one
                $.each(data, function(key, value) {
                    if (!selectedArticles.includes(key.toString()) || key.toString() === selectedValue) {
                        $articleSelect.append(new Option(value, key, false, key.toString() === selectedValue));
                    }
                });

                $articleSelect.trigger('change.select2'); // Trigger a safe select2 refresh
            }

            // Populate articles dropdown for the specified row
            function populateArticles(articleSelect) {
                $.ajax({
                    url: '{{ route('get.articles') }}',
                    type: 'GET',
                    success: function(data) {
                        var $articleSelect = $(articleSelect);
                        var $materialIdInput = $articleSelect.closest('.form-group.row').find('.material-id');

                        // Remove the event handler temporarily to avoid recursion
                        $articleSelect.off('change');
                        
                        filterArticles($articleSelect, data); // Filter articles before populating

                        // Rebind the change event after options are filtered
                        $articleSelect.on('change', function() {
                            var selectedId = $(this).val();
                            $materialIdInput.val(selectedId);
                            populateColors($articleSelect); 
                            
                            // Update article dropdowns in all rows to remove the selected article from other rows
                            $('.article-select').each(function() {
                                filterArticles($(this), data); // Reapply filtering for all article selects
                            });
                        });
                    },
                    error: function(xhr, status, error) {
                        console.error('Error fetching articles:', error);
                    }
                });
            }

            // Populate colors based on the selected article for the specified row
            function populateColors(articleSelect) {
                var article = $(articleSelect).val();
                var $colorSelect = $(articleSelect).closest('.form-group.row').find('.color-select');

                if (article) {
                    $.ajax({
                        url: '{{ route('get.colors') }}',
                        type: 'GET',
                        data: { article: article },
                        success: function(data) {
                            var allColorOptions = data.map(function(color) {
                                // return new Option(color.name, color.color_id, false, false);
                                return new Option(color.color_no + '-' + color.name , color.color_id, false, false);
                            });

                            var selectedValues = $colorSelect.val() || []; // Store current selected values
                            $colorSelect.empty(); // Clear existing options
                            $colorSelect.append(new Option('Select All Colors', 'all', false, false)); // Add 'Select All' option
                            $colorSelect.append(allColorOptions); // Add new options
                            $colorSelect.val(selectedValues).trigger('change'); // Reapply selected values
                        },
                        error: function(xhr, status, error) {
                            console.error('Error fetching colors:', error);
                        }
                    });
                } else {
                    $colorSelect.empty().trigger('change');
                }
            }

            // Handle selecting "Select All Colors" option
            $('#additional-section').on('change', '.color-select', function() {
                var $this = $(this);
                var selectedValue = $this.val();

                if (selectedValue && selectedValue.includes('all')) {
                    var allValues = $this.find('option').map(function() {
                        return $(this).val();
                    }).get().filter(value => value !== 'all'); // Exclude 'Select All' option
                    $this.val(allValues).trigger('change');
                }
            });

            // Add new row
            $('#add-row').on('click', function() {
                var index = $('#additional-section .form-group.row').length;
                var newRow = `
                    <div class="form-group row">
                        <div class="col-md-4">
                            <label for="article">Article:</label>
                            <select class="form-control-label select2 article-select" name="articles[${index}][article]">
                                <option>-- Select Article --</option>
                                <!-- Options will be dynamically populated -->
                            </select>
                            <input type="hidden" name="articles[${index}][material_id]" class="material-id">
                        </div>
                        
                        <div class="col-md-4">
                            <label for="color">Color:</label>
                            <select class="form-control-label select2 color-select" name="articles[${index}][colors][]" multiple="multiple">
                                <option>-- Select Color --</option>
                                <!-- Options will be dynamically populated -->
                            </select>
                        </div>
                        
                        <div class="col-md-4">
                            <button type="button" class="btn btn-danger remove-row mt-4">-</button>
                        </div>
                    </div>
                `;
                
                $('#additional-section').append(newRow);
                initializeSelect2(); // Initialize Select2 for the new elements
                
                // Populate articles only for the newly added row
                var newArticleSelect = $('#additional-section').find('.article-select').last();
                populateArticles(newArticleSelect); // Populate articles only for the new row
            });

            // Remove row
            $('#additional-section').on('click', '.remove-row', function() {
                $(this).closest('.form-group.row').remove();

                // Re-filter articles for all remaining rows to make the removed article available again
                $('.article-select').each(function() {
                    var $articleSelect = $(this);
                    populateArticles($articleSelect); // Repopulate articles
                });
            });

            // Initialize Select2 and populate articles on document ready
            initializeSelect2();
            populateArticles($('.article-select').first()); // Populate for the first row initially

            // sorting logic start
            var sortOrder = 1; // 1 for ascending, -1 for descending
            var $sortIcon = $('.sort-icon');

            var invoiceNumbers = @json($invoiceNumbers); // Convert PHP array to JavaScript array

            // Function to update the invoice number dropdown
            function updateInvoiceDropdown(currentInvoiceNo) {
                $('#add_invoice_no').empty(); // Clear existing options

                // Add the placeholder option
                $('#add_invoice_no').append('<option value="">--Select Invoice No.--</option>');

                // Add options for all invoice numbers
                $.each(invoiceNumbers, function(id, number) {
                    $('#add_invoice_no').append('<option value="' + id + '">' + number + '</option>');
                });

                // Add current invoice number if it exists and not already in the list
                if (currentInvoiceNo && !(currentInvoiceNo in invoiceNumbers)) {
                    $('#add_invoice_no').append('<option value="' + currentInvoiceNo + '">' + currentInvoiceNo + '</option>');
                }
            }
            
            // Handle form submission inside the modal
            $('#add_item_form').submit(function(event) {
                event.preventDefault();
            });

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
                    exRateInput.val('1');
                } else {
                    exRateInput.val(''); // Clear previous value if any
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

         // Custom parser for meter inputs
         $.tablesorter.addParser({
            id: 'meter-parser',
            is: function(s) {
                return false;
            },
            format: function(s, table, cell, cellIndex) {
                return parseFloat($(cell).find('input.meter').val()) || 0;
            },
            type: 'numeric'
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

        // $(window).on('load',function(){
        //     check_currency();
        // });
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

</script>
@endpush