@extends('layouts.master')
@section('title', 'Dashboard')
@section('content')
<!-- Begin Page Header-->
<div class="row">
    <div class="page-header">
        <div class="d-flex align-items-center">
            <h2 class="page-header-title">Dashboard</h2>
            {{-- <div>
                <ul class="breadcrumb">
                    <li class="breadcrumb-item"><a href="db-all.html"><i class="ti ti-home"></i></a></li>
                    <li class="breadcrumb-item active">Blank</li>
                </ul>
            </div> --}}
        </div>
    </div>
</div>
<!-- End Page Header -->

@role('super-admin|payment-receiver')
{{-- payment start --}}

    <!-- Small boxes (Stat box) -->
    <div class="row">
    <div class="col-lg-4 col-6">
        <!-- small box -->
        <div class="small-box bg-info">
        <div class="inner">
            <h3>{!! number_format((float)$pending_amount,2,'.','') !!}</h3>

            <p>Pending Payments</p>
        </div>
        <div class="icon">
            <i class="ion ion-social-usd"></i>
        </div>
        <a href="payments/pending-payments" class="small-box-footer">More info <i class="la la-arrow-circle-right"></i></a>
        </div>
    </div>
    <!-- ./col -->
    <div class="col-lg-4 col-6">
        <!-- small box -->
        <div class="small-box bg-success">
        <div class="inner">
            <h3>{!! number_format((float)$received_amount,2,'.','') !!}</h3>

            <p>Recceived Payments</p>
        </div>
        <div class="icon">
            <i class="ion ion-social-usd"></i>
        </div>
        <a href="payments/received-payments" class="small-box-footer">More info <i class="la la-arrow-circle-right"></i></a>
        </div>
    </div>
    <!-- ./col -->
    <div class="col-lg-4 col-6">
        <!-- small box -->
        <div class="small-box bg-warning">
        <div class="inner">
            <h3>{!! number_format((float)$due_amount,2,'.','') !!}</h3>

            <p>Due Outstanding Payments</p>
        </div>
        <div class="icon">
            <i class="ion ion-social-usd"></i>
        </div>
        <a href="payments/pending-payments?due_amount=1" class="small-box-footer">More info <i class="la la-arrow-circle-right"></i></a>
        </div>
    </div>
    <!-- ./col -->

    </div>
    <!-- /.row -->

{{-- payment end --}}
{{-- Low Quantity Item --}}

