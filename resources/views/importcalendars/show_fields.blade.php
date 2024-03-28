<!-- Id Field -->
<div class="col-sm-12">
    {!! Form::label('id', __('models/importcalendars.fields.id').':') !!}
    <p>{{ $importcalendar->id }}</p>
</div>

<!-- Name Field -->
<div class="col-sm-12">
    {!! Form::label('name', __('models/importcalendars.fields.name').':') !!}
    <p>{{ $importcalendar->name }}</p>
</div>

<!-- Date Debut Field -->
<div class="col-sm-12">
    {!! Form::label('date_debut', __('models/importcalendars.fields.date_debut').':') !!}
    <p>{{ $importcalendar->date_debut }}</p>
</div>

<!-- Date Fin Field -->
<div class="col-sm-12">
    {!! Form::label('date_fin', __('models/importcalendars.fields.date_fin').':') !!}
    <p>{{ $importcalendar->date_fin }}</p>
</div>

<!-- Observation Field -->
<div class="col-sm-12">
    {!! Form::label('observation', __('models/importcalendars.fields.observation').':') !!}
    <p>{{ $importcalendar->observation }}</p>
</div>

