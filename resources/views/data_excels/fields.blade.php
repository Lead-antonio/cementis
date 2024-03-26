<!-- Camion Field -->
<div class="form-group col-sm-6">
    {!! Form::label('camion', __('models/dataExcels.fields.camion').':') !!}
    {!! Form::text('camion', null, ['class' => 'form-control']) !!}
</div>

<!-- Date Debut Field -->
<div class="form-group col-sm-6">
    {!! Form::label('date_debut', __('models/dataExcels.fields.date_debut').':') !!}
    {!! Form::date('date_debut', null, ['class' => 'form-control','id'=>'date_debut']) !!}
</div>

@push('page_scripts')
    <script type="text/javascript">
        $('#date_debut').datetimepicker({
            format: 'YYYY-MM-DD HH:mm:ss',
            useCurrent: true,
            sideBySide: true
        })
    </script>
@endpush

<div class="form-group col-sm-6">
    {!! Form::label('date_fin', __('models/dataExcels.fields.date_fin').':') !!}
    {!! Form::date('date_fin', null, ['class' => 'form-control', 'id' => 'date_fin']) !!}
</div>

@push('page_scripts')
    <script type="text/javascript">
        $('#date_fin').datetimepicker({
            format: 'YYYY-MM-DD HH:mm:ss',
            useCurrent: true,
            sideBySide: true
        })
    </script>
@endpush

<!-- Delais Route Field -->
<div class="form-group col-sm-6">
    {!! Form::label('delais_route', __('models/dataExcels.fields.delais_route').':') !!}
    {!! Form::text('delais_route', null, ['class' => 'form-control']) !!}
</div>

<!-- Sigdep Reel Field -->
<div class="form-group col-sm-6">
    {!! Form::label('sigdep_reel', __('models/dataExcels.fields.sigdep_reel').':') !!}
    {!! Form::text('sigdep_reel', null, ['class' => 'form-control']) !!}
</div>

<!-- Marche Field -->
<div class="form-group col-sm-6">
    {!! Form::label('marche', __('models/dataExcels.fields.marche').':') !!}
    {!! Form::text('marche', null, ['class' => 'form-control']) !!}
</div>

<!-- Adresse Livraison Field -->
<div class="form-group col-sm-6">
    {!! Form::label('adresse_livraison', __('models/dataExcels.fields.adresse_livraison').':') !!}
    {!! Form::text('adresse_livraison', null, ['class' => 'form-control']) !!}
</div>