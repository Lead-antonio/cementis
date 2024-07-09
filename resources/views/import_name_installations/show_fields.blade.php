<!-- Id Field -->
<div class="col-sm-12">
    {!! Form::label('id', __('models/importNameInstallations.fields.id').':') !!}
    <p>{{ $importNameInstallation->id }}</p>
</div>

<!-- Name Field -->
<div class="col-sm-12">
    {!! Form::label('name', __('models/importNameInstallations.fields.name').':') !!}
    <p>{{ $importNameInstallation->name }}</p>
</div>

<!-- Observation Field -->
<div class="col-sm-12">
    {!! Form::label('observation', __('models/importNameInstallations.fields.observation').':') !!}
    <p>{{ $importNameInstallation->observation }}</p>
</div>