<div class="row flex-row">
    <div class="col-xl-12 col-12">
        <div class="widget has-shadow">
            <div class="widget-header bordered no-actions d-flex align-items-center">
                <h4>Inventory Items</h4>
            </div>
            <div class="widget-body">
                <div class="row">
                    <div class="col-md-12">

                        <div class="table-responsive">
                            <table id="tblPurchaseItems_edit" class="table table-hover order-list">
                                <thead>
                                    <tr>
                                        <th>Invoice No</th>
                                        <th>Date Of Purchase</th>
                                        <th>Material</th>
                                        <th>Article No</th>
                                        <th>Color NO</th>
                                        <th>Color</th>
                                        <th>Batch No</th>
                                        <th data-sorter="false" style="width:150px;">Roll No</th>
                                        <th style="width:150px;" class="meter">Available Meter / Total Meter</th>
                                        <th style="width:150px;" class="yard">Available Yard / Total Yard</th>
                                        <th data-sorter="false">Barcode</th>
                                        <th data-sorter="false">QRCode</th>
                                        <th>Cost</th>
                                        <th>Sold At</th>
                                        <th>Salesman Commision</th>
                                        <th>Discount</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @isset($items)
                                    @foreach ($items as $key => $item)
                                    <tr id="item-{{$item->id}}" class=@if ($item->qty==$item->available_qty) "table-success text-dark" @elseif($item->available_qty==0) "table-danger text-dark" @else "table-warning text-dark" @endif >
                                        <td>{{ $item->purchase->invoice_no }}</td>
                                        <td>{{ $item->purchase->purchase_date }}</td>
                                        <td>{{ $item->material->name }}</td>
                                        <td>
                                            <div id="print-article-{{$item->id}}">{{ $item->article_no }}</div>
                                        </td>
                                        <td>
                                            <div id="print-color-no-{{$item->id}}">{{$item->material->color_no}}</div>
                                        </td>
                                        <td>
                                            <div id="print-color-{{$item->id}}">{{$item->material->color}}</div>
                                        </td>
                                        <td>
                                            <div id="print-batch-{{$item->id}}">{{ $item->batch_no }}</div>
                                        </td>
                                        <td>
                                            <div id="print-roll-no-{{$item->id}}">{{ $item->roll_no }}</div>
                                        </td>
                                        <td>
                                            {{ $item->available_qty }} / {{ $item->qty }}
                                        </td>
                                        <td>
                                            {{ number_format((float)(meter2yard($item->available_qty)),2,'.','') }} / {{ number_format((float)(meter2yard($item->qty)),2,'.','') }}
                                        </td>
                                        <td>
                                            <div id="print-barcode-{{$item->id}}">{!! DNS1D::getBarcodeSVG($item->barcode,config('app.BARCODE_TYPE'), 1, 40)  !!}</div>
                                        </td>
                                        <td>
                                            <div id="print-qrcode-{{$item->id}}">{!!  DNS2D::getBarcodeSVG($item->article_no,'QRCODE')  !!}</div>
                                        </td>
                                        <td>
                                            {{ $item->purchase->price_thb }}
                                        </td>
                                        <td>
                                            {{ total_per_meter($item->purchase->price_thb,$item->purchase->shipping_cost_per_meter) }}
                                        </td>
                                        <td>

                                        </td>
                                        <td>
                                            {{ $item->purchase->discount }}
                                        </td>
                                    </tr>
                                    @endforeach
                                    @endisset
                                </tbody>
                            </table>
                            @isset($items)
                            {{ $items->render() }}
                            @endisset
                            {{-- @if ($items->hasPages())
                                <ul class="pagination">
                                    @if ($items->onFirstPage())
                                        <li class="btn btn-dark" style="display: none"><span>{{ __('Prev') }}</span></li>
                                    @else
                                        <li><a href="{{ $items->previousPageUrl() }}" class="btn btn-dark" rel="prev">{{ __('Prev') }}</a></li>
                                    @endif
                                    &nbsp;&nbsp;&nbsp;
                                    @if ($items->hasMorePages())
                                        <li><a href="{{ $items->nextPageUrl() }}" class="btn btn-dark" rel="next">{{ __('Next') }}</a></li>
                                    @else
                                        <li class="btn btn-dark" style="display: none"><span>{{ __('Next') }}</span></li>
                                    @endif
                                </ul>
                            @endif <br> --}}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
{{-- Low Quantity Item End --}}
{{-- Order List --}}
<div class="row flex-row">
    <div class="col-xl-12 col-12">
        <div class="widget has-shadow">
            <div class="widget-header bordered no-actions d-flex align-items-center">
                <h4>Latest Order</h4>
            </div>
            <div class="widget-body">
                <div class="row">
                    <div class="col-md-12">

                        <div class="table-responsive">
                            <table id="tblPurchaseItems_edit" class="table table-hover order-list">
                                <thead>
                                    <tr>
                                        <th>Customer Name</th>
                                        <th>Sales Person</th>
                                        <th data-sorter="false">Date</th>
                                        <th data-sorter="false">Materials</th>
                                        <th data-sorter="false">Colors</th>
                                        <th data-sorter="false">Meter</th>
                                        <th data-sorter="false">Price</th>
                                        <th data-sorter="false">Note</th>
                                        <th data-sorter="false">Order From</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @isset($orders)

                                    @foreach($orders as $order)
                                    <tr class="table-success text-dark" data-id="{{$order->id}}">
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
                                        <td>
                                            @if ($order->is_api == true)
                                                <i class="la la-mobile" aria-hidden="true"></i>
                                            @else
                                                <i class="la la-laptop" aria-hidden="true"></i>
                                            @endif
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
    </div>
