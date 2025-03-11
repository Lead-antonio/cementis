<!-- Chauffeur Id Field -->
<div class="form-group col-sm-6">
    {!! Form::label('chauffeur_id', __('models/chauffeurUpdateStories.fields.chauffeur_id').':') !!}
    {!! Form::text('chauffeur_id', null, ['class' => 'form-control']) !!}
</div>

<!-- Chauffeur Update Type Id Field -->
<div class="form-group col-sm-6">
    {!! Form::label('chauffeur_update_type_id', __('models/chauffeurUpdateStories.fields.chauffeur_update_type_id').':') !!}
    {!! Form::text('chauffeur_update_type_id', null, ['class' => 'form-control']) !!}
</div>

<!-- Commentaire Field -->
<div class="form-group col-sm-6">
    {!! Form::label('commentaire', __('models/chauffeurUpdateStories.fields.commentaire').':') !!}
    {!! Form::text('commentaire', null, ['class' => 'form-control']) !!}
</div>