<!-- Id Field -->
<div class="col-sm-12">
    {!! Form::label('id', __('models/importExcels.fields.id').':') !!}
    <p>{{ $importExcel->id }}</p>
</div>

<!-- Name Importation Field -->
<div class="col-sm-12">
    {!! Form::label('name_importation', __('models/importExcels.fields.name_importation').':') !!}
    <p>{{ $importExcel->name_importation }}</p>
</div>

<!-- Rfid Chauffeur Field -->
<div class="col-sm-12">
    {!! Form::label('rfid_chauffeur', __('models/importExcels.fields.rfid_chauffeur').':') !!}
    <p>{{ $importExcel->rfid_chauffeur }}</p>
</div>

<!-- Camion Field -->
<div class="col-sm-12">
    {!! Form::label('camion', __('models/importExcels.fields.camion').':') !!}
    <p>{{ $importExcel->camion }}</p>
</div>

<!-- Date Debut Field -->
<div class="col-sm-12">
    {!! Form::label('date_debut', __('models/importExcels.fields.date_debut').':') !!}
    <p>{{ $importExcel->date_debut }}</p>
</div>

<!-- Date Fin Field -->
<div class="col-sm-12">
    {!! Form::label('date_fin', __('models/importExcels.fields.date_fin').':') !!}
    <p>{{ $importExcel->date_fin }}</p>
</div>

<!-- Delais Route Field -->
<div class="col-sm-12">
    {!! Form::label('delais_route', __('models/importExcels.fields.delais_route').':') !!}
    <p>{{ $importExcel->delais_route }}</p>
</div>

<!-- Sigdep Reel Field -->
<div class="col-sm-12">
    {!! Form::label('sigdep_reel', __('models/importExcels.fields.sigdep_reel').':') !!}
    <p>{{ $importExcel->sigdep_reel }}</p>
</div>

<!-- Marche Field -->
<div class="col-sm-12">
    {!! Form::label('marche', __('models/importExcels.fields.marche').':') !!}
    <p>{{ $importExcel->marche }}</p>
</div>

<!-- Adresse Livraison Field -->
<div class="col-sm-12">
    {!! Form::label('adresse_livraison', __('models/importExcels.fields.adresse_livraison').':') !!}
    <p>{{ $importExcel->adresse_livraison }}</p>
</div>

