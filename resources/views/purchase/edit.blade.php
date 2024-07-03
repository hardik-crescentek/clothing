@extends('layouts.master')
@section('title', 'Purchase')
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
                <h4>Edit Purchase</h4>
            </div>
            <div class="widget-body">

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


                {!! Form::model($purchase, ['route' => ['purchase.update', $purchase->id],'method'=>'PATCH','id'=>'from_edit_purchase', 'class'=>"form-horizontal form-validate", 'novalidate','files' => true]) !!}
                <div class="row">
                    <div class="col-lg-6 row">
                        <div class="col-lg-3">
                            <div class="form-group">
                                <label class="form-control-label">Date of purchase<span class="text-danger ml-2">*</span></label>
                                {!! Form::text('purchase_date', null, array('id' => 'purchase_date','class' => 'form-control', 'data-validation'=>"required")) !!}
                            </div>
                        </div>
                        <div class="col-lg-3">
                            <div class="form-group">
                                <label class="form-control-label">Invoice No.<span class="text-danger ml-2">*</span></label>
                                {!! Form::text('invoice_no', null, array('id' => 'invoice_no','class' => 'form-control', 'data-validation'=>"required")) !!}
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label class="form-control-label">Supplier<span class="text-danger ml-2">*</span></label>
                                <div class="input-group">
                                    {!! Form::select('supplier_id', $suppliers, null, ['id' => 'supplier_id','class' => 'form-control custom-select','data-validation' => "required"]) !!}
                                    <div class="input-group-append">
                                        <span class="input-group-text">
                                            <a href="{{ route('supplier.create', ['redirect' =>  base64_encode(route('purchase.create'))]) }}" title="Add Supplier">
                                                <span><i class="fa fa-plus"></i></span>
                                            </a>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6 row">
                        <div class="col-lg-4">
                            <div class="form-group">
                                <label class="form-control-label">Purchase Type<span class="text-danger ml-2">*</span></label>
                                {!! Form::select('purchase_type', ['domestic'=>'Domestic', 'international'=>'International'],null, array('id'=>'purchase_type','class' => 'form-control custom-select', 'data-validation'=>"required")) !!}
                            </div>
                        </div>
                        <div class="col-lg-4">
                            <div class="form-group">
                                <label class="form-control-label">Currency Of Purchase<span class="text-danger ml-2">*</span></label>
                                {!! Form::select('currency_of_purchase', [''=>'Select Currency','USD'=>'USD','EUR'=>'EUR','CNY'=>'CNY','THB'=>'THB','INR'=>'INR'],null, array('id'=>'currency_of_purchase','class' => 'form-control custom-select', 'data-validation'=>"required")) !!}
                            </div>
                        </div>
                        <div class="col-lg-4">
                            <div class="form-group">
                                <label class="form-control-label">EX RATE<span class="text-danger ml-2">*</span></label>
                                {!! Form::text('ex_rate', null, array('id' => 'ex_rate','class' => 'form-control')) !!}
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="row col-lg-6">
                        <div class="col-lg-3">
                            <div class="form-group">
                                <label class="form-control-label">Total Meter<span class="text-danger ml-2">*</span></label>
                                {!! Form::text('total_meter', null, array('placeholder' => 'Meters','id'=>'total_meter','class' => 'form-control')) !!}
                            </div>
                        </div>
                        <div class="col-lg-3">
                            <div class="form-group">
                                <label class="form-control-label">Total Yards<span class="text-danger ml-2">*</span></label>
                                {!! Form::text('total_yard', null, array('placeholder' => 'Yards','id'=>'total_yard','class' => 'form-control','readonly'=>'true')) !!}
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label class="form-control-label">Import Tax<span class="text-danger ml-2">*</span></label>
                                {!! Form::text('import_tax', null, array('placeholder' => 'THB','id'=>'import_tax','class' => 'form-control')) !!}
                            </div>
                        </div>
                    </div>
                        
                    <div class="row col-lg-6">
                        <div class="col-lg-4">
                            <div class="form-group">
                                <label class="form-control-label">Transport & Shipping Paid<span class="text-danger ml-2">*</span></label>
                                {!! Form::text('transport_shipping_paid', null, array('placeholder' => 'THB','id'=>'transport_shipping_paid','class' => 'form-control')) !!}
                            </div>
                        </div>
                        <div class="col-lg-4">
                            <div class="form-group">
                                <label class="form-control-label">Discount</label>
                                {!! Form::text('discount', null, array('placeholder' => 'discount','class' => 'form-control','id'=>'discount')) !!}
                            </div>
                        </div>
                        <div class="col-lg-4">
                            <div class="form-group">
                                <label class="form-control-label">Attach Document</label>
                                {!! Form::file('attach_documents[]', [
                                        'class' => 'custom-file-input_0',
                                        'id' => 'attach_documents',
                                        'multiple' => true, // Enable multiple file selection
                                        'data-validation' => 'mime',
                                        'data-validation-allowing' => 'jpeg, jpg, png, pdf, doc, docx',
                                        'data-validation-error-msg-mime' => 'You can only upload image or document files'
                                    ]) !!}
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-3">
                        <div class="form-group">
                            <label class="form-control-label">Transportation & Shipping Cost Per Meter<span class="text-danger ml-2">*</span></label>
                            {!! Form::text('transport_shippment_cost_per_meter', null, array('class' => 'form-control','id'=>'transport_shippment_cost_per_meter','readonly'=>'readonly')) !!}
                        </div>
                    </div>
                </div>
                <!-- <div class="form-group row mb-3">
                    <div class="col-xl-6">
                        <div class="row">
                            <div class="col mb-3">
                                <label class="form-control-label">Payment Terms<span class="text-danger ml-2">*</span></label>
                                {!! Form::text('payment_terms', null, array('id' => 'payment_terms','class' => 'form-control')) !!}
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-6">
                        <label class="form-control-label">Price Per Meter<span class="text-danger ml-2">*</span></label>
                        <div class="row">
                            <div class="col">
                                {!! Form::text('price', null, array('placeholder' => 'USD','id'=>'price','class' => 'form-control', 'data-validation'=>"required")) !!}
                            </div>
                            <div class="col">
                                {!! Form::text('thb_ex_rate', null, array('placeholder' => 'Ex Rate','id'=>'thb_ex_rate','class' => 'form-control', 'data-validation'=>"required")) !!}
                            </div>
                            <div class="col">
                                {!! Form::text('price_thb', null, array('placeholder' => 'Bath','id'=>'price_thb','class' => 'form-control', 'data-validation'=>"required", 'readonly'=>'true')) !!}
                            </div>
                        </div>
                    </div>
                </div>
                <div class="form-group row mb-3">
                    <div class="col-xl-7">
                        <div class="row">
                            <div class="col">
                                <label class="form-control-label">Shipping Paid</label>
                                {!! Form::text('shipping_paid', null, array('placeholder' => 'Shipping THB','id'=>'shipping_paid','class' => 'form-control')) !!}
                            </div>
                            <div class="col">
                                <label class="form-control-label">Transportation</label>
                                {!! Form::text('transportation', null, array('placeholder' => 'THB','id'=>'transportation','class' => 'form-control')) !!}
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-5">
                        <div class="row">
                            <div class="col">
                                <label class="form-control-label">Tax</label>
                                <div class="input-group">
                                    {!! Form::text('total_tax', null, array('placeholder' => 'Tax','class' => 'form-control','id'=>'tax_per')) !!}
                                    <span class="input-group-addon addon-secondary">%</span>
                                </div>
                            </div>
                            <div class="col">
                                <label class="form-control-label">Gross Tax</label>
                                {!! Form::text('gross_tax', null, array('placeholder' => 'Gross Tax','class' => 'form-control','id'=>'gross_tax')) !!}
                            </div>
                            <div class="col">
                                <label class="form-control-label">Discount</label>
                                {!! Form::text('discount', null, array('placeholder' => 'discount','class' => 'form-control','id'=>'discount')) !!}
                            </div>
                        </div>
                    </div>
                </div>
                <div class="form-group row mb-3">
                    <div class="col-xl-6">
                        <div class="row">
                            <div class="col">
                                <label class="form-control-label">Shippment Cost Shipper</label>
                                {!! Form::text('shippment_cost_shipper', null, array('placeholder' => 'Shippment Cost Shipper','id'=>'shippment_cost_shipper','class' => 'form-control')) !!}
                            </div>
                            <div class="col">
                                <label class="form-control-label">Shippment Cost Destination</label>
                                {!! Form::text('shippment_cost_destination', null, array('placeholder' => 'Shippment Cost Destination','id'=>'shippment_cost_destination','class' => 'form-control')) !!}
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-6">
                        <label class="form-control-label">Attach Document</label>
                        <div class="row">
                            <div class="col">
                                <div class="custom-file">
                                    {!! Form::file('attach_document', ['class'=>'custom-file-input','id'=>'attach_document','data-validation'=>"mime",'data-validation-allowing'=>"jpeg, jpg, png, pdf, doc, docx",'data-validation-error-msg-mime'=>"You can only upload image or document file"]) !!}
                                    <label class="custom-file-label" for="customFile">Choose file</label>
                                  </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="form-group row mb-3">
                    <div class="col-xl-6">
                        <label class="form-control-label">Shippment Cost Per Meter</label>
                        <div class="row">
                            <div class="col">
                                {{-- <label class="form-control-label">Shipping Cost</label> --}}
                                {!! Form::text('shipping_cost_per_meter', null, array('placeholder' => 'Shipping Cost','class' => 'form-control','id'=>'shipping_cost')) !!}
                            </div>
                        </div>
                    </div>
                </div> -->
                <div class="row">
                    <div class="col-lg-12">
                        <div class="form-group">
                            <label class="form-control-label">Note</label>
                            {!! Form::textarea('note', null, ['class' => 'form-control','rows' => 3]); !!}
                        </div>
                    </div>
                </div>

                <div class="row my-4">
                    <div class="col-md-12">

                        <div class="card">
                            <h4 class="card-header">Purchase Items <a href="javascript:;" class="btn btn-primary p-2 btn-square btn-sm ml-5" data-toggle="modal" data-target="#addItemModal">Add Item</a>
                                <a href="javascript:;" style="display: none;" class="btn btn-primary btn-square btn-md float-right" id="add_single_row" ><i class="la la-plus p-0 m-0"></i></a>
                            </h4>
                            <div class="card-body">
                                <div class="table-responsive mt-3">
                                    <table id="tblPurchaseItems" class="table table-hover order-list">
                                        <thead>
                                            <tr>
                                                <th style="width:180px;">Material</th>
                                                <th style="width:160px;">Color</th>
                                                <th style="width:150px;">Article No</th>
                                                <th style="width:150px;">Color No</th>
                                                <th style="width:150px;">Batch No / Lot No</th>
                                                <th style="width:150px;">Width</th>
                                                <th style="width:150px;">Roll No</th>
                                                <th style="width:150px;">Meter</th>
                                                <th style="width:150px;">Yard</th>
                                            </tr>
                                        </thead>
                                        <tbody >

                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>

                <!-- <div class="form-group row mb-4 float-right">
                    <div class="col">
                        <button type="submit" class="btn btn-primary btn-lg">Save</button>
                    </div>
                </div> -->
                <div class="form-group row d-flex align-items-center mt-5">
                    <div class="col-lg-12 d-flex justify-content-center">
                        <button type="submit" class="btn btn-primary btn-lg">Save</button>
                    </div>
                </div>
                {!! Form::close() !!}
            </div>

        </div>
    </div>
