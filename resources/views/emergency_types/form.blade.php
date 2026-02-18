<div class="form-group {{ $errors->has('name_emergency') ? 'has-error' : ''}}">
    <label for="name_emergency" class="control-label">{{ 'Name Emergency' }}</label>
    <input class="form-control" name="name_emergency" type="text" id="name_emergency" value="{{ isset($emergency_type->name_emergency) ? $emergency_type->name_emergency : ''}}" >
    {!! $errors->first('name_emergency', '<p class="help-block">:message</p>') !!}
</div>
<div class="form-group {{ $errors->has('status') ? 'has-error' : ''}}">
    <label for="status" class="control-label">{{ 'Status' }}</label>
    <input class="form-control" name="status" type="text" id="status" value="{{ isset($emergency_type->status) ? $emergency_type->status : ''}}" >
    {!! $errors->first('status', '<p class="help-block">:message</p>') !!}
</div>


<div class="form-group">
    <input class="btn btn-primary" type="submit" value="{{ $formMode === 'edit' ? 'Update' : 'Create' }}">
</div>
