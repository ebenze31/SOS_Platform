<div class="form-group {{ $errors->has('name_command') ? 'has-error' : ''}}">
    <label for="name_command" class="control-label">{{ 'Name Command' }}</label>
    <input class="form-control" name="name_command" type="text" id="name_command" value="{{ isset($user_command->name_command) ? $user_command->name_command : ''}}" >
    {!! $errors->first('name_command', '<p class="help-block">:message</p>') !!}
</div>
<div class="form-group {{ $errors->has('command_role') ? 'has-error' : ''}}">
    <label for="command_role" class="control-label">{{ 'Command Role' }}</label>
    <input class="form-control" name="command_role" type="text" id="command_role" value="{{ isset($user_command->command_role) ? $user_command->command_role : ''}}" >
    {!! $errors->first('command_role', '<p class="help-block">:message</p>') !!}
</div>
<div class="form-group {{ $errors->has('number') ? 'has-error' : ''}}">
    <label for="number" class="control-label">{{ 'Number' }}</label>
    <input class="form-control" name="number" type="text" id="number" value="{{ isset($user_command->number) ? $user_command->number : ''}}" >
    {!! $errors->first('number', '<p class="help-block">:message</p>') !!}
</div>
<div class="form-group {{ $errors->has('status') ? 'has-error' : ''}}">
    <label for="status" class="control-label">{{ 'Status' }}</label>
    <input class="form-control" name="status" type="text" id="status" value="{{ isset($user_command->status) ? $user_command->status : ''}}" >
    {!! $errors->first('status', '<p class="help-block">:message</p>') !!}
</div>
<div class="form-group {{ $errors->has('creator') ? 'has-error' : ''}}">
    <label for="creator" class="control-label">{{ 'Creator' }}</label>
    <input class="form-control" name="creator" type="text" id="creator" value="{{ isset($user_command->creator) ? $user_command->creator : ''}}" >
    {!! $errors->first('creator', '<p class="help-block">:message</p>') !!}
</div>
<div class="form-group {{ $errors->has('user_id') ? 'has-error' : ''}}">
    <label for="user_id" class="control-label">{{ 'User Id' }}</label>
    <input class="form-control" name="user_id" type="text" id="user_id" value="{{ isset($user_command->user_id) ? $user_command->user_id : ''}}" >
    {!! $errors->first('user_id', '<p class="help-block">:message</p>') !!}
</div>


<div class="form-group">
    <input class="btn btn-primary" type="submit" value="{{ $formMode === 'edit' ? 'Update' : 'Create' }}">
</div>
