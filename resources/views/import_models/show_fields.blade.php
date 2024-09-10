<!-- Id Field -->
{{-- <div class="col-sm-12">
    {!! Form::label('id', __('models/importModels.fields.id').':') !!}
    <p>{{ $importModel->id }}</p>
</div> --}}

<!-- Nom Field -->
<div class="col-sm-12">
    {!! Form::label('nom', __('models/importModels.fields.nom').':') !!}
    <p>{{ $importModel->nom }}</p>
</div>

<!-- Model Field -->
<div class="col-sm-12">
    {!! Form::label('model', __('models/importModels.fields.model').':') !!}
    <p>{{ $importModel->model }}</p>
</div>

<!-- Association Field -->
<div class="col-sm-12">
    {!! Form::label('association', __('models/importModels.fields.association').':') !!}
    <p>
        {
        <br>
        @if(is_array($importModel->association))
            @foreach($importModel->association as $key => $value)
                "{{ $key }}":"{{ $value }}",<br>
            @endforeach
        @else
            {{ $importModel->association }}  <!-- Pour gérer le cas où le cast ne fonctionne pas -->
        @endif
        }
    </p>
</div>

<!-- Observation Field -->
<div class="col-sm-12">
    {!! Form::label('observation', __('models/importModels.fields.observation').':') !!}
    <p>{{ $importModel->observation }}</p>
</div>

{{-- <!-- Created At Field -->
<div class="col-sm-12">
    {!! Form::label('created_at', __('models/importModels.fields.created_at').':') !!}
    <p>{{ $importModel->created_at }}</p>
</div>

<!-- Updated At Field -->
<div class="col-sm-12">
    {!! Form::label('updated_at', __('models/importModels.fields.updated_at').':') !!}
    <p>{{ $importModel->updated_at }}</p>
</div> --}}

