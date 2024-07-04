@extends('layouts.master')
@section('title', 'Import Purchase')
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
                <h4>Import Purchase</h4>
            </div>
            <div class="widget-body">

                @if ($message = Session::get('success'))
                <div class="alert alert-success">
                    {{ $message }}
                </div>
                @endif
                @if ($message = Session::get('error'))
                <div class="alert alert-danger">
                    {{ $message }}
                </div>
                @endif
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


                {!! Form::open(array('route' => 'purchase.import','method'=>'POST','id'=>'from_add_purchase', 'class'=>"form-horizontal form-validate", 'novalidate','files' => true)) !!}
                <div class="row">
                    <div class="form-group col-lg-6">
                        <label class="form-control-label d-flex">Supplier<span class="text-danger ml-2">*</span></label>
                        {!! Form::select('supplier_id', $suppliers,null, array('id'=>'supplier_id','class' => 'form-control custom-select', 'data-validation'=>"required")) !!}
                    </div>
                    <div class="form-group col-lg-6">
                        <label class="form-control-label d-flex">Select purchase file<span class="text-danger ml-2">*</span></label>
                        <input type="file" name="purchase_file" class="form-control" required>
                    </div>
                </div>
                <div class="form-group row d-flex align-items-center mt-3">
                    <div class="col-lg-12 d-flex justify-content-center">
                        <button type="submit" class="btn btn-primary btn-lg">Save</button>
                        <button type="button" class="btn btn-danger btn-lg d-none" id="multiple_purchase_delete">Delete Selected</button>
                    </div>
                </div>
                {!! Form::close() !!}
            </div>
            @isset($purchases)
            <form id="print_table" action="{{ route('purchase.printall')}}" method="POST">
            @csrf
            <div class="row">
                <div class="col-lg-12">
                    <button type="submit" class="btn btn-primary" id="printall">Print</button>
                </div>
            </div>
            @endif
            <table class="table table-hover mb-0 " id="purchase_tbl">
                <thead>
                    <tr>
                        <th>  </th>
                        <th>Invoice No</th>
                        <th>Date</th>
                        <th>Qty</th>
                        <th data-sorter="false">Total Cost</th>
                        <th data-sorter="false">Cost/meter</th>
                        <th data-sorter="false">Supplier</th>
                        <th data-sorter="false">Payment Terms</th>
                        <th data-sorter="false">Purchase Type</th>
                        <th data-sorter="false" width="180px">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @isset($purchases)
                    @foreach ($purchases as $key => $purchase)
                    <tr class="purchase-link purchase-item-{{$purchase->id}}" data-id="{{$purchase->id}}">
                        <td>
                            <input type="hidden" name="purchase_id[]" value="{{$purchase->id}}">
                            <input class="form-control purchase-checkbox" type="checkbox" name="selected_items[]" id="purchase-link-{{$purchase->id}}" value="{{$purchase->id}}">
                        </td>
                        <td>{{ $purchase->invoice_no }}</td>
                        <td>{{ $purchase->purchase_date }}</td>
                        <td>{{ $purchase->total_qty }}</td>
                        <td>
                            {{$purchase->currency_of_purchase}}: {{ total_cost($purchase->price, $purchase->total_qty, $purchase->shipping_cost_per_meter) }}
                            <br>
                            THB: {{ total_cost($purchase->price_thb, $purchase->total_qty, $purchase->shipping_cost_per_meter) }}
                        </td>
                        <td>
                            {{$purchase->currency_of_purchase}}: {{ total_per_meter($purchase->price, $purchase->shipping_cost_per_meter) }}
                            <br>
                            THB: {{ total_per_meter($purchase->price_thb, $purchase->shipping_cost_per_meter)}}
                        </td>
                        <td><a href="{{ route('purchase.supplier-details',$purchase->supplier_id) }}" style="color:blue;">{{ $purchase->supplier->name }}</a></td>
                        <td>{{ $purchase->payment_terms }}</td>
                        <td>{{ $purchase->purchase_type }}</td>
                        <td class="td-actions">
                            <a class="btn btn-secondary btn-sm btn-square col-sm-6 mt-1" href="{{ route('printbarcode',['id' => $purchase->id,'invoice_no' => $purchase->invoice_no, 'printBarcode'=>1,'printQRCode'=>1,'printWithBatchNo'=>1,'printWithArticleNo'=>1,'printWithRollNo'=>1,'printInvoice'=>1,'printColor'=>1,'printWidth'=>1]) }}">Print</a>
                        </td>
                    </tr>
                    @endforeach
                    @endisset
                </tbody>
            </table>
            @isset($purchases)
            </form>
            @endif

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
</style>
@endpush
@push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/js/select2.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.tablesorter/2.31.3/js/jquery.tablesorter.min.js" integrity="sha512-qzgd5cYSZcosqpzpn7zF2ZId8f/8CHmFKZ8j7mU4OUXTNRd5g+ZHBPsgKEwoqxCtdQvExE5LprwwPAgoicguNg==" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.tablesorter/2.31.3/js/parsers/parser-input-select.min.js" integrity="sha512-1yWDRolEDA6z68VeUHdXNFZhWYteCOlutcPMPuDtX1f7/doKecWLx87shPRKx8zmxdWA0FV9mNRUr9NnSwzwyw==" crossorigin="anonymous"></script>
<script src="{{ asset('assets/js/datepicker/moment.min.js') }}"></script>
<script src="{{ asset('assets/js/datepicker/daterangepicker.js') }}"></script>
<script type="text/javascript">
    $('#printall').click(function(){
        $("#print_table").submit();
    });

    $('.purchase-checkbox').change(function() {
        var selectedItems = $('input[name="selected_items[]"]:checked');
        var selectedCount = selectedItems.length;
        if(selectedCount > 0) {
            $('#multiple_purchase_delete').removeClass('d-none');
        } else {
            $('#multiple_purchase_delete').addClass('d-none');
        }
    });

    $('#multiple_purchase_delete').click(function() {
        var selectedItems = $("input[name='selected_items[]']:checked").map(function(){
            return $(this).val(); 
        }).get();

        Swal.fire({
            title: 'Are you sure?',
            text: "You won't be able to revert this!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, delete it!'
        }).then((result) => {

            if (result.isConfirmed) {
                $.ajax({
                    url: "{{ route('purchase.multiple-delete') }}",
                    type: 'POST',
                    data: {_token: '{{ csrf_token() }}', selectedItems: selectedItems},
                    success: function() {

                        Swal.fire('Deleted!', 'Selected items has been deleted.', 'success')

                        $.each(selectedItems, function(key, value){
                            $('.purchase-item-'+value).remove();
                        });
                    }
                });
            }
        });
    });
</script>
@endpush
