@extends('layouts.master')
@section('title', 'Clients')
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
                <h4>Add Client</h4>
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


                {!! Form::open(array('route' => 'clients.store','method'=>'POST', 'class'=>"form-validate", 'novalidate','files' => true)) !!}
                <input type="hidden" name="redirectTo" value="{{$redirect}}">
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
                        @include('countries', ['default' => null, 'attributes' => array('class' => 'form-control', 'data-validation'=>"required")])
                    </div>
                    <div class="form-group col-lg-3">
                        <label class="form-control-label d-flex">Zip<span class="text-danger ml-2">*</span></label>
                        {!! Form::text('zip', null, array('placeholder' => 'Zip','class' => 'form-control', 'data-validation'=>"required")) !!}
                    </div>
                </div>

                <div class="row">
                    <div class="form-group col-lg-3">
                        <label class="form-control-label d-flex">Nature Of Business</label>
                        {!! Form::select('business_nature', $business_nature,null, array('class' => 'form-control' , 'id' => 'input_business_nature')) !!}
                    </div>
                    <div class="form-group col-lg-3 row_business_nature_other" style="display: none">
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
                    <div class="form-group col-lg-3 d-flex align-items-center">
                        <div class="form-check">
                            {!! Form::checkbox('newsletter', 1, null ,array('class' => 'form-check-input', 'id' => 'newsletter')) !!}
                            <label for="newsletter" class="form-check-label" for="printWidth">Recive update on new arrivals</label>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="form-group col-lg-3">
                        <label class="form-control-label d-flex">Password<span class="text-danger ml-2">*</span></label>
                        {!! Form::password('password', array('placeholder' => 'Password','class' => 'form-control', 'data-validation'=>"required")) !!}
                    </div>
                    <div class="form-group col-lg-3">
                        <label class="form-control-label d-flex">Confirm Password<span class="text-danger ml-2">*</span></label>
                        {!! Form::password('password_confirmation', array('placeholder' => 'Confirm Password','class' => 'form-control', 'data-validation'=>"required")) !!}
                    </div>
                    
                    <div class="form-group col-lg-6">
                        <label for="images form-control-label">Upload Images:</label>
                        <div style="display: flex; align-items: center;">
                            <input type="file" id="images" name="images[]" multiple  onchange="previewImages()">
                            
                            <button type=button class="btn btn-info btn-sl"  data-toggle="modal" onclick="showComara(this)"  data-target="#myModal" >Capture</button>
                            <div class="form-group image-preview" id="image-preview"></div>
                            <img class="listing-thumb image-load img-thumbnail " alt="" />
                            <input type="hidden" name="image_binary" class="image_binary"/> 
                            
                            <div id="preview-container" style="display: flex; max-width: 100px; margin-top: 10px;margin-left: 2px;">
                            </div>
                            <img id="image_preview" src="#" alt="Image Preview" style="display:none; max-width: 100px; margin-top: 10px;margin-left: 2px;" />
                        </div>
                        <small>
                            <p class="help-block">Only .jpeg, .jpg, .png, .gif file can be uploaded. Maximum image size 5MB</p>
                        </small>
                        <small>Leave Empty if you don't want to change it.</small>
                    </div>
                </div>



                <br>
                <div>
                    <h3>Article List</h3>
                </div>
                <br>

                <div class="table-responsive col-12 col-xl-12">
                    <table class="table table-hover mb-0" id="article_table">
                        <thead>
                            <tr>
                                <th>Article No</th>
                                <th>Selling Price:</th>
                                <th>Role(yrd)</th>
                                <th>Roll(mtr)</th>
                                <th>Cut Wholesale(yrd)</th>
                                <th>Cut Wholesale(mtr)</th>
                                <th>Retail(yrd)</th>
                                <th>Retail(mtr)</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($articles as $article)
                                <tr>
                                    <td>
                                        <a href="{{ route('client.articles.show', ['article_no' => $article->article_no]) }}">{{ $article->article_no }}</a>
                                        <input type="hidden" name="article_no[]" value="{{ $article->article_no }}">
                                    </td>
                                    <td></td>
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

<!-- Modal -->
<div id="myModal" class="modal fade" role="dialog">
  <div class="modal-dialog  modal-lg">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title">Modal Header</h4>
        <button type="button" class="close" onclick="closeWebcame()" data-dismiss="modal">&times;</button>        
      </div>
      <div class="modal-body modal-body-cus">
        <div class="col-md-6 pull-left"> 
            <div id="my_camera"></div>
            <input type="hidden" value="" id="row_id" />    
            <input type="button" value="Capture Image" class="btn btn-success mt-2" onClick="take_snapshot()">
        </div>
        <div id="results" class="col-md-6 pull-right">
            
            <img src="" class="img-thumbnail min-size-image" />        
        </div>    
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-primary" onclick="closeWebcame()" data-dismiss="modal">Ok</button>
      </div>
    </div>

  </div>
</div>

<!-- End Row -->
@endsection
@push('scripts')
<script src="{{ asset('assets/js/datepicker/moment.min.js') }}"></script>
<script src="{{ asset('assets/js/datepicker/daterangepicker.js') }}"></script>
<script type="text/javascript" src="{{asset('/js/webcam.min.js')}}"></script>
<script>
    (function($) {
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
        $(document).on('change','.roll',function(){
            if($(this).val()=="sales-person"){
                $('#commission_div').css('display','block');
                $('#commission_div').addClass('d-flex');
                $('#commission_div #salesman_commission').attr('data-validation','required');
            }
            else{
                $('#commission_div').css('display','none');
                $('#commission_div').removeClass('d-flex');
                $('#commission_div #salesman_commission').attr('data-validation','none');
            }
        })
        $(document).on('change', '#input_business_nature', function() {
            if ($(this).val() == "other") {
                $('.row_business_nature_other').show();
            } else {
                $('.row_business_nature_other').hide();
            }
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

    $(document).ready(function(){
        $('#article_table').DataTable({
            lengthMenu: [
                [10, 25, 50,100,500,1000,'All'],
                [10, 25, 50,100,500,1000,'All'],
            ],
            "aaSorting": []
        });
    })
</script>
@endpush