<!-- Id Field -->
<div class="col-sm-12">
    {!! Form::label('id', __('models/dataExcels.fields.id').':') !!}
    <p>{{ $dataExcel->id }}</p>
</div>

<!-- Camion Field -->
<div class="col-sm-12">
    {!! Form::label('camion', __('models/dataExcels.fields.camion').':') !!}
    <p>{{ $dataExcel->camion }}</p>
</div>

<!-- Date Debut Field -->
<div class="col-sm-12">
    {!! Form::label('date_debut', __('models/dataExcels.fields.date_debut').':') !!}
    <p>{{ $dataExcel->date_debut }}</p>
</div>

<!-- Date Fin Field -->
<div class="col-sm-12">
    {!! Form::label('date_fin', __('models/dataExcels.fields.date_fin').':') !!}
    <p>{{ $dataExcel->date_fin }}</p>
</div>

<!-- Delais Route Field -->
<div class="col-sm-12">
    {!! Form::label('delais_route', __('models/dataExcels.fields.delais_route').':') !!}
    <p>{{ $dataExcel->delais_route }}</p>
</div>

<!-- Sigdep Reel Field -->
<div class="col-sm-12">
    {!! Form::label('sigdep_reel', __('models/dataExcels.fields.sigdep_reel').':') !!}
    <p>{{ $dataExcel->sigdep_reel }}</p>
</div>

<!-- Marche Field -->
<div class="col-sm-12">
    {!! Form::label('marche', __('models/dataExcels.fields.marche').':') !!}
    <p>{{ $dataExcel->marche }}</p>
</div>

<!-- Adresse Livraison Field -->
<div class="col-sm-12">
    {!! Form::label('adresse_livraison', __('models/dataExcels.fields.adresse_livraison').':') !!}
    <p>{{ $dataExcel->adresse_livraison }}</p>
</div>

<!-- Created At Field -->
<div class="col-sm-12">
    {!! Form::label('created_at', __('models/dataExcels.fields.created_at').':') !!}
    <p>{{ $dataExcel->created_at }}</p>
</div>

<!-- Updated At Field -->
<div class="col-sm-12">
    {!! Form::label('updated_at', __('models/dataExcels.fields.updated_at').':') !!}
    <p>{{ $dataExcel->updated_at }}</p>
</div>

