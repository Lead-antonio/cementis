<!-- Id Field -->
<div class="col-sm-12">
    {!! Form::label('id', __('models/periodSettings.fields.id').':') !!}
    <p>{{ $periodSetting->id }}</p>
</div>

<!-- Name Field -->
<div class="col-sm-12">
    {!! Form::label('name', __('models/periodSettings.fields.name').':') !!}
    <p>{{ $periodSetting->name }}</p>
</div>

<!-- Days Field -->
<div class="col-sm-12">
    {!! Form::label('days', __('models/periodSettings.fields.days').':') !!}
    <p>{{ $periodSetting->days }}</p>
</div>

<!-- Created At Field -->
<div class="col-sm-12">
    {!! Form::label('created_at', __('models/periodSettings.fields.created_at').':') !!}
    <p>{{ $periodSetting->created_at }}</p>
</div>

<!-- Updated At Field -->
<div class="col-sm-12">
    {!! Form::label('updated_at', __('models/periodSettings.fields.updated_at').':') !!}
    <p>{{ $periodSetting->updated_at }}</p>
</div>

