<!-- Id Field -->
<div class="col-sm-12">
    {!! Form::label('id', __('models/installations.fields.id').':') !!}
    <p>{{ $installation->id }}</p>
</div>

<!-- Date Installation Field -->
<div class="col-sm-12">
    {!! Form::label('date_installation', __('models/installations.fields.date_installation').':') !!}
    <p>{{ $installation->date_installation }}</p>
</div>

<!-- Vehicule Id Field -->
<div class="col-sm-12">
    {!! Form::label('vehicule_id', __('models/installations.fields.vehicule_id').':') !!}
    <p>{{ $installation->vehicule_id }}</p>
</div>

<!-- Installateur Id Field -->
<div class="col-sm-12">
    {!! Form::label('installateur_id', __('models/installations.fields.installateur_id').':') !!}
    <p>{{ $installation->installateur_id }}</p>
</div>

