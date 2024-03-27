<!-- Id Field -->
<div class="col-sm-12">
    {!! Form::label('id', __('models/penalites.fields.id').':') !!}
    <p>{{ $penalite->id }}</p>
</div>

<!-- Event Field -->
<div class="col-sm-12">
    {!! Form::label('event', __('models/penalites.fields.event').':') !!}
    <p>{{ $penalite->event }}</p>
</div>

<!-- Point Penalite Field -->
<div class="col-sm-12">
    {!! Form::label('point_penalite', __('models/penalites.fields.point_penalite').':') !!}
    <p>{{ $penalite->point_penalite }}</p>
</div>

<!-- Created At Field -->
<div class="col-sm-12">
    {!! Form::label('created_at', __('models/penalites.fields.created_at').':') !!}
    <p>{{ $penalite->created_at }}</p>
</div>

<!-- Updated At Field -->
<div class="col-sm-12">
    {!! Form::label('updated_at', __('models/penalites.fields.updated_at').':') !!}
    <p>{{ $penalite->updated_at }}</p>
</div>

