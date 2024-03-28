<!-- Id Field -->
<div class="form-group col-sm-6">
    {!! Form::label('id', __('models/penaliteChauffeurs.fields.id').':') !!}
    {!! Form::text('id', null, ['class' => 'form-control']) !!}
</div>

<!-- Nom Chauffeur Field -->
<div class="form-group col-sm-6">
    {!! Form::label('nom_chauffeur', __('models/penaliteChauffeurs.fields.nom_chauffeur').':') !!}
    {!! Form::text('nom_chauffeur', null, ['class' => 'form-control']) !!}
</div>

<!-- Date Field -->
<div class="form-group col-sm-6">
    {!! Form::label('date', __('models/penaliteChauffeurs.fields.date').':') !!}
    {!! Form::text('date', null, ['class' => 'form-control','id'=>'date']) !!}
</div>

@push('page_scripts')
    <script type="text/javascript">
        $('#date').datetimepicker({
            format: 'YYYY-MM-DD HH:mm:ss',
            useCurrent: true,
            sideBySide: true
        })
    </script>
@endpush

<!-- Point Penalite Field -->
<div class="form-group col-sm-6">
    {!! Form::label('point_penalite', __('models/penaliteChauffeurs.fields.point_penalite').':') !!}
    {!! Form::number('point_penalite', null, ['class' => 'form-control']) !!}
</div>