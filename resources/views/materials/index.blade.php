@extends('layouts.master')
@section('title', 'Materials')
@section('content')

@if ($message = Session::get('success'))
<div class="alert alert-success">
    {{ $message }}
</div>
@endif

<style>
    .details-row {
        padding-left: 20px; /* Adjust as needed for indentation */
        background-color: #f8f9fa; /* Optional: Light background color for child rows */
    }
    .horizontal-line-row td {
        border-bottom: 1px solid #dee2e6; /* Light grey border for the horizontal line */
        padding: 0; /* Remove padding if needed */
        margin: 0; /* Remove margin if needed */
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
                    <div class="col-lg-2">
                        <label class="form-control-label">Article No </label>
                        {!! Form::select('search_article', $article_no, $article, ['class' => 'form-control', 'id' => 'search_article']) !!}
                    </div>
                    <div class="col-lg-2">
                        <label class="form-control-label">Color </label>
                        {!! Form::select('color', $colors, $color, ['class' => 'form-control', 'id' => 'color']) !!}
                    </div>
                    <div class="col-lg-2">
                        <label class="form-control-label">Category</label>
                        {!! Form::select('category_id', $categories, $category_id, ['class' => 'form-control custom-select', 'id' => 'category']) !!}
                    </div>
                    <div class="col-lg-2">
                        <label class="form-control-label">&nbsp;</label>
                        <div class="form-action">
                            <input type="submit" class="btn btn-primary btn-square" value="Filter">
                            <a href="{{ route('materials.index') }}" class="btn btn-secondary btn-square">Cancel</a>
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
                    <table class="table table-hover mb-0" id="material_tbl">
                        <thead>
                            <tr>
                                <th colspan="3">Name</th>
                                <th colspan="3">Article No</th>
                                <th colspan="3">Barcode</th>
                                <th colspan="3">QR Code</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($groupedMaterials as $articleNo => $group)
                                <!-- Main Row -->
                                <tr class="summary-row" data-article-no="{{ $articleNo }}" onclick="toggleDetails('{{ $articleNo }}')">
                                    <td colspan="3">{{ $group->first()->name }}</td>
                                    <td colspan="3">{{ $articleNo }}</td>
                                    <td colspan="3">{!! DNS1D::getBarcodeSVG($articleNo, config('app.BARCODE_TYPE'), 1, 40) !!}</td>
                                    <td colspan="3">{!! DNS2D::getBarcodeSVG($articleNo, 'QRCODE') !!}</td>
                                </tr>

                                <!-- Header Row for Detailed View -->
                                <tr class="details-header-row font-weight-bold bg-dark text-light" data-article-no="{{ $articleNo }}" style="display: none;">
                                    <th>=></th>
                                    <th>Name</th>
                                    <th>Article No</th>
                                    <th>Color No</th>
                                    <th>Color</th>
                                    <th>Price From Supplier</th>
                                    <th>Our Final Cost Price</th>
                                    <th>Image</th>
                                    <th>Barcode</th>
                                    <th>QR Code</th>
                                    <th>Status</th>
                                    @role('super-admin|stock-adder')<th>Action</th>@endrole
                                </tr>

                                @foreach ($group as $index => $material)
                                    <!-- Detailed Row -->
                                    <tr class="details-row" data-article-no="{{ $articleNo }}" style="display: none;">
                                        <td>=></td>
                                        <td>{{ $material->name }}</td>
                                        <td>{{ $material->article_no }}</td>
                                        <td>{{ $material->color_no }}</td>
                                        <td>{{ $material->color }}</td>
                                        <td>{{ $material->price_from_supplier }}</td>
                                        <td>{{ $material->final_cost_price }}</td>
                                        <td><img src="{{ img_url($material->image) }}" class="listing-thumb img-thumbnail" alt=""/></td>
                                        <td>{!! DNS1D::getBarcodeSVG($material->barcode, config('app.BARCODE_TYPE'), 1, 40) !!}</td>
                                        <td>{!! DNS2D::getBarcodeSVG($material->article_no, 'QRCODE') !!}</td>
                                        <td>
                                            @if($material->status)
                                            <span class="badge-text badge-text-small success">Active</span>
                                            @else
                                            <span class="badge-text badge-text-small info">Inactive</span>
                                            @endif
                                        </td>
                                        @role('super-admin|stock-adder')
                                            <td>
                                                <a class="btn btn-primary btn-sm" href="{{ route('materials.edit', $material->id) }}">Edit</a>
                                                {!! Form::open(['method' => 'DELETE', 'route' => ['materials.destroy', $material->id], 'style' => 'display:inline', 'onsubmit' => 'return delete_confirm()']) !!}
                                                {!! Form::submit('Delete', ['class' => 'btn btn-danger btn-sm mt-1']) !!}
                                                {!! Form::close() !!}
                                            </td>
                                        @endrole
                                    </tr>
                                    @endforeach
                                    <!-- Horizontal Line Row -->
                                    <tr class="horizontal-line-row" data-article-no="{{ $articleNo }}" style="display: none;">
                                        <td colspan="12">
                                            <hr style="border: 1px solid #dee2e6; margin: 0;">
                                        </td>
                                    </tr>
                            @endforeach
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

@endsection

@push('after-styles')
<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/css/select2.min.css" rel="stylesheet" />
@endpush

@push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.tablesorter/2.31.3/js/jquery.tablesorter.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/js/select2.min.js"></script>
<script>
    function delete_confirm() {
        return confirm("Are you sure want to delete?");
    }

    function toggleDetails(articleNo) {
        $(`.details-header-row[data-article-no="${articleNo}"]`).toggle();
        $(`.details-row[data-article-no="${articleNo}"]`).toggle();
        $(`.horizontal-line-row[data-article-no="${articleNo}"]`).toggle();
    }

    $(document).ready(function(){
        $('#material_tbl').tablesorter();
        
        // Initialize Select2 with full width
        $('#search_article').select2({  });
        $('#color').select2({  });
        $('#category').select2({  });

        // Load colors based on selected article
        $('#search_article').change(function(){
            var article_no = $(this).val();
            $("#color").html('');
            $.ajax({
                url: "{{ route('materials.index') }}",
                dataType: "json",
                data: { article: article_no , specific_page: 'materials_page'},
                success: function(data) {
                    $.each(data, function(index, value){
                        $("#color").append(`<option value="${index}">${value}</option>`);
                    });
                }
            });
        });
    });
</script>
@endpush
