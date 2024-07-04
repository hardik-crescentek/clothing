@extends('layouts.master')
@section('title', 'Materials')
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

<!-- Begin Row -->
<div class="row flex-row">
    <div class="col-xl-12 col-12">
        <div class="widget has-shadow">
            <div class="widget-header bordered no-actions1 d-block align-items-center">
                Filter
            </div>
            <div class="widget-body">
                {!! Form::open(['method' => 'GET','route' => ['report.stock']]) !!}
                <div class="form-group row d-flex align-items-center mt-3">
                    <div class="col-lg-2">
                        <label class="form-control-label">Article No </label>
                        {!! Form::select('search_article',$article_no,$article , array('class' => 'form-control','id'=>'search_article')) !!}
                    </div>
                    <div class="col-lg-2">
                        <label class="form-control-label">Color </label>
                        {!! Form::select('color',$colors,$color , array('class' => 'form-control','id'=>'color')) !!}
                    </div>
                    <div class="col-lg-2">
                        <label class="form-control-label">&nbsp;</label>
                        <div class="form-action">
                            <a href="{{ route('report.stock') }}" class="btn btn-warning btn-square">Reset</a>
                            <input type="submit" class="btn btn-primary btn-square" value="Filter">
                        </div>
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
            <div class="widget-body">
                <div class="table-responsive">
                    <table class="table table-hover mb-0 " id="material_tbl">
                        <thead>
                            <tr>
                                <!-- <th>Category</th> -->
                                <th>Name</th>
                                <th>Article No</th>
                                <th style="" class="meter">Available Meter</th>
                                <th style="" class="yard">Available Yard</th>
                                <th>Total Rols</th>
                                <th data-sorter="false">Image</th>
                                <th data-sorter="false">Barcode</th>
                                <th data-sorter="false">QR Code</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if(!empty($stock_items))
                            @php
                                $total_available_qty = 0;
                                $total_rolls = 0;
                            @endphp
                            @foreach ($stock_items as $key => $stock_item)
                            @php
                                $total_available_qty += $stock_item->total_available_qty;
                                $total_rolls += $stock_item->total_rolls;
                            @endphp
                            <tr class="material-link" data-id="{{ $stock_item->id }}">
                                <td>{{ $stock_item->material->name }}</td>
                                <td>{{ $stock_item->article_no }}</td>
                                <td>{{$stock_item->total_available_qty }}</td>
                                <td>{{ number_format((float)(meter2yard($stock_item->total_available_qty)),2,'.','') }}</td>
                                <td>{{$stock_item->total_rolls }}</td>
                                <td><img src="{{ img_url($stock_item->material->image) }}" class="listing-thumb img-thumbnail" alt=""/></td>
                                <td>{!! DNS1D::getBarcodeSVG($stock_item->material->barcode,config('app.BARCODE_TYPE'), 1, 40) !!}</td>
                                <td>{!! DNS2D::getBarcodeSVG($stock_item->material->article_no,'QRCODE') !!}</td>
                            </tr>
                            @endforeach
                            <tr class="total">
                                <th></th>
                                <th>Total </th>
                                <th>{{ $total_available_qty }}</th>
                                <th>{{ number_format((float)(meter2yard($total_available_qty)),2,'.','') }}</th>
                                <th>{{ $total_rolls }}</th>
                                <th></th>
                                <th></th>
                                <th></th>
                            </tr>
                            @endif
                        </tbody>
                    </table>
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

    // $(document).on("click", "tr.material-link td:not(:first-child, :last-child)", function() {
    //     var id = $(this).data('id');
    //     materialInfo(id);
    // });

    // $(document).on("click", ".material-info", function() {
    //     var id = $(this).data('id');
    //     materialInfo(id);
    // });

    // function materialInfo(id) {
    //     htmltext = '';
    //     $('#product-content').html(htmltext);
    //     $('#product-details').modal('show');
    // }
</script>
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
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.tablesorter/2.31.3/js/jquery.tablesorter.min.js" integrity="sha512-qzgd5cYSZcosqpzpn7zF2ZId8f/8CHmFKZ8j7mU4OUXTNRd5g+ZHBPsgKEwoqxCtdQvExE5LprwwPAgoicguNg==" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/js/select2.min.js"></script>
    <script>
        $(document).ready(function(){
            $('#material_tbl').tablesorter({
                cssAsc: 'up',
		        cssDesc: 'down',
                cssNone: 'both'
            });
            $('#search_article').select2();
            $('#color').select2();
            $('#category').select2();


            // Article Wise Color Selection

            $(document).on('change','#search_article',function(){
                var article_no = $(this).val();
                $("#color").html('');
                $.ajax({
                    url: "{{ route('materials.index') }}",
                    dataType: "json",
                    data: {
                        article: article_no
                    },
                    success: function(data) {
                        console.log(data);
                        $.each(data,function(i){
                            $("#color").append(`<option value="${i}">${data[i]}</option>`);
                        })
                    }
                });
            });
        })
    </script>
@endpush
