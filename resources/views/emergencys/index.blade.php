@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            @include('admin.sidebar')

            <div class="col-md-9">
                <div class="card">
                    <div class="card-header">Emergencys</div>
                    <div class="card-body">
                        <a href="{{ url('/emergencys/create') }}" class="btn btn-success btn-sm" title="Add New Emergency">
                            <i class="fa fa-plus" aria-hidden="true"></i> Add New
                        </a>

                        <form method="GET" action="{{ url('/emergencys') }}" accept-charset="UTF-8" class="form-inline my-2 my-lg-0 float-right" role="search">
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
                                        <th>#</th><th>Name Reporter</th><th>Type Reporter</th><th>Phone Reporter</th><th>Emergency Type</th><th>Emergency Detail</th><th>Emergency Lat</th><th>Emergency Lng</th><th>Emergency Location</th><th>Emergency Photo</th><th>Score Impression</th><th>Score Period</th><th>Score Total</th><th>Comment Help</th><th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                @foreach($emergencys as $item)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $item->name_reporter }}</td><td>{{ $item->type_reporter }}</td><td>{{ $item->phone_reporter }}</td><td>{{ $item->emergency_type }}</td><td>{{ $item->emergency_detail }}</td><td>{{ $item->emergency_lat }}</td><td>{{ $item->emergency_lng }}</td><td>{{ $item->emergency_location }}</td><td>{{ $item->emergency_photo }}</td><td>{{ $item->score_impression }}</td><td>{{ $item->score_period }}</td><td>{{ $item->score_total }}</td><td>{{ $item->comment_help }}</td>
                                        <td>
                                            <a href="{{ url('/emergencys/' . $item->id) }}" title="View Emergency"><button class="btn btn-info btn-sm"><i class="fa fa-eye" aria-hidden="true"></i> View</button></a>
                                            <a href="{{ url('/emergencys/' . $item->id . '/edit') }}" title="Edit Emergency"><button class="btn btn-primary btn-sm"><i class="fa fa-pencil-square-o" aria-hidden="true"></i> Edit</button></a>

                                            <form method="POST" action="{{ url('/emergencys' . '/' . $item->id) }}" accept-charset="UTF-8" style="display:inline">
                                                {{ method_field('DELETE') }}
                                                {{ csrf_field() }}
                                                <button type="submit" class="btn btn-danger btn-sm" title="Delete Emergency" onclick="return confirm(&quot;Confirm delete?&quot;)"><i class="fa fa-trash-o" aria-hidden="true"></i> Delete</button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                            <div class="pagination-wrapper"> {!! $emergencys->appends(['search' => Request::get('search')])->render() !!} </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