</div>
{{-- Order List End --}}
@endrole

 @role('warehouse')
    <!-- Small boxes (Stat box) -->
    <div class="row">
         <!-- ./col -->
        <div class="col-lg-4 col-6">
            <!-- small box -->
            <div class="small-box bg-warning">
            <div class="inner">
                <h3>{{ $pending_orders }}</h3>

                <p>Pending order</p>
            </div>
            <div class="icon">
                <i class="ion ion-cube"></i>
            </div>
            <a href="order" class="small-box-footer">More info <i class="la la-arrow-circle-right"></i></a>
            </div>
        </div>
        <!-- ./col -->

        <!-- ./col -->
        <div class="col-lg-4 col-6">
            <!-- small box -->
            <div class="small-box bg-success">
            <div class="inner">
                <h3>{{ $complited_orders }}</h3>

                <p>Complited/Dispatch order</p>
            </div>
            <div class="icon">
                <i class="ion ion-cube"></i>
            </div>
            <a href="order" class="small-box-footer">More info <i class="la la-arrow-circle-right"></i></a>
            </div>
        </div>

    </div>
    <!-- /.row -->
    @endrole

@endsection
@push('after-styles')
    <style>
        .small-box {
  border-radius: 0.25rem;
  box-shadow: 0 0 1px rgba(0, 0, 0, 0.125), 0 1px 3px rgba(0, 0, 0, 0.2);
  display: block;
  margin-bottom: 20px;
  position: relative;
}

.small-box > .inner {
  padding: 10px;
}

.small-box > .small-box-footer {
  background: rgba(0, 0, 0, 0.1);
  color: rgba(255, 255, 255, 0.8);
  display: block;
  padding: 3px 0;
  position: relative;
  text-align: center;
  text-decoration: none;
  z-index: 10;
}

.small-box > .small-box-footer:hover {
  background: rgba(0, 0, 0, 0.15);
  color: #ffffff;
}

.small-box h3 {
  font-size: 2.2rem;
  font-weight: bold;
  margin: 0 0 10px 0;
  padding: 0;
  white-space: nowrap;
}

@media (min-width: 992px) {
  .col-xl-2 .small-box h3,
  .col-lg-2 .small-box h3,
  .col-md-2 .small-box h3 {
    font-size: 1.6rem;
  }
  .col-xl-3 .small-box h3,
  .col-lg-3 .small-box h3,
  .col-md-3 .small-box h3 {
    font-size: 1.6rem;
  }
}

@media (min-width: 1200px) {
  .col-xl-2 .small-box h3,
  .col-lg-2 .small-box h3,
  .col-md-2 .small-box h3 {
    font-size: 2.2rem;
  }
  .col-xl-3 .small-box h3,
  .col-lg-3 .small-box h3,
  .col-md-3 .small-box h3 {
    font-size: 2.2rem;
  }
}

.small-box p {
  font-size: 1rem;
}

.small-box p > small {
  color: #f8f9fa;
  display: block;
  font-size: 0.9rem;
  margin-top: 5px;
}

.small-box h3,
.small-box p {
  z-index: 5;
}

.small-box .icon {
  color: rgba(0, 0, 0, 0.15);
  z-index: 0;
}

.small-box .icon > i {
  font-size: 90px;
  position: absolute;
  right: 15px;
  top: 15px;
  transition: all 0.3s linear;
}

.small-box .icon > i.fa, .small-box .icon > i.fas, .small-box .icon > i.far, .small-box .icon > i.fab, .small-box .icon > i.glyphicon, .small-box .icon > i.ion {
  font-size: 70px;
  top: 20px;
}

.small-box:hover {
  text-decoration: none;
}

.small-box:hover .icon > i {
  font-size: 95px;
}

.small-box:hover .icon > i.fa, .small-box:hover .icon > i.fas, .small-box:hover .icon > i.far, .small-box:hover .icon > i.fab, .small-box:hover .icon > i.glyphicon, .small-box:hover .icon > i.ion {
  font-size: 75px;
}

@media (max-width: 767.98px) {
  .small-box {
    text-align: center;
  }
  .small-box .icon {
    display: none;
  }
  .small-box p {
    font-size: 12px;
  }
}
    </style>
@endpush
@push('scripts')
<script src="{{ asset('assets/js/chart/chart.min.js') }}"></script>
<script src="{{ asset('assets/js/owl-carousel/owl.carousel.min.js') }}"></script>
<script src="{{ asset('assets/js/progress/circle-progress.min.js') }}"></script>
<script src="{{ asset('assets/js/components/widgets/widgets.min.js') }}"></script>
@endpush
