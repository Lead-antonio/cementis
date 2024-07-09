<!-- Date Installation Field -->
<div class="form-group col-sm-6">
    {!! Form::label('date_installation', __('models/installations.fields.date_installation').':') !!}
    {!! Form::text('date_installation', null, ['class' => 'form-control']) !!}
</div>

<!-- Vehicule Id Field -->
<div class="form-group col-sm-6">
    {!! Form::label('vehicule_id', __('models/installations.fields.vehicule_id').':') !!}
    {!! Form::text('vehicule_id', null, ['class' => 'form-control']) !!}
</div>

<!-- Installateur Id Field -->
<div class="form-group col-sm-6">
    {!! Form::label('installateur_id', __('models/installations.fields.installateur_id').':') !!}
    {!! Form::text('installateur_id', null, ['class' => 'form-control']) !!}
</div>