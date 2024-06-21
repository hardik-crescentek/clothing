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
                    <div class="col-lg-3">
                        <label class="form-control-label col-lg-12">Search <div class="d-inline text-muted" style="font-size: 10px;">[Customer Name/Sales Persion/Material]</div></label>
                        {!! Form::text('search', '', array('class' => 'form-control')) !!}
                    </div>
                    <div class="col-lg-2">
                        <label class="form-control-label col-lg-12">Start Date </label>
                        <input type="date" class="form-control" name="start_date">
                    </div>
                    <div class="col-lg-2">
                        <label class="form-control-label col-lg-12">End Date </label>
                        <input type="date" class="form-control" name="end_date">
                    </div>
                    <div class="col-lg-2">
                        <label class="form-control-label">&nbsp;</label>
                        <div class="form-action">
                            <a href="{{ url('/order') }}" class="btn btn-warning btn-square">Reset</a>
                            <input type="submit" class="btn btn-primary btn-square" value="Filter">
                        </div>
                    </div>
                </div>
                {!! Form::close() !!}
            </div>
        </div>
    </div>
</div>
<!-- Begin Row -->
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
                                    <th data-sorter="false">Note</th>
                                    <!-- <th data-sorter="false">Order Status</th> -->
                                    <th data-sorter="false" width="180px">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @isset($orders)
                                @foreach($orders as $order)
                                {{ isset($order->order_items['item_id']) }}
                                    @if($order->status == 0)
                                        <tr class="purchase-link" data-id="{{$order->id}}" style="color: #f0ad4e !important;">
                                            <td> {{$order->customer->firstname}} {{$order->customer->lastname}} </td>
                                            <td> {{$order->seller->firstname}} {{$order->seller->lastname}} </td>
                                            <td> {{$order->order_date}} </td>
                                            <td>
                                                @if ($order->order_items->count())
                                                @foreach ($order->order_items as $key => $order_item)
                                                {{$order_item->item->name}} <br />
                                                @endforeach
                                                @endif
                                            </td>
                                            <td>
                                                @if ($order->order_items->count())
                                                @foreach ($order->order_items as $key => $order_item)
                                                {{$order_item->item->color}} <br />
                                                @endforeach
                                                @endif
                                            </td>
                                            <td> 
                                                @if ($order->order_items->count())
                                                @foreach ($order->order_items as $key => $order_item)
                                                {{$order_item->item->article_no}} <br />
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
                                            <td> {{$order->note}} </td>
                                            <!-- <td>
                                                @if ($order->status == 1)
                                                <span class="badge-text badge-text-small success">Dispatch</span>
                                                @else
                                                <span class="badge-text badge-text-small warning">Pending</span>
                                                @endif
                                            </td> -->
                                            @role('super-admin')
                                            <td class="td-actions">
                                                <a data-toggle="modal" data-target="#order-status-model" data-id="{{ $order->id }}" class="btn btn-secondary btn-sm btn-square col-sm-6 mt-1" style="color: #366a2b !important;">Order <br> Status</a>
                                                <a class="btn btn-primary btn-sm btn-square col-sm-6" href="{{ route('order.edit',$order->id) }}">Edit</a>
                                                {{-- {{print_r(sizeof($order->invoice))}} --}}
                                                @role('super-admin')
                                                @if (!$order->invoice)
                                                    <a class="btn btn-secondary btn-sm btn-square col-sm-6 mt-1" href="{{ route('invoice.create',$order->id) }}">Generate <br> Invoice</a>
                                                @else
                                                    <a class="btn btn-primary btn-sm btn-square col-sm-6 mt-1" href="{{ route('invoice.edit',$order->invoice->id) }}" >Edit <br> Invoice</a>
                                                @endif
                                                @endrole
                                                {!! Form::open(['method' => 'DELETE','route' => ['order.destroy', $order->id],'style'=>'display:inline', 'onsubmit'=>'return delete_confirm()']) !!}
                                                {!! Form::submit('Delete', ['class' => 'btn btn-danger btn-sm btn-square delete col-sm-6 mt-1']) !!}
                                                {!! Form::close() !!}
                                                <a class="btn btn-primary btn-sm btn-square col-sm-6" href="{{ route('order.viewdetails',$order->id) }}">View Order <br> Details</a>
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
                       <!--  @isset($orders)
                        {{ $orders->render() }}
                        @endisset -->
                    </div>
                    <div class="tab-pane fade" id="tab-2" role="tabpanel" aria-labelledby="base-tab-2">
                        <table class="table table-hover mb-0 " id="order_tbl2">
                            <thead>
                                <tr>
                                    <th>Customer Name</th>
                                    <th>Sales Person</th>
                                    <th class="sorter-shortDate dateFormat-ddmmyyyy">Date</th>
                                    <th>Materials</th>
                                    <th>Colors</th>
                                    <th data-sorter="false">Meter</th>
                                    @role('super-admin')
                                    <th data-sorter="false">Price</th>
                                    @endrole
                                    <th data-sorter="false">Note</th>
                                    <th data-sorter="false">Remark</th>
                                    <!-- <th data-sorter="false">Order Status</th> -->
                                    @role('super-admin')
                                    <th data-sorter="false" width="180px">Action</th>
                                    @endrole
                                </tr>
                            </thead>
                            <tbody>
                                @isset($orders)

                                @foreach($orders as $order)
                                    @if($order->status == 1)
                                        <tr class="purchase-link" data-id="{{$order->id}}" style="color: #79b385 !important;">
                                            <td>{{$order->customer->firstname}} {{$order->customer->lastname}} </td>
                                            <td> {{$order->seller->firstname}} {{$order->seller->lastname}} </td>
                                            <td> {{$order->order_date}} </td>
                                            <td>
                                                @if ($order->order_items->count())
                                                @foreach ($order->order_items as $key => $order_item)
                                                {{$order_item->item->name}} <br />
                                                @endforeach
                                                @endif
                                            </td>
                                            <td>
                                                @if ($order->order_items->count())
                                                @foreach ($order->order_items as $key => $order_item)
                                                {{$order_item->item->color}} <br />
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
                                            <td> {{$order->note}} </td>
                                            <td> {{$order->remark}} </td>
                                            <!-- <td>
                                                @if ($order->status == 1)
                                                <span class="badge-text badge-text-small success">Dispatch</span>
                                                @else
                                                <span class="badge-text badge-text-small warning">Pending</span>
                                                @endif
                                            </td> -->
                                            @role('super-admin')
                                            <td class="td-actions">
                                               <!--  <a data-toggle="modal" data-target="#order-status-model" data-id="{{ $order->id }}" class="btn btn-secondary btn-sm btn-square col-sm-6 mt-1" style="color: #f0ad4e !important;">Order <br> Status</a> -->
                                                <a class="btn btn-primary btn-sm btn-square col-sm-6" href="{{ route('order.edit',$order->id) }}">Edit</a>
                                                {{-- {{print_r(sizeof($order->invoice))}} --}}
                                                @role('super-admin')
                                                @if (!$order->invoice)
                                                    <a class="btn btn-secondary btn-sm btn-square col-sm-6 mt-1" href="{{ route('invoice.create',$order->id) }}">Generate <br> Invoice</a>
                                                @else
                                                    <a class="btn btn-primary btn-sm btn-square col-sm-6 mt-1" href="{{ route('invoice.edit',$order->invoice->id) }}" >Edit <br> Invoice</a>
                                                @endif
                                                @endrole
                                                {!! Form::open(['method' => 'DELETE','route' => ['order.destroy', $order->id],'style'=>'display:inline', 'onsubmit'=>'return delete_confirm()']) !!}
                                                {!! Form::submit('Delete', ['class' => 'btn btn-danger btn-sm btn-square delete col-sm-6 mt-1']) !!}
                                                {!! Form::close() !!}
                                            </td>
                                            @endrole
                                        </tr>
                                    @endif
                                @endforeach
                                @endisset
                            </tbody>
                        </table>
                       <!--  @isset($orders)
                        {{ $orders->render() }}
                        @endisset -->
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

<script>
    function delete_confirm() {
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
        // $(document).ready(function(){
        //     $('#order_tbl').tablesorter({
        //         cssAsc: 'up',
		      //   cssDesc: 'down',
        //         cssNone: 'both'
        //     });
        // })
        $(document).ready(function () {
            $('#order_tbl').DataTable({
                lengthMenu: [
                    [10, 25, 50,100,500,1000,'All'],
                    [10, 25, 50,100,500,1000,'All'],
                ],
                "aaSorting": []
            });
        });
        $(document).ready(function () {
            $('#order_tbl2').DataTable({
                lengthMenu: [
                    [10, 25, 50,100,500,1000,'All'],
                    [10, 25, 50,100,500,1000,'All'],
                ],
                "aaSorting": []
            });
        });
    </script>
@endpush
