<div class="form-group col-sm-1">
    {!! Form::label('order', __('models/process.fields.order').':') !!}
    {!! Form::number('order', null, ['class' => 'form-control', 'min' => '0']) !!}
</div>


<div class="form-group col-sm-2">
    {!! Form::label('name', __('models/process.fields.name').':') !!}
    {!! Form::text('name', null, ['class' => 'form-control']) !!}
</div>

<div class="form-group col-sm-7">
    {!! Form::label('description', __('models/process.fields.description').':') !!}
    {!! Form::textarea('description', null, ['class' => 'form-control']) !!}
</div>

