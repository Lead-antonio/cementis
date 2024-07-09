<!-- Id Field -->
<div class="col-sm-12">
    {!! Form::label('id', __('models/importInstallationErrors.fields.id').':') !!}
    <p>{{ $importInstallationError->id }}</p>
</div>

<!-- Name Field -->
<div class="col-sm-12">
    {!! Form::label('name', __('models/importInstallationErrors.fields.name').':') !!}
    <p>{{ $importInstallationError->name }}</p>
</div>

<!-- Import Name Id Field -->
<div class="col-sm-12">
    {!! Form::label('import_name_id', __('models/importInstallationErrors.fields.import_name_id').':') !!}
    <p>{{ $importInstallationError->import_name_id }}</p>
</div>

