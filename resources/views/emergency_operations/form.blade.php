<div class="form-group {{ $errors->has('emergency_id') ? 'has-error' : ''}}">
    <label for="emergency_id" class="control-label">{{ 'Emergency Id' }}</label>
    <input class="form-control" name="emergency_id" type="text" id="emergency_id" value="{{ isset($emergency_operation->emergency_id) ? $emergency_operation->emergency_id : ''}}" >
    {!! $errors->first('emergency_id', '<p class="help-block">:message</p>') !!}
</div>
<div class="form-group {{ $errors->has('notify') ? 'has-error' : ''}}">
    <label for="notify" class="control-label">{{ 'Notify' }}</label>
    <input class="form-control" name="notify" type="text" id="notify" value="{{ isset($emergency_operation->notify) ? $emergency_operation->notify : ''}}" >
    {!! $errors->first('notify', '<p class="help-block">:message</p>') !!}
</div>
<div class="form-group {{ $errors->has('command_by') ? 'has-error' : ''}}">
    <label for="command_by" class="control-label">{{ 'Command By' }}</label>
    <input class="form-control" name="command_by" type="text" id="command_by" value="{{ isset($emergency_operation->command_by) ? $emergency_operation->command_by : ''}}" >
    {!! $errors->first('command_by', '<p class="help-block">:message</p>') !!}
</div>
<div class="form-group {{ $errors->has('operating_code') ? 'has-error' : ''}}">
    <label for="operating_code" class="control-label">{{ 'Operating Code' }}</label>
    <input class="form-control" name="operating_code" type="text" id="operating_code" value="{{ isset($emergency_operation->operating_code) ? $emergency_operation->operating_code : ''}}" >
    {!! $errors->first('operating_code', '<p class="help-block">:message</p>') !!}
</div>
<div class="form-group {{ $errors->has('waiting_reply') ? 'has-error' : ''}}">
    <label for="waiting_reply" class="control-label">{{ 'Waiting Reply' }}</label>
    <input class="form-control" name="waiting_reply" type="text" id="waiting_reply" value="{{ isset($emergency_operation->waiting_reply) ? $emergency_operation->waiting_reply : ''}}" >
    {!! $errors->first('waiting_reply', '<p class="help-block">:message</p>') !!}
</div>
<div class="form-group {{ $errors->has('officer_refuse') ? 'has-error' : ''}}">
    <label for="officer_refuse" class="control-label">{{ 'Officer Refuse' }}</label>
    <input class="form-control" name="officer_refuse" type="text" id="officer_refuse" value="{{ isset($emergency_operation->officer_refuse) ? $emergency_operation->officer_refuse : ''}}" >
    {!! $errors->first('officer_refuse', '<p class="help-block">:message</p>') !!}
</div>
<div class="form-group {{ $errors->has('officer_no_respond') ? 'has-error' : ''}}">
    <label for="officer_no_respond" class="control-label">{{ 'Officer No Respond' }}</label>
    <input class="form-control" name="officer_no_respond" type="text" id="officer_no_respond" value="{{ isset($emergency_operation->officer_no_respond) ? $emergency_operation->officer_no_respond : ''}}" >
    {!! $errors->first('officer_no_respond', '<p class="help-block">:message</p>') !!}
</div>
<div class="form-group {{ $errors->has('status') ? 'has-error' : ''}}">
    <label for="status" class="control-label">{{ 'Status' }}</label>
    <input class="form-control" name="status" type="text" id="status" value="{{ isset($emergency_operation->status) ? $emergency_operation->status : ''}}" >
    {!! $errors->first('status', '<p class="help-block">:message</p>') !!}
</div>
<div class="form-group {{ $errors->has('remark_status') ? 'has-error' : ''}}">
    <label for="remark_status" class="control-label">{{ 'Remark Status' }}</label>
    <input class="form-control" name="remark_status" type="text" id="remark_status" value="{{ isset($emergency_operation->remark_status) ? $emergency_operation->remark_status : ''}}" >
    {!! $errors->first('remark_status', '<p class="help-block">:message</p>') !!}
</div>
<div class="form-group {{ $errors->has('area_id') ? 'has-error' : ''}}">
    <label for="area_id" class="control-label">{{ 'Area Id' }}</label>
    <input class="form-control" name="area_id" type="text" id="area_id" value="{{ isset($emergency_operation->area_id) ? $emergency_operation->area_id : ''}}" >
    {!! $errors->first('area_id', '<p class="help-block">:message</p>') !!}
</div>
<div class="form-group {{ $errors->has('user_officers_id') ? 'has-error' : ''}}">
    <label for="user_officers_id" class="control-label">{{ 'User Officers Id' }}</label>
    <input class="form-control" name="user_officers_id" type="text" id="user_officers_id" value="{{ isset($emergency_operation->user_officers_id) ? $emergency_operation->user_officers_id : ''}}" >
    {!! $errors->first('user_officers_id', '<p class="help-block">:message</p>') !!}
