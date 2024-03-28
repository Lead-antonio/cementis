<!-- Name Field -->
<div class="form-group col-sm-6">
    {!! Form::label('name', __('models/importcalendars.fields.name').':') !!}
    {!! Form::text('name', null, ['class' => 'form-control']) !!}
</div>

<!-- Date Debut Field -->
<div class="form-group col-sm-6">
    {!! Form::label('date_debut', __('models/importcalendars.fields.date_debut').':') !!}
    {!! Form::text('date_debut', null, ['class' => 'form-control']) !!}
</div>

<!-- Date Fin Field -->
<div class="form-group col-sm-6">
    {!! Form::label('date_fin', __('models/importcalendars.fields.date_fin').':') !!}
    {!! Form::text('date_fin', null, ['class' => 'form-control']) !!}
</div>

<!-- Observation Field -->
<div class="form-group col-sm-6">
    {!! Form::label('observation', __('models/importcalendars.fields.observation').':') !!}
    {!! Form::text('observation', null, ['class' => 'form-control']) !!}
</div>