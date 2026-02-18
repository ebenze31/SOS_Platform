@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            @include('admin.sidebar')

            <div class="col-md-9">
                <div class="card">
                    <div class="card-header">User_officers</div>
                    <div class="card-body">
                        <a href="{{ url('/user_officers/create') }}" class="btn btn-success btn-sm" title="Add New User_officer">
                            <i class="fa fa-plus" aria-hidden="true"></i> Add New
                        </a>

                        <form method="GET" action="{{ url('/user_officers') }}" accept-charset="UTF-8" class="form-inline my-2 my-lg-0 float-right" role="search">
                            <div class="input-group">
                                <input type="text" class="form-control" name="search" placeholder="Search..." value="{{ request('search') }}">
                                <span class="input-group-append">
                                    <button class="btn btn-secondary" type="submit">
                                        <i class="fa fa-search"></i>
                                    </button>
                                </span>
                            </div>
                        </form>

                        <br/>
                        <br/>
                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>#</th><th>Name Officer</th><th>Type</th><th>Vehicle Type</th><th>Level</th><th>Amount Help</th><th>Status</th><th>Lat</th><th>Lng</th><th>User Id</th><th>Area Id</th><th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                @foreach($user_officers as $item)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $item->name_officer }}</td><td>{{ $item->type }}</td><td>{{ $item->vehicle_type }}</td><td>{{ $item->level }}</td><td>{{ $item->amount_help }}</td><td>{{ $item->status }}</td><td>{{ $item->lat }}</td><td>{{ $item->lng }}</td><td>{{ $item->user_id }}</td><td>{{ $item->area_id }}</td>
                                        <td>
                                            <a href="{{ url('/user_officers/' . $item->id) }}" title="View User_officer"><button class="btn btn-info btn-sm"><i class="fa fa-eye" aria-hidden="true"></i> View</button></a>
                                            <a href="{{ url('/user_officers/' . $item->id . '/edit') }}" title="Edit User_officer"><button class="btn btn-primary btn-sm"><i class="fa fa-pencil-square-o" aria-hidden="true"></i> Edit</button></a>

                                            <form method="POST" action="{{ url('/user_officers' . '/' . $item->id) }}" accept-charset="UTF-8" style="display:inline">
                                                {{ method_field('DELETE') }}
                                                {{ csrf_field() }}
                                                <button type="submit" class="btn btn-danger btn-sm" title="Delete User_officer" onclick="return confirm(&quot;Confirm delete?&quot;)"><i class="fa fa-trash-o" aria-hidden="true"></i> Delete</button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                            <div class="pagination-wrapper"> {!! $user_officers->appends(['search' => Request::get('search')])->render() !!} </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
