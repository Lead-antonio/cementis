<!-- Id Field -->
<div class="col-sm-12">
    {!! Form::label('id', __('models/chauffeurUpdateStories.fields.id').':') !!}
    <p>{{ $chauffeurUpdateStory->id }}</p>
</div>

<!-- Chauffeur Id Field -->
<div class="col-sm-12">
    {!! Form::label('chauffeur_id', __('models/chauffeurUpdateStories.fields.chauffeur_id').':') !!}
    <p>{{ $chauffeurUpdateStory->chauffeur_id }}</p>
</div>

<!-- Chauffeur Update Type Id Field -->
<div class="col-sm-12">
    {!! Form::label('chauffeur_update_type_id', __('models/chauffeurUpdateStories.fields.chauffeur_update_type_id').':') !!}
    <p>{{ $chauffeurUpdateStory->chauffeur_update_type_id }}</p>
</div>

<!-- Commentaire Field -->
<div class="col-sm-12">
    {!! Form::label('commentaire', __('models/chauffeurUpdateStories.fields.commentaire').':') !!}
    <p>{{ $chauffeurUpdateStory->commentaire }}</p>
</div>

