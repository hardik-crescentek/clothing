@extends('layouts.master')
@section('title', 'Purchase')
@section('style')
<style>
    /* Custom CSS for modal adjustments */
    .modal-lg {
        max-width: 90%; /* Adjust modal maximum width */
    }

    .modal-body {
        max-height: calc(100vh - 200px); /* Adjust modal body maximum height */
        overflow-y: auto; /* Enable vertical scrolling if content exceeds modal height */
    }
</style>
@endsection
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
                                        <td><a href="{{ route('purchase.supplier-details',$purchase->supplier_id) }}" style="color:blue;">{{ $purchase->supplier->name ?? '' }}</a></td>
                                        <td>{{ $purchase->payment_terms }}</td>
                                        <td>{{ $purchase->purchase_type }}</td>
                                        <td class="td-actions">                                    
                                            <div class="row">
                                                <a class="btn fa fa-print btn-sm btn-info" data-toggle="tooltip" data-placement="top" title="Print" href="{{ route('printbarcode',['id' => $purchase->id,'invoice_no' => $purchase->invoice_no, 'printBarcode'=>1,'printQRCode'=>1,'printWithBatchNo'=>1,'printWithArticleNo'=>1,'printWithRollNo'=>1,'printInvoice'=>1,'printColor'=>1,'printWidth'=>1]) }}"></a>
                                                <a class="btn fa fa-edit btn-sm btn-primary ml-1" href="{{ route('purchase.edit',$purchase->id) }}" data-toggle="tooltip" data-placement="top" title="Edit"></a>
                                                {!! Form::open(['method' => 'DELETE','route' => ['purchase.destroy', $purchase->id],'style'=>'display:inline', 'onsubmit'=>'return delete_confirm()']) !!}
                                                <button type="submit" class="btn-action btn fa fa-trash  btn-sm btn-danger ml-1" data-toggle="tooltip" data-placement="top" title="Delete">
                                                </button>
                                                {!! Form::close() !!}

                                                @php
                                                    $attachments = json_decode($purchase->attachment, true);
                                                @endphp
                                                @if(is_array($attachments) && !empty($attachments))
                                                    <button type="button" class="btn-action btn fa fa-eye btn-sm btn-warning ml-1" data-toggle="modal" data-target="#imageModal-{{ $purchase->id }}" data-placement="top" title="View Attachments"></button>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>

                                    <!-- Modal Structure -->
                                    <div class="modal fade" id="imageModal-{{ $purchase->id }}" tabindex="-1" role="dialog" aria-labelledby="imageModalLabel-{{ $purchase->id }}" aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-scrollable modal-lg" role="document">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="imageModalLabel-{{ $purchase->id }}">Attachments for Invoice No: {{ $purchase->invoice_no }}</h5>
                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                                </div>
                                                <div class="modal-body" style="height: auto; overflow-y: visible;">
                                                    <div class="row">
                                                        @php
                                                            $attachments = json_decode($purchase->attachment, true);
                                                        @endphp
                                                        @if(is_array($attachments) && !empty($attachments))
                                                            @foreach($attachments as $attachment)
                                                                @php
                                                                    $attachmentName = basename($attachment);
                                                                    $downloadName = $purchase->invoice_no . '_' . $attachmentName;
                                                                @endphp
                                                                <div class="col-lg-4" style="display: flex; align-items: center;">
                                                                    <a data-fancybox="gallery" href="{{ url('public/storage/' . $attachment) }}">
                                                                        <img src="{{ url('public/storage/' . $attachment) }}" class="img-fluid img-thumbnail" alt="Attachment">
                                                                    </a>
                                                                    <a href="{{ url('public/storage/' . $attachment) }}" class="btn ml-1 fa fa-download" download="{{ $downloadName }}"></a>
                                                                </div>

                                                            @endforeach
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

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
<!-- CSS for FancyBox -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/fancybox/3.5.7/jquery.fancybox.min.css" />

<!-- JavaScript for FancyBox -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/fancybox/3.5.7/jquery.fancybox.min.js"></script>

<script>
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