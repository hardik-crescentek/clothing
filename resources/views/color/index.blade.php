@extends('layouts.master')
@section('title', 'Colors')
@section('content')
<!-- Begin Page Header-->
<div class="row">
    <div class="page-header">
        <div class="d-flex align-items-center">
            <h2 class="page-header-title">Colors</h2>
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
            <div class="widget-body">
                <div class="table-responsive">
                    <table class="table table-hover mb-0 ">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Code</th>
                                <th>Status</th>
                                <th width="280px">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @isset($colors)
                                @foreach ($colors as $key => $color)
                                <tr>
                                    <td>{{ $color->name }}</td>
                                    <td>{{ $color->code }}</td>
                                    <td>
                                        @if($color->status)
                                        <span class="badge-text badge-text-small success">Active</span>
                                        @else
                                        <span class="badge-text badge-text-small info">Inactive</span>
                                        @endif
                                    </td>
                                    <td class="td-actions">
                                        <a class="btn btn-primary btn-sm btn-square" href="{{ route('color.edit',$color->id) }}">Edit</a>
                                        <a class="btn btn-danger btn-sm btn-square delete text-light" data-toggle="modal" data-color_id="{{$color->id}}" data-target="#deletebtnmodal">Delete</a>
                                        
                                    </td>
                                </tr>
                                @endforeach
                            @endisset
                        </tbody>
                    </table>
                    @isset($colors)
                    {{ $colors->render() }}
                    @endisset
                </div>
            </div>
        </div>
    </div>
</div>
<div class="modal" id="deletebtnmodal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Delete Color</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <div class="alert alert-warning" id="msg"></div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
          
            {!! Form::open(['method' => 'DELETE','id' =>"deleteformbtn" ]) !!}
            {!! Form::submit('Delete', ['class' => 'btn btn-danger','style'=>'display:none','id'=>'delete']) !!}
            {!! Form::close() !!}
            {{-- ['color.destroy', $colors->id] --}}
          <button type="button" class="btn btn-primary" style="display: none" id="delete" >Save changes</button>
            
        </div>
      </div>
    </div>
  </div>
<!-- End Row -->
@endsection
@push('scripts')
<script type="text/javascript">
    (function($) {
        $('#deletebtnmodal').on('shown.bs.modal', function(e) {
            var color_id= $(e.relatedTarget).data('color_id');
            var url="{{route('color.destroy',":id")}}";
            url=url.replace(':id',color_id);
            $('#deleteformbtn').attr('action',url);
            $('#msg').html("");
            $('#delete').css('display','none');
            $.ajax({
                    url: '{{route("color.check-color")}}',
                    data: {'color_id' : color_id},
                    success: function(data) {
                        if(data.status){
                            $('#msg').html(data.message);
                            $('#delete').css('display','block');
                        }
                        else{
                            $('#msg').html(data.message);
                            $('#delete').css('display','none');
                        }
                    }
                })
        });
    })(jQuery);
</script>
@endpush