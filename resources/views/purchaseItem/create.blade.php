@extends('layouts.master')
@section('title', 'Add Purchase Item')
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
                <h4>Add New Purchase Item</h4>
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


                {!! Form::open(array('route' => 'purchase-item.store','method'=>'POST','id'=>'from_add_purchase', 'class'=>"form-horizontal form-validate", 'novalidate','files' => true)) !!}
                <div class="row my-4">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-header d-flex align-items-center justify-content-between">
                                <div class="button-group">
                                    <h4 class="card-title">Purchase Items</h4>
                                    <a href="javascript:;" class="btn btn-primary p-2 btn-square btn-sm ml-2" id="add_item_model_btn" data-toggle="modal" data-target="#addItemModal">Add Item</a>
                                    <button type="button" class="btn btn-danger p-2 btn-square btn-sm ml-2" id="delete_selected">Delete</button>
                                </div>
                                <div class="invoice-dropdown-container d-flex align-items-center">
                                    <label for="search_invoice_no" class="mb-0 mr-2">Invoice No<span class="text-danger ml-2">*</span>:</label>
                                    <select id="search_invoice_no" class="form-control">
                                        <option value="">-- Select Invoice No --</option>
                                        @foreach ($invoiceNumbers as $invoiceNo => $createdDate)
                                            <option value="{{ $invoiceNo }}">{{ $invoiceNo }} ({{ $createdDate }})</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive mt-3">
                                    <table id="tblPurchaseItems" class="table table-hover order-list">
                                        <thead>
                                            <tr>
                                                <th style="width:3%;"><input type="checkbox" id="select_all"></th>
                                                <th style="width:7%;">Brand</th>
                                                <th style="width:8%;">Article No</th>
                                                <th style="width:7%;">Color</th>
                                                <th style="width:8%;">Color No</th>
                                                <th style="width:8%;">Batch/Lot No</th>
                                                <th style="width:6%;">Width(cm)</th>
                                                <th style="width:7%;">Meter<i class="fas fa-sort sort-icon ml-1"></i></th>
                                                <th style="width:7%;">Yard<i class="fas fa-sort sort-icon ml-1"></th>
                                                <th style="width:7%;">Roll No</th>
                                                <th style="width:14%;">Piece</th>
                                                <th style="width:9%;">Cost Per Mtr</th>
                                                <th style="width:9%;">Cost Per Yrd</th>
                                                <th style="width: 5%;"></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
                <div class="form-group row d-flex align-items-center mt-5">
                    <div class="col-lg-12 d-flex justify-content-center">
                        <button type="button" class="btn btn-primary btn-lg mr-2" id="generate_roll_piece">Generate Roll & Piece No.</button>
                        <button type="submit" class="btn btn-primary btn-lg" id="from_add_purchase_btn">Save</button>
                    </div>
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
                        <label class="form-control-label">Invoice No.<span class="text-danger ml-2">*</span></label>
                        {!! Form::select('invoice_no', array_combine(array_keys($invoiceNumbers), array_keys($invoiceNumbers)), null, [
                            'id' => 'add_invoice_no',
                            'class' => 'form-control custom-select',
                            'placeholder' => '-- Select Invoice No --',
                            'data-validation' => 'required',
                            'style' => 'width:100%'
                        ]) !!}
                    </div>
                    <input type="hidden" id="purchase_id" name="purchase_id">
                    <input type="hidden" id="purchase_ex_rate" name="purchase_ex_rate" class="purchase_ex_rate">
                    <input type="hidden" id="purchase_total_no_of_rolls" name="purchase_total_no_of_rolls" class="purchase_total_no_of_rolls">
                    <input type="hidden" id="purchase_transport_shippment_cost_per_meter" name="purchase_transport_shippment_cost_per_meter" class="purchase_transport_shippment_cost_per_meter">
                    <input type="hidden" id="material_color_no" name="material_color_no" class="material_color_no">
                    <div class="form-group">
                        <label class="form-control-label">Article No.<span class="text-danger ml-2">*</span></label>
                        {!! Form::select('article_no',$articleNumbers,null, array('id'=>'add_article_no','class' => 'form-control custom-select ',"placeholder"=>"--Select Article No.--", 'data-validation'=>"required",'style'=>"width:100%")) !!}
                        {!! Form::hidden('width', null, array('class' => 'add_width form-control','id'=>"add_width")) !!}
                    </div>
                    <div class="form-group">
                        <label class="form-control-label">Color<span class="text-danger ml-2">*</span></label>
                        {!! Form::select('color_id', $colorMaterial, null, [
                            'id' => 'add_color_id',
                            'class' => 'form-control custom-select',
                            'placeholder' => '--Select Color--',
                            'data-validation' => 'required',
                            'style' => 'width:100%'
                        ]) !!}
                    </div>
                    <div class="form-group">
                        <label class="form-control-label">Batch / Lot No.<span class="text-danger ml-2">*</span></label>
                        {!! Form::text('batch_no', null, array('id'=>'add_batch_no','placeholder' => 'Batch No.','class' => 'form-control', 'data-validation'=>"required",'style'=>"width:100%")) !!}
                    </div>

                    <div class="form-group">
                        <label class="form-control-label">Number Of Rolls<span class="text-danger ml-2">*</span></label>
                        {!! Form::number('number_of_rows', 1, array('id'=>'add_number_of_rolls', 'class' => 'form-control', 'data-validation'=>"required",'style'=>"width:100%")) !!}
                    </div>
                    <div class="form-action d-flex justify-content-center">
                        <button name="cancel_btn" id="cancel_btn" class="btn btn-primary">Cancel</button>
                        <button type="submit" id="save_close" name="save_close" class="btn btn-primary">Save & Close</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<script type="text/template" id="templateAddItem">

    <td><input type="checkbox" class="row_checkbox"></td>                                    
    <td>{!! Form::text('brand[]',null, array('class' => 'brand form-control valid','data-validation'=>"required",'readonly'=>'readonly')) !!}</td>
    <td>{!! Form::text('article_no[]', null, array('class' => 'article_no form-control valid', 'data-validation'=>"required",'readonly'=>'readonly')) !!}</td>
    <td>{!! Form::text('color[]', null, array('class' => 'color_name form-control valid','id'=>'add_color_id','readonly'=>'readonly')) !!}</td>
    <td>{!! Form::text('color_no[]', null, array('class' => 'color_no form-control valid','readonly'=>'readonly','id'=>'color_no')) !!}</td>
    <td>{!! Form::text('batch_no[]', null, array('class' => 'batch_no form-control valid', 'data-validation'=>"required")) !!}</td>
    <td>{!! Form::text('width[]', null, array('class' => 'width form-control valid','readonly'=>'readonly')) !!}</td>
    <td>{!! Form::text('meter[]', null, array('class' => 'meter meter_val form-control valid','id' => 'meter_val', 'data-validation'=>"required")) !!}</td>
    <td>{!! Form::text('yard[]', null, array('class' => 'yard yard_val form-control valid','id' => 'yard_val', 'data-validation'=>"required")) !!}</td>
    <td>{!! Form::text('roll_no[]', null, array('class' => 'roll_no form-control valid', 'data-validation'=>"required")) !!}</td>
    <td>{!! Form::text('piece_no[]', null, array('class' => 'piece_no form-control valid', 'readonly'=>'readonly')) !!}</td>
    <td>{!! Form::text('cost_per_mtr[]', null, array('class' => 'cost_per_mtr form-control valid', 'readonly'=>'readonly')) !!}</td>
    <td>{!! Form::text('cost_per_yrd[]', null, array('class' => 'cost_per_yrd form-control valid', 'readonly'=>'readonly')) !!}</td>
    {!! Form::hidden('price[]', null, array('class' => 'price form-control valid','readonly'=>'readonly')) !!}
    {!! Form::hidden('add_invoice_no[]', null, array('class' => 'invoice_no_hidden hidden')) !!}
    {!! Form::hidden('purchase_id[]', null, array('class' => 'purchase_id hidden')) !!}
    {!! Form::hidden('purchase_ex_rate[]', null, array('class' => 'purchase_ex_rate hidden')) !!}
    {!! Form::hidden('purchase_total_no_of_rolls[]', null, array('class' => 'purchase_total_no_of_rolls hidden')) !!}
    {!! Form::hidden('purchase_transport_shippment_cost_per_meter[]', null, array('class' => 'purchase_transport_shippment_cost_per_meter hidden')) !!}
    <td><button type="button" class="btn btn-secondary copy_row_btn">+</button></td>