</div>
<div class="form-group {{ $errors->has('time_create_sos') ? 'has-error' : ''}}">
    <label for="time_create_sos" class="control-label">{{ 'Time Create Sos' }}</label>
    <input class="form-control" name="time_create_sos" type="datetime-local" id="time_create_sos" value="{{ isset($emergency_operation->time_create_sos) ? $emergency_operation->time_create_sos : ''}}" >
    {!! $errors->first('time_create_sos', '<p class="help-block">:message</p>') !!}
</div>
<div class="form-group {{ $errors->has('time_command') ? 'has-error' : ''}}">
    <label for="time_command" class="control-label">{{ 'Time Command' }}</label>
    <input class="form-control" name="time_command" type="datetime-local" id="time_command" value="{{ isset($emergency_operation->time_command) ? $emergency_operation->time_command : ''}}" >
    {!! $errors->first('time_command', '<p class="help-block">:message</p>') !!}
</div>
<div class="form-group {{ $errors->has('time_go_to_help') ? 'has-error' : ''}}">
    <label for="time_go_to_help" class="control-label">{{ 'Time Go To Help' }}</label>
    <input class="form-control" name="time_go_to_help" type="datetime-local" id="time_go_to_help" value="{{ isset($emergency_operation->time_go_to_help) ? $emergency_operation->time_go_to_help : ''}}" >
    {!! $errors->first('time_go_to_help', '<p class="help-block">:message</p>') !!}
</div>
<div class="form-group {{ $errors->has('time_to_the_scene') ? 'has-error' : ''}}">
    <label for="time_to_the_scene" class="control-label">{{ 'Time To The Scene' }}</label>
    <input class="form-control" name="time_to_the_scene" type="datetime-local" id="time_to_the_scene" value="{{ isset($emergency_operation->time_to_the_scene) ? $emergency_operation->time_to_the_scene : ''}}" >
    {!! $errors->first('time_to_the_scene', '<p class="help-block">:message</p>') !!}
</div>
<div class="form-group {{ $errors->has('time_sos_success') ? 'has-error' : ''}}">
    <label for="time_sos_success" class="control-label">{{ 'Time Sos Success' }}</label>
    <input class="form-control" name="time_sos_success" type="datetime-local" id="time_sos_success" value="{{ isset($emergency_operation->time_sos_success) ? $emergency_operation->time_sos_success : ''}}" >
    {!! $errors->first('time_sos_success', '<p class="help-block">:message</p>') !!}
</div>
<div class="form-group {{ $errors->has('time_sum_sos') ? 'has-error' : ''}}">
    <label for="time_sum_sos" class="control-label">{{ 'Time Sum Sos' }}</label>
    <input class="form-control" name="time_sum_sos" type="text" id="time_sum_sos" value="{{ isset($emergency_operation->time_sum_sos) ? $emergency_operation->time_sum_sos : ''}}" >
    {!! $errors->first('time_sum_sos', '<p class="help-block">:message</p>') !!}
</div>
<div class="form-group {{ $errors->has('photo_by_officer') ? 'has-error' : ''}}">
    <label for="photo_by_officer" class="control-label">{{ 'Photo By Officer' }}</label>
    <input class="form-control" name="photo_by_officer" type="text" id="photo_by_officer" value="{{ isset($emergency_operation->photo_by_officer) ? $emergency_operation->photo_by_officer : ''}}" >
    {!! $errors->first('photo_by_officer', '<p class="help-block">:message</p>') !!}
</div>
<div class="form-group {{ $errors->has('remark_photo_by_officer') ? 'has-error' : ''}}">
    <label for="remark_photo_by_officer" class="control-label">{{ 'Remark Photo By Officer' }}</label>
    <input class="form-control" name="remark_photo_by_officer" type="text" id="remark_photo_by_officer" value="{{ isset($emergency_operation->remark_photo_by_officer) ? $emergency_operation->remark_photo_by_officer : ''}}" >
    {!! $errors->first('remark_photo_by_officer', '<p class="help-block">:message</p>') !!}
</div>
<div class="form-group {{ $errors->has('photo_succeed') ? 'has-error' : ''}}">
    <label for="photo_succeed" class="control-label">{{ 'Photo Succeed' }}</label>
    <input class="form-control" name="photo_succeed" type="text" id="photo_succeed" value="{{ isset($emergency_operation->photo_succeed) ? $emergency_operation->photo_succeed : ''}}" >
    {!! $errors->first('photo_succeed', '<p class="help-block">:message</p>') !!}
</div>
<div class="form-group {{ $errors->has('remark_by_helper') ? 'has-error' : ''}}">
    <label for="remark_by_helper" class="control-label">{{ 'Remark By Helper' }}</label>
    <input class="form-control" name="remark_by_helper" type="text" id="remark_by_helper" value="{{ isset($emergency_operation->remark_by_helper) ? $emergency_operation->remark_by_helper : ''}}" >
    {!! $errors->first('remark_by_helper', '<p class="help-block">:message</p>') !!}
</div>


<div class="form-group">
    <input class="btn btn-primary" type="submit" value="{{ $formMode === 'edit' ? 'Update' : 'Create' }}">
</div>
