<!-- Name Field -->
<div class="form-group col-sm-6">
    {!! Form::label('name', __('models/importInstallationErrors.fields.name').':') !!}
    {!! Form::text('name', null, ['class' => 'form-control']) !!}
</div>

<!-- Import Name Id Field -->
<div class="form-group col-sm-6">
    {!! Form::label('import_name_id', __('models/importInstallationErrors.fields.import_name_id').':') !!}
    {!! Form::text('import_name_id', null, ['class' => 'form-control']) !!}
</div>