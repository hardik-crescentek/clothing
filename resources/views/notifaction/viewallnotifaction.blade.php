@extends('layouts.master')
@section('title', 'AllitemNotifaction')
@section('content')
<!-- Begin Page Header-->
<div class="row">
    <div class="page-header">
        <div class="d-flex align-items-center">
            <h2 class="page-header-title">All Notifaction</h2>
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
{{-- <div class="row flex-row">
    <div class="col-xl-12 col-12">
        <div class="widget has-shadow">
            <div class="widget-header bordered no-actions1 d-block align-items-center">
                Filter
            </div>
            <div class="widget-body">
                {!! Form::open(['method' => 'GET','route' => ['noti.index']]) !!}
                <div class="form-group row d-flex align-items-center mt-3">
                    <div class="col-lg-3">  
                        <label class="form-control-label">Search <div class="d-inline text-muted" style="font-size: 10px;">[User Name/Mobile/E-mail]</div></label>
                        {!! Form::text('search', '', array('class' => 'form-control')) !!}
                    </div>
                    <div class="col-lg-3">
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
</div> --}}
<!-- Begin Row -->
<div class="row flex-row">
    <div class="col-xl-12 col-12">
        <div class="widget has-shadow">
            <div class="widget-body">
                <div class="table-responsive">
                    <table class="table table-hover mb-0 " id="notifaction_tbl">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Color</th>
                                <th>Article Number</th>
                                <th data-sorter="false">Image</th>
                                <th data-sorter="false">Barcode</th>
                                <th data-sorter="false">QR Code</th>
                                <th data-sorter="false">Alert Quantity</th>
                                <th>Available Qty</th>  
                                <th data-sorter="false" width="130px">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @isset($notifyItems)
                            {{-- {{dd($notifyItems)}} --}}
                            @foreach ($notifyItems as $key => $noti)
                            <tr>
                                <td>{{ $noti->material->name }}</td>
                                <td>{{ $noti->material->color_code }}</td>
                                <td>{{ $noti->material->article_no }}</td>
                                <td><img src="{{ img_url($noti->material->image) }}" class="listing-thumb img-thumbnail" alt=""/></td>
                                <td>{!! DNS1D::getBarcodeSVG($noti->material->barcode,env('BARCODE_TYPE'), 1, 40) !!}</td>
                                <td>{!! DNS2D::getBarcodeSVG($noti->material->article_no,'QRCODE') !!}</td>
                                <td>{{ $noti->material->min_alert_qty }}</td>
                                <td>{{ $noti->available_qty."/".$noti->qty }}</td>
                                @role('super-admin|stock-adder')
                                <td class="td-actions">
                                    <a class="btn btn-primary btn-sm btn-square mx-1 my-1" href="{{ route('materials.edit',$noti->material->id) }}">Edit</a>
                                    {!! Form::open(['method' => 'DELETE','route' => ['materials.destroy', $noti->material->id],'style'=>'display:inline', 'onsubmit'=>'return delete_confirm()']) !!}
                                    {{-- {!! Form::submit('Delete', ['class' => 'btn btn-danger btn-sm btn-square']) !!} --}}
                                    {!! Form::close() !!}
                                </td>
                                @endrole
                            </tr>
                            @endforeach
                            @endisset
                        </tbody>
                    </table>
                    @isset($notifyItems)
                    {{ $notifyItems->render() }}
                    @endisset

                </div>
            </div>
        </div>
    </div>
</div>
<script>
    function delete_confirm() {
        return confirm("Are you sure want to delete?");
    }
</script>
<!-- End Row -->
@endsection
@push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.tablesorter/2.31.3/js/jquery.tablesorter.min.js" integrity="sha512-qzgd5cYSZcosqpzpn7zF2ZId8f/8CHmFKZ8j7mU4OUXTNRd5g+ZHBPsgKEwoqxCtdQvExE5LprwwPAgoicguNg==" crossorigin="anonymous"></script>
    <script>
        $(document).ready(function(){
            $('#notifaction_tbl').tablesorter({
                cssAsc: 'up',
		        cssDesc: 'down',
                cssNone: 'both'
            });
        })
    </script>
@endpush
