@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            @include('admin.sidebar')

            <div class="col-md-9">
                <div class="card">
                    <div class="card-header">User_officer {{ $user_officer->id }}</div>
                    <div class="card-body">

                        <a href="{{ url('/user_officers') }}" title="Back"><button class="btn btn-warning btn-sm"><i class="fa fa-arrow-left" aria-hidden="true"></i> Back</button></a>
                        <a href="{{ url('/user_officers/' . $user_officer->id . '/edit') }}" title="Edit User_officer"><button class="btn btn-primary btn-sm"><i class="fa fa-pencil-square-o" aria-hidden="true"></i> Edit</button></a>

                        <form method="POST" action="{{ url('user_officers' . '/' . $user_officer->id) }}" accept-charset="UTF-8" style="display:inline">
                            {{ method_field('DELETE') }}
                            {{ csrf_field() }}
                            <button type="submit" class="btn btn-danger btn-sm" title="Delete User_officer" onclick="return confirm(&quot;Confirm delete?&quot;)"><i class="fa fa-trash-o" aria-hidden="true"></i> Delete</button>
                        </form>
                        <br/>
                        <br/>

                        <div class="table-responsive">
                            <table class="table">
                                <tbody>
                                    <tr>
                                        <th>ID</th><td>{{ $user_officer->id }}</td>
                                    </tr>
                                    <tr><th> Name Officer </th><td> {{ $user_officer->name_officer }} </td></tr><tr><th> Type </th><td> {{ $user_officer->type }} </td></tr><tr><th> Vehicle Type </th><td> {{ $user_officer->vehicle_type }} </td></tr><tr><th> Level </th><td> {{ $user_officer->level }} </td></tr><tr><th> Amount Help </th><td> {{ $user_officer->amount_help }} </td></tr><tr><th> Status </th><td> {{ $user_officer->status }} </td></tr><tr><th> Lat </th><td> {{ $user_officer->lat }} </td></tr><tr><th> Lng </th><td> {{ $user_officer->lng }} </td></tr><tr><th> User Id </th><td> {{ $user_officer->user_id }} </td></tr><tr><th> Area Id </th><td> {{ $user_officer->area_id }} </td></tr>
                                </tbody>
                            </table>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
