<!-- Days Field -->
<div class="form-group col-sm-2">
    {!! Form::label('days', __('models/periodSettings.fields.days').' :') !!}
    {!! Form::number('days', null, ['class' => 'form-control']) !!}
</div>

<!-- Name Field -->
<div class="form-group col-sm-2">
    {!! Form::label('name', __('models/periodSettings.fields.name').' :') !!}
    {!! Form::text('name', null, ['class' => 'form-control']) !!}
</div>

