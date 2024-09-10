<!-- Calendar Id Field -->
<div class="form-group col-sm-6">
    {!! Form::label('calendar_id', __('models/movements.fields.calendar_id').':') !!}
    {!! Form::number('calendar_id', null, ['class' => 'form-control']) !!}
</div>

<!-- Start Date Field -->
<div class="form-group col-sm-6">
    {!! Form::label('start_date', __('models/movements.fields.start_date').':') !!}
    {!! Form::text('start_date', null, ['class' => 'form-control','id'=>'start_date']) !!}
</div>

@push('page_scripts')
    <script type="text/javascript">
        $('#start_date').datetimepicker({
            format: 'YYYY-MM-DD HH:mm:ss',
            useCurrent: true,
            sideBySide: true
        })
    </script>
@endpush

<!-- Start Hour Field -->
<div class="form-group col-sm-6">
    {!! Form::label('start_hour', __('models/movements.fields.start_hour').':') !!}
    {!! Form::text('start_hour', null, ['class' => 'form-control']) !!}
</div>

<!-- End Date Field -->
<div class="form-group col-sm-6">
    {!! Form::label('end_date', __('models/movements.fields.end_date').':') !!}
    {!! Form::text('end_date', null, ['class' => 'form-control','id'=>'end_date']) !!}
</div>

@push('page_scripts')
    <script type="text/javascript">
        $('#end_date').datetimepicker({
            format: 'YYYY-MM-DD HH:mm:ss',
            useCurrent: true,
            sideBySide: true
        })
    </script>
@endpush

<!-- End Hour Field -->
<div class="form-group col-sm-6">
    {!! Form::label('end_hour', __('models/movements.fields.end_hour').':') !!}
    {!! Form::text('end_hour', null, ['class' => 'form-control']) !!}
</div>

<!-- Duration Field -->
<div class="form-group col-sm-6">
    {!! Form::label('duration', __('models/movements.fields.duration').':') !!}
    {!! Form::text('duration', null, ['class' => 'form-control']) !!}
</div>

<!-- Type Field -->
<div class="form-group col-sm-6">
    {!! Form::label('type', __('models/movements.fields.type').':') !!}
    {!! Form::text('type', null, ['class' => 'form-control']) !!}
</div>