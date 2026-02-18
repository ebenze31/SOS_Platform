@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            @include('admin.sidebar')

            <div class="col-md-9">
                <div class="card">
                    <div class="card-header">Area {{ $area->id }}</div>
                    <div class="card-body">

                        <a href="{{ url('/areas') }}" title="Back"><button class="btn btn-warning btn-sm"><i class="fa fa-arrow-left" aria-hidden="true"></i> Back</button></a>
                        <a href="{{ url('/areas/' . $area->id . '/edit') }}" title="Edit Area"><button class="btn btn-primary btn-sm"><i class="fa fa-pencil-square-o" aria-hidden="true"></i> Edit</button></a>

                        <form method="POST" action="{{ url('areas' . '/' . $area->id) }}" accept-charset="UTF-8" style="display:inline">
                            {{ method_field('DELETE') }}
                            {{ csrf_field() }}
                            <button type="submit" class="btn btn-danger btn-sm" title="Delete Area" onclick="return confirm(&quot;Confirm delete?&quot;)"><i class="fa fa-trash-o" aria-hidden="true"></i> Delete</button>
                        </form>
                        <br/>
                        <br/>

                        <div class="table-responsive">
                            <table class="table">
                                <tbody>
                                    <tr>
                                        <th>ID</th><td>{{ $area->id }}</td>
                                    </tr>
                                    <tr><th> Name Area </th><td> {{ $area->name_area }} </td></tr><tr><th> Type </th><td> {{ $area->type }} </td></tr><tr><th> Polygon </th><td> {{ $area->polygon }} </td></tr><tr><th> Status </th><td> {{ $area->status }} </td></tr><tr><th> Creator </th><td> {{ $area->creator }} </td></tr>
                                </tbody>
                            </table>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
