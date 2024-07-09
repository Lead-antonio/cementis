<!-- Matricule Field -->
<div class="form-group col-sm-6">
    {!! Form::label('matricule', __('models/installateurs.fields.matricule').':') !!}
    {!! Form::text('matricule', null, ['class' => 'form-control']) !!}
</div>

<!-- Obs Field -->
<div class="form-group col-sm-6">
    {!! Form::label('obs', __('models/installateurs.fields.obs').':') !!}
    {!! Form::text('obs', null, ['class' => 'form-control']) !!}
</div>