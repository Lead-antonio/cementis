<!-- Id Field -->
<div class="col-sm-12">
    {!! Form::label('id', __('models/scoreDrivers.fields.id').':') !!}
    <p>{{ $scoreDriver->id }}</p>
</div>

<!-- Badge Field -->
<div class="col-sm-12">
    {!! Form::label('badge', __('models/scoreDrivers.fields.badge').':') !!}
    <p>{{ $scoreDriver->badge }}</p>
</div>

<!-- Score Field -->
<div class="col-sm-12">
    {!! Form::label('score', __('models/scoreDrivers.fields.score').':') !!}
    <p>{{ $scoreDriver->score }}</p>
</div>

<!-- Transporteur Field -->
<div class="col-sm-12">
    {!! Form::label('transporteur', __('models/scoreDrivers.fields.transporteur').':') !!}
    <p>{{ $scoreDriver->transporteur }}</p>
</div>

<!-- Observation Field -->
<div class="col-sm-12">
    {!! Form::label('observation', __('models/scoreDrivers.fields.observation').':') !!}
    <p>{{ $scoreDriver->observation }}</p>
</div>

