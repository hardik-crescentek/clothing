@extends('layouts.master') <!-- Extend your main layout -->

@section('title', 'Warehouse Change History') <!-- Set the page title -->

@section('content')
<!-- Begin Page Header -->
<div class="row">
    <div class="page-header">
        <div class="d-flex align-items-center">
            <h2 class="page-header-title">Warehouse Change History</h2>
        </div>
    </div>
</div>
<!-- End Page Header -->

@if ($message = Session::get('success'))
<div class="alert alert-success">
    {{ $message }}
</div>
@endif

<!-- Begin Row -->
<div class="row flex-row">
    <div class="col-xl-12 col-12">
        <div class="widget has-shadow">
            <div class="widget-body">
                <div class="table-responsive">
                    <table class="table table-hover mb-0" id="history_tbl">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Purchase Item ID</th>
                                <!-- <th>Old Warehouse ID</th> -->
                                <th>Warehouse Name</th>
                                <th>Moved By</th>
                                <th>Transported By</th>
                                <th>Changed At</th>
                                <th>Created At</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if($history->isEmpty())
                                <tr>
                                    <td colspan="6" class="text-center">No history found for this purchase item.</td>
                                </tr>
                            @else
                                @foreach($history as $record)
                                    <tr>
                                        <td>{{ $record->id }}</td>
                                        <td>{{ $record->purchase_item_id }}</td>
                                        <!-- <td>{{ $record->old_warehouse_id }}</td> Display old warehouse ID -->
                                        <td>{{ $record->warehouse ? $record->warehouse->name : 'N/A' }}</td> <!-- Display warehouse name -->
                                        <td>{{ $record->moved_by ? $record->moved_by : 'N/A' }}</td>
                                        <td>{{ $record->transported_by ? $record->transported_by : 'N/A' }}</td>
                                        <td>{{ \Carbon\Carbon::parse($record->changed_at)->format('Y-m-d H:i:s') }}</td> <!-- Parse and format changed_at -->
                                        <td>{{ \Carbon\Carbon::parse($record->created_at)->format('Y-m-d H:i:s') }}</td> <!-- Parse and format created_at -->
                                    </tr>
                                @endforeach
                            @endif
                        </tbody>
                    </table>
                </div>
                <a href="{{ url()->previous() }}" class="btn btn-secondary mt-3">Back</a> <!-- Back button -->
            </div>
        </div>
    </div>
</div>
<!-- End Row -->
@endsection

@push('scripts')
<script>
    $(document).ready(function() {
        $('#history_tbl').DataTable({
            lengthMenu: [
                [10, 25, 50, 100, 500, 1000, 'All'],
                [10, 25, 50, 100, 500, 1000, 'All'],
            ],
            "aaSorting": [] // Disable default sorting
        });
    });
</script>
@endpush
