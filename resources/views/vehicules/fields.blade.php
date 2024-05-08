<!-- Id Field -->
<div class="form-group col-sm-6">
    {!! Form::label('id', __('models/vehicules.fields.id').':') !!}
    {!! Form::number('id', null, ['class' => 'form-control']) !!}
</div>

<!-- Nom Field -->
<div class="form-group col-sm-6">
    {!! Form::label('nom', __('models/vehicules.fields.nom').':') !!}
    {!! Form::text('nom', null, ['class' => 'form-control']) !!}
</div>