</div>
<div class="row flex-row">
    <div class="col-xl-12 col-12">
        <div class="widget has-shadow">
            <div class="widget-header bordered no-actions d-flex align-items-center">
                <h4>Edit Purchase Items</h4>
                <a class="ml-auto btn btn-secondary btn-square" href="{{ route('printbarcode',['id' => $purchase->id,'invoice_no' => $purchase->invoice_no, 'printBarcode'=>1,'printQRCode'=>1,'printWithBatchNo'=>1,'printWithArticleNo'=>1,'printWithRollNo'=>1]) }}">Print Barcode</a>
            </div>
            <div class="widget-body">

                <div class="row">
                    <div class="col-md-12">
                        <div class="table-responsive">
                            <table id="tblPurchaseItems_edit" class="table table-hover order-list">
                                <thead>
                                    <tr>
                                        <th>Material</th>
                                        <th>Color</th>
                                        <th>Article No</th>
                                        <th>Color NO</th>
                                        <th>Batch No</th>
                                        <th>Barcode</th>
                                        <th>QRCode</th>
                                        <th style="width:150px;">Width</th>
                                        <th style="width:150px;">Roll No</th>
                                        <th style="width:150px;">Meter</th>
                                        <th style="width:150px;">Yard</th>
                                        <th style="width:150px;">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @isset($items)
                                    @foreach ($items as $key => $item)
                                        <tr id="item-{{$item->id}}" class='hasNewOrOldItem' data-item_id="{{$item->id}}">
                                            @if($item->material_id != '' && $item->material_id != 0)
                                            @php $material_name = $item->material->name; @endphp
                                            <td class="td-material valid" data-value="{{ $item->material->id }}" data-name="{{$item->material->name}}">{{ $item->material->name }}</td>
                                            @else
                                            @php $material_name = ""; @endphp
                                            <td class="td-material valid"> </td>
                                            @endif
                                            <td class="td-color valid" data-value="{{ $item->color }}" >{{ $item->color }}</td>
                                            <td class="td-article_no valid" data-value="{{ $item->article_no }}">{{ $item->article_no }}</td>
                                            <td class="td-color_no valid" data-value="{{ $item->color_no }}">{{ $item->color_no }}</td>
                                            <td class="td-batch_no valid" data-value="{{ $item->batch_no }}">{{ $item->batch_no }}</td>
                                            <td class="td-barcode valid" data-value="{{ $item->barcode }}">{!!  DNS1D::getBarcodeSVG($item->barcode,config('app.BARCODE_TYPE'), 1, 40)  !!}</td>
                                            <td class="td-qrcode valid" data-value="{{ $item->qrcode }}">{!!  DNS2D::getBarcodeSVG($item->qrcode,'QRCODE')  !!}</td>
                                            <td class="td-width valid" data-value="{{ $item->width }}">{{ $item->width }}</td>
                                            <td class="td-roll_no valid" data-value="{{ $item->roll_no }}">{{ $item->roll_no }}</td>
                                            <td class="td-qty td_meter_count valid" data-value="{{ $item->qty }}">{{ $item->qty }}</td>
                                            <td>{{ number_format((float) meter2yard($item->qty), 2, '.', '') }}</td>
                                            <td>
                                                <button type="button" class="btn btn-sm btn-secondary btn-square btn-roll-history mb-1" data-item_id="{{$item->id}}" data-total_roll="{{$item->qty}}" data-available_roll="{{$item->available_qty}}" data-name="{{$material_name}}" data-article_no="{{$item->article_no}}" data-color_no="{{$item->color_no}}" data-color="{{$item->color}}" data-roll_no="{{$item->roll_no}}" data-toggle="modal" data-target="#rollHistoryModel">Roll History</button>
                                                <button type="button" class="btn btn-sm btn-primary btn-square btn-edit-purchase-item" data-item_id="{{$item->id}}" data-toggle="modal" data-target="#editItemModal">Edit</button>
                                                {!! Form::open(['method' => 'DELETE','route' => ['purchase.deletePurchaseItem', $item->id],'style'=>'display:inline', 'onsubmit'=>'return delete_confirm()']) !!}
                                                {!! Form::submit('Delete', ['class' => 'btn btn-danger btn-sm btn-square delete delete-purchase-item']) !!}
                                                {!! Form::close() !!}
                                            </td>
                                        </tr>
                                    @endforeach
                                    @endisset
                                </tbody>
                            </table>
                        </div>
                        {{ $items->render() }}
                    </div>
                </div>

            </div>

        </div>
    </div>
