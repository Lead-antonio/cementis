<!-- Nom Field -->
<div class="form-group col-sm-5">
    {!! Form::label('nom', __('models/transporteurs.fields.nom') .': *') !!}
    {!! Form::text('nom', null, ['class' => 'form-control','placeholder'=>'Nom']) !!}
</div>

<!-- Adresse Field -->
<div class="form-group col-sm-5">
    {!! Form::label('Adresse', __('models/transporteurs.fields.Adresse').':') !!}
    {!! Form::text('Adresse', null, ['class' => 'form-control','placeholder'=>'Adresse']) !!}
</div>