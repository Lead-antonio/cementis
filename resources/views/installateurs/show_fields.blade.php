<!-- Id Field -->
<div class="col-sm-12">
    {!! Form::label('id', __('models/installateurs.fields.id').':') !!}
    <p>{{ $installateur->id }}</p>
</div>

<!-- Matricule Field -->
<div class="col-sm-12">
    {!! Form::label('matricule', __('models/installateurs.fields.matricule').':') !!}
    <p>{{ $installateur->matricule }}</p>
</div>

<!-- Obs Field -->
<div class="col-sm-12">
    {!! Form::label('obs', __('models/installateurs.fields.obs').':') !!}
    <p>{{ $installateur->obs }}</p>
</div>

