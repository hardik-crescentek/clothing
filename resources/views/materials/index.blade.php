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
<style> 
    svg:not(:root) {
      overflow: show !important;
    }
</style>
<!-- Begin Row -->
<div class="row flex-row">
    <div class="col-xl-12 col-12">
        <div class="widget has-shadow">
            <div class="widget-header bordered no-actions1 d-block align-items-center">
                Filter
            </div>
            <div class="widget-body">
                {!! Form::open(['method' => 'GET','route' => ['materials.index']]) !!}
                <div class="form-group row d-flex align-items-center mt-3">
                    {{-- <div class="col-lg-2">
                        <label class="form-control-label">Search &nbsp;</label><div class="d-inline h6 text-muted">[Name]</div>
                        {!! Form::text('search', $search, array('class' => 'form-control')) !!}
                    </div> --}}
                    <div class="col-lg-2">
                        <label class="form-control-label">Article No </label>
                        {!! Form::select('search_article',$article_no,$article , array('class' => 'form-control','id'=>'search_article')) !!}
                    </div>
                    <div class="col-lg-2">
                        <label class="form-control-label">Color </label>
                        {!! Form::select('color',$colors,$color , array('class' => 'form-control','id'=>'color')) !!}
                    </div>
                    <div class="col-lg-2">
                        <label class="form-control-label">Category</label>
                        {!! Form::select('category_id', $categories, $category_id, array('class' => 'form-control custom-select','id'=>'category')) !!}
                    </div>
                    <div class="col-lg-2">
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
                                <th>Color No</th>
                                <th>Color</th>
                                <th>Price From Supplier</th>
                                <th>Our Final Cost Price</th>
                                <th data-sorter="false">Image</th>
                                <th data-sorter="false">Barcode</th>
                                <th data-sorter="false">QR Code</th>
                                <th data-sorter="false">Status</th>
                                @role('super-admin|stock-adder')<th data-sorter="false" width="100px" >Action</th>@endrole
                            </tr>
                        </thead>
                        <tbody>
                            @isset($materials)
                            @foreach ($materials as $key => $material)
                            <tr class="material-link" data-id="{{$material->id}}">
                                <!-- <td>{{ $material->category->name }}</td> -->
                                <td>{{ $material->name }}</td>
                                <td>{{ $material->article_no }}</td>
                                <td>{{$material->color_no}}</td>
                                <td>{{ $material->color }}</td>
                                <td></td>
                                <td></td>
                                <td><img src="{{ img_url($material->image) }}" class="listing-thumb img-thumbnail" alt=""/></td>
                                <td>{!! DNS1D::getBarcodeSVG($material->barcode,config('app.BARCODE_TYPE'), 1, 40) !!}</td>
                                <td>{!! DNS2D::getBarcodeSVG($material->article_no,'QRCODE') !!}</td>
                                <td>
                                    @if($material->status)
                                    <span class="badge-text badge-text-small success">Active</span>
                                    @else
                                    <span class="badge-text badge-text-small info">Inactive</span>
                                    @endif
                                </td>
                                @role('super-admin|stock-adder')
                                <td class="td-actions">
                                    <a class="btn btn-primary btn-sm btn-square col-sm-12" href="{{ route('materials.edit',$material->id) }}">Edit</a>
                                    {!! Form::open(['method' => 'DELETE','route' => ['materials.destroy', $material->id],'style'=>'display:inline', 'onsubmit'=>'return delete_confirm()']) !!}
                                    {!! Form::submit('Delete', ['class' => 'btn btn-danger btn-sm btn-square col-sm-12 mt-1']) !!}
                                    {!! Form::close() !!}
                                </td>
                                @endrole
                            </tr>
                            @endforeach
                            @endisset
                        </tbody>
                    </table>
                    @isset($materials)
                        {{ $materials->render() }}
                    @endisset

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
