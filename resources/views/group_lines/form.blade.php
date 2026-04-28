<div class="form-group {{ $errors->has('groupId') ? 'has-error' : ''}}">
    <label for="groupId" class="control-label">{{ 'Groupid' }}</label>
    <input class="form-control" name="groupId" type="text" id="groupId" value="{{ isset($group_line->groupId) ? $group_line->groupId : ''}}" >
    {!! $errors->first('groupId', '<p class="help-block">:message</p>') !!}
</div>
<div class="form-group {{ $errors->has('groupName') ? 'has-error' : ''}}">
    <label for="groupName" class="control-label">{{ 'Groupname' }}</label>
    <input class="form-control" name="groupName" type="text" id="groupName" value="{{ isset($group_line->groupName) ? $group_line->groupName : ''}}" >
    {!! $errors->first('groupName', '<p class="help-block">:message</p>') !!}
</div>
<div class="form-group {{ $errors->has('pictureUrl') ? 'has-error' : ''}}">
    <label for="pictureUrl" class="control-label">{{ 'Pictureurl' }}</label>
    <textarea class="form-control" rows="5" name="pictureUrl" type="textarea" id="pictureUrl" >{{ isset($group_line->pictureUrl) ? $group_line->pictureUrl : ''}}</textarea>
    {!! $errors->first('pictureUrl', '<p class="help-block">:message</p>') !!}
</div>
<div class="form-group {{ $errors->has('time_zone') ? 'has-error' : ''}}">
    <label for="time_zone" class="control-label">{{ 'Time Zone' }}</label>
    <input class="form-control" name="time_zone" type="text" id="time_zone" value="{{ isset($group_line->time_zone) ? $group_line->time_zone : ''}}" >
    {!! $errors->first('time_zone', '<p class="help-block">:message</p>') !!}
</div>
<div class="form-group {{ $errors->has('language') ? 'has-error' : ''}}">
    <label for="language" class="control-label">{{ 'Language' }}</label>
    <input class="form-control" name="language" type="text" id="language" value="{{ isset($group_line->language) ? $group_line->language : ''}}" >
    {!! $errors->first('language', '<p class="help-block">:message</p>') !!}
</div>
<div class="form-group {{ $errors->has('area_id') ? 'has-error' : ''}}">
    <label for="area_id" class="control-label">{{ 'Area Id' }}</label>
    <input class="form-control" name="area_id" type="text" id="area_id" value="{{ isset($group_line->area_id) ? $group_line->area_id : ''}}" >
    {!! $errors->first('area_id', '<p class="help-block">:message</p>') !!}
</div>
<div class="form-group {{ $errors->has('status') ? 'has-error' : ''}}">
    <label for="status" class="control-label">{{ 'Status' }}</label>
    <input class="form-control" name="status" type="text" id="status" value="{{ isset($group_line->status) ? $group_line->status : ''}}" >
    {!! $errors->first('status', '<p class="help-block">:message</p>') !!}
</div>


<div class="form-group">
    <input class="btn btn-primary" type="submit" value="{{ $formMode === 'edit' ? 'Update' : 'Create' }}">
</div>
