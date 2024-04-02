<!-- Chauffeur Field -->
<div class="form-group col-sm-6">
    {!! Form::label('chauffeur', __('models/events.fields.chauffeur').':') !!}
    {!! Form::text('chauffeur', null, ['class' => 'form-control']) !!}
</div>

<div class="form-group col-sm-6">
    {!! Form::label('vehicule', __('models/events.fields.vehicule').':') !!}
    {!! Form::text('vehicule', null, ['class' => 'form-control']) !!}
</div>

<!-- Type Field -->
<div class="form-group col-sm-6">
    {!! Form::label('type', __('models/events.fields.type').':') !!}
    {!! Form::text('type', null, ['class' => 'form-control']) !!}
</div>

<!-- Description Field -->
<div class="form-group col-sm-6">
    {!! Form::label('description', __('models/events.fields.description').':') !!}
    {!! Form::text('description', null, ['class' => 'form-control']) !!}
</div>

<!-- Date Field -->
<div class="form-group col-sm-6">
    {!! Form::label('date', __('models/events.fields.date').':') !!}
    {!! Form::text('date', null, ['class' => 'form-control']) !!}
</div>