</div>

<div class="row flex-row">
    <div class="col-xl-12 col-12">
        <div class="widget has-shadow">
            <div class="widget-header bordered no-actions d-flex align-items-center">
                <h4>Order History</h4>
            </div>
            <div class="widget-body">

                <div class="row">
                    <div class="col-md-12">
                        <div class="table-responsive">
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
                                    <th data-sorter="false">Remark</th>
                                    <th data-sorter="false" width="180px">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @isset($orders)
                                @foreach($orders as $order)
                                    <tr class="purchase-link" data-id="{{$order->id}}" style="color: #f0ad4e !important;">
                                        <td> {{$order->customer_firstname}} {{$order->customer_lastname}} </td>
                                        <td> {{$order->seller_firstname}} {{$order->seller_lastname}} </td>
                                        <td> {{$order->order_date}} </td>
                                        <td> {{$order->material_name}} </td>
                                        <td> {{$order->color}} </td>
                                        <td> {{$order->article_no}} </td>
                                        <td> {{$order->meter}} </td>
                                        <td> {{$order->price}} </td>
                                        <td> {{$order->note}} </td>
                                        <td> {{$order->remark}} </td>
                                        <td>
                                            @if (!$order->invoice)
                                                <a class="btn btn-secondary btn-sm btn-square col-sm-6 mt-1" href="{{ route('invoice.create',$order->id) }}">Generate <br> Invoice</a>
                                            @else
                                                <a class="btn btn-primary btn-sm btn-square col-sm-6 mt-1" href="{{ route('invoice.edit',$order->invoice->id) }}" >Edit <br> Invoice</a>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                                @endisset
                            </tbody>
                        </table>
                        </div>
                        {{ $items->render() }}
                    </div>
                </div>

            </div>

        </div>
    </div>
</div>


