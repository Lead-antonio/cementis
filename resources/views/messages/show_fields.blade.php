<!-- Id Field -->
<div class="col-sm-12">
    {!! Form::label('id', __('models/messages.fields.id').':') !!}
    <p>{{ $message->id }}</p>
</div>

<!-- Contenu Field -->
<div class="col-sm-12">
    {!! Form::label('contenu', __('models/messages.fields.contenu').':') !!}
    <p>{{ $message->contenu }}</p>
</div>

<!-- Destinataire Field -->
<div class="col-sm-12">
    {!! Form::label('destinataire', __('models/messages.fields.destinataire').':') !!}
    <p>{{ $message->destinataire }}</p>
</div>

<!-- Api Field -->
<div class="col-sm-12">
    {!! Form::label('api', __('models/messages.fields.api').':') !!}
    <p>{{ $message->api }}</p>
</div>

