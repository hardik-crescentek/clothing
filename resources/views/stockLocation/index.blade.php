@extends('layouts.master')
@section('title', 'Stock Locations')
@section('style')
<style>
    @media (min-width: 100px) {
        #updateWarehouseModal .modal-body {
            height: 400px !important;
            overflow-y: auto !important;
        }
    }
</style>
@endsection
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
    <div class="col-xl-3">
        <label for="article_no"><b>Filter by Article No:</b></label>
        <select class="form-control select2" id="article_no">
            <option value="">Select Article No</option>
            @foreach($articles as $article)
                <option value="{{ $article->article_no }}">{{ $article->article_no }}</option>
            @endforeach
        </select>
    </div>

    <div class="col-xl-3">
        <label for="color"><b>Filter by Color:</b></label>
        <select class="form-control select2" id="color">
            <option value="">Select Color</option>
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
                                <th>Actions</th>
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

<!-- Update Warehouse Modal -->
<div class="modal fade" id="updateWarehouseModal" tabindex="-1" role="dialog" aria-labelledby="updateWarehouseModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="updateWarehouseModalLabel">Update Warehouse</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body custom-modal-body">
                <input type="hidden" id="warehouse_id">
                <div class="form-group">
                    <label class="form-control-label">WareHouse<span class="text-danger ml-2">*</span></label>
                    {!! Form::select('warehouse_id', [''=>' --Select WareHouse-- ']+$wareHouse, null, [
                        'id' => 'warehouse_select',
                        'class' => 'form-control custom-select',
                        'data-validation' => "required"
                    ]) !!}
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" id="updateWarehouseBtn">Update Warehouse</button>
            </div>
        </div>
    </div>
</div>

<!-- Update Multiple Warehouses Modal -->
<div class="modal fade" id="updateMultipleWarehouseModal" tabindex="-1" role="dialog" aria-labelledby="updateMultipleWarehouseModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="updateMultipleWarehouseModalLabel">Update Multiple Warehouses</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label class="form-control-label">WareHouse<span class="text-danger ml-2">*</span></label>
                    {!! Form::select('warehouse_id', [''=>' --Select WareHouse-- ']+$wareHouse, null, [
                        'id' => 'multiple_warehouse_select',
                        'class' => 'form-control custom-select',
                        'data-validation' => "required"
                    ]) !!}
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" id="updateMultipleWarehouseBtn">Update Multiple Warehouses</button>
            </div>
        </div>
    </div>
</div>

@endsection

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
                    data: null,
                    render: function(data, type, row) {
                        return '<a target="_blank" href="{{ url('warehouse-history') }}/' + row.id + '" class="btn btn-info">View History</a>';
                    }
                },
                {
                    data: null,
                    render: function(data, type, row) {
                        // return '<button class="btn btn-primary updateWarehouseBtn" data-id="' + row.id + '" data-warehouse="' + row.current_warehouse + '">Update WareHouse</button>';
                        return '<button class="btn btn-primary updateWarehouseBtn" data-id="' + row.id + '" data-warehouse-id="' + row.current_warehouse + '" target="_blank">Update WareHouse</button>';
                    }
                },
            ],
            lengthMenu: [
                [10, 25, 50, 100],
                [10, 25, 50, 100]
            ],
            "aaSorting": []
        });

        // Open modal on update button click
        $('#stocklocation_tbl tbody').on('click', '.updateWarehouseBtn', function() {
            var itemId = $(this).data('id'); // Get the item's ID
            var warehouseId = $(this).data('warehouse-id'); // Get the current warehouse ID

            $('#warehouse_id').val(itemId);
            $('#warehouse').val(warehouseId).trigger('change'); // Use trigger to update Select2

            $('#updateWarehouseModal').modal('show');
        });

        // Handle warehouse update
        $('#updateWarehouseBtn').on('click', function() {
            var id = $('#warehouse_id').val();
            var newWarehouse = $('#warehouse_select').val();

            // Validate that a warehouse is selected
            if (!newWarehouse) {
                alert('Please select a warehouse before updating.'); // Alert if no warehouse is selected
                return; // Exit the function if validation fails
            }

            $.ajax({
                url: '{{ route("stockLocation.update", ":id") }}'.replace(':id', id),
                type: 'PUT',
                data: {
                    warehouse_id: newWarehouse,
                    _token: '{{ csrf_token() }}'
                },
                success: function(response) {
                    if (response.success) {
                        $('#updateWarehouseModal').modal('hide');
                        table.ajax.reload(); // Reload table data
                        alert('Warehouse updated successfully!');
                    } else {
                        alert('Error updating warehouse. Please try again.');
                    }
                },
                error: function(xhr, status, error) {
                    alert('An error occurred. Please try again later.');
                }
            });
        });

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

            // Show the modal for updating multiple warehouses
            $('#updateMultipleWarehouseModal').modal('show');
        });

        // Handle multiple warehouse update submission
        $('#updateMultipleWarehouseBtn').on('click', function() {
            var newWarehouse = $('#multiple_warehouse_select').val();
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
