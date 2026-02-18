@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            @include('admin.sidebar')

            <div class="col-md-9">
                <div class="card">
                    <div class="card-header">User_command {{ $user_command->id }}</div>
                    <div class="card-body">

                        <a href="{{ url('/user_commands') }}" title="Back"><button class="btn btn-warning btn-sm"><i class="fa fa-arrow-left" aria-hidden="true"></i> Back</button></a>
                        <a href="{{ url('/user_commands/' . $user_command->id . '/edit') }}" title="Edit User_command"><button class="btn btn-primary btn-sm"><i class="fa fa-pencil-square-o" aria-hidden="true"></i> Edit</button></a>

                        <form method="POST" action="{{ url('user_commands' . '/' . $user_command->id) }}" accept-charset="UTF-8" style="display:inline">
                            {{ method_field('DELETE') }}
                            {{ csrf_field() }}
                            <button type="submit" class="btn btn-danger btn-sm" title="Delete User_command" onclick="return confirm(&quot;Confirm delete?&quot;)"><i class="fa fa-trash-o" aria-hidden="true"></i> Delete</button>
                        </form>
                        <br/>
                        <br/>

                        <div class="table-responsive">
                            <table class="table">
                                <tbody>
                                    <tr>
                                        <th>ID</th><td>{{ $user_command->id }}</td>
                                    </tr>
                                    <tr><th> Name Command </th><td> {{ $user_command->name_command }} </td></tr><tr><th> Command Role </th><td> {{ $user_command->command_role }} </td></tr><tr><th> Number </th><td> {{ $user_command->number }} </td></tr><tr><th> Status </th><td> {{ $user_command->status }} </td></tr><tr><th> Creator </th><td> {{ $user_command->creator }} </td></tr><tr><th> User Id </th><td> {{ $user_command->user_id }} </td></tr>
                                </tbody>
                            </table>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
