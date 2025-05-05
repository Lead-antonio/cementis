<!-- Id Field -->
<div class="col-sm-12">
    {!! Form::label('id', __('models/incidentVehiculeCoordonnees.fields.id').':') !!}
    <p>{{ $incidentVehiculeCoordonnee->id }}</p>
</div>

<!-- Incident Vehicule Id Field -->
<div class="col-sm-12">
    {!! Form::label('incident_vehicule_id', __('models/incidentVehiculeCoordonnees.fields.incident_vehicule_id').':') !!}
    <p>{{ $incidentVehiculeCoordonnee->incident_vehicule_id }}</p>
</div>

<!-- Latitude Field -->
<div class="col-sm-12">
    {!! Form::label('latitude', __('models/incidentVehiculeCoordonnees.fields.latitude').':') !!}
    <p>{{ $incidentVehiculeCoordonnee->latitude }}</p>
</div>

<!-- Longitude Field -->
<div class="col-sm-12">
    {!! Form::label('longitude', __('models/incidentVehiculeCoordonnees.fields.longitude').':') !!}
    <p>{{ $incidentVehiculeCoordonnee->longitude }}</p>
</div>

<!-- Date Heure Field -->
<div class="col-sm-12">
    {!! Form::label('date_heure', __('models/incidentVehiculeCoordonnees.fields.date_heure').':') !!}
    <p>{{ $incidentVehiculeCoordonnee->date_heure }}</p>
</div>

<!-- Vitesse Field -->
<div class="col-sm-12">
    {!! Form::label('vitesse', __('models/incidentVehiculeCoordonnees.fields.vitesse').':') !!}
    <p>{{ $incidentVehiculeCoordonnee->vitesse }}</p>
</div>

