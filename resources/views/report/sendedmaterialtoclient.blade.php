@extends('layouts.master')
@section('title', 'Payment')
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
            {{-- @foreach ($order as $item)
            {{dd($item->customer->full_name)}}    
            @endforeach --}}
            
            <div class="widget-body">
                {!! Form::open(['method' => 'GET','route' => ['report.materialsended']]) !!}
                <div class="form-group row d-flex align-items-center mt-3">
                    <div class="col-lg-2">
                        <label class="form-control-label">Customer</label>
                        <select name="cust_name" class="form-control" id="cust_name">
                            <option value="" selected>---Select Customer---</option>
                            @foreach ($order as $item)
                                <option value="{{ $item->customer->id }}" > {{ $item->customer->full_name }} </option>   
                            @endforeach
                        </select>
                    </div>
                    <div class="col-lg-2">
                        <label class="form-control-label">Select Material</label>
                        <select name="article_name" class="form-control" id="article_name">
                            <option value="" selected>---Select Material---</option>
                        </select>
                    </div>
                    <div class="col-lg-2">
                        <label class="form-control-label">Select Article No</label>
                        <select name="article_code" class="form-control" id="article_code">
                            <option value="" selected>---Select Article No---</option>
                        </select>
                    </div>
                    <div class="col-lg-2">
                        <label class="form-control-label">&nbsp;</label>
                        <div class="form-action">
                            <a href="{{ route('report.materialsended') }}" class="btn btn-warning btn-square">Reset</a>
                            <input type="submit" name="submit" class="btn btn-primary btn-square" value="submit">
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
        <div class="widget has-shadow">
            <div class="widget-body">
                <div class="table-responsive">
                    <table class="table table-hover mb-0 " id="received_payments_tbl">
                        <thead>
                            <tr>
                                <th>Order No</th>
                                <th>Type Of Sale</th>
                                <th class="sorter-shortDate dateFormat-ddmmyyyy">Order Date</th>
                                <th>Meter</th>
                                <th>Total Price</th>
                            </tr>
                        </thead>
                        <tbody>
                            @isset($sendedMaterial)
                                @foreach ($sendedMaterial as $material)
                                <tr>
                                    <td>{{ $material->order_id}}</td>
                                    <td>{!! $material->type_of_sale === 'W' ? 'WholeSale' : ($material->type_of_sale === 'R' ? 'Retails' : ($material->type_of_sale === 'P' ? 'Sample' : '-')) !!}</td>
                                    <td>{{ $material->order['order_date'] }}</td>
                                    <td>{{ $material->meter }}</td>
                                    <td>{{ $material->price*$material->meter}}</td>
                                </tr>
                                @endforeach
                            @endisset
                        </tbody>
                    </table>
                    @isset($sendedMaterial)
                    {{ $sendedMaterial->render() }}   
                    @endisset
                </div>
            </div>
        </div>
    </div>
</div>
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
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.tablesorter/2.31.3/js/jquery.tablesorter.min.js" integrity="sha512-qzgd5cYSZcosqpzpn7zF2ZId8f/8CHmFKZ8j7mU4OUXTNRd5g+ZHBPsgKEwoqxCtdQvExE5LprwwPAgoicguNg==" crossorigin="anonymous"></script>
<script src="{{ asset('assets/js/datepicker/moment.min.js') }}"></script>
    <script>
        $(document).ready(function(){
            $('#received_payments_tbl').tablesorter({
                cssAsc: 'up',
		        cssDesc: 'down',
                cssNone: 'both'
            });
            $('#cust_name').select2({});
            $('#article_name').select2({});
            $('#article_code').select2({});
            $(document).on('change','#cust_name',function(){
               var cust_id = $('#cust_name :selected').val();
                $("#article_name").html('');
               $.ajax({
                    url : "{{url('report/sended-material')}}",
                    data : {
                        customer_id : cust_id,
                    },
                    success : function(data){
                        // console.log(data);
                        $.each(data,function(i){
                            
                            $("#article_name").append(`<option value="${i}">${data[i]}</option>`);
                        })
                    }
               });  

            });

            $(document).on('change','#article_name',function(){
               var art_name = $('#article_name :selected').val();
                $("#article_code").html('');
               $.ajax({
                    url : "{{url('report/sended-material')}}",
                    data : {
                        article_name:  art_name,
                    },
                    success : function(data){
                        console.log(data);
                        $.each(data,function(i){
                            $("#article_code").append(`<option value="${i}">${data[i]}</option>`); 
                        })
                    }
               });  

            });
        })
    </script>
@endpush