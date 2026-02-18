@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            @include('admin.sidebar')

            <div class="col-md-9">
                <div class="card">
                    <div class="card-header">Emergency {{ $emergency->id }}</div>
                    <div class="card-body">

                        <a href="{{ url('/emergencys') }}" title="Back"><button class="btn btn-warning btn-sm"><i class="fa fa-arrow-left" aria-hidden="true"></i> Back</button></a>
                        <a href="{{ url('/emergencys/' . $emergency->id . '/edit') }}" title="Edit Emergency"><button class="btn btn-primary btn-sm"><i class="fa fa-pencil-square-o" aria-hidden="true"></i> Edit</button></a>

                        <form method="POST" action="{{ url('emergencys' . '/' . $emergency->id) }}" accept-charset="UTF-8" style="display:inline">
                            {{ method_field('DELETE') }}
                            {{ csrf_field() }}
                            <button type="submit" class="btn btn-danger btn-sm" title="Delete Emergency" onclick="return confirm(&quot;Confirm delete?&quot;)"><i class="fa fa-trash-o" aria-hidden="true"></i> Delete</button>
                        </form>
                        <br/>
                        <br/>

                        <div class="table-responsive">
                            <table class="table">
                                <tbody>
                                    <tr>
                                        <th>ID</th><td>{{ $emergency->id }}</td>
                                    </tr>
                                    <tr><th> Name Reporter </th><td> {{ $emergency->name_reporter }} </td></tr><tr><th> Type Reporter </th><td> {{ $emergency->type_reporter }} </td></tr><tr><th> Phone Reporter </th><td> {{ $emergency->phone_reporter }} </td></tr><tr><th> Emergency Type </th><td> {{ $emergency->emergency_type }} </td></tr><tr><th> Emergency Detail </th><td> {{ $emergency->emergency_detail }} </td></tr><tr><th> Emergency Lat </th><td> {{ $emergency->emergency_lat }} </td></tr><tr><th> Emergency Lng </th><td> {{ $emergency->emergency_lng }} </td></tr><tr><th> Emergency Location </th><td> {{ $emergency->emergency_location }} </td></tr><tr><th> Emergency Photo </th><td> {{ $emergency->emergency_photo }} </td></tr><tr><th> Score Impression </th><td> {{ $emergency->score_impression }} </td></tr><tr><th> Score Period </th><td> {{ $emergency->score_period }} </td></tr><tr><th> Score Total </th><td> {{ $emergency->score_total }} </td></tr><tr><th> Comment Help </th><td> {{ $emergency->comment_help }} </td></tr>
                                </tbody>
                            </table>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
