@extends('layouts.master')
@section('title', 'Clients')
@section('content')

@if ($message = Session::get('success'))
<div class="alert alert-success">
    {{ $message }}
</div>
@endif

<!-- Begin Row -->
{!! Form::model($user, ['method' => 'POST','route' => ['client.update', $user->id], 'class'=>"form-validate", 'novalidate','files' => true]) !!}
<div class="row flex-row">
    <div class="col-xl-12 col-12">
        <div class="widget has-shadow">
            <div class="widget-header bordered no-actions d-flex align-items-center">
                <h4>Edit Client</h4>
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

                <div class="row">
                    <div class="form-group col-lg-3">
                        <label class="form-control-label d-flex">Company Name / Shop Name</label>
                        {!! Form::text('company_name', null, array('id'=>'company_name','class' => 'form-control')) !!}
                    </div>
                    <div class="form-group col-lg-3">
                        <label class="form-control-label d-flex">Owner FirstName<span class="text-danger ml-2">*</span></label>
                        {!! Form::text('firstname', null, array('placeholder' => 'FirstName','class' => 'form-control', 'data-validation'=>"required")) !!}
                    </div>
                    <div class="form-group col-lg-3">
                        <label class="form-control-label d-flex">LastName<span class="text-danger ml-2">*</span></label>
                        {!! Form::text('lastname', null, array('placeholder' => 'LastName','class' => 'form-control', 'data-validation'=>"required")) !!}
                    </div>
                    <div class="form-group col-lg-3">
                        <label class="form-control-label d-flex">Address<span class="text-danger ml-2">*</span></label>
                        {!! Form::text('address', null, array('placeholder' => 'Address','class' => 'form-control', 'data-validation'=>"required")) !!}
                    </div>
                </div>

                <div class="row">
                    <div class="form-group col-lg-3">
                        <label class="form-control-label d-flex">City<span class="text-danger ml-2">*</span></label>
                        {!! Form::text('city', null, array('placeholder' => 'City','class' => 'form-control', 'data-validation'=>"required")) !!}
                    </div>
                    <div class="form-group col-lg-3">
                        <label class="form-control-label d-flex">State<span class="text-danger ml-2">*</span></label>
                        {!! Form::text('state', null, array('placeholder' => 'State','class' => 'form-control', 'data-validation'=>"required")) !!}
                    </div>
                    <div class="form-group col-lg-3">
                        <label class="form-control-label d-flex">Country<span class="text-danger ml-2">*</span></label>
                        @include('countries', ['default' => $user->country, 'attributes' => array('class' => 'form-control', 'data-validation'=>"required")])
                    </div>
                    <div class="form-group col-lg-3">
                        <label class="form-control-label d-flex">Zip<span class="text-danger ml-2">*</span></label>
                        {!! Form::text('zip', null, array('placeholder' => 'Zip','class' => 'form-control', 'data-validation'=>"required")) !!}
                    </div>
                </div>

                <div class="row">
                    <div class="form-group col-lg-3">
                        <label class="form-control-label d-flex">Nature Of Business</label>
                        {!! Form::select('business_nature', $business_nature, $user->business_nature, array('class' => 'form-control', 'id'=>'input_business_nature')) !!}
                    </div>
                    <div class="form-group col-lg-3 row_business_nature_other" style="display: {{ $user->business_nature == 'other' ? '' : 'none' }};">
                        <label class="form-control-label d-flex">Other Nature Of Business</label>
                        {!! Form::text('business_nature_other', null, array('id'=>'business_nature_other','placeholder' => 'Other Nature Of Business','class' => 'form-control')) !!}
                    </div>
                    <div class="form-group col-lg-3">
                        <label class="form-control-label d-flex">Date Of Birth<span class="text-danger ml-2">*</span></label>
                        {!! Form::text('dob', null, array('id'=>'dob','placeholder' => 'Date of Birth','class' => 'form-control', 'data-validation'=>"required")) !!}
                    </div>
                    <div class="form-group col-lg-3">
                        <label class="form-control-label d-flex">Phone<span class="text-danger ml-2">*</span></label>
                        {!! Form::text('phone', null, array('placeholder' => 'Phone','class' => 'form-control', 'data-validation'=>"required")) !!}
                    </div>
                </div>

                <div class="row">
                    <div class="form-group col-lg-3">
                        <label class="form-control-label d-flex">Skype</label>
                        <div class="input-group">
                            {!! Form::text('skype', null, array('placeholder' => 'Skype','class' => 'form-control')) !!}
                            <span class="input-group-addon addon-secondary"><i class="la la-skype" aria-hidden="true"></i></span>
                        </div>
                    </div>
                    <div class="form-group col-lg-3">
                        <label class="form-control-label d-flex">Facebook</label>
                        <div class="input-group">
                            {!! Form::text('facebook', null, array('placeholder' => 'Facebook','class' => 'form-control')) !!}
                            <span class="input-group-addon addon-secondary"><i class="la la-facebook" aria-hidden="true"></i></span>
                        </div>
                    </div>
                    <div class="form-group col-lg-3">
                        <label class="form-control-label d-flex">Pinterest</label>
                        <div class="input-group">
                            {!! Form::text('pinterest', null, array('placeholder' => 'Pinterest','class' => 'form-control')) !!}
                            <span class="input-group-addon addon-secondary"><i class="la la-pinterest" aria-hidden="true"></i></span>
                        </div>
                    </div>
                    <div class="form-group col-lg-3">
                        <label class="form-control-label d-flex">WeChat</label>
                        <div class="input-group">
                            {!! Form::text('wechat', null, array('placeholder' => 'WeChat','class' => 'form-control')) !!}
                            <span class="input-group-addon addon-secondary"><i class="la la-wechat" aria-hidden="true"></i></span>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="form-group col-lg-3">
                        <label class="form-control-label d-flex">Whatsapp</label>
                        <div class="input-group">
                            {!! Form::text('whatsapp', null, array('placeholder' => 'Whatsapp','class' => 'form-control')) !!}
                            <span class="input-group-addon addon-secondary"><i class="la la-whatsapp" aria-hidden="true"></i></span>
                        </div>
                    </div>
                    <div class="form-group col-lg-3">
                        <label class="form-control-label d-flex">Line</label>
                        <!-- <div class="input-group"> -->
                            {!! Form::text('line', null, array('placeholder' => 'Line','class' => 'form-control')) !!}
                            <!-- <span class="input-group-addon addon-secondary"> <img src="{{ asset('assets/img/icons8-line-50.png') }}" height="20px" width="20px"></span> -->
                        <!-- </div> -->
                    </div>
                    <div class="form-group col-lg-3">
                        <label class="form-control-label d-flex">Email<span class="text-danger ml-2">*</span></label>
                        {!! Form::text('email', null, array('placeholder' => 'Email','class' => 'form-control', 'data-validation'=>"required")) !!}
                    </div>
                    <div class="form-group col-lg-3">
                        <label class="form-control-label d-flex">Password</label>
                        {!! Form::password('password', array('placeholder' => 'Password','class' => 'form-control')) !!}
                        <small>Leave Empty if you don't want to change it.</small>
                    </div>
                </div>
                
                <div class="row">
                    <div class="form-group col-lg-3 d-flex align-items-center">
                        <div class="form-check">
                            {!! Form::checkbox('newsletter', 1, null ,array('class' => 'form-check-input', 'id' => 'newsletter')) !!}
                            <label for="newsletter" class="form-check-label" for="printWidth">Recive update on new arrivals</label>
                        </div>
                    </div>
                    <!-- Upload New Image -->

                    <div class="form-group col-lg-6">
                        <label for="images form-control-label">Upload Images:</label>
                        @if($user->images->isNotEmpty())
                            <a class="btn fa fa-eye btn-sm btn-warning ml-1"  data-target="#viewImagesModal-{{ $user->id }}"  data-toggle="modal" data-placement="top" title="View Images"></a>
                        @endif
                        <div style="display: flex; align-items: center;">
                            <input type="file" id="images" name="images[]" multiple  onchange="previewImages()">
                            
                            <button type=button class="btn btn-info btn-sl"  data-toggle="modal" onclick="showComara(this)"  data-target="#myModal" >Capture</button>
                            <div class="form-group image-preview" id="image-preview"></div>
                            <img src="{{ img_url($user->name) }}" class="listing-thumb image-load img-thumbnail " alt="" />
                            <input type="hidden" name="image_binary" class="image_binary"/> 
                            
                            <div id="results">
                                @if($user->image_binary)
                                <img src="{{ Storage::url($client->image_binary) }}" class="img-thumbnail min-size-image"/>
                                @endif
                            </div>
                            <div id="preview-container" style="display: flex; max-width: 100px; margin-top: 10px;margin-left: 2px;">
                            </div>
                            <img id="image_preview" src="#" alt="Image Preview" style="display:none; max-width: 100px; margin-top: 10px;margin-left: 2px;" />
                        </div>
                        <small>
                            <p class="help-block">Only .jpeg, .jpg, .png, .gif file can be uploaded. Maximum image size 5MB</p>
                        </small>
                        <small>Leave Empty if you don't want to change it.</small>
                    </div>
                    <!-- <div class="form-group col-lg-3">
                        <label class="form-control-label">Upload Images:</label>
                        <div style="display: flex; align-items: center;">
                            {!! Form::file('images', [
                            'id' => 'upload_image', 
                            'accept' => 'image/*',
                            'data-validation' => "mime",
                            'data-validation-allowing' => "jpeg, jpg, png, gif",
                            'data-validation-error-msg-mime' => "You can only upload images",
                            'capture' => "camera",
                            'class' => 'form-control-file',
                            'multiple' => true
                        ]) !!}`
                            <button type=button class="btn btn-info btn-sl"  data-toggle="modal" onclick="showComara(this)"  data-target="#myModal" >Capture</button>
                            <img src="{{ img_url($user->name) }}" class="listing-thumb image-load img-thumbnail " alt="" />
                        </div>
                        <small>
                            <p class="help-block">Only .jpeg, .jpg, .png, .gif file can be uploaded. Maximum image size 5MB</p>
                        </small>
                        <input type="hidden" name="image_binary" class="image_binary"/> 
                        <small>Leave Empty if you don't want to change it.</small>
                    </div>
                    <div class="col-lg-3">
                        <img id="image_preview" src="#" alt="Image Preview" style="display:none; max-width: 100px; margin-top: 10px;" />
                    </div> -->
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="viewImagesModal-{{ $user->id }}" tabindex="-1" role="dialog" aria-labelledby="viewImagesModalLabel-{{ $user->id }}" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="viewImagesModalLabel-{{ $user->id }}">Client Images</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    @foreach($user->images as $image)
                        <div class="col-md-4 mt-5">
                            <img  src="{{ url('public/uploads/clients/' . $image->name) }}" class="img-fluid" alt="Client Image">
                            <!-- Download Button -->
                        </div>
                        <a href="{{ url('public/uploads/clients/' . $image->name) }}" download="{{ $image->name }}" class="btn fa fa-download mt-5">
                        </a>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>
<!-- End Row -->

<div class="row">
    <div class="col-xl-12">
        <div class="widget has-shadow">
            <div class="widget-header bordered no-actions d-flex align-items-center">
                <!-- Tabs Navigation -->
                <ul class="nav nav-tabs" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link active" data-toggle="tab" href="#order-history" role="tab" aria-controls="order-history" aria-selected="true">Order History</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" data-toggle="tab" href="#client-articles" role="tab" aria-controls="client-articles" aria-selected="false">Client Articles</a>
                    </li>
                </ul>
                <!-- End Tabs Navigation -->
            </div>
            <div class="widget-body">
                <div class="tab-content">
                    <!-- Tab Pane for Order History -->
                    <div class="tab-content mt-3">
                        <!-- Order History Tab Pane -->
                        <div class="tab-pane fade show active" id="order-history" role="tabpanel">
                            <div class="row flex-row">
                                <div class="col-xl-12 col-12">
                                    <div class="widget has-shadow">
                                        <div class="widget-body">
                                            <div class="col-lg-3">
                                                <label for="article_filter">Article Vise Filter</label>
                                            <select id="article_filter" class="form-control">
                                                <option value="">Select Article No</option>
                                                @foreach ($user->pricelist as $key => $pricelist)
                                                <option value="{{ $pricelist->material->article_no }}">{{ $pricelist->material->article_no }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <br>
                                        <div class="table-responsive col-12 col-xl-12">
                                            <table class="table table-hover mb-0" id="user_tbl">
                                                <thead>
                                                    <tr>
                                                        <th>Item name<br>Article No</th>
                                                        <th>Wholesale Price</th>
                                                        <th>Wholesale Payment Terms</th>
                                                        <th>Retail Price</th>
                                                        <th>Retail Payment Terms</th>
                                                        <th>Sample Price</th>
                                                        <th>Sample Payment Terms</th>
                                                        <th>Note</th>
                                                        <th>Action</th>
                                                    </tr>
                                                </thead>
                                                <tbody id="article_table">
                                                    @isset($user->pricelist)
                                                    @foreach ($user->pricelist as $key => $pricelist)
                                                    <tr id="row-{{ $pricelist->id }}">
                                                        <td>{{ $pricelist->material->name }} <br />{{ $pricelist->material->article_no }}</td>
                                                        <td>
                                                            <input type="hidden" id="material_id_{{ $pricelist->id }}" value="{{ $pricelist->material->id }}">
                                                            <input type="text" id="wholesale_price_{{ $pricelist->id }}" value="{{ $pricelist->wholesale_price != null ? $pricelist->wholesale_price : '0.00' }}" class="form-control">
                                                        </td>
                                                        <td>
                                                            <input type="text" id="w_credit_days_{{ $pricelist->id }}" value="{{ $pricelist->wholesale_credit_days != null ? $pricelist->wholesale_credit_days : '0.00' }}" class="form-control">
                                                        </td>
                                                        <td>
                                                            <input type="text" id="retail_price_{{ $pricelist->id }}" value="{{ $pricelist->price != null ? $pricelist->price : '0.00' }}" class="form-control">
                                                        </td>
                                                        <td>
                                                            <input type="text" id="r_credit_days_{{ $pricelist->id }}" value="{{ $pricelist->retail_credit_days != null ? $pricelist->retail_credit_days : '0.00' }}" class="form-control">
                                                        </td>
                                                        <td>
                                                            <input type="text" id="sample_price_{{ $pricelist->id }}" value="{{ $pricelist->sample_price != null ? $pricelist->sample_price : '0.00' }}" class="form-control">
                                                        </td>
                                                        <td>
                                                            <input type="text" id="s_credit_days_{{ $pricelist->id }}" value="{{ $pricelist->sample_credit_days != null ? $pricelist->sample_credit_days : '0.00' }}" class="form-control">
                                                        </td>
                                                        <td>
                                                            <textarea id="note_{{ $pricelist->id }}" class="form-control" rows="3">{{ $pricelist->remark_note != null ? $pricelist->remark_note : '' }}</textarea>
                                                        </td>
                                                        <td class="td-actions">
                                                            <button data-priceitem_id="{{ $pricelist->id }}" class="save_item_price btn btn-success btn-sm btn-square">Save</button>
                                                            <button data-priceitemdelete_id="{{ $pricelist->id }}" class="delete_item_price btn btn-danger btn-sm btn-square">Delete</button>
                                                        </td>
                                                    </tr>
                                                    @endforeach
                                                    @endisset
                                                </tbody>
                                            </table>
                                            @isset($users)
                                            {{ $users->render() }}
                                            @endisset
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Tab Pane for Client Articles -->
                    <div class="tab-pane fade" id="client-articles" role="tabpanel">
                        <div class="table-responsive">
                            <table class="table table-hover mb-0" id="articles_tbl">
                                <thead>
                                    <tr>
                                        <th>Article Number</th>
                                        <th>Roll(yrd)</th>
                                        <th>Roll(mtr)</th>
                                        <th>Cut Wholesale(yrd)</th>
                                        <th>Cut Wholesale(mtr)</th>
                                        <th>Retail(yrd)</th>
                                        <th>Retail(mtr)</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($user->clientArticles as $article)
                                    <tr>
                                        <td>{{ $article->article_no }}<input type="hidden" name="article_no[]" value="{{ $article->article_no }}"></td>
                                        <td>{!! Form::text('roll[]', (isset($article->roll) && !empty($article->roll) ? $article->roll : 0), ['class' => 'form-control']) !!}</td>
                                        <td>{!! Form::text('roll_per_mtr[]', (isset($article->roll_per_mtr) && !empty($article->roll_per_mtr) ? $article->roll_per_mtr : 0), ['class' => 'form-control']) !!}</td>
                                        <td>{!! Form::text('cut_wholesale[]', (isset($article->cut_wholesale) && !empty($article->cut_wholesale) ? $article->cut_wholesale : 0), ['class' => 'form-control']) !!}</td>
                                        <td>{!! Form::text('cut_wholesale_per_mtr[]', (isset($article->cut_wholesale_per_mtr) && !empty($article->cut_wholesale_per_mtr) ? $article->cut_wholesale_per_mtr : 0), ['class' => 'form-control']) !!}</td>
                                        <td>{!! Form::text('retail[]', (isset($article->retail) && !empty($article->retail) ? $article->retail : 0), ['class' => 'form-control']) !!}</td>
                                        <td>{!! Form::text('retail_per_mtr[]', (isset($article->retail_per_mtr) && !empty($article->retail_per_mtr) ? $article->retail_per_mtr : 0), ['class' => 'form-control']) !!}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                    
                    
                </div>
            </div>
        </div>
    </div>
</div>

<div id="myModal" class="modal fade" role="dialog">
    <div class="modal-dialog modal-lg">

        <!-- Modal content-->
        <div class="modal-content">
        <div class="modal-header">
            <h4 class="modal-title">Modal Header</h4>
            <button type="button" class="close"  onclick="closeWebcame()"  data-dismiss="modal">&times;</button>        
        </div>
        <div class="modal-body modal-body-cus">
            
            <div class="col-md-6 pull-left">
                <div id="my_camera"></div>
                <input type="hidden" value="" id="row_id" />    
                <input type="button" value="Capture Image" class="btn btn-success mt-2" onClick="take_snapshot()">
            </div>
            <div id="results" class="col-md-6 pull-right">                            
                <img src=""  class="img-thumbnail min-size-image"/>        
            </div>    
            
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-primary" onclick="closeWebcame()" data-dismiss="modal">Ok</button>
        </div>
        </div>

    </div>
</div>

<div class="container">
    <div class="row justify-content-center">
        <div class="col-lg-12">
            <div class="d-flex justify-content-center">
                <button type="submit" class="btn btn-primary btn-lg">Update</button>
                <a class="btn btn-secondary btn-lg ml-1" href="{{ route('clients.index') }}"> Cancel</a>
            </div>
        </div>
    </div>
</div>

{!! Form::close() !!}

@endsection
@push('after-styles')
<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/css/select2.min.css" rel="stylesheet" />
<style>
    .selection{
        display:block !important;
    }
    .image-preview {
        display: flex;
        flex-wrap: wrap;
    }
    .image-preview img {
        max-width: 150px;
        max-height: 150px;
        margin: 5px;
        border: 1px solid #ddd;
    }

</style>
@endpush
@push('scripts')
<script type="text/javascript" src="{{asset('/js/webcam.min.js')}}"></script>
<script src="{{ asset('assets/js/datepicker/moment.min.js') }}"></script>
<script src="{{ asset('assets/js/datepicker/daterangepicker.js') }}"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/js/select2.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.tablesorter/2.31.3/js/jquery.tablesorter.min.js" integrity="sha512-qzgd5cYSZcosqpzpn7zF2ZId8f/8CHmFKZ8j7mU4OUXTNRd5g+ZHBPsgKEwoqxCtdQvExE5LprwwPAgoicguNg==" crossorigin="anonymous"></script>
<script type="text/javascript">
    (function($) {
        $('#article_filter').select2();
        $(document).ready(function() {
            $('#dob').daterangepicker({
                singleDatePicker: true,
                showDropdowns: true,
                locale: {
                    format: 'DD/MM/YYYY'
                }
            });
            $('#joining_date').daterangepicker({
                singleDatePicker: true,
                showDropdowns: true,
                locale: {
                    format: 'DD/MM/YYYY'
                }
            });
            $(document).on('change', '.roll', function() {
                if ($(this).val() == "sales-person") {
                    $('#commission_div').css('display', 'block');
                    $('#commission_div').addClass('d-flex');
                    $('#commission_div #salesman_commission').attr('data-validation', 'required');
                } else {
                    $('#commission_div').css('display', 'none');
                    $('#commission_div').removeClass('d-flex');
                    $('#commission_div #salesman_commission').attr('data-validation', 'none');
                }
            })
            $(document).on('change', '#input_business_nature', function() {
                if ($(this).val() == "other") {
                    $('.row_business_nature_other').show();
                } else {
                    $('.row_business_nature_other').hide();
                }
            })
            

            $(document).on('change','#article_filter',function(){
                var filter = $(this).val();
                var url = "{{ route('clients.edit',$user->id) }}";
                $.ajax({
                    url : url,
                    data: {
                        'Article': filter
                    },
                    datatype: 'JSON',
                    success : function(data){
                        $('#article_table').html('');
                        var trHTML = '';
                        $.each(data.pricelist, function (i,pricelist) {
                        trHTML += '<tr><td>' + pricelist.material.name + '</br>' + pricelist.material.article_no + '</td>'+
                                        '<td>'+
                                            '<input type="hidden" id="material_id_'+pricelist.id+'" value="'+pricelist.material.id+'">'+
                                            '<input type="text" id="wholsale_price_'+ pricelist.id +'" value="'+ (pricelist.wholesale_price != null ? pricelist.wholesale_price : '0.00') +'" class="form-control">'+
                                        '</td>'+
                                        '<td>'+
                                            '<input type="text" id="w_credit_days_'+ pricelist.id +'" value="'+ (pricelist.wholesale_credit_days != null ? pricelist.wholesale_credit_days : '0.00') +'" class="form-control">'+
                                        '</td>'+
                                        '<td>'+
                                            '<input type="text" id="retail_price_'+ pricelist.id +'" value="'+ (pricelist.wholesale_price != null ? pricelist.price : '0.00') +'" class="form-control">'+
                                        '</td>'+
                                        '<td>'+
                                            '<input type="text" id="r_credit_days_'+ pricelist.id +'" value="'+ (pricelist.retail_credit_days != null ? pricelist.retail_credit_days : '0.00') +'" class="form-control">'+
                                        '</td>'+
                                        '<td>'+
                                            '<input type="text" id="sample_price_'+ pricelist.id +'" value="'+ (pricelist.sample_price != null ? pricelist.sample_price : '0.00') +'" class="form-control">'+
                                        '</td>'+
                                        '<td>'+
                                            '<input type="text" id="s_credit_days_'+ pricelist.id +'" value="'+ (pricelist.sample_credit_days != null ? pricelist.sample_credit_days : '0.00') +'" class="form-control">'+
                                        '</td>'+
                                        '<td>'+
                                            '<textarea id="note_'+ pricelist.id +'" class="form-control" rows="3">'+ (pricelist.remark_note != null ? pricelist.remark_note : "") +'</textarea>'+
                                        '</td>'+
                                        '<td class="td-actions">'+
                                            '<button data-priceitem_id="'+ pricelist.id +'" class="save_item_price btn btn-success btn-sm btn-square">Save</button>'+
                                            '<button data-priceitemdelete_id="'+ pricelist.id +'" class="delete_item_price btn btn-danger btn-sm btn-square">delete</button>'+
                                        '</td></tr>';
                        });
                        $('#article_table').append(trHTML);
                    }
                });

            })
            $(document).on('click','.save_item_price', function(e) {
                e.preventDefault();
                var id = $(this).data('priceitem_id');
                var wholePrice = $("#wholsale_price_"+id).val();
                var wholePrice_credit = $("#w_credit_days_"+id).val();

                var retailPrice = $('#retail_price_'+id).val();
                var retailPrice_credit = $('#r_credit_days_'+id).val();

                var samplePrice = $('#sample_price_'+id).val();
                var samplePrice_credit = $('#s_credit_days_'+id).val();

                var materialId = $('#material_id_'+id).val();
                var note = $('#note_'+id).val();
                var url = "{{ route('client.update',$user->id) }}";
                $.ajax({
                    url : url,
                    type : 'POST',
                    data: {
                        'wholeprice'   : wholePrice,
                        'w_credit_day' : wholePrice_credit,
                        'retailprice'  : retailPrice,
                        'r_credit_day' : retailPrice_credit,
                        'sampleprice'  : samplePrice,
                        's_credit_day' : samplePrice_credit,
                        'materialid'   : materialId,
                        'note'         : note,
                    },
                    datatype: 'JSON',
                    success : function(data){
                        new Noty({
                            type: 'success',
                            text: data.msg,
                            timeout: 2500
                        }).show()
                    }
                });

               
            })
            $(document).on('click','.delete_item_price', function(e) {
                e.preventDefault();
                var obj = $(this);
                var id = $(this).data('priceitemdelete_id');
                var materialId = $('#material_id_'+id).val();
                var url = "{{ route('client.delete',$user->id) }}";
                $.ajax({
                    url : url,
                    type : 'POST',
                    data: {
                        'materialid' : materialId,
                    },
                    datatype: 'JSON',
                    success : function(data){
                        obj.closest('tr').remove();
                        new Noty({
                            type: 'warning',
                            text: data.msg,
                            timeout: 2500
                        }).show()
                    }
                });
             
            })
        })

        Webcam.set({
			width: 320,
			height: 240,
			image_format: 'jpeg',
			jpeg_quality: 90
		});
    })(jQuery);
    function take_snapshot() {
        // take snapshot and get image data
        Webcam.snap( function(data_uri) {
            
            $("#results img").attr("src", data_uri);
            $('.image_binary').val(data_uri); 
            $(".image-load").attr("src", data_uri);               
        } );
    }
    
    function showComara(ele){        
        Webcam.reset();
		Webcam.attach( '#my_camera' );         
    }

    function closeWebcame(){
        Webcam.reset();
    }

    function previewImages() {
        const previewContainer = document.getElementById('preview-container');
        previewContainer.innerHTML = ''; // Clear previous previews

        const files = document.getElementById('images').files;
        for (const file of files) {
            if (!file.type.startsWith('image/')) { 
                continue;
            }

            const img = document.createElement('img');
            img.classList.add('img-thumbnail');
            img.file = file;

            previewContainer.appendChild(img); 

            const reader = new FileReader();
            reader.onload = (e) => {
                img.src = e.target.result;
            };
            reader.readAsDataURL(file);
        }
    }

    $('#articles_tbl').DataTable({
        lengthMenu: [
            [10, 25, 50, 100, 500, 1000, -1],
            [10, 25, 50, 100, 500, 1000, "All"]
        ],
        "aaSorting": []
    });
</script>
@endpush
