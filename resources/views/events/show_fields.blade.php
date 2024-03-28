<!-- Id Field -->
<div class="col-sm-12">
    {!! Form::label('id', __('models/events.fields.id').':') !!}
    <p>{{ $event->id }}</p>
</div>

<!-- Chauffeur Field -->
<div class="col-sm-12">
    {!! Form::label('chauffeur', __('models/events.fields.chauffeur').':') !!}
    <p>{{ $event->chauffeur }}</p>
</div>

<!-- Type Field -->
<div class="col-sm-12">
    {!! Form::label('type', __('models/events.fields.type').':') !!}
    <p>{{ $event->type }}</p>
</div>

<!-- Description Field -->
<div class="col-sm-12">
    {!! Form::label('description', __('models/events.fields.description').':') !!}
    <p>{{ $event->description }}</p>
</div>

<!-- Date Field -->
<div class="col-sm-12">
    {!! Form::label('date', __('models/events.fields.date').':') !!}
    <p>{{ $event->date }}</p>
</div>

<!-- Created At Field -->
<div class="col-sm-12">
    {!! Form::label('created_at', __('models/events.fields.created_at').':') !!}
    <p>{{ $event->created_at }}</p>
</div>

<!-- Updated At Field -->
<div class="col-sm-12">
    {!! Form::label('updated_at', __('models/events.fields.updated_at').':') !!}
    <p>{{ $event->updated_at }}</p>
</div>

