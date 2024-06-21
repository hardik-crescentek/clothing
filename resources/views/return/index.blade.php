@extends('layouts.master')
@section('title', 'Return')
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

<div class="row flex-row">
    <div class="col-xl-12 col-12">
        <div class="widget has-shadow">
            <div class="widget-header bordered no-actions1 d-block align-items-center">
                Search
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
                {!! Form::open(['method' => 'GET','route' => ['return']]) !!}
                <div class="form-group row d-flex align-items-center mt-3">
                    <div class="col-lg-3">
                        <label class="form-control-label">Invoice No</label>
                        {!! Form::text('invoice_no', $invoice_no, array('class' => 'form-control','id'=>'invoice_no','data-validation'=>'required')) !!}
                    </div>
                    <div class="col-lg-3">
                        <label class="form-control-label">&nbsp;</label>
                        <div class="form-action">
                            <input type="submit" class="btn btn-primary btn-square" value="Find">
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="alert alert-danger" style="display: none;" id="error_msg"></div>
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
            {{-- <div class="widget-header bordered no-actions1 d-block align-items-center">
                Search
            </div> --}}
            <div class="widget-body">
                {!! Form::open(['method' => 'post','route' => ['return.store']]) !!}
                <table class="table" id="tblReturn">
                    <thead>
                        <tr>
                            <th>{!! Form::checkbox("select_all", 0, false, ['class'=>'form-control','id'=>'select_all']) !!}</th>
                            <th>Material Name</th>
                            <th>Color</th>
                            <th>Color No</th>
                            <th>Roll No</th>                            
                            <th>Meter</th>
                        </tr>
                    </thead>
                    <tbody>
                        @isset($invoice)
                            @foreach ($invoice->invoice_item_rolls as $item)
                                <tr>
                                    <td>
                                        {!! Form::checkbox('select[]',$item->id,false, array('id'=>'select','class' => 'form-control select')) !!}
                                    </td>
                                    <td>
                                        {{$item->roll->material->name}}
                                    </td>
                                    <td>
                                        {{$item->roll->material->color}}
                                    </td>
                                    <td>
                                        {{$item->roll->material->color_no}}
                                    </td>
                                    <td>
                                        {{$item->roll_no}}
                                    </td>
                                    <td>
                                        {!! Form::text("meter[".$item->roll_id."]", $item->meter, ['class'=>'form-control input-sm','id'=>'meter','disabled'=>'disabled']) !!}
                                    </td>
                                </tr>
                            @endforeach
                        @endisset
                    </tbody>
                </table>
                <div class="row">
                    <div class="col-lg-10">
                    </div>
                    <div class="col-lg-2">
                        <label class="form-control-label">&nbsp;</label>
                        <div class="form-action">
                            <input type="submit" class="btn btn-primary btn-square" id="submit_btn" value="Return">
                        </div>
                    </div>
                </div>
                {!! Form::close() !!}
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script type="text/javascript">

    (function($) {

        $(document).on('change','#select_all',function(){
            if(this.checked){
                $('#tblReturn tbody tr input.select').attr('checked',true);
            }
            else{
                $('#tblReturn tbody tr input.select').attr('checked',false);
            }
        });
        $(document).on('change','#select',function(){
            if(this.checked){
                $(this).closest('tr').find('#meter').attr('disabled',false);
            }
            else{
                $(this).closest('tr').find('#meter').attr('disabled',true);
            }
        });
        $(document).on('click','#submit_btn',function(){
            var body=$('#tblReturn tbody').html();
            if(body==''){
                $('#error_msg').html('Please Enter Invoice Number').css('display','block').fadeout(200);
                return false;
            }
            else{
                $('#error_msg').css('display','none').fadeout(200);
            }
            
        });

    })(jQuery);
</script>
@endpush