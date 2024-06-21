@extends('layouts.master')
@section('title', 'Purchase')
@section('content')
<!-- Begin Page Header-->
<!-- <div class="row">
    <div class="page-header">
        <div class="d-flex align-items-center">
            <h2 class="page-header-title">Users</h2>
        </div>
    </div>
</div> -->
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
                    <table class="table table-hover mb-0 " id="purchase_tbl">
                        <thead>
                            <tr>
                                <th>Number Of orders</th>
                                <th>Material Name</th>
                                <th>Article No</th>
                                <th>Color <br> Code</th>
                                <th data-sorter="false">Barcode</th>
                                <th data-sorter="false">Material Image</th>
                            </tr>
                        </thead>
                        <tbody>
                            @isset($topMaterial)
                            @foreach ($topMaterial as $key => $tm)
                            <tr class="purchase-link" data-id="{{$tm->id}}">
                                <td>{{ $tm->material_id}}</td>
                                <td>{{ $tm->item->name }}</td>
                                <td>{{ $tm->item->article_no }}</td>
                                <td>{{ $tm->item->color_code }}</td>
                                <td>{!! DNS1D::getBarcodeSVG($tm->item->barcode,config('app.BARCODE_TYPE'), 1, 40)  !!}</td>
                                <td><img src="{{ img_url($tm->purchase_type) }}" class="listing-thumb img-thumbnail" alt="Not Found"></td>
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
<!-- End Row -->
<script>
    function delete_confirm() {
        return confirm("Are you sure want to delete?");
    }

</script>
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
<script src="{{ asset('assets/js/datepicker/moment.min.js') }}"></script>
<script src="{{ asset('assets/js/datepicker/daterangepicker.js') }}"></script>
    <script>
        $(document).ready(function(){
            $('#purchase_tbl').tablesorter({
                cssAsc: 'up',
		        cssDesc: 'down',
                cssNone: 'both'
            });
            $('#to_date').daterangepicker({
                // autoUpdateInput: false,
                singleDatePicker: true,
                showDropdowns: true,
                locale: {
                    format: 'DD/MM/YYYY',
                }
            });
            $('#from_date').daterangepicker({
                autoUpdateInput: false,
                singleDatePicker: true,
                showDropdowns: true,
                locale: {
                    format: 'DD/MM/YYYY',
                }
            },function(chosen_date) {
                $('#from_date').val(chosen_date.format('DD/MM/YYYY'));
            });
            
            
        })
    </script>
@endpush