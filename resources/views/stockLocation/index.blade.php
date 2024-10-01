@extends('layouts.master')
@section('title', 'Stock Locations')
@section('content')

<!-- Begin Page Header -->
<div class="row">
    <div class="page-header">
        <div class="d-flex align-items-center">
            <h2 class="page-header-title">Stock Locations</h2>
        </div>
    </div>
</div>
<!-- End Page Header -->

@if ($message = Session::get('success'))
<div class="alert alert-success">
    {{ $message }}
</div>
@endif

<!-- Begin Filter Row -->
<div class="row">
    <div class="col-lg-2">
        <label for="article_no" class="form-control-label"><b>Article No:</b></label>
        <select class="form-control select2" id="article_no" >
            <option value="">-- Select Article No --</option>
            @foreach($articles as $article)
                <option value="{{ $article->article_no }}">{{ $article->article_no }}</option>
            @endforeach
        </select>
    </div>

    <div class="col-lg-2">
        <label for="color" class="form-control-label"><b>Color:</b></label>
        <select class="form-control select2" id="color">
            <option value="">-- Select Color --</option>
            @foreach($colors as $color)
                <option value="{{ $color->color }}">{{ $color->color }}</option>
            @endforeach
        </select>
    </div>
</div>
<!-- End Filter Row -->

<!-- Begin Table Row -->
<div class="row flex-row mt-4">
    <div class="col-xl-12 col-12">
        <div class="widget has-shadow">
            <div class="widget-body">
                <div class="table-responsive">
                    <table class="table table-hover mb-0" id="stocklocation_tbl">
                        <thead>
                            <tr>
                                <th><input type="checkbox" id="select_all"></th> <!-- Checkbox for selecting all -->
                                <th>PCS No.</th>
                                <th>Article No.</th>
                                <th>Color</th>
                                <th>Color No.</th>
                                <th>Current Warehouse</th>
                                <th>Warehouse History</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
                <button class="btn btn-primary mt-3" id="updateMultipleBtn">Update Selected Warehouses</button>
            </div>
        </div>
    </div>
</div>
<!-- End Table Row -->

<!-- Update Multiple Warehouses Modal -->
<div class="modal fade" id="updateMultipleWarehouseModal" tabindex="-1" role="dialog" aria-labelledby="updateMultipleWarehouseModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="updateMultipleWarehouseModalLabel">Update Multiple Warehouses</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" style="height: auto !important; overflow-y: visible !important;">
                <div class="form-group">
                    <label class="form-control-label">WareHouse<span class="text-danger ml-2">*</span></label>
                    {!! Form::select('warehouse_id', [''=>' --Select WareHouse-- ']+$wareHouse, null, [
                        'id' => 'multiple_warehouse_select',
                        'class' => 'form-control custom-select',
                        'data-validation' => "required"
                    ]) !!}
                </div>

                <!-- Moved By Input -->
                <div class="form-group mt-3">
                    <label class="form-control-label" for="moved_by">Moved By<span class="text-danger ml-2">*</span></label>
                    <input type="text" class="form-control" id="moved_by" name="moved_by" placeholder="Enter the person who moved">
                </div>

                <!-- Transported By Input -->
                <div class="form-group mt-3">
                    <label class="form-control-label" for="transported_by">Transported By<span class="text-danger ml-2">*</span></label>
                    <input type="text" class="form-control" id="transported_by" name="transported_by" placeholder="Enter the person who transported">
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" id="updateMultipleWarehouseBtn">Update</button>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

@endsection

@push('after-styles')
    <style>
        .select2-container, .select2-container span.selection {
            width: 100% !important;
        }
    </style>
@endpush

@push('scripts')
<!-- Include Bootstrap CSS (for modal styling) -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">

<!-- Include Select2 CSS -->
<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" rel="stylesheet" />

<!-- Include DataTables CSS -->
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/jquery.dataTables.min.css">

<!-- Include jQuery (must be loaded first) -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>

<!-- Include Bootstrap JS (for modal functionality) -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>

<!-- Include Select2 JS -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>

