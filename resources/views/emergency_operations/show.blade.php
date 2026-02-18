@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            @include('admin.sidebar')

            <div class="col-md-9">
                <div class="card">
                    <div class="card-header">Emergency_operation {{ $emergency_operation->id }}</div>
                    <div class="card-body">

                        <a href="{{ url('/emergency_operations') }}" title="Back"><button class="btn btn-warning btn-sm"><i class="fa fa-arrow-left" aria-hidden="true"></i> Back</button></a>
                        <a href="{{ url('/emergency_operations/' . $emergency_operation->id . '/edit') }}" title="Edit Emergency_operation"><button class="btn btn-primary btn-sm"><i class="fa fa-pencil-square-o" aria-hidden="true"></i> Edit</button></a>

                        <form method="POST" action="{{ url('emergency_operations' . '/' . $emergency_operation->id) }}" accept-charset="UTF-8" style="display:inline">
                            {{ method_field('DELETE') }}
                            {{ csrf_field() }}
                            <button type="submit" class="btn btn-danger btn-sm" title="Delete Emergency_operation" onclick="return confirm(&quot;Confirm delete?&quot;)"><i class="fa fa-trash-o" aria-hidden="true"></i> Delete</button>
                        </form>
                        <br/>
                        <br/>

                        <div class="table-responsive">
                            <table class="table">
                                <tbody>
                                    <tr>
                                        <th>ID</th><td>{{ $emergency_operation->id }}</td>
                                    </tr>
                                    <tr><th> Emergency Id </th><td> {{ $emergency_operation->emergency_id }} </td></tr><tr><th> Notify </th><td> {{ $emergency_operation->notify }} </td></tr><tr><th> Command By </th><td> {{ $emergency_operation->command_by }} </td></tr><tr><th> Operating Code </th><td> {{ $emergency_operation->operating_code }} </td></tr><tr><th> Waiting Reply </th><td> {{ $emergency_operation->waiting_reply }} </td></tr><tr><th> Officer Refuse </th><td> {{ $emergency_operation->officer_refuse }} </td></tr><tr><th> Officer No Respond </th><td> {{ $emergency_operation->officer_no_respond }} </td></tr><tr><th> Status </th><td> {{ $emergency_operation->status }} </td></tr><tr><th> Remark Status </th><td> {{ $emergency_operation->remark_status }} </td></tr><tr><th> Area Id </th><td> {{ $emergency_operation->area_id }} </td></tr><tr><th> User Officers Id </th><td> {{ $emergency_operation->user_officers_id }} </td></tr><tr><th> Time Create Sos </th><td> {{ $emergency_operation->time_create_sos }} </td></tr><tr><th> Time Command </th><td> {{ $emergency_operation->time_command }} </td></tr><tr><th> Time Go To Help </th><td> {{ $emergency_operation->time_go_to_help }} </td></tr><tr><th> Time To The Scene </th><td> {{ $emergency_operation->time_to_the_scene }} </td></tr><tr><th> Time Sos Success </th><td> {{ $emergency_operation->time_sos_success }} </td></tr><tr><th> Time Sum Sos </th><td> {{ $emergency_operation->time_sum_sos }} </td></tr><tr><th> Photo By Officer </th><td> {{ $emergency_operation->photo_by_officer }} </td></tr><tr><th> Remark Photo By Officer </th><td> {{ $emergency_operation->remark_photo_by_officer }} </td></tr><tr><th> Photo Succeed </th><td> {{ $emergency_operation->photo_succeed }} </td></tr><tr><th> Remark By Helper </th><td> {{ $emergency_operation->remark_by_helper }} </td></tr>
                                </tbody>
                            </table>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
