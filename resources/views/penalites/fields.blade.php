<!-- Id Field -->
<div class="form-group col-sm-6">
    {!! Form::label('id', __('models/penalites.fields.id').':') !!}
    {!! Form::number('id', null, ['class' => 'form-control']) !!}
</div>

<!-- Event Field -->
<div class="form-group col-sm-6">
    {!! Form::label('event', __('models/penalites.fields.event').':') !!}
    {!! Form::text('event', null, ['class' => 'form-control']) !!}
</div>

<!-- Point Penalite Field -->
<div class="form-group col-sm-6">
    {!! Form::label('point_penalite', __('models/penalites.fields.point_penalite').':') !!}
    {!! Form::number('point_penalite', null, ['class' => 'form-control']) !!}
</div>