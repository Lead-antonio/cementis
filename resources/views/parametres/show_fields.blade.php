<!-- Id Field -->
<div class="col-sm-12">
    {!! Form::label('id', __('models/parametres.fields.id').':') !!}
    <p>{{ $parametre->id }}</p>
</div>

<!-- Name Field -->
<div class="col-sm-12">
    {!! Form::label('name', __('models/parametres.fields.name').':') !!}
    <p>{{ $parametre->name }}</p>
</div>

<!-- Color Field -->
<div class="col-sm-12">
    {!! Form::label('color', __('models/parametres.fields.color').':') !!}
    <p>{{ $parametre->color }}</p>
</div>

<!-- Limite Field -->
<div class="col-sm-12">
    {!! Form::label('limite', __('models/parametres.fields.limite').':') !!}
    <p>{{ $parametre->limite }}</p>
</div>

<!-- Created At Field -->
<div class="col-sm-12">
    {!! Form::label('created_at', __('models/parametres.fields.created_at').':') !!}
    <p>{{ $parametre->created_at }}</p>
</div>

<!-- Updated At Field -->
<div class="col-sm-12">
    {!! Form::label('updated_at', __('models/parametres.fields.updated_at').':') !!}
    <p>{{ $parametre->updated_at }}</p>
</div>

