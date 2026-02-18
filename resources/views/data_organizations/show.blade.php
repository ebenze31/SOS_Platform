@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            @include('admin.sidebar')

            <div class="col-md-9">
                <div class="card">
                    <div class="card-header">Data_organization {{ $data_organization->id }}</div>
                    <div class="card-body">

                        <a href="{{ url('/data_organizations') }}" title="Back"><button class="btn btn-warning btn-sm"><i class="fa fa-arrow-left" aria-hidden="true"></i> Back</button></a>
                        <a href="{{ url('/data_organizations/' . $data_organization->id . '/edit') }}" title="Edit Data_organization"><button class="btn btn-primary btn-sm"><i class="fa fa-pencil-square-o" aria-hidden="true"></i> Edit</button></a>

                        <form method="POST" action="{{ url('data_organizations' . '/' . $data_organization->id) }}" accept-charset="UTF-8" style="display:inline">
                            {{ method_field('DELETE') }}
                            {{ csrf_field() }}
                            <button type="submit" class="btn btn-danger btn-sm" title="Delete Data_organization" onclick="return confirm(&quot;Confirm delete?&quot;)"><i class="fa fa-trash-o" aria-hidden="true"></i> Delete</button>
                        </form>
                        <br/>
                        <br/>

                        <div class="table-responsive">
                            <table class="table">
                                <tbody>
                                    <tr>
                                        <th>ID</th><td>{{ $data_organization->id }}</td>
                                    </tr>
                                    <tr><th> Name </th><td> {{ $data_organization->name }} </td></tr><tr><th> Full Name </th><td> {{ $data_organization->full_name }} </td></tr><tr><th> Phone </th><td> {{ $data_organization->phone }} </td></tr><tr><th> Mail </th><td> {{ $data_organization->mail }} </td></tr>
                                </tbody>
                            </table>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
