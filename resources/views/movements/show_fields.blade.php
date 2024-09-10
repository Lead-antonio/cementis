<!-- Id Field -->
<div class="col-sm-12">
    {!! Form::label('id', __('models/movements.fields.id').':') !!}
    <p>{{ $movement->id }}</p>
</div>

<!-- Calendar Id Field -->
<div class="col-sm-12">
    {!! Form::label('calendar_id', __('models/movements.fields.calendar_id').':') !!}
    <p>{{ $movement->calendar_id }}</p>
</div>

<!-- Start Date Field -->
<div class="col-sm-12">
    {!! Form::label('start_date', __('models/movements.fields.start_date').':') !!}
    <p>{{ $movement->start_date }}</p>
</div>

<!-- Start Hour Field -->
<div class="col-sm-12">
    {!! Form::label('start_hour', __('models/movements.fields.start_hour').':') !!}
    <p>{{ $movement->start_hour }}</p>
</div>

<!-- End Date Field -->
<div class="col-sm-12">
    {!! Form::label('end_date', __('models/movements.fields.end_date').':') !!}
    <p>{{ $movement->end_date }}</p>
</div>

<!-- End Hour Field -->
<div class="col-sm-12">
    {!! Form::label('end_hour', __('models/movements.fields.end_hour').':') !!}
    <p>{{ $movement->end_hour }}</p>
</div>

<!-- Duration Field -->
<div class="col-sm-12">
    {!! Form::label('duration', __('models/movements.fields.duration').':') !!}
    <p>{{ $movement->duration }}</p>
</div>

<!-- Type Field -->
<div class="col-sm-12">
    {!! Form::label('type', __('models/movements.fields.type').':') !!}
    <p>{{ $movement->type }}</p>
</div>

<!-- Created At Field -->
<div class="col-sm-12">
    {!! Form::label('created_at', __('models/movements.fields.created_at').':') !!}
    <p>{{ $movement->created_at }}</p>
</div>

<!-- Updated At Field -->
<div class="col-sm-12">
    {!! Form::label('updated_at', __('models/movements.fields.updated_at').':') !!}
    <p>{{ $movement->updated_at }}</p>
</div>

