
<!-- Matricule Field -->
<div class="form-group col-sm-6">
    {!! Form::label('matricule', __('models/rotations.fields.matricule').':') !!}
    {!! Form::text('matricule', null, ['class' => 'form-control']) !!}
</div>

<!-- Mouvement Field -->
<div class="form-group col-sm-6">
    {!! Form::label('mouvement', __('models/rotations.fields.mouvement').':') !!}
    {!! Form::text('mouvement', null, ['class' => 'form-control']) !!}
</div>

<!-- Date Heur Field -->
<div class="form-group col-sm-6">
    <label for="date_heur">{{ __('models/rotations.fields.date_heur') }}:</label>
    <input type="datetime-local" name="date_heur" class="form-control" id="date_heur">
</div>

@push('page_scripts')
    <script type="text/javascript">
        $('#date_heur').datetimepicker({
            format: 'YYYY-MM-DD HH:mm:ss',
            useCurrent: true,
            sideBySide: true
        })
    </script>
@endpush

<!-- Coordonne Gps Field -->
<div class="form-group col-sm-6">
    {!! Form::label('coordonne_gps', __('models/rotations.fields.coordonne_gps').':') !!}
    {!! Form::text('coordonne_gps', null, ['class' => 'form-control']) !!}
</div>