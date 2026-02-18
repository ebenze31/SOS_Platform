<div class="form-group {{ $errors->has('name_reporter') ? 'has-error' : ''}}">
    <label for="name_reporter" class="control-label">{{ 'Name Reporter' }}</label>
    <input class="form-control" name="name_reporter" type="text" id="name_reporter" value="{{ isset($emergency->name_reporter) ? $emergency->name_reporter : ''}}" >
    {!! $errors->first('name_reporter', '<p class="help-block">:message</p>') !!}
</div>
<div class="form-group {{ $errors->has('type_reporter') ? 'has-error' : ''}}">
    <label for="type_reporter" class="control-label">{{ 'Type Reporter' }}</label>
    <input class="form-control" name="type_reporter" type="text" id="type_reporter" value="{{ isset($emergency->type_reporter) ? $emergency->type_reporter : ''}}" >
    {!! $errors->first('type_reporter', '<p class="help-block">:message</p>') !!}
</div>
<div class="form-group {{ $errors->has('phone_reporter') ? 'has-error' : ''}}">
    <label for="phone_reporter" class="control-label">{{ 'Phone Reporter' }}</label>
    <input class="form-control" name="phone_reporter" type="text" id="phone_reporter" value="{{ isset($emergency->phone_reporter) ? $emergency->phone_reporter : ''}}" >
    {!! $errors->first('phone_reporter', '<p class="help-block">:message</p>') !!}
</div>
<div class="form-group {{ $errors->has('emergency_type') ? 'has-error' : ''}}">
    <label for="emergency_type" class="control-label">{{ 'Emergency Type' }}</label>
    <input class="form-control" name="emergency_type" type="text" id="emergency_type" value="{{ isset($emergency->emergency_type) ? $emergency->emergency_type : ''}}" >
    {!! $errors->first('emergency_type', '<p class="help-block">:message</p>') !!}
</div>
<div class="form-group {{ $errors->has('emergency_detail') ? 'has-error' : ''}}">
    <label for="emergency_detail" class="control-label">{{ 'Emergency Detail' }}</label>
    <input class="form-control" name="emergency_detail" type="text" id="emergency_detail" value="{{ isset($emergency->emergency_detail) ? $emergency->emergency_detail : ''}}" >
    {!! $errors->first('emergency_detail', '<p class="help-block">:message</p>') !!}
</div>
<div class="form-group {{ $errors->has('emergency_lat') ? 'has-error' : ''}}">
    <label for="emergency_lat" class="control-label">{{ 'Emergency Lat' }}</label>
    <input class="form-control" name="emergency_lat" type="text" id="emergency_lat" value="{{ isset($emergency->emergency_lat) ? $emergency->emergency_lat : ''}}" >
    {!! $errors->first('emergency_lat', '<p class="help-block">:message</p>') !!}
</div>
<div class="form-group {{ $errors->has('emergency_lng') ? 'has-error' : ''}}">
    <label for="emergency_lng" class="control-label">{{ 'Emergency Lng' }}</label>
    <input class="form-control" name="emergency_lng" type="text" id="emergency_lng" value="{{ isset($emergency->emergency_lng) ? $emergency->emergency_lng : ''}}" >
    {!! $errors->first('emergency_lng', '<p class="help-block">:message</p>') !!}
</div>
<div class="form-group {{ $errors->has('emergency_location') ? 'has-error' : ''}}">
    <label for="emergency_location" class="control-label">{{ 'Emergency Location' }}</label>
    <input class="form-control" name="emergency_location" type="text" id="emergency_location" value="{{ isset($emergency->emergency_location) ? $emergency->emergency_location : ''}}" >
    {!! $errors->first('emergency_location', '<p class="help-block">:message</p>') !!}
</div>
<div class="form-group {{ $errors->has('emergency_photo') ? 'has-error' : ''}}">
    <label for="emergency_photo" class="control-label">{{ 'Emergency Photo' }}</label>
    <input class="form-control" name="emergency_photo" type="text" id="emergency_photo" value="{{ isset($emergency->emergency_photo) ? $emergency->emergency_photo : ''}}" >
    {!! $errors->first('emergency_photo', '<p class="help-block">:message</p>') !!}
</div>
<div class="form-group {{ $errors->has('score_impression') ? 'has-error' : ''}}">
    <label for="score_impression" class="control-label">{{ 'Score Impression' }}</label>
    <input class="form-control" name="score_impression" type="text" id="score_impression" value="{{ isset($emergency->score_impression) ? $emergency->score_impression : ''}}" >
    {!! $errors->first('score_impression', '<p class="help-block">:message</p>') !!}
</div>
<div class="form-group {{ $errors->has('score_period') ? 'has-error' : ''}}">
    <label for="score_period" class="control-label">{{ 'Score Period' }}</label>
    <input class="form-control" name="score_period" type="text" id="score_period" value="{{ isset($emergency->score_period) ? $emergency->score_period : ''}}" >
    {!! $errors->first('score_period', '<p class="help-block">:message</p>') !!}
</div>
<div class="form-group {{ $errors->has('score_total') ? 'has-error' : ''}}">
    <label for="score_total" class="control-label">{{ 'Score Total' }}</label>
    <input class="form-control" name="score_total" type="text" id="score_total" value="{{ isset($emergency->score_total) ? $emergency->score_total : ''}}" >
    {!! $errors->first('score_total', '<p class="help-block">:message</p>') !!}
</div>
<div class="form-group {{ $errors->has('comment_help') ? 'has-error' : ''}}">
    <label for="comment_help" class="control-label">{{ 'Comment Help' }}</label>
    <input class="form-control" name="comment_help" type="text" id="comment_help" value="{{ isset($emergency->comment_help) ? $emergency->comment_help : ''}}" >
    {!! $errors->first('comment_help', '<p class="help-block">:message</p>') !!}
</div>


<div class="form-group">
    <input class="btn btn-primary" type="submit" value="{{ $formMode === 'edit' ? 'Update' : 'Create' }}">
</div>
