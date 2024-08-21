@extends('layouts.master')
@section('title', 'Order')
@section('content')

<!-- Begin Page Header-->
<div class="row">
    <div class="page-header">
        <div class="d-flex align-items-center">
            <h2 class="page-header-title">Bookings</h2>
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
<!-- <div class="row flex-row">
    <div class="col-xl-12 col-12">
        <div class="widget has-shadow">
            <div class="widget-header bordered no-actions1 d-block align-items-center">
                Filter
            </div>
            <div class="widget-body">
                {!! Form::open(['method' => 'GET','route' => ['bookings']]) !!}
                <div class="form-group row d-flex align-items-center mt-3">
                    <div class="col-lg-4">
                        <label class="form-control-label col-lg-12">Search <div class="d-inline text-muted" style="font-size: 10px;">[Customer Name/Sales Persion/Material]</div></label>
                        {!! Form::text('search', '', array('class' => 'form-control')) !!}
                    </div>
                    <div class="col-lg-3">
                        <label class="form-control-label">&nbsp;</label>
                        <div class="form-action">
                            <input type="submit" class="btn btn-primary btn-square" value="Filter">
                        </div>
                    </div>
                </div>
                {!! Form::close() !!}
            </div>
        </div>
    </div>
</div> -->
<!-- Begin Row -->
<!-- Begin Row -->
<div class="row flex-row">
    <div class="col-xl-12 col-12">
        <div class="widget has-shadow">
            <div class="widget-body">
                <div class="table-responsive">
                    <table class="table table-hover mb-0 " id="order_tbl">
                        <thead>
                            <tr>
                                <th>Customer Name</th>
                                <th>Sales Person</th>
                                <th class="sorter-shortDate dateFormat-ddmmyyyy">Date</th>
                                <th data-sorter="false">Materials</th>
                                <th data-sorter="false">Colors</th>
                                <th data-sorter="false">Meter</th>
                                <th data-sorter="false">Price</th>
                                <th data-sorter="false">Note</th>
                                <!-- <th data-sorter="false" width="180px">Action</th> -->
                            </tr>
                        </thead>
                        <tbody>
                            @isset($orders)

                            @foreach($orders as $order)
                            <tr class="purchase-link" data-id="{{$order->id}}">
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
                                    {{$order_item->meter}} <br />
                                    @endforeach
                                    @endif
                                </td>
                                <td>
                                    @if ($order->order_items->count())
                                    @foreach ($order->order_items as $key => $order_item)
                                    {{$order_item->price}} <br />
                                    @endforeach
                                    @endif
                                </td>
                                <td> {{$order->note}} </td>
                                {{-- <td class="td-actions">
                                <a class="btn btn-primary btn-sm btn-square" href="{{ route('order.edit',$order->id) }}">Edit</a>
                                    @role('super-admin')
                                    @if (!$order->invoice)
                                        <a class="btn btn-secondary btn-sm btn-square mb-1" href="{{ route('invoice.create',$order->id) }}">Generate Invoice</a>
                                    @else
                                        <a class="btn btn-primary btn-sm btn-square" href="{{ route('invoice.edit',$order->invoice->id) }}" >Edit Invoice</a>
                                    @endif
                                    @endrole
                                    {!! Form::open(['method' => 'DELETE','route' => ['order.destroy', $order->id],'style'=>'display:inline', 'onsubmit'=>'return delete_confirm()']) !!}
                                    {!! Form::submit('Delete', ['class' => 'btn btn-danger btn-sm btn-square delete']) !!}
                                    {!! Form::close() !!}
                                </td> --}}
                            </tr>
                            @endforeach
                            @endisset
                        </tbody>
                    </table>
                    <!-- @isset($orders)
                    {{ $orders->render() }}
                    @endisset -->

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
        $(document).ready(function(){
            // $('#order_tbl').tablesorter({
            //     cssAsc: 'up',
		    //     cssDesc: 'down',
            //     cssNone: 'both'
            // });
            $('#order_tbl').DataTable({
                lengthMenu: [
                    [10, 25, 50,100,500,1000,'All'],
                    [10, 25, 50,100,500,1000,'All'],
                ],
                "aaSorting": []
            });
        })
    </script>
@endpush
