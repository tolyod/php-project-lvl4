    <div class="form-group">
        {!! Form::label(__('layout.common.label.name'), null, ['class' => 'control-label']) !!}
        {!! Form::text('name', null, ['class' => 'form-control']) !!}
    </div>
    <div class="form-group">
        {!! Form::label(__('layout.common.label.description'), null, ['class' => 'control-label']) !!}
        {!! Form::textarea('description', null, ['class' => 'form-control']) !!}
    </div>
    {!! Form::token() !!}