<div id="addItemModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" class="modal fade text-left">
    <div role="document" class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 id="modal-header" class="modal-title">Add Purchase Item</h5>
                <button type="button" data-dismiss="modal" aria-label="Close" class="close"><span aria-hidden="true">×</span></button>
            </div>
            <div class="modal-body">
                <form id="add_item_form" class="">
                    <div class="form-group">
                        <label class="form-control-label">Select Material<span class="text-danger ml-2">*</span></label>
                        {!! Form::select('material_id', $materials,null, array('id'=>'add_material_id','class' => 'form-control', 'data-validation'=>"required","placeholder"=>"--Select Material--",'style'=>"width:100%")) !!}
                    </div>
                    <div class="form-group">
                        <label class="form-control-label">Select Color<span class="text-danger ml-2">*</span></label>
                        {!! Form::select('color_id', [''=>'--Select Color--'],null, array('id'=>'add_color_id','class' => 'form-control custom-select', 'data-validation'=>"required",'style'=>"width:100%",'disabled'=>'disabled')) !!}
                    </div>
                    <div class="form-group">
                        <label class="form-control-label">Purchase Price<span class="text-danger ml-2">*</span></label>
                        {!! Form::text('add_purchase_price', null, array('id'=>'add_purchase_price','placeholder' => 'Purchase Price','class' => 'form-control ','readonly'=>'readonly')) !!}
                    </div>
                    <div class="form-group">
                        <label class="form-control-label">Article No.<span class="text-danger ml-2">*</span></label>
                        {!! Form::text('article_no', null, array('id'=>'add_article_no','placeholder' => 'Article No.','class' => 'form-control', 'data-validation'=>"required",'readonly'=>'readonly')) !!}
                        {!! Form::hidden('width', null, array('id'=>'add_width','class' => 'form-control add_width')) !!}
                        {!! Form::hidden('material_name', null, ['id'=>'add_material_name','class' => 'form-control add_material_name']) !!}
                    </div>
                    <div class="form-group">
                        <label class="form-control-label">Color No.<span class="text-danger ml-2">*</span></label>
                        {!! Form::text('color_no', null, array('id'=>'add_color_no','placeholder' => 'Color No','class' => 'form-control ', 'data-validation'=>"required",'readonly'=>'readonly')) !!}
                    </div>
                    <div class="form-group">
                        <label class="form-control-label">Batch No.<span class="text-danger ml-2">*</span></label>
                        {!! Form::text('batch_no', null, array('id'=>'add_batch_no','placeholder' => 'Batch No.','class' => 'form-control', 'data-validation'=>"required")) !!}
                    </div>
                    {{-- <div class="form-group">
                        <label class="form-control-label">Width<span class="text-danger ml-2">*</span></label>
                        {!! Form::text('width', null, array('id'=>'add_width','placeholder' => 'Width','class' => 'form-control', 'data-validation'=>"required")) !!}
                    </div> --}}

                    <div class="form-group">
                        <label class="form-control-label">Number Of Rolls<span class="text-danger ml-2">*</span></label>
                        {!! Form::number('number_of_rows', 1, array('id'=>'add_number_of_rolls', 'class' => 'form-control', 'data-validation'=>"required")) !!}
                    </div>
                    <div class="form-action float-right">
                        <button name="cancel_btn" id="cancel_btn" class="btn btn-primary">Cancel</button>
                        <button type="submit" id="save_continue" name="save_continue" class="btn btn-primary">Save & Continue</button>
                        <button type="submit" id="save_close" name="save_close" class="btn btn-primary">Save & Close</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<script type="text/template" id="templateAddItem">

    <td>{!! Form::select('material_id[]', $materials,null, array('class' => 'material form-control custom-select valid', 'data-validation'=>"required")) !!}</td>
    <!-- <td>{!! Form::hidden('material_id[]', null, array('class' => 'meter form-control', 'data-validation'=>"required")) !!}</td> -->
    <td>{!! Form::select('color[]', [],null, array('class' => 'color form-control valid','id'=>'color')) !!}</td>
    <td>{!! Form::text('article_no[]', null, array('class' => 'article_no form-control valid', 'data-validation'=>"required",'readonly'=>'readonly')) !!}</td>
    <td>{!! Form::text('color_no[]', null, array('class' => 'color_no form-control valid','readonly'=>'readonly','id'=>'color_no')) !!}</td>
    <td>{!! Form::text('batch_no[]', null, array('class' => 'batch_no form-control valid', 'data-validation'=>"required")) !!}</td>
    <td>{!! Form::text('width[]', null, array('class' => 'width form-control valid','readonly'=>'readonly')) !!}</td>
    <td>{!! Form::text('roll_no[]', null, array('class' => 'roll_no form-control valid', 'data-validation'=>"required")) !!}</td>
    <td>{!! Form::text('meter[]', null, array('class' => 'meter form-control valid', 'data-validation'=>"required")) !!}</td>
    <td><input class="yard form-control valid" name='yard[]' type="text"></td>

</script>


<div id="editItemModal" tabindex="-1" role="dialog" aria-labelledby="Edit" aria-hidden="true" class="modal fade text-left">
    <div role="document" class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 id="modal-header" class="modal-title">Edit Purchase Item</h5>
                <button type="button" data-dismiss="modal" aria-label="Close" class="close"><span aria-hidden="true">×</span></button>
            </div>
            <div class="modal-body">
                {!! Form::open(array('route' => 'purchase.update-item','method'=>'PATCH','id'=>'edit_item_form', 'class'=>"form-horizontal form-validate", 'novalidate')) !!}
                <input type="hidden" id="edit_item_id" name="purchaseItemId" value="" />
                <div class="form-group">
                    <label class="form-control-label">Select Material<span class="text-danger ml-2">*</span></label>
                    {!! Form::select('material_id', $materials,null, array('id'=>'edit_material_id','class' => 'form-control custom-select', 'data-validation'=>"required",'style'=>'width:100%')) !!}
                </div>
                <div class="form-group">
                    <label class="form-control-label">Select Color<span class="text-danger ml-2">*</span></label>
                    {!! Form::select('color_id', [''=>'--Select Color--'],null, array('id'=>'edit_color_id','class' => 'form-control custom-select', 'data-validation'=>"required",'style'=>"width:100%")) !!}
                </div>

                <div class="form-group">
                    <label class="form-control-label">Article No.<span class="text-danger ml-2">*</span></label>
                    {!! Form::text('article_no', null, array('id'=>'edit_article_no','placeholder' => 'Article No.','class' => 'form-control', 'data-validation'=>"required",'readonly'=>'readonly')) !!}
                </div>
                <div class="form-group">
                    <label class="form-control-label">Color No.<span class="text-danger ml-2">*</span></label>
                    {!! Form::text('color_no', null, array('id'=>'edit_color_no','placeholder' => 'Color No','class' => 'form-control ', 'data-validation'=>"required",'readonly'=>'readonly')) !!}
                </div>
                <div class="form-group">
                    <label class="form-control-label">Batch No.<span class="text-danger ml-2">*</span></label>
                    {!! Form::text('batch_no', null, array('id'=>'edit_batch_no','placeholder' => 'Batch No.','class' => 'form-control', 'data-validation'=>"required")) !!}
                </div>
                {{-- <div class="form-group">
                    <label class="form-control-label">Width<span class="text-danger ml-2">*</span></label>
                    {!! Form::text('width', null, array('id'=>'edit_width','placeholder' => 'Width','class' => 'form-control', 'data-validation'=>"required")) !!}
                </div> --}}
                <div class="form-group">
                    <label class="form-control-label">Roll No.<span class="text-danger ml-2">*</span></label>
                    {!! Form::number('roll_no', 1, array('id'=>'edit_roll_no', 'class' => 'form-control', 'data-validation'=>"required")) !!}
                </div>
                <div class="form-group">
                    <label class="form-control-label">Qty<span class="text-danger ml-2">*</span></label>
                    {!! Form::number('qty', 1, array('id'=>'edit_qty', 'class' => 'form-control', 'data-validation'=>"required")) !!}
                </div>
                <div class="form-action float-right">
                    <button type="submit" name="update_btn" class="btn btn-primary">Update</button>
                </div>
                {!! Form::close() !!}
            </div>
        </div>
    </div>
