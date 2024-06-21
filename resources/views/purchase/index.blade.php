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
                                <th>Invoice No</th>
                                <th>PCS NO</th>
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
                            <tr class="purchase-link" data-id="{{$purchase->id}}">
                                <td>{{ $purchase->invoice_no }} <br> 
                                    <span>{{ $purchase->purchase_date }}</span>
                                </td>
                                <td>{{ $purchase->pcs_no }}</td>
                                <td>{{ $purchase->available_qty }}/{{ $purchase->total_qty }}</td>
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
                                    <a class="btn btn-primary btn-sm btn-square col-sm-6 mt-1" href="{{ route('purchase.edit',$purchase->id) }}">Edit</a>
                                    {!! Form::open(['method' => 'DELETE','route' => ['purchase.destroy', $purchase->id],'style'=>'display:inline', 'onsubmit'=>'return delete_confirm()']) !!}
                                    {!! Form::submit('Delete', ['class' => 'btn btn-danger btn-sm btn-square col-sm-6 mt-1']) !!}
                                    {!! Form::close() !!}
                                </td>
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
@push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.tablesorter/2.31.3/js/jquery.tablesorter.min.js" integrity="sha512-qzgd5cYSZcosqpzpn7zF2ZId8f/8CHmFKZ8j7mU4OUXTNRd5g+ZHBPsgKEwoqxCtdQvExE5LprwwPAgoicguNg==" crossorigin="anonymous"></script>
    <script>
        // $(document).ready(function(){
        //     $('#purchase_tbl').tablesorter({
        //         cssAsc: 'up',
		      //   cssDesc: 'down',
        //         cssNone: 'both'
        //     });
        // })
        $(document).ready(function () {
            $('#purchase_tbl').DataTable({
                lengthMenu: [
                    [10, 25, 50,100,500,1000,'All'],
                    [10, 25, 50,100,500,1000,'All'],
                ],
                "aaSorting": []
            });
        });
    </script>
@endpush