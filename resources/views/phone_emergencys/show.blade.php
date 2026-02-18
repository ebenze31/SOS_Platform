@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            @include('admin.sidebar')

            <div class="col-md-9">
                <div class="card">
                    <div class="card-header">Phone_emergency {{ $phone_emergency->id }}</div>
                    <div class="card-body">

                        <a href="{{ url('/phone_emergencys') }}" title="Back"><button class="btn btn-warning btn-sm"><i class="fa fa-arrow-left" aria-hidden="true"></i> Back</button></a>
                        <a href="{{ url('/phone_emergencys/' . $phone_emergency->id . '/edit') }}" title="Edit Phone_emergency"><button class="btn btn-primary btn-sm"><i class="fa fa-pencil-square-o" aria-hidden="true"></i> Edit</button></a>

                        <form method="POST" action="{{ url('phone_emergencys' . '/' . $phone_emergency->id) }}" accept-charset="UTF-8" style="display:inline">
                            {{ method_field('DELETE') }}
                            {{ csrf_field() }}
                            <button type="submit" class="btn btn-danger btn-sm" title="Delete Phone_emergency" onclick="return confirm(&quot;Confirm delete?&quot;)"><i class="fa fa-trash-o" aria-hidden="true"></i> Delete</button>
                        </form>
                        <br/>
                        <br/>

                        <div class="table-responsive">
                            <table class="table">
                                <tbody>
                                    <tr>
                                        <th>ID</th><td>{{ $phone_emergency->id }}</td>
                                    </tr>
                                    <tr><th> Name </th><td> {{ $phone_emergency->name }} </td></tr><tr><th> Phone </th><td> {{ $phone_emergency->phone }} </td></tr><tr><th> Priority </th><td> {{ $phone_emergency->priority }} </td></tr><tr><th> Status </th><td> {{ $phone_emergency->status }} </td></tr>
                                </tbody>
                            </table>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
