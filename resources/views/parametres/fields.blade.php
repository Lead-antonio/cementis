<!-- Name Field -->
<div class="form-group col-sm-6">
    {!! Form::label('name', __('models/parametres.fields.name').':') !!}
    {!! Form::text('name', null, ['class' => 'form-control']) !!}
</div>

<!-- Limite Field -->
<div class="form-group col-sm-6">
    {!! Form::label('color', __('models/parametres.fields.color').':') !!}
    {!! Form::text('color', null, ['class' => 'form-control']) !!}
</div>

<!-- Limite Field -->
<div class="form-group col-sm-6">
    {!! Form::label('limite', __('models/parametres.fields.limite').':') !!}
    {!! Form::number('limite', null, ['class' => 'form-control']) !!}
</div>