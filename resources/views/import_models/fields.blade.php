<!-- Nom Field -->
<div class="form-group col-sm-6">
    {!! Form::label('nom', __('models/importModels.fields.nom').':') !!}
    {!! Form::text('nom', null, ['class' => 'form-control']) !!}
</div>

<!-- Model Field -->
<div class="form-group col-sm-6">
    {!! Form::label('model', __('models/importModels.fields.model').':') !!}
    {!! Form::text('model', null, ['class' => 'form-control']) !!}
</div>

<!-- Association Field -->
<div class="form-group col-sm-12 col-lg-12">
    {!! Form::label('association', __('models/importModels.fields.association').':') !!}
    {!! Form::textarea('association', $importModel->association, ['class' => 'form-control', 'rows' => 10]) !!}
</div>


<!-- Observation Field -->
<div class="form-group col-sm-12 col-lg-12">
    {!! Form::label('observation', __('models/importModels.fields.observation').':') !!}
    {!! Form::textarea('observation', null, ['class' => 'form-control']) !!}
</div>