@extends('layouts.master')
@section('title', 'Order')
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
            <div class="widget-header bordered no-actions1 d-block align-items-center">
                Filter
            </div>
            <div class="widget-body">
                {!! Form::open(['method' => 'GET','route' => ['order.index']]) !!}
                    <div class="form-group row d-flex align-items-center mt-3">
                        <div class="col-lg-2">
                            <label class="form-control-label col-lg-12">Start Date </label>
                            <input type="date" class="form-control" name="start_date">
                        </div>
                        <div class="col-lg-2">
                            <label class="form-control-label col-lg-12">End Date </label>
                            <input type="date" class="form-control" name="end_date">
                        </div>
                        <div class="col-lg-2">
                            <label class="form-control-label col-lg-12">Status</label>
                            <select class="form-control custom-select" name="status">
                                <option value="">-- Select Status --</option>
                                @foreach(['Pending', 'Completed', 'Not Enough', 'Out Of Stock', 'Damaged'] as $status)
                                    <option value="{{ $status }}" {{ request('status') == $status ? 'selected' : '' }}>
                                        {{ $status }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-lg-2">
                            <label class="form-control-label col-lg-12">Dispatcher</label>
                            <select class="form-control custom-select" name="dispatcher_id">
                                <option value="">-- Select Dispatcher --</option>
                                @foreach($dispatchers as $id => $name)
                                    <option value="{{ $id }}" {{ request('dispatcher_id') == $id ? 'selected' : '' }}>
                                        {{ $name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>                        
                        <div class="col-lg-2">
                            <label class="form-control-label">&nbsp;</label>
                            <div class="form-action">
                                <input type="submit" class="btn btn-primary btn-square" value="Filter">
                                <a href="{{ url('/order') }}" class="btn btn-warning btn-square">Reset</a>
                            </div>
                        </div>
                    </div>
                {!! Form::close() !!}
            </div>
        </div>
    </div>
</div>
<!-- Begin Row -->
<div class="row flex-row">
    <div class="col-xl-12 col-12">

        <!-- Basic Tabs -->
        <div class="widget has-shadow">
            <div class="widget-header bordered no-actions d-flex align-items-center">
                <h4>Orders</h4>
            </div>
            <div class="widget-body sliding-tabs">
                <ul class="nav nav-tabs" id="example-one" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link active" id="base-tab-1" data-toggle="tab" href="#tab-1" role="tab" aria-controls="tab-1" aria-selected="true">Pending</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="base-tab-2" data-toggle="tab" href="#tab-2" role="tab" aria-controls="tab-2" aria-selected="false" style="color:#7dd84a !important;">Complited/Dispatch</a>
                    </li>
                </ul>
                <div class="tab-content pt-3">
                    <div class="tab-pane fade show active" id="tab-1" role="tabpanel" aria-labelledby="base-tab-1">
                        <table class="table table-hover mb-0 " id="order_tbl">
                            <thead>
                                <tr>
                                    <th>Order No</th>
                                    <th>Customer Name</th>
                                    <th>Sales Person</th>
                                    <th class="sorter-shortDate dateFormat-ddmmyyyy">Date</th>
                                    <th>Materials</th>
                                    <th>Colors</th>
                                    <th>Artical No</th>
                                    <th data-sorter="false">Meter</th>
                                    @role('super-admin')
                                        <th data-sorter="false">Price</th>
                                    @endrole
                                    <th data-sorter="false">Grand Total</th>
                                    <th data-sorter="false">Note</th>
                                    <th data-sorter="false">Status</th>
                                    {{-- <th data-sorter="false">Status Date</th> --}}
                                    <th data-sorter="false">Dispatcher</th>
                                    <th data-sorter="false" width="180px">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @isset($orders)
                                @foreach($orders as $order)
                                {{ isset($order->order_items['item_id']) }}
                                    @if($order->status != "Completed")
                                        <tr class="purchase-link" data-id="{{$order->id}}" style="color: #f0ad4e !important;">
                                            <td> {{$order->order_no}} </td>
                                            <td> {{$order->customer->firstname}} {{$order->customer->lastname}} </td>
                                            <td> {{$order->seller->firstname}} {{$order->seller->lastname}} </td>
                                            <td> {{$order->order_date}} </td>
                                            <td>
                                                @if ($order->order_items->count())
                                                    @foreach ($order->order_items as $key => $order_item)
                                                        {{$order_item->item->name ?? ''}} <br />
                                                    @endforeach
                                                @endif
                                            </td>
                                            <td>
                                                @if ($order->order_items->count())
                                                    @foreach ($order->order_items as $key => $order_item)
                                                        {{$order_item->item->color ?? ''}} <br />
                                                    @endforeach
                                                @endif
                                            </td>
                                            <td> 
                                                @if ($order->order_items->count())
                                                    @foreach ($order->order_items as $key => $order_item)
                                                        {{$order_item->item->article_no ?? ''}} <br />
                                                    @endforeach
                                                @endif
                                            </td>
                                            <td>
                                                @if ($order->order_items->count())
                                                    @foreach ($order->order_items as $key => $order_item)
                                                        {{$order_item->meter}} <br />
                                                    @endforeach
                                                @endif
                                            </td>
                                            @role('super-admin')
                                            <td>
                                                @if ($order->order_items->count())
                                                    @foreach ($order->order_items as $key => $order_item)
                                                        {{$order_item->price}} <br />
                                                    @endforeach
                                                @endif
                                            </td>
                                            @endrole
                                            <td> {{$order->grand_total}} </td>
                                            <td> {{$order->note}} </td>
                                            <td> {{$order->status}} </td>
                                            {{-- <td> {{$order->status_date}} </td> --}}
                                            <td>
                                                @if($order->dispatcher)
                                                    {{ $order->dispatcher->firstname }} {{ $order->dispatcher->lastname }}
                                                @else
                                                @endif 
                                            </td>
                                            <!-- <td>
                                                @if ($order->status == 1)
                                                <span class="badge-text badge-text-small success">Dispatch</span>
                                                @else
                                                <span class="badge-text badge-text-small warning">Pending</span>
                                                @endif
                                            </td> -->
                                            @role('super-admin')
                                            <td class="td-actions">
                                                <!-- <a data-toggle="modal" data-target="#order-status-model" data-id="{{ $order->id }}" class="btn btn-secondary btn-sm btn-square col-sm-6 mt-1" style="color: #366a2b !important;">Order <br> Status</a> -->
                                                <a class="btn fa fa-spinner btn-sm btn-primary ml-1"  data-toggle="modal" data-target="#order-status-model" data-id="{{ $order->id }}" data-placement="top" title="Order Status"></a>
                                                <a class="btn fa fa-edit btn-sm btn-primary ml-1" href="{{ route('order.edit',$order->id) }}" data-toggle="tooltip" data-placement="top" title="Edit Order"></a>
                                                {{-- {{print_r(sizeof($order->invoice))}} --}}
                                                @role('super-admin')
                                                @if (!$order->invoice)
                                                    <!-- <a class="btn btn-secondary btn-sm btn-square col-sm-6 mt-1" href="{{ route('invoice.create',$order->id) }}">Generate <br> Invoice</a> -->
                                                    <a class="btn fa fa-file-invoice btn-sm btn-info ml-1" href="{{ route('invoice.create',$order->id) }}" data-toggle="tooltip" data-placement="top" title="Generate Invoice"></a>
                                                @else
                                                    <!-- <a class="btn btn-primary btn-sm btn-square col-sm-6 mt-1" href="{{ route('invoice.edit',$order->invoice->id) }}" >Edit <br> Invoice</a> -->
                                                    <a class="btn fa fa-file-invoice-dollar btn-sm btn-primary ml-1" href="{{ route('invoice.edit',$order->invoice->id) }}" data-toggle="tooltip" data-placement="top" title="Edit Invoice"></a>
                                                @endif
                                                @endrole
                                                <a class="btn fa fa-eye btn-sm btn-warning ml-1" href="{{ route('order.viewdetails',$order->id) }}" data-toggle="tooltip" data-placement="top" title="View Order Details"></a>
                                                {!! Form::open(['method' => 'DELETE','route' => ['order.destroy', $order->id],'style'=>'display:inline', 'onsubmit'=>'return delete_confirm_order()']) !!}
                                                <!-- {!! Form::submit('Delete', ['class' => 'btn btn-danger btn-sm btn-square delete col-sm-6 mt-1']) !!} -->
                                                <button type="submit" class="btn-action btn fa fa-trash  btn-sm btn-danger ml-1" data-toggle="tooltip" data-placement="top" title="Delete">
                                                </button>
                                                {!! Form::close() !!}
                                                <!-- <a class="btn btn-primary btn-sm btn-square col-sm-6" href="{{ route('order.viewdetails',$order->id) }}">View Order <br> Details</a> -->
                                            </td>
                                            @endrole
                                            @role('warehouse')
                                            <td class="td-actions">
                                                 <a class="btn btn-secondary btn-sm btn-square col-sm-6 mt-1" href="{{ route('printorderbarcode',$order->id) }}">Print</a>
                                                <a data-toggle="modal" data-target="#order-status-model" data-id="{{ $order->id }}" class="btn btn-square col-sm-6 mt-1" style="color: #366a2b !important;">
                                                    <div class="styled-checkbox">
                                                        <input type="checkbox" name="checkbox" id="check-2" checked="">
                                                        <label for="check-2">Order <br>Status</label>
                                                    </div>
                                                </a>
                                                <a class="btn btn-primary btn-sm btn-square col-sm-6" href="{{ route('order.viewdetails',$order->id) }}">View Order <br> Details</a>
                                            </td>
                                            @endrole
                                        </tr>
                                    @endif
                                @endforeach
                                @endisset
                            </tbody>
                        </table>
                    </div>
                    <div class="tab-pane fade" id="tab-2" role="tabpanel" aria-labelledby="base-tab-2">
                        <table class="table table-hover mb-0 " id="order_tbl2">
                            <thead>
                                <tr>
                                    <th>Order No</th>
                                    <th>Customer Name</th>
                                    <th>Sales Person</th>
                                    <th class="sorter-shortDate dateFormat-ddmmyyyy">Date</th>
                                    <th>Materials</th>
                                    <th>Colors</th>
                                    <th data-sorter="false">Meter</th>
                                    @role('super-admin')
                                    <th data-sorter="false">Price</th>
                                    @endrole
                                    <th data-sorter="false">Grand Total</th>
                                    <th data-sorter="false">Note</th>
                                    <th data-sorter="false">Remark</th>
                                    <th data-sorter="false">Status</th>
                                    <th data-sorter="false">Status Date</th>
                                    <th data-sorter="false">Dispatcher</th>
                                    @role('super-admin')
                                    <th data-sorter="false" width="180px">Action</th>
                                    @endrole
                                </tr>
                            </thead>
                            <tbody>
                                @isset($orders)

                                @foreach($orders as $order)
                                    @if($order->status == "Completed")
                                        <tr class="purchase-link" data-id="{{$order->id}}" style="color: #79b385 !important;">
                                            <td> {{$order->order_no}} </td>
                                            <td>{{$order->customer->firstname}} {{$order->customer->lastname}} </td>
                                            <td> {{$order->seller->firstname}} {{$order->seller->lastname}} </td>
                                            <td> {{$order->order_date}} </td>
                                            <td>
                                                @if ($order->order_items->count())
                                                @foreach ($order->order_items as $key => $order_item)
                                                {{$order_item->item->name ?? ''}} <br />
                                                @endforeach
                                                @endif
                                            </td>
                                            <td>
                                                @if ($order->order_items->count())
                                                @foreach ($order->order_items as $key => $order_item)
                                                {{$order_item->item->color ?? ''}} <br />
                                                @endforeach
                                                @endif
                                            </td>
                                            <td>
                                                @if ($order->order_items->count())
                                                @foreach ($order->order_items as $key => $order_item)
                                                {{$order_item->meter}} <br />
                                                @endforeach
                                                @endif
                                            </td>
                                            @role('super-admin')
                                            <td>
                                                @if ($order->order_items->count())
                                                @foreach ($order->order_items as $key => $order_item)
                                                {{$order_item->price}} <br />
                                                @endforeach
                                                @endif
                                            </td>
                                            @endrole
                                            <td> {{$order->grand_total}} </td>
                                            <td> {{$order->note}} </td>
                                            <td> {{$order->remark}} </td>
                                            <td> {{$order->status}} </td>
                                            <td> {{$order->status_date}} </td>
                                            <td>@if($order->dispatcher)
                                                    {{ $order->dispatcher->firstname }} {{ $order->dispatcher->lastname }}
                                                @else
                                                @endif 
                                            </td>
                                            @role('super-admin')
                                            <td class="td-actions">
                                               <!--  <a data-toggle="modal" data-target="#order-status-model" data-id="{{ $order->id }}" class="btn btn-secondary btn-sm btn-square col-sm-6 mt-1" style="color: #f0ad4e !important;">Order <br> Status</a> -->
                                                <a class="btn fa fa-edit btn-sm btn-primary ml-1" href="{{ route('order.edit',$order->id) }}" data-toggle="tooltip" data-placement="top" title="Edit"></a>
                                                {{-- {{print_r(sizeof($order->invoice))}} --}}
                                                @role('super-admin')
                                                @if (!$order->invoice)
                                                    <a class="btn fa fa-file-invoice btn-sm btn-info ml-1" href="{{ route('invoice.create',$order->id) }}" data-toggle="tooltip" data-placement="top" title="Generate Invoice"></a>
                                                @else
                                                    <a class="btn fa fa-edit btn-sm btn-primary ml-1" href="{{ route('invoice.edit',$order->invoice->id) }}" data-toggle="tooltip" data-placement="top" title="Edit Invoice"></a>
                                                @endif
                                                @endrole
                                                {!! Form::open(['method' => 'DELETE','route' => ['order.destroy', $order->id],'style'=>'display:inline', 'onsubmit'=>'return delete_confirm_order()']) !!}
                                                <!-- {!! Form::submit('Delete', ['class' => 'btn btn-danger btn-sm btn-square delete col-sm-6 mt-1']) !!} -->
                                                <button type="submit" class="btn-action btn fa fa-trash  btn-sm btn-danger ml-1" data-toggle="tooltip" data-placement="top" title="Delete">
                                                </button>
                                                {!! Form::close() !!}
                                            </td>
                                            @endrole
                                        </tr>
                                    @endif
                                @endforeach
                                @endisset
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <!-- End Basic Tabs -->
    </div>
</div>
<!-- End Row -->

<div id="order-status-model" class="modal fade">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Order complete</h4>
                <button type="button" class="close" data-dismiss="modal">
                    <span aria-hidden="true">×</span>
                    <span class="sr-only">close</span>
                </button>
            </div>
            <form id="change_model" action="{{ route('order.changeOrderStatus') }}" class="" method="post">
                @csrf
            <div class="modal-body" style="height:165px !important">
                <div class="form-group">
                    <label class="form-control-label">Remark</label>
                    <textarea class="form-control" name="remark"></textarea>
                </div>
            </div>
            <div class="modal-footer">
                <input type="hidden" name="order_id">
                <button type="button" class="btn btn-shadow" data-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-primary">Complete</button>
            </div>
            </form>
        </div>
    </div>
</div>

<div id="editItemModal" tabindex="-1" role="dialog" aria-labelledby="Edit" aria-hidden="true" class="modal fade text-left">
    <div role="document" class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 id="modal-header" class="modal-title">Edit Order Item</h5>
                <button type="button" data-dismiss="modal" aria-label="Close" class="close"><span aria-hidden="true">×</span></button>
            </div>
            <div class="modal-body">
                {!! Form::open(array('route' => 'order.update-order-item','method'=>'PATCH','id'=>'edit_item_form', 'class'=>"form-horizontal form-validate", 'novalidate')) !!}
                <input type="hidden" id="edit_item_id" name="orderItemId" value="" />
                @if(isset($order))
                    <div class="form-group">
                        {!! Form::hidden('customer_id',$order->customer_id) !!}
                        <label class="form-control-label">Item Name<span class="text-danger ml-2">*</span></label>
                        {!! Form::text('name',null, array('id'=>'edit_name','class' => 'form-control', 'data-validation'=>"required",'readonly'=>"readonly")) !!}
                    </div>
                @endif
                <div class="form-group">
                    <label class="form-control-label">Barcode<span class="text-danger ml-2">*</span></label>
                    {!! Form::text('barcode',null, array('id'=>'edit_barcode','class' => 'form-control', 'data-validation'=>"required",'readonly'=>"readonly")) !!}
                </div>
                <div class="form-group">
                    <label class="form-control-label">Type Of Sale<span class="text-danger ml-2">*</span></label>
                    <td>{!! Form::select("type_of_sale", ["W"=>"Wholsale","R"=>"Retail","P"=>"Sample Poh"], null, ['class'=>'form-control edit_type_of_sale','data-validation'=>"required"]) !!}</td>
                </div>
                <div class="form-group">
                    <label class="form-control-label">Price<span class="text-danger ml-2">*</span></label>
                    {!! Form::text('price',0, array('id'=>'edit_price','class' => 'form-control', 'data-validation'=>"required")) !!}
                </div>
                <div class="form-group">
                    <label class="form-control-label">Meter<span class="text-danger ml-2">*</span></label>
                    {!! Form::text('meter',0, array('id'=>'edit_meter','class' => 'form-control', 'data-validation'=>"required")) !!}
                </div>
                <div class="form-group">
                    <label class="form-control-label">Yard<span class="text-danger ml-2">*</span></label>
                    <input name="yard" class="yard form-control" id="edit_yard" readonly="readonly" value="" type="text">                
                </div>

                <div class="form-action float-right">
                    <button type="submit" name="update_btn" class="btn btn-primary">Update</button>
                </div>
                {!! Form::close() !!}
            </div>
        </div>
    </div>
</div>

<script>
    function delete_confirm_order() {
        return confirm("Are you sure want to delete?");
    }
</script>
@endsection
@push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.tablesorter/2.31.3/js/jquery.tablesorter.min.js" integrity="sha512-qzgd5cYSZcosqpzpn7zF2ZId8f/8CHmFKZ8j7mU4OUXTNRd5g+ZHBPsgKEwoqxCtdQvExE5LprwwPAgoicguNg==" crossorigin="anonymous"></script>
<script type="text/javascript">
    // change status js
    $(document).ready(function() {
        $('#order-status-model').on('show.bs.modal', function (e) {
            if (e.namespace === 'bs.modal') {
                var opener=e.relatedTarget;
                var user_id         =$(opener).attr('data-id');
                $('#change_model').find('[name="order_id"]').val(user_id);
            }
        });
    });
</script>
<script>
    var csrf_token = '{{ csrf_token() }}';

    $(document).ready(function () {
        const table = $('#order_tbl').DataTable({
            lengthMenu: [
                [10, 25, 50,100,500,1000,'All'],
                [10, 25, 50,100,500,1000,'All'],
            ],
            "aaSorting": []
        });

        const table2 =$('#order_tbl2').DataTable({
            lengthMenu: [
                [10, 25, 50, 100, 500, 1000, 'All'],
                [10, 25, 50, 100, 500, 1000, 'All'],
            ],
            "aaSorting": []
        });

        $('#order_tbl tbody').on('click', 'td', function () {
            const td = $(this);
            const tr = td.closest('tr');
            const colIndex = td.index();

            if (colIndex < 12) {
                const row = table.row(tr);
                const orderId = tr.data('id');

                if (row.child.isShown()) {
                    row.child.hide();
                    tr.removeClass('shown');
                } else {                    
                    $.ajax({
                        url: `orders/${orderId}/order-items`,
                        type: 'GET',
                        success: function (data) {
                            const subOrderHtml = formatSubOrderItems(data);
                            row.child(subOrderHtml).show();
                            tr.addClass('shown');
                        },
                        error: function () {
                            alert('Failed to load sub-order items.');
                        }
                    });
                }
            }
        });

        $('#order_tbl2 tbody').on('click', 'td', function () {
            const td = $(this);
            const tr = td.closest('tr');
            const colIndex = td.index();

            if (colIndex < 12) {
                const row = table2.row(tr);
                const orderId = tr.data('id');

                if (row.child.isShown()) {
                    row.child.hide();
                    tr.removeClass('shown');
                } else {                    
                    $.ajax({
                        url: `orders/${orderId}/order-items`,
                        type: 'GET',
                        success: function (data) {
                            const subOrderHtml = formatSubOrderItems(data);
                            row.child(subOrderHtml).show();
                            tr.addClass('shown');
                        },
                        error: function () {
                            alert('Failed to load sub-order items.');
                        }
                    });
                }
            }
        });

        function formatSubOrderItems(data) {
            let html = '<table class="table table-bordered">';
            html += '<thead><tr class=""><th>=></th><th>Item Name</th><th>Barcode</th><th>Type Of Sale</th><th>Article No</th><th>Price</th><th>Meter</th><th>Yard</th><th>Total Price</th><th>Status</th><th>Status Date</th><th>Action</th></tr></thead>';
            html += '<tbody>';
            data.forEach(item => {      
                const yards = (item.meter * 1.09361).toFixed(2);
                const totalPrice = (item.price * yards).toFixed(2);
                const editButton = `<button type="button" class="btn btn-sm btn-primary btn-square btn-edit-order-item" 
                                        data-item_id="${item.id}" data-toggle="modal" data-target="#editItemModal">
                                        Edit
                                    </button>`;
                            
                const deleteButton = `<button type="button" class="btn btn-danger delete-order-item btn-sm btn-square" data-item_id="${item.id}">Delete</button>`;

                html += `<tr>
                            <td>=></td>
                            <td>${item.item.name} - ${item.item.color}</td>
                            <td>${item.barcode_svg}</td>
                            <td>${item.type_of_sale}</td>
                            <td>${item.item.article_no}</td>
                            <td>${item.price}</td>
                            <td>${item.meter}</td>
                            <td>${yards}</td>
                            <td>${totalPrice}</td>
                            <td>${item.status}</td>
                            <td>${item.status_date ?? ''}</td>
                            <td>${editButton} ${deleteButton}</td>
                        </tr>`;
            });
            html += '</tbody></table>';
            return html;
        }

        $('#editItemModal').on('shown.bs.modal', function(e) {
            var item_id = $(e.relatedTarget).data('item_id');

            $.ajax({
                url: 'order-items/'+item_id, 
                method: 'GET',
                success: function(response) {
                    if (response.data) {
                        var item = response.data;

                        $('#edit_item_id').val(item.id);
                        $('#edit_name').val(item.item.name);
                        $('#edit_barcode').val(item.item.barcode);
                        $('#edit_type_of_sale').val(item.type_of_sale);
                        $('#edit_price').val(item.price);
                        $('#edit_meter').val(item.meter);
                        $('#edit_yard').val((item.meter * 1.09361).toFixed(2));  

                    } else {
                        alert('Order Item not found.');
                    }
                },
                error: function(error) {
                    console.error('Error fetching order item details:', error);
                    alert('Error fetching order item details.');
                }
            });
        });

        $(document).on('click', '.delete-order-item', function () {
            var orderItemId = $(this).data('item_id');
            deleteOrderItem(orderItemId);
        });

        function deleteOrderItem(orderItemId) {
            if (!confirm('Are you sure you want to delete this order item?')) {
                return;
            }

            // const csrf_token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

            $.ajax({
                url: `delete-order-item/${orderItemId}`,
                method: 'DELETE',
                data: {
                    _token: csrf_token,
                },
                success: function(response) {
                    if (response.success) {
                        alert(response.message); 
                        location.reload();
                    } else {
                        alert('Error deleting order item: ' + response.message);
                    }
                },
                error: function(error) {
                    console.error('Error:', error);
                    alert('Something went wrong while deleting the item.');
                }
            });
        }

    });
</script>
@endpush
