<div class="form-group {{ $errors->has('name_area') ? 'has-error' : ''}}">
    <label for="name_area" class="control-label">{{ 'Name Area' }}</label>
    <input class="form-control" name="name_area" type="text" id="name_area" value="{{ isset($area->name_area) ? $area->name_area : ''}}" >
    {!! $errors->first('name_area', '<p class="help-block">:message</p>') !!}
</div>
<div class="form-group {{ $errors->has('type') ? 'has-error' : ''}}">
    <label for="type" class="control-label">{{ 'Type' }}</label>
    <input class="form-control" name="type" type="text" id="type" value="{{ isset($area->type) ? $area->type : ''}}" >
    {!! $errors->first('type', '<p class="help-block">:message</p>') !!}
</div>
<div class="form-group {{ $errors->has('polygon') ? 'has-error' : ''}}">
    <label for="polygon" class="control-label">{{ 'Polygon' }}</label>
    <textarea class="form-control" rows="5" name="polygon" type="textarea" id="polygon" >{{ isset($area->polygon) ? $area->polygon : ''}}</textarea>
    {!! $errors->first('polygon', '<p class="help-block">:message</p>') !!}
</div>
<div class="form-group {{ $errors->has('status') ? 'has-error' : ''}}">
    <label for="status" class="control-label">{{ 'Status' }}</label>
    <input class="form-control" name="status" type="text" id="status" value="{{ isset($area->status) ? $area->status : ''}}" >
    {!! $errors->first('status', '<p class="help-block">:message</p>') !!}
</div>
<div class="form-group {{ $errors->has('creator') ? 'has-error' : ''}}">
    <label for="creator" class="control-label">{{ 'Creator' }}</label>
    <input class="form-control" name="creator" type="text" id="creator" value="{{ isset($area->creator) ? $area->creator : ''}}" >
    {!! $errors->first('creator', '<p class="help-block">:message</p>') !!}
</div>


<div class="form-group">
    <input class="btn btn-primary" type="submit" value="{{ $formMode === 'edit' ? 'Update' : 'Create' }}">
</div>
