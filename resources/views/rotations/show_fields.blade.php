<!-- Id Field -->
<div class="col-sm-12">
    {!! Form::label('id', __('models/rotations.fields.id').':') !!}
    <p>{{ $rotation->id }}</p>
</div>

<!-- Matricule Field -->
<div class="col-sm-12">
    {!! Form::label('matricule', __('models/rotations.fields.matricule').':') !!}
    <p>{{ $rotation->matricule }}</p>
</div>

<!-- Mouvement Field -->
<div class="col-sm-12">
    {!! Form::label('mouvement', __('models/rotations.fields.mouvement').':') !!}
    <p>{{ $rotation->mouvement }}</p>
</div>

<!-- Date Heur Field -->
<div class="col-sm-12">
    {!! Form::label('date_heur', __('models/rotations.fields.date_heur').':') !!}
    <p>{{ $rotation->date_heur }}</p>
</div>

<!-- Coordonne Gps Field -->
<div class="col-sm-12">
    {!! Form::label('coordonne_gps', __('models/rotations.fields.coordonne_gps').':') !!}
    <p>{{ $rotation->coordonne_gps }}</p>
</div>

