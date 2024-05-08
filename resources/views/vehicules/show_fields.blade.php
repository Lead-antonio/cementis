<!-- Id Field -->
<div class="col-sm-12">
    {!! Form::label('id', __('models/vehicules.fields.id').':') !!}
    <p>{{ $vehicule->id }}</p>
</div>

<!-- Nom Field -->
<div class="col-sm-12">
    {!! Form::label('nom', __('models/vehicules.fields.nom').':') !!}
    <p>{{ $vehicule->nom }}</p>
</div>

<!-- Created At Field -->
<div class="col-sm-12">
    {!! Form::label('created_at', __('models/vehicules.fields.created_at').':') !!}
    <p>{{ $vehicule->created_at }}</p>
</div>

<!-- Updated At Field -->
<div class="col-sm-12">
    {!! Form::label('updated_at', __('models/vehicules.fields.updated_at').':') !!}
    <p>{{ $vehicule->updated_at }}</p>
</div>

