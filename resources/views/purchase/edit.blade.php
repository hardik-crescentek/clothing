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
                                <!-- {!! Form::text('total_meter', null, array('placeholder' => 'Meters','class' => 'form-control total_meter','id'=>'total_meter')) !!} -->
                                {!! Form::text('total_meter', null, array('placeholder' => 'Meters','id'=>'total_meter','class' => 'form-control total_meter','data-validation' => "required")) !!}
                            </div>
                        </div>
                        <div class="col-lg-3">
                            <div class="form-group">
                                <label class="form-control-label">Total Yards<span class="text-danger ml-2">*</span></label>
                                {!! Form::text('total_yard', null, array('placeholder' => 'Yards','class' => 'form-control total_yard','id'=>'total_yard','readonly'=>'true')) !!}
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
                    <div class="col-lg-3">
                        <div class="form-group">
                            <label class="form-control-label">Total No Of Rolls<span class="text-danger ml-2">*</span></label>
                            {!! Form::text('no_of_rolls', null, array('placeholder' => 'Total No Of Rolls','class' => 'form-control','id'=>'no_of_rolls','data-validation' => "required")) !!}
                        </div>
                    </div>
                    <div class="col-lg-3">
                        <div class="form-group">
                            <label class="form-control-label">Total No Of Bales Arrived<span class="text-danger ml-2">*</span></label>
                            {!! Form::text('no_of_bales', null, array('placeholder' => 'Total No Of Bales Arrived','class' => 'form-control','id'=>'no_of_bales','data-validation' => "required")) !!}
                        </div>
                    </div>
                    <div class="col-lg-3">
                        <div class="form-group">
                            <label class="form-control-label">WareHouse<span class="text-danger ml-2">*</span></label>
                            {!! Form::select('warehouse_id', [''=>' --Select WareHouse-- ']+$wareHouse, null, [
                                'id' => 'warehouse',
                                'class' => 'form-control custom-select',
                                'data-validation' => "required"
                            ]) !!}
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-12">
                        <div class="form-group">
                            <label class="form-control-label">Note</label>
                            {!! Form::textarea('note', null, ['class' => 'form-control','rows' => 3]); !!}
                        </div>
                    </div>
                </div>

                <hr class="mt-5;">
                <div class="widget-header bordered no-actions d-flex align-items-center justify-content-between" id="toggle-form" style="padding: 15px; background-color: #f8f9fa; cursor: pointer; border: 1px solid #dee2e6;">
                    <h4 class="mb-0">Add Article & Color</h4>
                    <button class="btn btn-outline-gray btn-sm" id="toggle-icon" style="font-size: 24px;">+</button>
                </div>
                               

                <div id="additional-section">
                    @foreach($articlesWithColors as $index => $articleWithColors)
                        <div class="form-group row">
                            <!-- Article Dropdown -->
                            <div class="col-md-4">
                                <label for="article">Article:</label>
                                <select class="form-control select2 article-select" name="articles[{{ $index }}][article]" data-index="{{ $index }}">
                                    <option value="">-- Select Article --</option>
                                    @foreach($articleNumbers as $articleNumber)
                                        <option value="{{ $articleNumber }}" {{ $articleWithColors['article_name'] == $articleNumber ? 'selected' : '' }}>
                                            {{ $articleNumber }}
                                        </option>
                                    @endforeach
                                </select>
                                <input type="hidden" name="articles[{{ $index }}][article_id]" class="article-id" value="{{ $articleWithColors['article_id'] }}">
                            </div>

                            <!-- Color Dropdown with "Select All" -->
                            <div class="col-md-4">
                                <label for="color">Color:</label>
                                <select class="form-control select2 color-select" name="articles[{{ $index }}][colors][]" multiple="multiple">
                                    <!-- Add the "Select All" option at the top -->
                                    <option value="select_all">Select All Colors</option>
                                    @foreach ($articleWithColors['all_colors'] as $color)
                                        <option value="{{ $color['color_id'] }}" 
                                            {{ in_array($color['color_id'], $articleWithColors['colors']) ? 'selected' : '' }}>
                                            {{ $color['name'] }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>


                            <!-- Add or Remove Button -->
                            <div class="col-md-4">
                                @if($loop->first)
                                    <button type="button" id="add-row" class="btn btn-success mt-4">+</button>
                                @else
                                    <button type="button" class="btn btn-danger remove-row mt-4">-</button>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>


                <hr>

                <div class="row my-4">
                    <div class="col-md-12">

                        <div class="card">
                            <h4 class="card-header">Purchase Items <a href="javascript:;" class="btn btn-primary p-2 btn-square btn-sm ml-5" data-toggle="modal" data-target="#addItemModal">Add Item</a>
                                <a href="javascript:;" style="display: none;" class="btn btn-primary btn-square btn-md float-right" id="add_single_row" ><i class="la la-plus p-0 m-0"></i></a>
                                <button type="button" class="btn btn-danger p-2 btn-square btn-sm btn-square ml-2" id="delete_selected">Delete</button>
                                <button type="button" class="btn btn-primary p-2 btn-square btn-sm ml-2" id="generate_roll_piece">Generate Roll & Piece Numbers</button>
                            </h4>
                            <div class="card-body">
                                <div class="table-responsive mt-3">
                                    <table id="tblPurchaseItems" class="table table-hover order-list">
                                        <thead>
                                            <tr>
                                                <th style="width:3%;"><input type="checkbox" id="select_all"></th>
                                                <th style="width:9%;">Brand</th>
                                                <th style="width:10%;">Article No</th>
                                                <th style="width:9%;">Color</th>
                                                <th style="width:6%;">Color No</th>
                                                <th style="width:8%;">Batch/Lot No</th>
                                                <th style="width:9%;">Width(cm)</th>
                                                <th style="width:8%;">Meter<i class="fas fa-sort sort-icon ml-1"></i></th>
                                                <th style="width:8%;">Yard<i class="fas fa-sort sort-icon ml-1"></th>
                                                <th style="width:6%;">Roll No</th>
                                                <th style="width:23%;">Piece</th>
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

                <div class="form-group row d-flex align-items-center mt-5">
                    <div class="col-lg-12 d-flex justify-content-center">
                        <button type="submit" class="btn btn-primary btn-lg">Update</button>
                        <a class="btn btn-secondary btn-lg ml-1" href="{{ route('purchase.index') }}"> Cancel</a>
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
                        <label class="form-control-label">Select Invoice No.<span class="text-danger ml-2">*</span></label>
                        {!! Form::select('invoice_no',$invoiceNumbers,null, array('id'=>'add_invoice_no','class' => 'form-control custom-select ',"placeholder"=>"--Select Invoice No.--", 'data-validation'=>"required",'style'=>"width:100%")) !!}
                    </div>
                    <div class="form-group">
                        <label class="form-control-label">Article No.<span class="text-danger ml-2">*</span></label>
                        {!! Form::select('article_no',$articleNumbers,null, array('id'=>'add_article_no','class' => 'form-control custom-select ',"placeholder"=>"--Select Article No.--", 'data-validation'=>"required",'style'=>"width:100%")) !!}
                        {!! Form::hidden('width', null, array('class' => 'add_width form-control','id'=>"add_width")) !!}
                    </div>
                    <div class="form-group">
                        <label class="form-control-label">Color<span class="text-danger ml-2">*</span></label>
                        {!! Form::select('color_id',$colorMaterial,null, array('id'=>'add_color_id','class' => 'form-control custom-select ',"placeholder"=>"--Select Color--", 'data-validation'=>"required",'style'=>"width:100%")) !!}
                    </div>
                    <div class="form-group">
                        <label class="form-control-label">Batch / Lot No.<span class="text-danger ml-2">*</span></label>
                        {!! Form::text('batch_no', null, array('id'=>'add_batch_no','placeholder' => 'Batch No.','class' => 'form-control', 'data-validation'=>"required")) !!}
                    </div>
                    <div class="form-group">
                        <label class="form-control-label">Number Of Rolls<span class="text-danger ml-2">*</span></label>
                        {!! Form::number('number_of_rows', 1, array('id'=>'add_number_of_rolls', 'class' => 'form-control', 'data-validation'=>"required")) !!}
                    </div>

                    <div class="form-action d-flex justify-content-center">
                        <button name="cancel_btn" id="cancel_btn" class="btn btn-primary">Cancel</button>
                        <!-- <button type="submit" id="save_continue" name="save_continue" class="btn btn-primary">Save & Continue</button> -->
                        <button type="submit" id="save_close" name="save_close" class="btn btn-primary">Save & Close</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<script type="text/template" id="templateAddItem">

    <td><input type="checkbox" class="row_checkbox"></td>                                    
    <td>{!! Form::text('brand[]',null, array('class' => 'brand form-control valid','data-validation'=>"required",'readonly'=>'readonly')) !!}</td>
    <td>{!! Form::text('article_no[]', null, array('class' => 'article_no form-control valid', 'data-validation'=>"required",'readonly'=>'readonly')) !!}</td>
    <td>{!! Form::select('color[]',[], null, array('class' => 'color form-control valid','id'=>'color')) !!}</td>
    <td>{!! Form::text('color_no[]', null, array('class' => 'color_no form-control valid','readonly'=>'readonly','id'=>'color_no')) !!}</td>
    <td>{!! Form::text('batch_no[]', null, array('class' => 'batch_no form-control valid', 'data-validation'=>"required")) !!}</td>
    <td>{!! Form::text('width[]', null, array('class' => 'width form-control valid','readonly'=>'readonly')) !!}</td>
    <td>{!! Form::text('meter[]', null, array('class' => 'meter meter_val form-control valid','id' => 'meter_val', 'data-validation'=>"required")) !!}</td>
    <td>{!! Form::text('yard[]', null, array('class' => 'yard yard_val form-control valid','id' => 'yard_val', 'data-validation'=>"required")) !!}</td>
    <td>{!! Form::text('roll_no[]', null, array('class' => 'roll_no form-control valid', 'data-validation'=>"required")) !!}</td>
    <td>{!! Form::text('piece_no[]', null, array('class' => 'piece_no form-control valid', 'readonly'=>'readonly')) !!}</td>
    <td>{!! Form::hidden('add_invoice_no[]', null, array('class' => 'invoice_no_hidden')) !!}</td>

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
                    <input type="hidden" name="mat_id" value="">
                    {!! Form::select('material_id', $materials,null, array('id'=>'edit_material_id','class' => 'form-control custom-select', 'data-validation'=>"required",'style'=>'width:100%','disabled')) !!}
                </div>
                <div class="form-group">
                    <label class="form-control-label">Select Color<span class="text-danger ml-2">*</span></label>
                    {!! Form::select('color_id',$colors, null, array('id'=>'edit_color_id','class' => 'form-control custom-select', 'data-validation'=>"required",'style'=>"width:100%",'disabled')) !!}
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
                <div class="form-group row d-flex align-items-center mt-5">
                    <div class="col-lg-12 d-flex justify-content-center">
                        <button type="submit" name="update_btn" class="btn btn-primary">Update</button>
                    </div>
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
    .select2-container {
        width: 100% !important; /* Ensures Select2 takes the full width of its container */
    }
    
    /* Custom styles for select2 */
    .select2-container--default .select2-selection--multiple .select2-selection__choice {
        border: none !important;
    }
    .select2-container .select2-selection--multiple .select2-selection__rendered {
        display: ruby !important;
    }
    .select2-container--default .select2-search--inline .select2-search__field {
        width: 100% !important; /* Set full width */
        min-width: 100px; /* Ensure there's a minimum width for better display */
    }
</style>
@endpush
@push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/js/select2.min.js"></script>
<script src="{{ asset('assets/js/datepicker/moment.min.js') }}"></script>
<script src="{{ asset('assets/js/datepicker/daterangepicker.js') }}"></script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.tablesorter/2.31.3/js/jquery.tablesorter.min.js" integrity="sha512-qzgd5cYSZcosqpzpn7zF2ZId8f/8CHmFKZ8j7mU4OUXTNRd5g+ZHBPsgKEwoqxCtdQvExE5LprwwPAgoicguNg==" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.tablesorter/2.31.3/js/parsers/parser-input-select.min.js" integrity="sha512-1yWDRolEDA6z68VeUHdXNFZhWYteCOlutcPMPuDtX1f7/doKecWLx87shPRKx8zmxdWA0FV9mNRUr9NnSwzwyw==" crossorigin="anonymous"></script>

<script type="text/javascript">

    function delete_confirm() {
        return confirm("Are you sure want to delete?");
    }
    (function($) {

        // Function to fetch colors based on the selected article
        function fetchColors(article, $colorSelect, selectedColorIds = []) {
            $.ajax({
                url: '{{ route('get.colors') }}',
                type: 'GET',
                data: { article: article },
                success: function(data) {
                    $colorSelect.empty(); // Clear existing options

                    // Add the "Select All" option at the top
                    $colorSelect.append(new Option("Select All Colors", "select_all"));

                    // Populate new options
                    $.each(data, function(index, color) {
                        var isSelected = selectedColorIds.includes(color.color_id.toString()); // Check if color is selected
                        $colorSelect.append(new Option(color.name, color.color_id, false, isSelected));
                    });

                    $colorSelect.trigger('change'); // Trigger change to initialize Select2
                },
                error: function(xhr, status, error) {
                    console.error('Error fetching colors:', error);
                }
            });
        }

        // Function to populate article dropdowns
        function populateArticleSelect() {
            $('.article-select').each(function() {
                var $articleSelect = $(this);
                var selectedArticle = $articleSelect.val();

                // Clear the existing options and add a placeholder
                $articleSelect.empty().append(new Option('-- Select Article --', ''));

                // Populate with available article options
                var articleNumbers = @json($articleNumbers);  // Assuming $articleNumbers is passed from Blade as JSON

                $.each(articleNumbers, function(index, article) {
                    // Add each article as an option in the select dropdown
                    var isSelected = selectedArticle === article;
                    $articleSelect.append(new Option(article, article, false, isSelected));
                });

                // Initialize or update the Select2 plugin
                $articleSelect.select2();
            });
        }


        // Handle article change event to dynamically load colors
        $(document).on('change', '.article-select', function() {
            var selectedArticle = $(this).val();
            var $colorSelect = $(this).closest('.form-group.row').find('.color-select');

            // Fetch colors for the selected article
            if (selectedArticle) {
                var selectedColorIds = @json($articlesWithColors).find(a => a.article_name === selectedArticle)?.colors.map(c => c.color_id) || [];
                fetchColors(selectedArticle, $colorSelect, selectedColorIds);
            } else {
                $colorSelect.empty().append(new Option("-- Select Color --", ""));
            }
        });

        // Handle "Select All" functionality for colors
        $(document).on('change', '.color-select', function() {
            var $colorSelect = $(this);
            
             // If the "Select All" option is selected, select all colors
            if ($colorSelect.val().includes('select_all')) {
                $colorSelect.find('option').each(function() {
                    if ($(this).val() !== 'select_all') {
                        $(this).prop('selected', true); // Select all options except "Select All"
                    }
                });
                // Remove the "Select All" from the selection to prevent loop
                $colorSelect.val($colorSelect.val().filter(value => value !== 'select_all'));
                $colorSelect.trigger('change.select2'); // Update Select2 without triggering a recursive loop
            }
        });

        // On page load, populate colors for already selected articles
        $('.article-select').each(function() {
            var selectedArticle = $(this).val();
            var $colorSelect = $(this).closest('.form-group.row').find('.color-select');
            var selectedColorIds = @json($articlesWithColors).find(a => a.article_name === selectedArticle)?.colors.map(c => c.color_id) || [];

            // Fetch and populate colors for the selected article
            if (selectedArticle) {
                fetchColors(selectedArticle, $colorSelect, selectedColorIds);
            }
        });

        // Dynamically add a new row
        $('#add-row').on('click', function() {
            var index = $('#additional-section .form-group.row').length;
            var newRow = `
                <div class="form-group row">
                    <div class="col-md-4">
                        <label for="article">Article:</label>
                        <select class="form-control select2 article-select" name="articles[${index}][article]">
                            <option value="">-- Select Article --</option>
                        </select>
                    </div>

                    <div class="col-md-4">
                        <label for="color">Color:</label>
                        <select class="form-control select2 color-select" name="articles[${index}][colors][]" multiple="multiple">
                            <option value="select_all">Select All Colors</option>
                        </select>
                    </div>

                    <div class="col-md-4">
                        <button type="button" class="btn btn-danger remove-row mt-4">-</button>
                    </div>
                </div>
            `;

            // Append new row and initialize Select2
            $('#additional-section').append(newRow);
            initializeSelect2();

            // Populate the article select for the new row
            var $newArticleSelect = $('#additional-section .article-select').last();
            populateArticleSelect();

            // Populate colors when an article is selected in the new row
            $newArticleSelect.on('change', function() {
                var selectedArticle = $(this).val();
                var $newColorSelect = $(this).closest('.form-group.row').find('.color-select');
                fetchColors(selectedArticle, $newColorSelect);
            });
        });

        // Initialize Select2 for existing rows
        function initializeSelect2() {
            $('.select2').each(function() {
                if (!$(this).data('select2')) {
                    $(this).select2();
                }
            });
        }

        // Initialize Select2 on page load
        initializeSelect2();

        // Populate articles on page load
        populateArticleSelect();

        // Remove a row
        $(document).on('click', '.remove-row', function() {
            $(this).closest('.form-group.row').remove();
        });

        function calculateTransportationShippingCostPerMeter() {
            var totalMeter = parseFloat($('#total_meter').val()) || 0;
            var importTax = parseFloat($('#import_tax').val()) || 0;
            var transportationPaid = parseFloat($('#transport_shipping_paid').val()) || 0;

            // Calculate the transportation & shipping cost per meter
            var transportationShippingCostPerMeter = (importTax + transportationPaid) / totalMeter;

            // Update the displayed value
            if(totalMeter == 0){
                $('#transport_shippment_cost_per_meter').val(0);
            } else {
                $('#transport_shippment_cost_per_meter').val(transportationShippingCostPerMeter.toFixed(2));
            }
        }

        // Select all checkboxes functionality
        $(document).on('change', '#select_all', function() {
            $('.row_checkbox').prop('checked', $(this).prop('checked'));
        });

        // Delete selected items functionality
        $(document).on('click', '#delete_selected', function() {
            var selected = [];
            $('.row_checkbox:checked').each(function() {
                selected.push($(this).closest('tr'));
            });

            if (selected.length === 0) {
                alert("Please select at least one item to delete.");
            } else {
                if (confirm("Are you sure you want to delete selected items?")) {
                    // selected.forEach(function(item) {
                    //     item.remove();
                    // });

                    selected.forEach(function(row) {
                        console.log("test this");
                        var meterValue = parseFloat(row.find('.meter_val').val());
                        console.log(meterValue);
                        console.log("meterValue");
                        // if (!isNaN(meterValue)) {
                        //     var currentTotal = parseFloat($('#total_meter').val());
                        //     var totlameter = currentTotal - meterValue;
                        //     $('#total_meter').val(totlameter.toFixed(2)); // Subtract deleted item's meter value
                        //     var yards = totlameter * 1.09361;
                        //     // Update the Total Yards field
                        //     $('#total_yard').val(yards.toFixed(2)); // Adjust to display 2 decimal places
                        // }
                        row.remove();
                    });
                    // getNewAddedMeter();
                    // updateTotalMeterAfterDelete();
                }
            }
        });

        // Delete row functionality
        $(document).on('click', '.delete_row', function(){
            $(this).closest('tr').remove();
            getNewAddedMeter();
        });
        
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
        // $('#total_meter, #import_tax, #transport_shipping_paid').on('input', calculateTransportationShippingCostPerMeter);
        $('#total_meter, #import_tax, #transport_shipping_paid').on('input', calculateTransportationShippingCostPerMeter);

      
        $(document).on('keyup', '.meter_val', function(){
            // getTotalMeters();
            getNewAddedMeter();
        });

        $(document).on('keyup', '.yard_val', function(){
            // getTotalMeters();
            getNewAddedMeter();
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

        $(document).on('click', '#delete_row', function(){
            var row_id = $(this).data('row_id');
            $('#' + row_id).remove();
            getNewAddedMeter();
        });

        $(document).ready(function() {

            // Toggle the additional form section
            $('#toggle-form').on('click', function(event) {
                // Prevent form submission on button click
                event.preventDefault();

                // Toggle the visibility of the section
                $('#additional-section').toggle();
                
                // Toggle the + to - and vice versa
                var icon = $('#toggle-icon');
                icon.text(icon.text() === '+' ? '-' : '+');
            });    




             // Add the click event listener to the button
             $('#generate_roll_piece').click(function() {
                generateRollAndPieceNumbers();
            });

            // sorting logic start
            var sortOrder = 1; // 1 for ascending, -1 for descending
            var $sortIcon = $('.sort-icon');

            // Handle click event on sort icon
            $sortIcon.on('click', function() {
                sortOrder *= -1; // Toggle sort order

                // Update sort icon based on sort order
                if (sortOrder === 1) {
                    $sortIcon.removeClass('fa-sort-down').addClass('fa-sort-up');
                } else {
                    $sortIcon.removeClass('fa-sort-up').addClass('fa-sort-down');
                }

                // Sort table rows based on meter value
                var rows = $('#tblPurchaseItems tbody > tr').get();
                rows.sort(function(rowA, rowB) {
                    var meterA = parseFloat($(rowA).find('.meter').val()) || 0;
                    var meterB = parseFloat($(rowB).find('.meter').val()) || 0;
                    return sortOrder * (meterA - meterB);
                });

                // Reorder the table rows
                $.each(rows, function(index, row) {
                    $('#tblPurchaseItems').children('tbody').append(row);
                });
            });
            // sorting logic end

            // Click event for "Add Item" button
            $('#add_item_model_btn').on('click', function() {
                var currentInvoiceNo = $('#invoice_no').val();

                // Update invoice dropdown
                updateInvoiceDropdown(currentInvoiceNo);

                // Display the modal
                $('#addItemModal').modal('show');
            });

            // Function to update the invoice number dropdown
            function updateInvoiceDropdown(currentInvoiceNo) {
                $('#add_invoice_no').empty(); // Clear existing options

                // Add the placeholder option
                $('#add_invoice_no').append('<option value="">--Select Invoice No.--</option>');

                // Add options for all invoice numbers
                $.each(invoiceNumbers, function(id, number) {
                    $('#add_invoice_no').append('<option value="' + id + '">' + number + '</option>');
                });

                // Add current invoice number if it exists and not already in the list
                if (currentInvoiceNo && !(currentInvoiceNo in invoiceNumbers)) {
                    $('#add_invoice_no').append('<option value="' + currentInvoiceNo + '">' + currentInvoiceNo + '</option>');
                }
            }

            // Handle form submission inside the modal
             $('#add_item_form').submit(function(event) {
                event.preventDefault();
                // Your logic to handle form submission
                // Example: AJAX call to submit form data
            });

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

        function generateRollAndPieceNumbers() {
            // Get all rows of purchase items
            const $rows = $('#tblPurchaseItems tbody tr');
            
            // Update the roll numbers and piece numbers
            $rows.each((index, row) => {
                const $row = $(row);
                const rollNo = index + 1;
                const articleNo = $row.find('.article_no').val();
                const colorName = $row.find('.color option:selected').text() || '';
                const invoiceNo = $row.find('.invoice_no_hidden').val() || '';
                const dateOfPurchase = $('#purchase_date').val() || '';
                const totalRolls = $rows.length;
                console.log("generate piece no and roll no.");
                console.log(colorName);
                console.log(invoiceNo);
                console.log(row);
                
                const pieceValue = `${articleNo}_${colorName}_${invoiceNo}_${dateOfPurchase}_${rollNo}_${totalRolls}`;
                
                $row.find('.roll_no').val(rollNo);
                $row.find('.piece_no').val(pieceValue);
            });
        }

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

        $(document).on('change','#add_article_no',function(){
            var data = {!! json_encode($materials2) !!};
            var row_id=$(this).data('row_id');
            var articleNo = $(this).val();
            var colorDropdown = $('#add_color_id');
            var colorList = "<option value=''>--Select Color--</option>";

            // Clear previous selection in material dropdown
            $('#' + row_id).find('.material').empty();

            $.each(data, function(index, value) {
                if (articleNo == value.article_no) {
                    var text = (value.color_no ? value.color_no + ' - ' : '') + value.color;
                    colorList += "<option value='" + value.id + "'>" + text + "</option>";
                }
            });

            colorDropdown.html(colorList).trigger('change');
        });

        $('#add_material_id').select2({
            dropdownParent: $('#add_item_form'),
            width: 'resolve',
        });
        $('#add_invoice_no').select2({
            dropdownParent: $('#add_item_form'),
            width: 'resolve',
        });
        $('#add_article_no').select2({
            dropdownParent: $('#add_item_form'),
            width: 'resolve',
        });
        $('#add_color_id').select2({
            dropdownParent: $('#add_item_form'),
            width: 'resolve',
        });
        $(document).on('change','#add_article_no',function(){
            var data = {!! json_encode($materials2) !!};
            var row_id=$(this).data('row_id');
            var articleNo = $(this).val();
            var colorDropdown = $('#add_color_id');
            var colorList = "<option value=''>--Select Color--</option>";

            // Clear previous selection in material dropdown
            $('#' + row_id).find('.material').empty();

            $.each(data, function(index, value) {
                if (articleNo == value.article_no) {
                    var text = (value.color_no ? value.color_no + ' - ' : '') + value.color;
                    colorList += "<option value='" + value.id + "'>" + text + "</option>";
                }
            });

            colorDropdown.html(colorList).trigger('change');
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
                $($form).trigger("reset");
                $('#add_material_id').val('').trigger('change');
                $('#add_color_id').val('').trigger('change');
                // $('#add_color_id').attr('disabled',true);
                // $('#add_color_id').attr('disabled',true);
                if(!save_continue){
                    $('#addItemModal').modal('hide');
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
            console.log("edit call");
            var materials2 = @json($materials2);
            var item_id = $(e.relatedTarget).data('item_id');
            var material_name = $('#item-' + item_id).find('.td-material').data('name');
            var color = $('#item-' + item_id).find('.td-color').data('value');
            var article_no = $('#item-' + item_id).find('.td-article_no').data('value');
            var color_no = $('#item-' + item_id).find('.td-color_no').data('value');
            var batch_no = $('#item-' + item_id).find('.td-batch_no').data('value');
            var roll_no = $('#item-' + item_id).find('.td-roll_no').data('value');
            var qty = $('#item-' + item_id).find('.td-qty').data('value');

            $('#edit_item_id').val(item_id);
            $('#edit_material_id').val(material_name).trigger('change');

            // Initialize select2 for material
            $('#edit_material_id').select2({
                dropdownParent: $('#edit_item_form'),
                width: 'resolve',
            });

            // Populate color options based on the selected material
            var edit_color = "<option value=''>--Select Color--</option>";
            var material_id = null;
            $.each(materials2, function(i, v) {
                console.log("test");
                if (material_name == v.name) {
                    console.log(color);
                    if(color == v.color){
                        material_id = v.id;
                    }
                    edit_color += "<option value='" + v.color + "'>" + v.color + "</option>";
                }
            });
            console.log("material_id");
            console.log(material_id);
            $('#edit_color_id').html(edit_color);

            // Initialize select2 for color
            $('#edit_color_id').select2({
                dropdownParent: $('#edit_item_form'),
                width: 'resolve',
            });

            // Ensure options are loaded before setting the value
            $('#edit_color_id').val(color).trigger('change');

            // Set other form field values
            $('#edit_article_no').val(article_no);
            $('#edit_color_no').val(color_no);
            $('#edit_batch_no').val(batch_no);
            $('#edit_roll_no').val(roll_no);
            $('#edit_qty').val(qty);
            // Set the material_id hidden input value
            $('input[name="mat_id"]').val(material_id);
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
        var color_id=0;
        if(roll_no){
            color_id= $('#add_color_id', $form).val();
            color_name = $('#add_color_id option:selected', $form).text().split(' - ')[1];
        }
        else{
            color_id = $('#add_color_id option:selected', $form).val();
            color_name = $('#add_color_id option:selected', $form).text().split(' - ')[1];
        }
        var number_of_rolls = $('#add_number_of_rolls', $form).val();
        var article_no = $('#add_article_no', $form).val();
        var invoice_no = $('#add_invoice_no', $form).val();
        var color_no = $('#add_color_no', $form).val();
        var batch_no = $('#add_batch_no', $form).val();
        var date_of_purchase = $('#purchase_date').val();
        var total_roll = $('#add_number_of_rolls', $form).val();
        $template = $('#templateAddItem').html();
        for (i = 0; i < number_of_rolls; i++) {
            var roll_no = i+1;
            var $uniqueId = uuid();
            var $tr = $('<tr class="purchaseItem" id="' + $uniqueId + '">').append($template);
            $('#tblPurchaseItems tbody').append($tr);

            var color_list="";
            var material_list="";
            $.each({!! json_encode($materials2) !!},function(i,v){
                if(article_no==v.article_no){
                    console.log(v);
                    color_list+="<option value='"+v.id+"'>"+v.color+"</option>";
                    $('#' + $uniqueId).find('.color_no').val(String(v.color_no).padStart(2,"0"));
                    $('#' + $uniqueId).find('.width').val(v.width_cm);
                    $('#' + $uniqueId).find('.brand').val(v.name);    
                    unit_purchased_in = v.unit_purchased_in;   

                    // Calculate and set the Piece value (concatenation of Article No and Color No)
                    // var pieceValue = article_no + '_' + v.color +  '_' + invoice_no + '_' + (date_of_purchase ?? '') + '_' + roll_no + '_' + total_roll;
                    // $('#' + $uniqueId).find('.piece_no').val(pieceValue);
                    // $('#' + $uniqueId).find('.roll_no').val(roll_no);
                }
            });

            $('#' + $uniqueId).find('.color').html(color_list);
            $('#' + $uniqueId).find('.color').val(color_id);
            $('#' + $uniqueId).find('.color').attr('data-row_id',$uniqueId);

            $('#' + $uniqueId).find('.article_no').val(article_no);
            $('#' + $uniqueId).find('.batch_no').val(batch_no);
            $('#' + $uniqueId).find('#delete_row').attr("data-row_id",$uniqueId);

            $('#' + $uniqueId).find('.invoice_no_hidden').val(invoice_no);

            // Enable/disable fields based on unit purchased in
            if (unit_purchased_in === 'meter') {
                $('#' + $uniqueId).find('.meter').prop('readonly', false);
                $('#' + $uniqueId).find('.yard').prop('readonly', true);
            } else if (unit_purchased_in === 'yard') {
                $('#' + $uniqueId).find('.meter').prop('readonly', true);
                $('#' + $uniqueId).find('.yard').prop('readonly', false);
            }
        }
        if(($('.purchaseItem').length)!=0){
            $('#add_single_row').css('display','block');
        }
    }

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

     // Reset the form values when the modal is closed
     $('#addItemModal').on('hidden.bs.modal', function () {
        $('#add_invoice_no').val('').trigger('change');
        $('#add_article_no').val('').trigger('change');
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
            var yards = getNewMeter * 1.09361;
            $('#total_yard').val(yards.toFixed(2)); // Adjust to display 2 decimal places
        });

        $(".yard").on('keyup',function(){
            getEditMeter();
            var getNewMeter = parseFloat($('#total_meter').val());
            $('.yard').each( function() {
                var getMe = parseFloat($(this).val());
                getNewMeter += isNaN(getMe) ? 0 : getMe * 0.9144;
            });

            $('#total_meter').val(getNewMeter);
            var yards = getNewMeter * 1.09361;
            $('#total_yard').val(yards.toFixed(2)); // Adjust to display 2 decimal places
        });

    }
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