</script>

<h3>Purchase Items</h3>
<table id="purchase_items_table" class="table table-bordered">
    <thead>
        <tr>
            <th>Invoice No</th>
            <th>Article No</th>
            <th>Color</th>
            <th>Color No</th>
            <th>Batch No</th>
            <th>Roll No</th>
            <th>Width</th>
            <th>Meter</th>
            <th>Yard</th>
            <th>Cost Per Mtr</th>
            <th>Cost Per Yrd</th>
            <th>Piece</th>
            <th>Barcode</th>
            <th>Print</th>
        </tr>
    </thead>
    <tbody>
        <!-- Purchase items will be appended here -->
    </tbody>
</table>

<!-- Edit Purchase Master Modal Structure -->
<div class="modal fade" id="purchaseEditModal" tabindex="-1" role="dialog" aria-labelledby="modalTitle" aria-hidden="true">
  <div class="modal-dialog modal-sm" role="document"> <!-- Added modal-sm for a smaller modal -->
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="modalTitle">Update Missing Fields</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <input type="hidden" id="purchaseItem" name="purchaseItem" value="purchaseItem">
        <p>It seems that some require fields value are missing. Please update the purchase details.</p>
        <a href="#" id="modalEditLink" class="btn btn-primary btn-block">Edit Purchase</a>
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
    .card-header {
        display: flex;
        align-items: center;
        justify-content: space-between; /* Align items to the start and end */
    }

    .button-group {
        display: flex;
        align-items: center;
    }

    .dropdown-container {
        display: flex;
        flex-direction: column;
        align-items: flex-end;
        margin-left: 20px; /* Space between the buttons and dropdown */
        min-width: 200px; /* Ensure enough space for the dropdown */
    }

    .form-control {
        width: 100%; /* Full width of the container */
    }

    .hidden {
        display: none;
    }

    /* Limit the modal height */
    #purchaseEditModal .modal-content {
        max-height: 400px;  /* Set your desired max-height */
        overflow-y: auto;   /* Make the content scrollable if it exceeds the max-height */
    }

    .select2-results__option {
        padding: 5px 10px; /* Add padding for better visibility */
    }

    .saved-color {
        background-color: red !important; /* Fallback if needed */
        color: white !important; /* Ensure text is visible */
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

        var currentPurchaseId = null;

        // Function to get purchase ID based on selected invoice number
        function fetchPurchaseId(invoiceNo, callback) {
            $.ajax({
                url: '{{ url("get_purchase_id") }}', // Adjust URL as needed
                type: 'GET',
                data: { invoice_no: invoiceNo },
                success: function(response) {
                    if (response.success) {
                        currentPurchaseId = response.purchase_id;
                        $('#purchase_id').val(currentPurchaseId); // Set hidden field value
                        if (callback) callback();
                    } else {
                        alert('Failed to retrieve purchase ID.');
                    }
                },
                error: function() {
                    alert('Error occurred while fetching purchase ID.');
                }
            });
        }

        // On change of invoice number dropdown
        $('#add_invoice_no').on('change', function() {
            var invoiceNo = $(this).val();
            fetchPurchaseId(invoiceNo);
        });

        $('#search_invoice_no').change(function() {
            var invoiceNo = $(this).val();

            if (invoiceNo) {
                $.ajax({
                    url: '/purchase-by-invoice',
                    type: 'GET',
                    data: {
                        invoice_no: invoiceNo
                    },
                    success: function(response) {
                        if (response.success) {
                            // Use the ex_rate and no_of_rolls from the response
                            var exRate = response.data.ex_rate;
                            var total_no_of_rolls = response.data.total_no_of_rolls;
                            var transport_shippment_cost_per_meter = response.data.transport_shippment_cost_per_meter;

                            // Populate your form fields or variables
                            console.log("exRate"+exRate);
                            $('#purchase_ex_rate').val(exRate);
                            $('#purchase_total_no_of_rolls').val(total_no_of_rolls);
                            $('#purchase_transport_shippment_cost_per_meter').val(transport_shippment_cost_per_meter);
                        }
                    },
                    error: function() {
                        alert('An error occurred while fetching purchase details.');
                    }
                });
            }
        });

        $(document).on('keyup', '#tblPurchaseItems input.yard', function() {
            var yard = $(this).val();
            yard = parseFloat(yard);
            if (!isNaN(yard) && yard) {
                var $thisRow = $(this).closest('tr.purchaseItem');
                $('input.meter', $thisRow).val((yard / 1.09361).toFixed(2));
                // Trigger recalculation of totals
            }
        });

        // Select all checkboxes functionality
        $(document).on('change', '#select_all', function() {
            $('.row_checkbox').prop('checked', $(this).prop('checked'));
        });

        // Delete selected items functionality
        $(document).on('click', '#delete_selected', function() {
            var selected = [];
            $('.row_checkbox:checked').each(function() {
                selected.push($(this).closest('tr'));
            });

            if (selected.length === 0) {
                alert("Please select at least one item to delete.");
            } else {
                if (confirm("Are you sure you want to delete selected items?")) {
                    selected.forEach(function(item) {
                        item.remove();
                    });

                    // Uncheck the "Select All" checkbox after deletion
                    $('#select_all').prop('checked', false);

                    // Check if rows exist and enable/disable the Add Item button after deletion
                    checkRowsAndToggleAddButton();
                }
            }
        });

        // Check if rows exist and enable/disable the Add Item button accordingly
        function checkRowsAndToggleAddButton() {
            if ($('.row_checkbox').length === 0) {
                enableAddItemButton(); // Enable if no rows exist
            } else {
                disableAddItemButton(); // Disable if rows exist
            }
        }

        $(document).ready(function() {

            var savedColors = {}; // Store saved colors

            $('#add_invoice_no').change(function() {
                var invoiceNo = $(this).val();

                if (invoiceNo) {
                    $.ajax({
                        url: '/get-articles-by-invoice', // Adjust the URL if necessary
                        type: 'GET',
                        data: { invoice_no: invoiceNo },
                        success: function(response) {
                            if (response.success) {
                                // Populate the article dropdown
                                var articleSelect = $('#add_article_no');
                                articleSelect.empty();
                                articleSelect.append('<option value="">--Select Article No.--</option>');
                                $.each(response.articles, function(id, article) {
                                    articleSelect.append('<option value="' + id + '">' + article.name + '</option>');
                                });

                                // When an article is selected, update the color dropdown
                                articleSelect.change(function() {
                                    var selectedArticleId = $(this).val();
                                    var colorSelect = $('#add_color_id');
                                    colorSelect.empty();
                                    colorSelect.append('<option value="">--Select Color--</option>');
                                    savedColors = {}; // Reset saved colors

                                    // Check if there are colors for the selected article
                                    if (response.articleColors[selectedArticleId]) {
                                        console.log("response" + JSON.stringify(response.articleColors));
                                        
                                        $.each(response.articleColors[selectedArticleId], function(id, color) {
                                            console.log("color" + JSON.stringify(color));
                                            
                                            // Create an <option> element
                                            var option = $('<option></option>')
                                                .val(id)
                                                .text(color.color)
                                                .attr('data-color-no', color.color_no);
                                            
                                            // Store saved colors in the object
                                            if (color.saved) {
                                                savedColors[id] = true; // Save the id of the saved color
                                                option.css('background-color', 'red'); // Highlight saved colors
                                            }
                                            
                                            // Append the option to the select element
                                            colorSelect.append(option);
                                        });
                                    }

                                    // Handle color selection
                                    colorSelect.change(function() {
                                        var selectedColorId = $(this).val();
                                        if (savedColors[selectedColorId]) {
                                            alert('This color is already saved.');
                                            
                                            // Reset and repopulate the color dropdown
                                            colorSelect.empty();
                                            colorSelect.append('<option value="">--Select Color--</option>');

                                            // Repopulate colors
                                            $.each(response.articleColors[selectedArticleId], function(id, color) {
                                                var option = $('<option></option>')
                                                    .val(id)
                                                    .text(color.color)
                                                    .attr('data-color-no', color.color_no);
                                                
                                                // Highlight saved colors
                                                if (color.saved) {
                                                    option.css('background-color', 'red');
                                                }
                                                
                                                colorSelect.append(option);
                                            });
                                        }
                                    });
                                });
                            }
                        },
                        error: function() {
                            alert('An error occurred while fetching purchase details.');
                        }
                    });
                }
            });

            // Add More button click event
            $(document).on('click', '.copy_row_btn', function() {
                var row = $(this).closest('tr'); // Get the closest row to the clicked button
                var newRow = row.clone(true); // Clone the existing row

                $('#tblPurchaseItems tbody').append(newRow); // Append the cloned row to the table body

                row.find('select').each(function(i) {
                    newRow.find('select').eq(i).val($(this).val())
                })
            });
            //end addmore code

            $('#add_item_model_btn').click(function(event) {
                var invoiceNo = $('#search_invoice_no').val();
                
                if (!invoiceNo) {
                    // Prevent the default action (which is opening the modal)
                    event.preventDefault();
                    event.stopImmediatePropagation();
                    
                    // Display the alert message
                    alert('Please select an Invoice No before adding an item.');
                } else {
                    // Show the modal and set the invoice number
                    $('#addItemModal').modal('show'); // Show the modal immediately

                   // Set the value and disable the field when modal is shown
                    $('#addItemModal').on('shown.bs.modal', function () {
                        console.log("invoiceNo"+ invoiceNo);
                        $('#add_invoice_no').val(invoiceNo).prop('disabled', true);

                        // Force the select element to re-initialize (if using Select2)
                        $('#add_invoice_no').trigger('change');
                    });
                }
            });

            // Add the click event listener to the button
            $('#generate_roll_piece').click(function() {
                generateRollAndPieceNumbers();
            });

            // sorting logic start
            var sortOrder = 1; // 1 for ascending, -1 for descending
            var $sortIcon = $('.sort-icon');

            // Handle click event on sort icon
            $sortIcon.on('click', function() {
                sortOrder *= -1; // Toggle sort order

                // Update sort icon based on sort order
                if (sortOrder === 1) {
                    $sortIcon.removeClass('fa-sort-down').addClass('fa-sort-up');
                } else {
                    $sortIcon.removeClass('fa-sort-up').addClass('fa-sort-down');
                }

                // Sort table rows based on meter value
                var rows = $('#tblPurchaseItems tbody > tr').get();
                rows.sort(function(rowA, rowB) {
                    var meterA = parseFloat($(rowA).find('.meter').val()) || 0;
                    var meterB = parseFloat($(rowB).find('.meter').val()) || 0;
                    return sortOrder * (meterA - meterB);
                });

                // Reorder the table rows
                $.each(rows, function(index, row) {
                    $('#tblPurchaseItems').children('tbody').append(row);
                });
            });
            // sorting logic end

            var invoiceNumbers = @json($invoiceNumbers); // Convert PHP array to JavaScript array
            
            // Handle form submission inside the modal
            $('#add_item_form').submit(function(event) {
                event.preventDefault();
                // Your logic to handle form submission
                // Example: AJAX call to submit form data
            });

            // based on invoice no display all purchase item
            $('#search_invoice_no').select2();

            function meter2yard(meters) {
                return meters * 1.09361; // Conversion factor from meters to yards
            }

            var table = $('#purchase_items_table').DataTable({
                "lengthMenu": [
                    [10, 25, 50, 100, 500, 1000],
                    [10, 25, 50, 100, 500, 1000],
                ],
                "aaSorting": []
            });

            $('#search_invoice_no').change(function() {
                var invoice_no = $(this).val();

                // Clear existing table data
                table.clear().draw();

                if (invoice_no) {
                    $.ajax({
                        url: '{{ route("purchase.items.by.invoice") }}',
                        method: 'GET',
                        data: { invoice_no: invoice_no },
                        success: function(response) {
                            if (response.success) {
                                var purchaseItems = response.data.purchase_items;
                                var invoiceNumber = response.data.invoice_no; 
                                $.each(purchaseItems, function(index, item) {
                                    table.row.add([
                                        invoiceNumber,
                                        item.article_no,
                                        item.color,
                                        item.color_no,
                                        item.batch_no,
                                        item.roll_no,
                                        item.width,
                                        '<td class="td-qty td_meter_count valid" data-value="' + item.qty + '">' + item.qty + '</td>',
                                        '<td>' + number_format(meter2yard(item.qty), 2, '.', '') + '</td>',
                                        parseFloat(item.cost_per_mtr).toFixed(2),
                                        parseFloat(item.cost_per_yrd).toFixed(2),
                                        item.piece_no,
                                        '<td>' + item.barcode_svg + '</td>',
                                        '<td><a class="btn fa fa-print btn-sm btn-info" data-toggle="tooltip" data-placement="top" title="Print" href="' + item.print_url + '"></a></td>'
                                    ]).draw(false);
                                });
                            }
                        }
                    });
                }
            });

            // Function to format numbers
            function number_format(number, decimals, decPoint, thousandsSep) {
                number = number.toFixed(decimals);

                var nstr = number.toString();
                nstr += '';
                var x = nstr.split('.');
                var x1 = x[0];
                var x2 = x.length > 1 ? decPoint + x[1] : '';
                var rgx = /(\d+)(\d{3})/;

                while (rgx.test(x1)) {
                    x1 = x1.replace(rgx, '$1' + thousandsSep + '$2');
                }

                return x1 + x2;
            }
            // end code
        });

        function generateRollAndPieceNumbers() {
            // Get all rows of purchase items
            const $rows = $('#tblPurchaseItems tbody tr');
            
            // Update the roll numbers and piece numbers
            $rows.each((index, row) => {
                const $row = $(row);
                console.log('row =>'+ $row.html());
                const price = parseFloat($row.find('.price').val()) || 0;
                const rollNo = index + 1;
                const articleNo = $row.find('.article_no').val();
                const colorNo = $row.find('.color_no').val() || '';
                const invoiceNo = $row.find('.invoice_no_hidden').val() || '';
                const totalRolls = $rows.length;
                const purchase_id = $row.find('.purchase_id').val();
                const purchase_ex_rate =  parseFloat($row.find('.purchase_ex_rate').val()) || 0;;
                const purchase_total_no_of_rolls = parseFloat($row.find('.purchase_total_no_of_rolls').val()) || 0;
                const purchase_transport_shippment_cost_per_meter = parseFloat($row.find('.purchase_transport_shippment_cost_per_meter').val()) || 0;

                // Check if purchase_ex_rate or purchase_transport_shippment_cost_per_meter is empty
                console.log("check");
                if (!purchase_ex_rate || !purchase_transport_shippment_cost_per_meter) {
                    // Open modal and provide a link to edit the purchase material
                    const editLink = `/purchase/${purchase_id}/edit`;  // Assuming the invoiceNo is a unique identifier for the purchase material

                    // Populate modal with the edit link and show the modal
                    $('#modalEditLink').attr('href', editLink);
                    $('#purchaseEditModal').modal('show');

                    // Exit the loop for this row (skip the rest of the code)
                    return;
                }
                console.log("els eiff");
                
                // Get today's date in the format 'dd/mm/yyyy'
                const today = new Date();
                const day = String(today.getDate()).padStart(2, '0');
                const month = String(today.getMonth() + 1).padStart(2, '0'); // Months are zero-based
                const year = today.getFullYear();
                const dateOfPurchase = `${day}/${month}/${year}`;
                
                const pieceValue = `${articleNo}_${colorNo}_${invoiceNo}_${dateOfPurchase}_${rollNo}_${totalRolls}`;

                console.log('price '+price);
                console.log('purchase_ex_rate '+purchase_ex_rate);
                console.log('purchase_transport_shippment_cost_per_meter '+purchase_transport_shippment_cost_per_meter);

                const cost_per_mtr = parseFloat((price * purchase_ex_rate) + purchase_transport_shippment_cost_per_meter).toFixed(3);
                const cost_per_yrd = parseFloat(((price * purchase_ex_rate) + purchase_transport_shippment_cost_per_meter) * 0.9144).toFixed(3);
                
                $row.find('.roll_no').val(rollNo).attr('title', `Roll No: ${rollNo}`);
                $row.find('.piece_no').val(pieceValue).attr('title', `Piece No: ${pieceValue}`);
                $row.find('.cost_per_mtr').val(cost_per_mtr).attr('title', `Cost per Meter: ${cost_per_mtr}`);
                $row.find('.cost_per_yrd').val(cost_per_yrd).attr('title', `Cost per Yard: ${cost_per_yrd}`);
            });
        }

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

        // Initialize tablesorter with sorting icons
        $('#tblPurchaseItems').tablesorter({
            headers: {
                7: { sorter: 'meter-parser' }, // Assuming meter column is the 8th column (index 7)
                8: { sorter: 'digit' }   // Assuming yard column is the 9th column (index 8)
            },
            widgets: ['zebra', 'columns'],
            widgetOptions: {
                columns_zebra: true,
                columns: ['primary', 'secondary', 'tertiary']
            }
        });

        $('#tblPurchaseItems th.header').click(function() {
            var column = $(this).index();
            var direction = $(this).hasClass('headerSortUp') ? 'desc' : 'asc';

            // Remove existing sort icons
            $(this).find('.sort-icon').remove();

            // Add new sort icon
            if (direction === 'asc') {
                $(this).append('<span class="sort-icon asc"></span>');
            } else {
                $(this).append('<span class="sort-icon desc"></span>');
            }
        });


        $('.material').select2();
        $(document).on('change','.material',function(){
            var material_name=$(this).val();
            var row_id=$(this).data('row_id');
            var data={!! json_encode($materials2) !!};
            $.each(data,function(index,value){
                if(material_name==value.name){
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

        // Reset the form values when the modal is closed
        $('#addItemModal').on('hidden.bs.modal', function () {
            $('#add_article_no').val('').trigger('change');
            // $('#add_color_id').val('').trigger('change');
            // $('#add_color_id').html("<option value=''>--Select Color--</option>");
            // Reset other input fields if necessary
        });

        $(document).on('change','.color',function(){
            var id=$(this).val();
            var row_id=$(this).data('row_id');
            var data={!! json_encode($materials2) !!};
            $.each(data,function(index,value){
                if(id==value.id){
                    $('#'+row_id).find('.color_no').val(String(value.color_no).padStart(2,"0"));
                    $('#'+row_id).find('.article_no').val(value.article_no);
                    $('#'+row_id).find('.width').val(value.width);
                }
            });
        });

        var add_selected_material_name=null;
        var add_color=[];
        $('#add_invoice_no').select2({
            dropdownParent: $('#add_item_form'),
            width: 'resolve',
        });
        $('#add_article_no').select2({
            dropdownParent: $('#add_item_form'),
            width: 'resolve',
        });
        $('#add_color_id').select2({
            dropdownParent: $('#add_item_form'),
            width: 'resolve',
        });

        // Function to check if a combination already exists in the table
        function isDuplicateItem(invoice_no, article_no, colorName) {
            let isDuplicate = false;

            $('#purchase_items_table tbody tr').each(function() {
                const existingInvoiceNo = $(this).find('td').eq(0).text().trim(); // Invoice No is in the first column
                const existingArticleNo = $(this).find('td').eq(1).text().trim(); // Article No is in the second column
                const existingColor = $(this).find('td').eq(2).text().trim(); // Color is in the third column

                // Compare the values
                if (existingInvoiceNo === invoice_no && existingArticleNo === article_no && existingColor === colorName) {
                    console.log('matched');
                    isDuplicate = true;
                    return false; // Break out of each loop
                }
            });

            return isDuplicate;
        }

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
                // $('#add_color_id').val('').trigger('change');
                if(!save_continue){
                    $('#addItemModal').modal('hide');
                }
                return false; // Will stop the submission of the form
            },
        });


        $(document).on('click','#cancel_btn',function(){
            $('#add_item_form').trigger("reset");
            $('#addItemModal').modal('hide');
            // $('#add_color_id').attr('disabled',true);
            // $('#add_color_id').val(['','--Select Color--']);
            return false;
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

        //changes
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

    // Function to check if the item already exists in the table
    function isDuplicateItem(invoice_no, article_no, color_id) {
        let isDuplicate = false;
        
        $('#tblPurchaseItems tbody .purchaseItem').each(function() {
            const existingInvoiceNo = $(this).find('.invoice_no_hidden').val();
            const existingArticleNo = $(this).find('.article_no').val();
            const existingColorId = $(this).find('.color').val();
            
            if (existingInvoiceNo === invoice_no && existingArticleNo === article_no && existingColorId === color_id) {
                isDuplicate = true;
                return false; // Break out of each loop
            }
        });
        
        return isDuplicate;
    }

    function addItem($form) {
        // Extract data from the form
        var roll_no = $('#add_number_of_rolls', $form).data('roll_no') || 0;
        // Retrieve the selected option
        var selectedOption = $('#add_color_id option:selected');
        console.log("Selected text: " + selectedOption.text());

        // Extract the color number from data-color-no attribute
        var colorNo = selectedOption.data('color-no');
        console.log("Selected colorNo: " + colorNo);
        $('#material_color_no').val(colorNo);

        var color_id = $('#add_color_id', $form).val() || 0;
        var color_name = $('#add_color_id option:selected', $form).text();
        var color_no = $('#material_color_no', $form).val() || '';


        var number_of_rolls = $('#add_number_of_rolls', $form).val();
        var article_no = $('#add_article_no option:selected', $form).text();
        var invoice_no = $('#add_invoice_no', $form).val();
        var batch_no = $('#add_batch_no', $form).val();
        var date_of_purchase = $('#purchase_date').val();
        var purchase_id = $('#purchase_id', $form).val();
        var purchase_ex_rate = $('#purchase_ex_rate', $form).val();
        var purchase_total_no_of_rolls = $('#purchase_total_no_of_rolls', $form).val();
        var purchase_transport_shippment_cost_per_meter = $('#purchase_transport_shippment_cost_per_meter', $form).val();

        // Check for duplicates
        if (isDuplicateItem(invoice_no, article_no, color_id)) {
            alert('Item with the same Invoice No, Article No, and Color already exists.');
            return; // Exit function if duplicate found
        }

        var $template = $('#templateAddItem').html();
        
        // Loop for adding multiple rows if necessary
        for (var i = 0; i < number_of_rolls; i++) {
            var roll_no = i + 1;
            var $uniqueId = uuid(); // Generate a unique ID for each row
            var $tr = $('<tr class="purchaseItem" id="' + $uniqueId + '">').append($template);
            $('#tblPurchaseItems tbody').append($tr); // Add the new row to the table

            // var color_list = "";
            var unit_purchased_in = '';
            
            // Loop through materials and create color options dynamically
            $.each({!! json_encode($materials2) !!}, function(i, v) {
                if (article_no == v.article_no) {
                    // color_list += "<option value='" + v.id + "' data-color-no='" + v.color_no + "'>" + v.color + "</option>";
                    $('#' + $uniqueId).find('.width').val(v.width_cm).attr('title', `Width: ${v.width_cm}`);
                    $('#' + $uniqueId).find('.brand').val(v.name).attr('title', `Brand: ${v.name}`);
                    $('#' + $uniqueId).find('.price').val(v.price);
                    unit_purchased_in = v.unit_purchased_in;
                }
            });

            // Update other fields for the row
            $('#' + $uniqueId).find('.article_no').val(article_no).attr('title', `Article No: ${article_no}`);
            $('#' + $uniqueId).find('.color_name').val(color_name).attr('title', `Color: ${color_name}`);
            $('#' + $uniqueId).find('.batch_no').val(batch_no).attr('title', `Batch No.: ${batch_no}`).attr('readonly', true);
            $('#' + $uniqueId).find('#delete_row').attr("data-row_id", $uniqueId);
            $('#' + $uniqueId).find('.invoice_no_hidden').val(invoice_no);  // Set the hidden invoice number
            $('#' + $uniqueId).find('.color_no').val(color_no).attr('title', `Color No.: ${color_no}`);
            $('#' + $uniqueId).find('.purchase_id').val(purchase_id);
            $('#' + $uniqueId).find('.purchase_ex_rate').val(purchase_ex_rate);
            $('#' + $uniqueId).find('.purchase_total_no_of_rolls').val(purchase_total_no_of_rolls);
            $('#' + $uniqueId).find('.purchase_transport_shippment_cost_per_meter').val(purchase_transport_shippment_cost_per_meter);

            // Enable or disable meter/yard fields based on unit purchased
            if (unit_purchased_in === 'meter') {
                $('#' + $uniqueId).find('.meter').prop('readonly', false);
                $('#' + $uniqueId).find('.yard').prop('readonly', true);
            } else if (unit_purchased_in === 'yard') {
                $('#' + $uniqueId).find('.meter').prop('readonly', true);
                $('#' + $uniqueId).find('.yard').prop('readonly', false);
            }
        }

        disableAddItemButton();
    }
    
    // Disable the Add Item button to prevent multiple clicks
    function disableAddItemButton() {
        $('#add_item_model_btn').addClass('disabled'); // Add a disabled class
        $('#add_item_model_btn').off('click'); // Disable the click event
    }

    function enableAddItemButton() {
        $('#add_item_model_btn').removeClass('disabled'); // Add a disabled class
        $('#add_item_model_btn').on('click', function() {
            // Add your logic to show the modal or perform any action when the button is clicked
            $('#addItemModal').modal('show'); 
        });
    }

</script>
@endpush
