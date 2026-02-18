<div class="form-group {{ $errors->has('line') ? 'has-error' : ''}}">
    <label for="line" class="control-label">{{ 'Line' }}</label>
    <input class="form-control" name="line" type="text" id="line" value="{{ isset($my_log->line) ? $my_log->line : ''}}" >
    {!! $errors->first('line', '<p class="help-block">:message</p>') !!}
</div>
<div class="form-group {{ $errors->has('title') ? 'has-error' : ''}}">
    <label for="title" class="control-label">{{ 'Title' }}</label>
    <input class="form-control" name="title" type="text" id="title" value="{{ isset($my_log->title) ? $my_log->title : ''}}" >
    {!! $errors->first('title', '<p class="help-block">:message</p>') !!}
</div>
<div class="form-group {{ $errors->has('content') ? 'has-error' : ''}}">
    <label for="content" class="control-label">{{ 'Content' }}</label>
    <input class="form-control" name="content" type="text" id="content" value="{{ isset($my_log->content) ? $my_log->content : ''}}" >
    {!! $errors->first('content', '<p class="help-block">:message</p>') !!}
</div>


<div class="form-group">
    <input class="btn btn-primary" type="submit" value="{{ $formMode === 'edit' ? 'Update' : 'Create' }}">
</div>
