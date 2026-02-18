@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            @include('admin.sidebar')

            <div class="col-md-9">
                <div class="card">
                    <div class="card-header">Emergency_operations</div>
                    <div class="card-body">
                        <a href="{{ url('/emergency_operations/create') }}" class="btn btn-success btn-sm" title="Add New Emergency_operation">
                            <i class="fa fa-plus" aria-hidden="true"></i> Add New
                        </a>

                        <form method="GET" action="{{ url('/emergency_operations') }}" accept-charset="UTF-8" class="form-inline my-2 my-lg-0 float-right" role="search">
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
                                        <th>#</th><th>Emergency Id</th><th>Notify</th><th>Command By</th><th>Operating Code</th><th>Waiting Reply</th><th>Officer Refuse</th><th>Officer No Respond</th><th>Status</th><th>Remark Status</th><th>Area Id</th><th>User Officers Id</th><th>Time Create Sos</th><th>Time Command</th><th>Time Go To Help</th><th>Time To The Scene</th><th>Time Sos Success</th><th>Time Sum Sos</th><th>Photo By Officer</th><th>Remark Photo By Officer</th><th>Photo Succeed</th><th>Remark By Helper</th><th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                @foreach($emergency_operations as $item)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $item->emergency_id }}</td><td>{{ $item->notify }}</td><td>{{ $item->command_by }}</td><td>{{ $item->operating_code }}</td><td>{{ $item->waiting_reply }}</td><td>{{ $item->officer_refuse }}</td><td>{{ $item->officer_no_respond }}</td><td>{{ $item->status }}</td><td>{{ $item->remark_status }}</td><td>{{ $item->area_id }}</td><td>{{ $item->user_officers_id }}</td><td>{{ $item->time_create_sos }}</td><td>{{ $item->time_command }}</td><td>{{ $item->time_go_to_help }}</td><td>{{ $item->time_to_the_scene }}</td><td>{{ $item->time_sos_success }}</td><td>{{ $item->time_sum_sos }}</td><td>{{ $item->photo_by_officer }}</td><td>{{ $item->remark_photo_by_officer }}</td><td>{{ $item->photo_succeed }}</td><td>{{ $item->remark_by_helper }}</td>
                                        <td>
                                            <a href="{{ url('/emergency_operations/' . $item->id) }}" title="View Emergency_operation"><button class="btn btn-info btn-sm"><i class="fa fa-eye" aria-hidden="true"></i> View</button></a>
                                            <a href="{{ url('/emergency_operations/' . $item->id . '/edit') }}" title="Edit Emergency_operation"><button class="btn btn-primary btn-sm"><i class="fa fa-pencil-square-o" aria-hidden="true"></i> Edit</button></a>

                                            <form method="POST" action="{{ url('/emergency_operations' . '/' . $item->id) }}" accept-charset="UTF-8" style="display:inline">
                                                {{ method_field('DELETE') }}
                                                {{ csrf_field() }}
                                                <button type="submit" class="btn btn-danger btn-sm" title="Delete Emergency_operation" onclick="return confirm(&quot;Confirm delete?&quot;)"><i class="fa fa-trash-o" aria-hidden="true"></i> Delete</button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                            <div class="pagination-wrapper"> {!! $emergency_operations->appends(['search' => Request::get('search')])->render() !!} </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
