<!-- Incident Vehicule Id Field -->
<div class="form-group col-sm-6">
    {!! Form::label('incident_vehicule_id', __('models/incidentVehiculeCoordonnees.fields.incident_vehicule_id').':') !!}
    {!! Form::text('incident_vehicule_id', null, ['class' => 'form-control']) !!}
</div>

<!-- Latitude Field -->
<div class="form-group col-sm-6">
    {!! Form::label('latitude', __('models/incidentVehiculeCoordonnees.fields.latitude').':') !!}
    {!! Form::text('latitude', null, ['class' => 'form-control']) !!}
</div>

<!-- Longitude Field -->
<div class="form-group col-sm-6">
    {!! Form::label('longitude', __('models/incidentVehiculeCoordonnees.fields.longitude').':') !!}
    {!! Form::text('longitude', null, ['class' => 'form-control']) !!}
</div>

<!-- Date Heure Field -->
<div class="form-group col-sm-6">
    {!! Form::label('date_heure', __('models/incidentVehiculeCoordonnees.fields.date_heure').':') !!}
    {!! Form::text('date_heure', null, ['class' => 'form-control']) !!}
</div>

<!-- Vitesse Field -->
<div class="form-group col-sm-6">
    {!! Form::label('vitesse', __('models/incidentVehiculeCoordonnees.fields.vitesse').':') !!}
    {!! Form::text('vitesse', null, ['class' => 'form-control']) !!}
</div>