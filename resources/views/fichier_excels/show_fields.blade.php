<!-- Id Field -->
<div class="col-sm-12">
    {!! Form::label('id', __('models/fichierExcels.fields.id').':') !!}
    <p>{{ $fichierExcel->id }}</p>
</div>

<!-- Name Field -->
<div class="col-sm-12">
    {!! Form::label('name', __('models/fichierExcels.fields.name').':') !!}
    <p>{{ $fichierExcel->name }}</p>
</div>

<!-- Created At Field -->
<div class="col-sm-12">
    {!! Form::label('created_at', __('models/fichierExcels.fields.created_at').':') !!}
    <p>{{ $fichierExcel->created_at }}</p>
</div>

<!-- Updated At Field -->
<div class="col-sm-12">
    {!! Form::label('updated_at', __('models/fichierExcels.fields.updated_at').':') !!}
    <p>{{ $fichierExcel->updated_at }}</p>
</div>

