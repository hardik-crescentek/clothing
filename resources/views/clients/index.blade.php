@extends('layouts.master')
@section('title', 'Clients')
@section('content')
<!-- Begin Page Header-->
<div class="row">
    <div class="page-header">
        <div class="d-flex align-items-center">
            <h2 class="page-header-title">Clients</h2>
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
<!-- <div class="row flex-row">
    <div class="col-xl-12 col-12">
        <div class="widget has-shadow">
            <div class="widget-header bordered no-actions1 d-block align-items-center">
                Filter
            </div>
            <div class="widget-body">
                {!! Form::open(['method' => 'GET','route' => ['clients.index']]) !!}
                <div class="form-group row d-flex align-items-center mt-3">
                    <div class="col-lg-3">
                        <label class="form-control-label">Search</label>
                        {!! Form::text('search', '', array('class' => 'form-control','placeholder' => 'Clients Name/Email/Phone')) !!}
                    </div>
                    <div class="col-lg-3">
                        <label class="form-control-label">&nbsp;</label>
                        <div class="form-action">
                            <input type="submit" class="btn btn-primary btn-square" value="Filter">
                            <a class="btn btn-primary btn-square ml-1" href="{{ route('clients.index') }}"> Cancel</a>
                        </div>
                    </div>
                </div>
                {!! Form::close() !!}
            </div>
        </div>
    </div>
</div> -->
<!-- Begin Row -->
<!-- Begin Row -->
<div class="row flex-row">
    <div class="col-xl-12 col-12">
        <div class="widget has-shadow">
            <div class="widget-body">
                <div class="table-responsive">
                    <table class="table table-hover mb-0 " id="user_tbl">
                        <thead>
                            <tr>
                                <th>Comapny/Shop name</th>
                                <th>FirstName</th>
                                <th>Email</th>
                                <th>Phone</th>
                                <th data-sorter="false" width="280px">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @isset($clients)
                            @foreach ($clients as $key => $user)
                            <tr>
                                <td>{{ $user->company_name }}</td>
                                <td>{{ $user->firstname }}</td>
                                <td>{{ $user->email }}</td>
                                <td>{{ $user->phone }}</td>
                                <td class="td-actions">
                                    <a class="btn btn-primary btn-sm btn-square col-sm-3 mt-1" href="{{ route('clients.edit',$user->id) }}">Edit</a>
                                    {!! Form::open(['method' => 'DELETE','route' => ['clients.destroy', $user->id],'style'=>'display:inline', 'onsubmit'=>'return delete_confirm()']) !!}
                                    {!! Form::submit('Delete', ['class' => 'btn btn-danger btn-sm btn-square col-sm-3 mt-1']) !!}
                                    {!! Form::close() !!}
                                </td>
                            </tr>
                            @endforeach
                            @endisset
                        </tbody>
                    </table>
                    <!-- @isset($users)
                    {{ $users->render() }}
                    @endisset -->

                </div>
            </div>
        </div>
    </div>
</div>
<script>
    function delete_confirm() {
        return confirm("Are you sure want to delete this user?");
    }
</script>
<!-- End Row -->
@endsection
@push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.tablesorter/2.31.3/js/jquery.tablesorter.min.js" integrity="sha512-qzgd5cYSZcosqpzpn7zF2ZId8f/8CHmFKZ8j7mU4OUXTNRd5g+ZHBPsgKEwoqxCtdQvExE5LprwwPAgoicguNg==" crossorigin="anonymous"></script>
    <script>
        $(document).ready(function(){
            // $('#user_tbl').tablesorter({
            //     cssAsc: 'up',
		    //     cssDesc: 'down',
            //     cssNone: 'both' 
            // });
            $('#user_tbl').DataTable({
                lengthMenu: [
                    [10, 25, 50,100,500,1000,'All'],
                    [10, 25, 50,100,500,1000,'All'],
                ],
                "aaSorting": []
            });
        })
    </script>
@endpush
