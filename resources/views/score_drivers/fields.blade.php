<!-- Badge Field -->
<div class="form-group col-sm-6">
    {!! Form::label('badge', __('models/scoreDrivers.fields.badge').':') !!}
    {!! Form::text('badge', null, ['class' => 'form-control']) !!}
</div>

<!-- Score Field -->
<div class="form-group col-sm-6">
    {!! Form::label('score', __('models/scoreDrivers.fields.score').':') !!}
    {!! Form::text('score', null, ['class' => 'form-control']) !!}
</div>

<!-- Transporteur Field -->
<div class="form-group col-sm-6">
    {!! Form::label('transporteur', __('models/scoreDrivers.fields.transporteur').':') !!}
    {!! Form::text('transporteur', null, ['class' => 'form-control']) !!}
</div>

<!-- Observation Field -->
<div class="form-group col-sm-12 col-lg-12">
    {!! Form::label('observation', __('models/scoreDrivers.fields.observation').':') !!}
    {!! Form::textarea('observation', null, ['class' => 'form-control']) !!}
</div>