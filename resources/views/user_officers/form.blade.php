<div class="form-group {{ $errors->has('name_officer') ? 'has-error' : ''}}">
    <label for="name_officer" class="control-label">{{ 'Name Officer' }}</label>
    <input class="form-control" name="name_officer" type="text" id="name_officer" value="{{ isset($user_officer->name_officer) ? $user_officer->name_officer : ''}}" >
    {!! $errors->first('name_officer', '<p class="help-block">:message</p>') !!}
</div>
<div class="form-group {{ $errors->has('type') ? 'has-error' : ''}}">
    <label for="type" class="control-label">{{ 'Type' }}</label>
    <input class="form-control" name="type" type="text" id="type" value="{{ isset($user_officer->type) ? $user_officer->type : ''}}" >
    {!! $errors->first('type', '<p class="help-block">:message</p>') !!}
</div>
<div class="form-group {{ $errors->has('vehicle_type') ? 'has-error' : ''}}">
    <label for="vehicle_type" class="control-label">{{ 'Vehicle Type' }}</label>
    <input class="form-control" name="vehicle_type" type="text" id="vehicle_type" value="{{ isset($user_officer->vehicle_type) ? $user_officer->vehicle_type : ''}}" >
    {!! $errors->first('vehicle_type', '<p class="help-block">:message</p>') !!}
</div>
<div class="form-group {{ $errors->has('level') ? 'has-error' : ''}}">
    <label for="level" class="control-label">{{ 'Level' }}</label>
    <input class="form-control" name="level" type="text" id="level" value="{{ isset($user_officer->level) ? $user_officer->level : ''}}" >
    {!! $errors->first('level', '<p class="help-block">:message</p>') !!}
</div>
<div class="form-group {{ $errors->has('amount_help') ? 'has-error' : ''}}">
    <label for="amount_help" class="control-label">{{ 'Amount Help' }}</label>
    <input class="form-control" name="amount_help" type="text" id="amount_help" value="{{ isset($user_officer->amount_help) ? $user_officer->amount_help : ''}}" >
    {!! $errors->first('amount_help', '<p class="help-block">:message</p>') !!}
</div>
<div class="form-group {{ $errors->has('status') ? 'has-error' : ''}}">
    <label for="status" class="control-label">{{ 'Status' }}</label>
    <input class="form-control" name="status" type="text" id="status" value="{{ isset($user_officer->status) ? $user_officer->status : ''}}" >
    {!! $errors->first('status', '<p class="help-block">:message</p>') !!}
</div>
<div class="form-group {{ $errors->has('lat') ? 'has-error' : ''}}">
    <label for="lat" class="control-label">{{ 'Lat' }}</label>
    <input class="form-control" name="lat" type="text" id="lat" value="{{ isset($user_officer->lat) ? $user_officer->lat : ''}}" >
    {!! $errors->first('lat', '<p class="help-block">:message</p>') !!}
</div>
<div class="form-group {{ $errors->has('lng') ? 'has-error' : ''}}">
    <label for="lng" class="control-label">{{ 'Lng' }}</label>
    <input class="form-control" name="lng" type="text" id="lng" value="{{ isset($user_officer->lng) ? $user_officer->lng : ''}}" >
    {!! $errors->first('lng', '<p class="help-block">:message</p>') !!}
</div>
<div class="form-group {{ $errors->has('user_id') ? 'has-error' : ''}}">
    <label for="user_id" class="control-label">{{ 'User Id' }}</label>
    <input class="form-control" name="user_id" type="text" id="user_id" value="{{ isset($user_officer->user_id) ? $user_officer->user_id : ''}}" >
    {!! $errors->first('user_id', '<p class="help-block">:message</p>') !!}
</div>
<div class="form-group {{ $errors->has('area_id') ? 'has-error' : ''}}">
    <label for="area_id" class="control-label">{{ 'Area Id' }}</label>
    <input class="form-control" name="area_id" type="text" id="area_id" value="{{ isset($user_officer->area_id) ? $user_officer->area_id : ''}}" >
    {!! $errors->first('area_id', '<p class="help-block">:message</p>') !!}
</div>


<div class="form-group">
    <input class="btn btn-primary" type="submit" value="{{ $formMode === 'edit' ? 'Update' : 'Create' }}">
</div>
