<!-- Id Field -->
<div class="col-sm-12">
    {!! Form::label('id', __('models/penaliteChauffeurs.fields.id').':') !!}
    <p>{{ $penaliteChauffeur->id }}</p>
</div>

<!-- Nom Chauffeur Field -->
<div class="col-sm-12">
    {!! Form::label('nom_chauffeur', __('models/penaliteChauffeurs.fields.nom_chauffeur').':') !!}
    <p>{{ $penaliteChauffeur->nom_chauffeur }}</p>
</div>

<!-- Date Field -->
<div class="col-sm-12">
    {!! Form::label('date', __('models/penaliteChauffeurs.fields.date').':') !!}
    <p>{{ $penaliteChauffeur->date }}</p>
</div>

<!-- Point Penalite Field -->
<div class="col-sm-12">
    {!! Form::label('point_penalite', __('models/penaliteChauffeurs.fields.point_penalite').':') !!}
    <p>{{ $penaliteChauffeur->point_penalite }}</p>
</div>

<!-- Created At Field -->
<div class="col-sm-12">
    {!! Form::label('created_at', __('models/penaliteChauffeurs.fields.created_at').':') !!}
    <p>{{ $penaliteChauffeur->created_at }}</p>
</div>

<!-- Updated At Field -->
<div class="col-sm-12">
    {!! Form::label('updated_at', __('models/penaliteChauffeurs.fields.updated_at').':') !!}
    <p>{{ $penaliteChauffeur->updated_at }}</p>
</div>