<!-- Include DataTables JS -->
<script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
<script>
    $(document).ready(function() {
        // Initialize Select2 dropdowns
        $('.select2').select2();

        // Initialize DataTable
        var table = $('#stocklocation_tbl').DataTable({
            ajax: {
                url: '{{ route("stockLocation.filter") }}',
                dataSrc: ''
            },
            autoWidth: false,
            responsive: true,
            columns: [
                {
                    data: null,
                    render: function(data, type, row) {
                        return '<input type="checkbox" class="row_checkbox" value="' + row.id + '">';
                    }
                },
                { data: 'piece_no' },
                { data: 'article_no' },
                { data: 'color' },
                { data: 'color_no' },
                { data: 'current_warehouse' },
                {
                    data: 'warehouse_history',
                    render: function(data, type, row) {
                        var currentWarehouse = row.current_warehouse; // Accessing the property directly
                        var warehouseHistory = row.warehouse_history;

                        // Check if both current warehouse and history are null or empty
                        if (!currentWarehouse && (!warehouseHistory || warehouseHistory.trim() === '')) {
                            return ''; // Do not display anything if both are null or empty
                        }

                        // Combine current warehouse and history
                        var historyDisplay = currentWarehouse || ''; // Start with current warehouse or empty string
                        if (warehouseHistory && warehouseHistory.trim() !== '') {
                            historyDisplay += ' << ' + warehouseHistory; // Append history if available
                        }

                        // Generate the clickable link
                        var link = '<a href="{{ url('warehouse-history') }}/' + row.id + '" target="_blank">' + historyDisplay + '</a>';

                        // Return the link
                        return link; // This will make the entire text clickable
                    }
                }
            ],
            lengthMenu: [
                [10, 25, 50, 100],
                [10, 25, 50, 100]
            ],
            "aaSorting": []
        });

        // Function to reset the modal fields
        function resetModalFields() {
            $('#multiple_warehouse_select').val('').trigger('change'); // Reset Select2
            $('#moved_by').val(''); // Clear moved_by input
            $('#transported_by').val(''); // Clear transported_by input
        }

        // Handle multiple warehouse updates
        $('#updateMultipleBtn').on('click', function() {
            // Check if at least one checkbox is selected
            var selectedIds = [];
            $('.row_checkbox:checked').each(function() {
                selectedIds.push($(this).val());
            });

            if (selectedIds.length === 0) {
                alert('Please select at least one warehouse to update.');
                return;
            }

            // Reset modal fields
            resetModalFields();

            // Show the modal for updating multiple warehouses
            $('#updateMultipleWarehouseModal').modal('show');
        });

        // Handle multiple warehouse update submission
        $('#updateMultipleWarehouseBtn').on('click', function() {
            var newWarehouse = $('#multiple_warehouse_select').val();
            var movedBy = $('#moved_by').val();
            var transportedBy = $('#transported_by').val();

            // Validate that a warehouse is selected
            if (!newWarehouse || !movedBy || !transportedBy) {
                alert('Please add required fields before updating.');
                return;
            }

            var selectedIds = [];
            $('.row_checkbox:checked').each(function() {
                selectedIds.push($(this).val());
            });

            $.ajax({
                url: '{{ route("stockLocation.updateMultiple") }}',
                type: 'PUT',
                data: {
                    ids: selectedIds,
                    new_warehouse: newWarehouse,
                    movedBy: movedBy,
                    transportedBy: transportedBy,
                    _token: '{{ csrf_token() }}'
                },
                success: function(response) {
                    if (response.success) {
                        $('#updateMultipleWarehouseModal').modal('hide');
                        table.ajax.reload(); // Reload table data
                        alert('Selected warehouses updated successfully!');
                    } else {
                        alert('Error updating selected warehouses. Please try again.');
                    }
                },
                error: function(xhr, status, error) {
                    alert('An error occurred. Please try again later.');
                }
            });
        });

        // Select all checkboxes
        $('#select_all').on('change', function() {
            $('.row_checkbox').prop('checked', this.checked);
        });

        // Fetch filtered data when dropdown values change
        $('#article_no, #color').on('change', function() {
            var article_no = $('#article_no').val();
            var color = $('#color').val();

            // Reload the DataTable with filtered data
            table.ajax.url('{{ route("stockLocation.filter") }}?article_no=' + article_no + '&color=' + color).load();
        });
    });
</script>
@endpush
