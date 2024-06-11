<!-- Id Field -->
<div class="col-sm-12">
    {!! Form::label('id', __('models/scorings.fields.id').':') !!}
    <p>{{ $scoring->id }}</p>
</div>

<!-- Id Planning Field -->
<div class="col-sm-12">
    {!! Form::label('id_planning', __('models/scorings.fields.id_planning').':') !!}
    <p>{{ $scoring->id_planning }}</p>
</div>

<!-- Driver Id Field -->
<div class="col-sm-12">
    {!! Form::label('driver_id', __('models/scorings.fields.driver_id').':') !!}
    <p>{{ $scoring->driver_id }}</p>
</div>

<!-- Transporteur Id Field -->
<div class="col-sm-12">
    {!! Form::label('transporteur_id', __('models/scorings.fields.transporteur_id').':') !!}
    <p>{{ $scoring->transporteur_id }}</p>
</div>

<!-- Camion Field -->
<div class="col-sm-12">
    {!! Form::label('camion', __('models/scorings.fields.camion').':') !!}
    <p>{{ $scoring->camion }}</p>
</div>

<!-- Comment Field -->
<div class="col-sm-12">
    {!! Form::label('comment', __('models/scorings.fields.comment').':') !!}
    <p>{{ $scoring->comment }}</p>
</div>

<!-- Distance Field -->
<div class="col-sm-12">
    {!! Form::label('distance', __('models/scorings.fields.distance').':') !!}
    <p>{{ $scoring->distance }}</p>
</div>

<!-- Point Field -->
<div class="col-sm-12">
    {!! Form::label('point', __('models/scorings.fields.point').':') !!}
    <p>{{ $scoring->point }}</p>
</div>

<!-- Created At Field -->
<div class="col-sm-12">
    {!! Form::label('created_at', __('models/scorings.fields.created_at').':') !!}
    <p>{{ $scoring->created_at }}</p>
</div>

<!-- Updated At Field -->
<div class="col-sm-12">
    {!! Form::label('updated_at', __('models/scorings.fields.updated_at').':') !!}
    <p>{{ $scoring->updated_at }}</p>
</div>

