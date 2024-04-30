<!-- Id Field -->
<div class="col-sm-12">
    {!! Form::label('id', __('models/groupeEvents.fields.id').':') !!}
    <p>{{ $groupeEvent->id }}</p>
</div>

<!-- Key Field -->
<div class="col-sm-12">
    {!! Form::label('key', __('models/groupeEvents.fields.key').':') !!}
    <p>{{ $groupeEvent->key }}</p>
</div>

<!-- Imei Field -->
<div class="col-sm-12">
    {!! Form::label('imei', __('models/groupeEvents.fields.imei').':') !!}
    <p>{{ $groupeEvent->imei }}</p>
</div>

<!-- Chauffeur Field -->
<div class="col-sm-12">
    {!! Form::label('chauffeur', __('models/groupeEvents.fields.chauffeur').':') !!}
    <p>{{ $groupeEvent->chauffeur }}</p>
</div>

<!-- Vehicule Field -->
<div class="col-sm-12">
    {!! Form::label('vehicule', __('models/groupeEvents.fields.vehicule').':') !!}
    <p>{{ $groupeEvent->vehicule }}</p>
</div>

<!-- Type Field -->
<div class="col-sm-12">
    {!! Form::label('type', __('models/groupeEvents.fields.type').':') !!}
    <p>{{ $groupeEvent->type }}</p>
</div>

<!-- Latitude Field -->
<div class="col-sm-12">
    {!! Form::label('latitude', __('models/groupeEvents.fields.latitude').':') !!}
    <p>{{ $groupeEvent->latitude }}</p>
</div>

<!-- Longitude Field -->
<div class="col-sm-12">
    {!! Form::label('longitude', __('models/groupeEvents.fields.longitude').':') !!}
    <p>{{ $groupeEvent->longitude }}</p>
</div>

<!-- Duree Field -->
<div class="col-sm-12">
    {!! Form::label('duree', __('models/groupeEvents.fields.duree').':') !!}
    <p>{{ $groupeEvent->duree }}</p>
</div>

<!-- Created At Field -->
<div class="col-sm-12">
    {!! Form::label('created_at', __('models/groupeEvents.fields.created_at').':') !!}
    <p>{{ $groupeEvent->created_at }}</p>
</div>

<!-- Updated At Field -->
<div class="col-sm-12">
    {!! Form::label('updated_at', __('models/groupeEvents.fields.updated_at').':') !!}
    <p>{{ $groupeEvent->updated_at }}</p>
</div>