</div>

<div id="rollHistoryModel" tabindex="-1" role="dialog" aria-labelledby="Edit" aria-hidden="true" class="modal fade">
    <div role="document" class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 id="modal-header" class="modal-title"><div id="header_info"></div></h5>
                <button type="button" data-dismiss="modal" aria-label="Close" class="close"><span aria-hidden="true">×</span></button>
            </div>
            <div class="modal-body">
                <div class="container">
                    <div class="row">
                        <div class="col-12 ">
                            <div style="float: left;" id="total_roll">
                            </div>
                            <div style="float: right;" id="available_roll">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="table-responsive">
                    <table class="table" id="rollhistorytbl">
                        <thead class="text-center">
                            <tr>
                                <th>Invoice Number</th>
                                <th>Customer Name</th>
                                <th>Price</th>
                                <th>Meter</th>
                                <th>Yard</th>
                                <th>Date</th>
                            </tr>
                            </thead>
                            <tbody>

                            </tbody>
                    </table>
                </div>
            </div>
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
<script src="{{ asset('assets/js/datepicker/moment.min.js') }}"></script>
<script src="{{ asset('assets/js/datepicker/daterangepicker.js') }}"></script>
<script type="text/javascript">
    function delete_confirm() {
        return confirm("Are you sure want to delete?");
    }
    (function($) {

        function calculateTransportationShippingCostPerMeter() {
            var totalMeter = parseFloat($('#total_meter').val()) || 0;
            var importTax = parseFloat($('#import_tax').val()) || 0;
            var transportationPaid = parseFloat($('#transport_shipping_paid').val()) || 0;

            // Calculate the transportation & shipping cost per meter
            var transportationShippingCostPerMeter = (importTax + transportationPaid) / totalMeter;

            // Update the displayed value
            $('#transport_shippment_cost_per_meter').val(transportationShippingCostPerMeter.toFixed(2));
        }

        
        function convertMetersToYards() {
            var meters = $('#total_meter').val();
            if (meters) {
                // Perform conversion assuming 1 meter = 1.09361 yards
                var yards = meters * 1.09361;
                // Update the Total Yards field
                $('#total_yard').val(yards.toFixed(2)); // Adjust to display 2 decimal places
            } else {
                $('#total_yard').val(''); // If no input, clear the Total Yards field
            }
        }
        
        // Trigger calculation when any relevant input field changes
        $('#total_meter, #import_tax, #transport_shipping_paid').on('input', calculateTransportationShippingCostPerMeter);
      
        $(document).on('input', '.meter_val', function(){
            getTotalMeters();
        });

        $(document).on('click', '#delete_row', function(){
            var row_id = $(this).data('row_id');
            $('#' + row_id).remove();
            getTotalMeters();
        });

        function getTotalMeters() {
            var totalMeter = 0;
            $("input[name='meter[]']").each(function(){
                totalMeter += $(this).val() ? parseFloat($(this).val()) : 0;
            });

            $('#total_meter').val(totalMeter);
            $('#total_meter').trigger('change');

            var yards = totalMeter * 1.09361;
            $('#total_yard').val(yards.toFixed(2));
            $('#total_yard').trigger('change');

            calculateTransportationShippingCostPerMeter();
        }

        $(document).ready(function() {

            // Trigger the conversion and calculation when the form is opened or the page is loaded
            convertMetersToYards();
            calculateTransportationShippingCostPerMeter();

            $('#supplier_id').change(function() {
                var supplierId = $(this).val();
                if (supplierId) {
                    $.ajax({
                        url: '{{ route('get.suppliers') }}',
                        type: 'GET',
                        data: {
                            supplier_id: supplierId
                        },
                        success: function(data) {
                            console.log('AJAX Response:', data); // Debugging: Log the response to console

                            // Set purchase type dropdown value
                            $('#purchase_type').val(data.purchase_type);
                            $('#purchase_type').trigger('change');

                            // Set currency type dropdown value
                            $('#currency_of_purchase').val(data.currency_type).change();
                            $('#currency_of_purchase').trigger('change');
                        },
                        error: function(xhr, status, error) {
                            console.error('AJAX Error:', error); // Log any AJAX errors for debugging
                        }
                    });
                } else {
                    // If no supplier selected, clear purchase type and currency type fields
                    $('#purchase_type').val('').change();
                    $('#currency_of_purchase').val('').change();
                }
            });

            // Assuming you have a change event listener on #purchase_type dropdown
            $('#purchase_type').change(function() {
                var selectedPurchaseType = $(this).val();
                var exRateInput = $('#ex_rate');
                console.log('test'+selectedPurchaseType);

                if (selectedPurchaseType === 'domestic') {
                    // Set ex_rate value to 1 and disable the input
                    exRateInput.val('1');
                    // exRateInput.prop('disabled', true); // Optionally disable the input
                } else {
                    // Allow the user to manually input ex_rate
                    exRateInput.val(''); // Clear previous value if any
                    // exRateInput.prop('disabled', false); // Enable the input
                }
            });

            $('#total_meter').on('input', function() {
                var meters = $(this).val();
                if (meters) {
                    // Perform conversion assuming 1 meter = 1.09361 yards
                    var yards = meters * 1.09361;
                    // Update the Total Yards field
                    $('#total_yard').val(yards.toFixed(2)); // Adjust to display 2 decimal places
                } else {
                    $('#total_yard').val(''); // If no input, clear the Total Yards field
                }
            });
        });

        $('#thb_ex_rate, #price_usd').keyup(function() {
            var price_thb = 0;
            var thb_ex_rate = parseFloat($('#thb_ex_rate').val());
            var price_usd = parseFloat($('#price_usd').val());
            if (!isNaN(thb_ex_rate) && !isNaN(price_usd)) {
                price_thb = (price_usd * thb_ex_rate).toFixed(2);
            }

            $('#price_thb').val(price_thb);
        });
        var last_row_data=null;

        var add_selected_material_name=null;
        var add_color=[];


        $('#add_material_id').select2({
            dropdownParent: $('#add_item_form'),
            width: 'resolve',
        });
        $(document).on('change','#add_material_id',function(){
            var name=$(this).val();
            var data={!! json_encode($materials2) !!};
            $.each(data,function(index,value){
                if(name==value.name){
                    add_selected_material_name=value.name;
                    $('#add_article_no').val(value.article_no);
                    $('#add_width').val(value.width);
                    // $('#add_material_name').val(value.name+' - '+value.color);
                    $('#add_color_id').attr('disabled',false);
                }
            });
            add_color.length=0;
            $.each(data,function(i,v){
                if(add_selected_material_name==v.name){
                  var taxt = (v.color_no) ? v.color_no + ' - ' : '';
                  taxt += v.color;
                    add_color.push({'id':v.id,'text':taxt});
                }
            });
            $('#add_color_id').html('');
            $('#add_color_id').html('<option value="" selected="selected">--Select Color--</option>');
            $('#add_color_id').select2({dropdownParent: $('#add_item_form'),width: 'resolve',data:add_color});
        });
        $('#add_color_id').select2({
            dropdownParent: $('#add_item_form'),
            width: 'resolve',
        });
        $(document).on('change','#add_color_id',function(){
            var id=$(this).val();
            var data={!! json_encode($materials2) !!};
            $.each(data,function(index,value){
                if(id==value.id){
                    $('#add_color_no').val(String(value.color_no).padStart(2,"0"));
                    // $('#add_material_id').val(value.id);
                }
            });
        });
        $(document).on('click','#add_item_model_btn',function(){
            $('#add_purchase_price').val($('#price').val());
        });

        $.validate({
            form: '#from_add_material',
            modules: 'file'
        });

        var save_continue = false;
        $(document).on('click','#save_continue',function(){
            save_continue = true;
        });
        $(document).on('click','#save_close',function(){
            save_continue = false;
        });

        $.validate({
            form: '#add_item_form',
            onSuccess: function($form) {
                last_row_data=$form;
                addItem($form);
                if(!save_continue){
                    $($form).trigger("reset");
                    $('#addItemModal').modal('hide');
                    $('#add_color_id').attr('disabled',true);
                    $('#add_color_id').html('');
                    $('#add_color_id').html('<option value="" selected="selected">--Select Color--</option>');
                }
                return false; // Will stop the submission of the form
            },
        });
        $(document).on('click','#cancel_btn',function(){
            $('#add_item_form').trigger("reset");
            $('#addItemModal').modal('hide');
            $('#add_color_id').attr('disabled',true);
            $('#add_color_id').val(['','--Select Color--']);
            return false;
        });
        $(document).on('click','#add_single_row',function(){
            $('#add_material_id', last_row_data).val($('#tblPurchaseItems tbody tr:last').find('.material').val());
            $('#add_color_id', last_row_data).html($('#tblPurchaseItems tbody tr:last').find('.color').html());
            $('#add_color_id', last_row_data).val($('#tblPurchaseItems tbody tr:last').find('.color option:selected').val());
            $('#add_article_no', last_row_data).val($('#tblPurchaseItems tbody tr:last').find('.article_no').val());
            $('#add_color_no', last_row_data).val($('#tblPurchaseItems tbody tr:last').find('.color_no').val());
            $('#add_batch_no', last_row_data).val($('#tblPurchaseItems tbody tr:last').find('.batch_no').val());
            $('#add_width', last_row_data).val($('#tblPurchaseItems tbody tr:last').find('.width').val());
            $('#add_number_of_rolls', last_row_data).val(1);
            var roll_no=parseInt($('#tblPurchaseItems tbody tr:last').find('.roll_no').val());
            $('#add_number_of_rolls',last_row_data).data("roll_no",(roll_no+1));

            addItem(last_row_data);
        });

        $.validate({
            form: '#edit_item_form',
            // onSuccess: function($form) {
            //     saveItem($form);
            //     $($form).trigger("reset");
            //     $('#editItemModal').modal('hide');
            //     return false; // Will stop the submission of the form
            // },
        });


        $('#editItemModal').on('shown.bs.modal', function(e) {
            var edit_selected_material_name=null;
            var edit_color="<option value=''>--Select Color--</option>";
            var item_id = $(e.relatedTarget).data('item_id');
            var material = $('#item-' + item_id).find('.td-material').data('value');
            var material_name=$('#item-' + item_id).find('.td-material').data('name');
            var color = $('#item-' + item_id).find('.td-color').data('id');
            var article_no = $('#item-' + item_id).find('.td-article_no').data('value');
            var color_no = $('#item-' + item_id).find('.td-color_no').data('value');
            var batch_no = $('#item-' + item_id).find('.td-batch_no').data('value');
            // var width = $('#item-' + item_id).find('.td-width').data('value');
            var roll_no = $('#item-' + item_id).find('.td-roll_no').data('value');
            var qty = $('#item-' + item_id).find('.td-qty').data('value');
            edit_selected_material_name=material_name;

            $('#edit_item_id').val(item_id);
            $('#edit_material_id').val(material_name).trigger('change');
            $('#edit_material_id').select2({
                dropdownParent: $('#edit_item_form'),
                width: 'resolve',
            });
            $('#edit_color_id').select2({
                dropdownParent: $('#edit_item_form'),
                width: 'resolve',
            });
            $('#edit_color_id').html('');
            var data={!! json_encode($materials2) !!};
            $.each(data,function(i,v){
                if(edit_selected_material_name==v.name){
                    edit_color+="<option value='"+v.id+"'>"+v.color+"</option>";

                }
            });
            $('#edit_color_id').html(edit_color);

            $('#edit_color_id').val(color);
            $('#edit_article_no').val(article_no);
            $('#edit_color_no').val(color_no);
            $('#edit_batch_no').val(batch_no);
            // $('#edit_width').val(width);
            $('#edit_roll_no').val(roll_no);
            $('#edit_qty').val(qty);


        });

        $(document).on('change','#edit_material_id',function(){
            var edit_selected_material_name=null;
            var edit_color="<option value=''>--Select Color--</option>";
            var name=$(this).val();
            var data={!! json_encode($materials2) !!};
            $.each(data,function(index,value){
                if(name==value.name){
                    edit_selected_material_name=value.name;
                    $('#edit_article_no').val(value.article_no);
                    // $('#edit_color_id').attr('disabled',false);
                    $('#edit_color_no').val('');
                }
            });

            $.each(data,function(i,v){
                if(edit_selected_material_name==v.name){
                  var taxt = (v.color_no) ? v.color_no + ' - ' : '';
                  taxt += v.color;
                    edit_color+="<option value='"+v.id+"'>"+taxt+"</option>";
                }
            });
            $('#edit_color_id').html('');
            $('#edit_color_id').html(edit_color);
            // $('#edit_color_id').select2({dropdownParent: $('#edit_item_form'),width: 'resolve',data:edit_color});
        });

        $(document).on('change','#edit_color_id',function(){
            var id=$(this).val();
            var data={!! json_encode($materials2) !!};
            $.each(data,function(index,value){
                if(id==value.id){
                    $('#edit_color_no').val(String(value.color_no).padStart(2,"0"));
                    // $('#edit_material_id').val(value.id);
                }
            });
        });

        $('#genrate_code').on("click", function() {
            $.get('{{url("genrate_code")}}', function(data) {
                $("#input_barcode").val(data);
            });
        });

        $(document).on('keyup', '#tblPurchaseItems input.yard', function() {
            var yard = $(this).val();
            if (!isNaN(yard) && yard) {
                var $thisRow = $(this).closest('tr.purchaseItem');
                $('input.meter', $thisRow).val((yard/1.094).toFixed(2));
            }
        });
        $(document).on('keyup', '#tblPurchaseItems input.meter', function() {
            var meter = $(this).val();
            meter = parseFloat(meter);
            if (!isNaN(meter) && meter) {
                var $thisRow = $(this).closest('tr.purchaseItem');
                $('input.yard', $thisRow).val(meter2yard(meter).toFixed(2));
            }
        });

        $(window).keydown(function(e) {
            if (e.which == 13) {
                var $targ = $(e.target);

                if (!$targ.is("textarea") && !$targ.is(":button,:submit")) {
                    var focusNext = false;
                    $(this).find(":input:visible:not([disabled],[readonly]), a").each(function() {
                        if (this === e.target) {
                            focusNext = true;
                        } else if (focusNext) {
                            $(this).focus();
                            return false;
                        }
                    });

                    return false;
                }
            }
        });
        $('#purchase_date').daterangepicker({
            singleDatePicker: true,
            showDropdowns: true,
            locale: {
                format: 'DD/MM/YYYY'
            }
        });

        // $(window).on('load',function(){
        //     check_currency();
        // });
        $(document).on('change','#purchase_type',function(){
            check_currency();
        });
        $(document).on('change','#currency_of_purchase',function(){
            var price_type=$(this).val();
            $('#price').attr('placeholder',price_type);
        });
        $(document).on('change','#thb_ex_rate',function(){
           shipping_cost();
        });
        $(document).on('change','#total_meter',function(){
           shipping_cost();
        });
        $(document).on('change','#shipping_paid',function(){
           shipping_cost();
        });
        $(document).on('change','#transportation',function(){
           shipping_cost();
        });
        $(document).on('change','#tax_per',function(){
           shipping_cost();
        });
        $(document).on('change','#gross_tax',function(){
           shipping_cost();
        });
        $(document).on('change','#discount',function(){
           shipping_cost();
        });

        function check_currency(){
        var purchase_type=$('#purchase_type').val();
        if(purchase_type=="domestic"){
            $('#currency_of_purchase').val('THB');
            $('#price').attr('placeholder','THB');
        }
        else{
            $('#currency_of_purchase').val('');
        }
    }
    function shipping_cost(){
        var price_thb = $('#price_thb').val()=='' ? 0 : parseFloat($('#price_thb').val());
        var shipping_paid = $('#shipping_paid').val()=='' ? 0 : parseFloat($('#shipping_paid').val());
        var transportation = $('#transportation').val()==''? 0 : parseFloat($('#transportation').val());
        var tax_per = $('#tax_per').val()=='' ? 0 : parseFloat($('#tax_per').val());
        var tax_thb = (price_thb*tax_per)/100;
        var gross_tax = $('#gross_tax').val()=='' ? 0 : parseFloat($('#gross_tax').val());
        var discount = $('#discount').val()=='' ? 0 : parseFloat($('#discount').val());
        var total_meter = $('#total_meter').val()=='' ? 0 : parseFloat($('#total_meter').val());

        var shipping_cost = (shipping_paid + transportation + tax_thb + gross_tax - discount) / total_meter;
        $('#shipping_cost').val(shipping_cost.toFixed(2));

    }

    function addItem($form) {
        var roll_no=0;
        if($('#add_number_of_rolls',$form).data('roll_no')){
            roll_no=$('#add_number_of_rolls',$form).data('roll_no');
        }
        var material_name = $('#add_material_id ', $form).val();
        var material_id=0;
        if(roll_no){
            material_id= $('#add_color_id', $form).val();
            // console.log(material_id);
        }
        else{
            material_id = $('#add_color_id option:selected', $form).val();
        }
        // var color_id = $('#add_color_id option:selected', $form).text();
        var number_of_rolls = $('#add_number_of_rolls', $form).val();
        var article_no = $('#add_article_no', $form).val();
        var color_no = $('#add_color_no', $form).val();
        var batch_no = $('#add_batch_no', $form).val();
        var width = $('#add_width', $form).val();
        $template = $('#templateAddItem').html();
        for (i = 0; i < number_of_rolls; i++) {
            var $uniqueId = uuid();
            var $tr = $('<tr class="purchaseItem" id="' + $uniqueId + '">').append($template);
            $('#tblPurchaseItems tbody').append($tr);
            $('#' + $uniqueId).find('.material').val(material_name);
            $('#' + $uniqueId).find('.material').attr('data-row_id',$uniqueId);


            var color_list="";
            $.each({!! json_encode($materials2) !!},function(i,v){
                if(material_name==v.name){
                    color_list+="<option value='"+v.id+"'>"+v.color+"</option>";
                }
            });
            $('#' + $uniqueId).find('.color').html('');
            $('#' + $uniqueId).find('.color').html(color_list);
            $('#' + $uniqueId).find('.color').val(material_id);
            $('#' + $uniqueId).find('.color').attr('data-row_id',$uniqueId);
            $('#' + $uniqueId).find('.article_no').val(article_no);
            $('#' + $uniqueId).find('.color_no').val(String(color_no).padStart(2,"0"));
            $('#' + $uniqueId).find('.batch_no').val(batch_no);
            $('#' + $uniqueId).find('.width').val(width);
            if(roll_no){
                $('#' + $uniqueId).find('.roll_no').val(roll_no);
            }
            else{
                $('#' + $uniqueId).find('.roll_no').val(i + 1);
            }
        }

        if(($('.purchaseItem').length)!=0){
            $('#add_single_row').css('display','block');
        }

        getNewAddedMeter();
    };


    $(document).on('change','.material',function(){
        var name=$(this).val();
        var row_id=$(this).data('row_id');
        var data={!! json_encode($materials2) !!};
        $.each(data,function(index,value){
            if(name==value.name){
                // $('#'+row_id).find('.color').val(value.color);
                // $('#'+row_id).find('.color_no').val(String(value.color_no).padStart(2,"0"));
                // $('#'+row_id).find('.article_no').val(value.article_no);
                // $('#'+row_id).find('.width').val(value.width);
                $('#'+row_id).find('.color_no').val('');
                $('#'+row_id).find('.article_no').val('');
                $('#'+row_id).find('.width').val('');
                $('#'+row_id).find('.batch_no').val('');
                $('#'+row_id).find('.roll_no').val('');
            }
        });
        var color_list="<option value=''>--Select Color--</option>";
            $.each(data,function(i,v){
                if(name==v.name){
                  var taxt = (v.color_no) ? v.color_no + ' - ' : '';
                  taxt += v.color;
                    color_list+="<option value='"+v.id+"'>"+taxt+"</option>";
                }
            });
            $('#'+row_id).find('.color').html(color_list);
    });
    $(document).on('change','.color',function(){
            var id=$(this).val();
            var row_id=$(this).data('row_id');
            var data={!! json_encode($materials2) !!};
            $.each(data,function(index,value){
                if(id==value.id){
                    // $('#'+row_id).find('.color').val(value.color);
                    $('#'+row_id).find('.color_no').val(String(value.color_no).padStart(2,"0"));
                    $('#'+row_id).find('.article_no').val(value.article_no);
                    $('#'+row_id).find('.width').val(value.width);
                }
            });
        });
    $('#rollHistoryModel').on('shown.bs.modal', function(e) {
        $('#rollhistorytbl tbody').empty();
        var item_id = $(e.relatedTarget).data('item_id');
        var total_roll=$(e.relatedTarget).data('total_roll');
        var available_roll=$(e.relatedTarget).data('available_roll');
        var name = $(e.relatedTarget).data('name');
        var article_no = $(e.relatedTarget).data('article_no');
        var color_no = $(e.relatedTarget).data('color_no');
        var color = $(e.relatedTarget).data('color');
        var roll_no = $(e.relatedTarget).data('roll_no');
        $('#total_roll').html("Total Roll : "+total_roll);
        $('#available_roll').html("Available Roll : "+available_roll);
        $('#header_info').html(name + " - " + article_no + " - " + color_no + " - " + color + " - " + roll_no);
        $.ajax({
            url: '{{route("purchase.roll-history")}}',
            data: {'item_id' : item_id},
            dataType: "json",
            success: function(data) {
                var tr;
                if(data.length){
                    $.each(data,function(i,v){
                            var date=new Date(v.created_at);
                            tr="<tr><td>"+v.invoice.invoice_no+"</td>";
                            tr+="<td>"+v.invoice.customer.firstname+" "+v.invoice.customer.lastname+"</td>";
                            tr+="<td>"+v.invoice_item.price+"</td>";
                            tr+="<td>"+v.meter+"</td>";
                            tr+="<td>"+meter2yard(v.meter).toFixed(2)+"</td>";
                            tr+="<td>"+date.getDate()+" / "+(date.getMonth()+1)+" / "+date.getFullYear()+"</td></tr>";
                    });
                }
                else{
                    tr="<tr class='text-center'><td colspan=6> No History For This Roll </td></tr>";
                }

                $('#rollhistorytbl tbody').append(tr);
            }
        });

    });
    getEditMeter();
    $(".btn-edit-purchase-item,.delete-purchase-item").on('click',function(){
        getEditMeter();
    });
    function getEditMeter() {
        var getMeter = 0;
        $('#tblPurchaseItems_edit tbody tr').each( function() {
            getMeter += parseFloat($('.td_meter_count',this).text());
        });
        $('#total_meter').val(getMeter);
    }
    function getNewAddedMeter(){
        $(".meter").on('keyup',function(){
            getEditMeter();
            var getNewMeter = parseFloat($('#total_meter').val());
            $('.meter').each( function() {
                var getMe = parseFloat($(this).val());
                getNewMeter += isNaN(getMe) ? 0 : getMe;
            });

            $('#total_meter').val(getNewMeter);
        });

    }

    $(document).on('submit','#from_edit_purchase',function(){
        var checkNewItemClass =  $('#tblPurchaseItems tbody tr').find('.valid');
        var checkOldItemClass =  $('#tblPurchaseItems_edit tbody tr').find('.valid');
        if (checkNewItemClass.length > 0 || checkOldItemClass.length > 0) {
            return true;
        }else{
            new Noty({
                        type: 'warning',
                        text: 'Please select item first',
                        timeout: 2500,
                    }).show();
            return false;
        }
    });
})(jQuery);
</script>
@endpush
@push('after-styles')
    <style>
        @media (max-width: 1451px){
            .delete{
                margin-top: 3% ;
            }
        }

    </style>
@endpush
