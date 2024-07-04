@extends('layouts.master')
@section('title', 'Categories')
@section('content')
<!-- Begin Page Header-->
<div class="row">
    <div class="page-header">
        <div class="d-flex align-items-center">
            <h2 class="page-header-title">Categories</h2>
        </div>
    </div>
</div>
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
                {!! Form::open(['method' => 'GET','route' => ['category.index']]) !!}
                <div class="form-group row d-flex align-items-center mt-3 col-lg-3">
                    <label class="form-control-label d-flex">Show Category</label>
                    <div class="ml-3">
                        {!! Form::select('parent', $parent_categories,$parent, array('class' => 'form-control custom-select', 'onchange'=>'this.form.submit()')) !!}
                    </div>
                </div>
                {!! Form::close() !!}
            </div>
            <div class="widget-body">
                <div class="table-responsive">
                    <table class="table table-hover mb-0 " id="category_tbl">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th data-sorter="false">Parent</th>
                                <th data-sorter="false">Status</th>
                                <th data-sorter="false" width="280px">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @isset($categories)
                            @foreach ($categories as $key => $category)
                            <tr>
                                <td>{{ $category->name }}</td>
                                <td>{{ $category->getParentsNames() }}</td>
                                <td>
                                    @if($category->status)
                                    <span class="badge-text badge-text-small success">Active</span>
                                    @else
                                    <span class="badge-text badge-text-small info">Inactive</span>
                                    @endif
                                </td>
                                <td class="td-actions">
                                    <a class="btn btn-primary btn-sm btn-square col-sm-3 mt-1" href="{{ route('category.edit',$category->id) }}">Edit</a>
                                    {!! Form::open(['method' => 'DELETE','route' => ['category.destroy', $category->id],'style'=>'display:inline', 'onsubmit'=>'return delete_confirm()']) !!}
                                    {!! Form::submit('Delete', ['class' => 'btn btn-danger btn-sm btn-square col-sm-3 mt-1']) !!}
                                    {!! Form::close() !!}
                                </td>
                            </tr>
                            @endforeach
                            @endisset
                        </tbody>
                    </table>
                    @isset($categories)
                    {{ $categories->render() }}    
                    @endisset
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    function delete_confirm() {
        return confirm("Are you sure want to delete this cateogry?");
    }
</script>
<!-- End Row -->
@endsection
@push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.tablesorter/2.31.3/js/jquery.tablesorter.min.js" integrity="sha512-qzgd5cYSZcosqpzpn7zF2ZId8f/8CHmFKZ8j7mU4OUXTNRd5g+ZHBPsgKEwoqxCtdQvExE5LprwwPAgoicguNg==" crossorigin="anonymous"></script>
<script>
    $(document).ready(function(){
        $('#category_tbl').tablesorter({
            cssAsc: 'up',
            cssDesc: 'down',
            cssNone: 'both'
        });
    });
</script>
@endpush