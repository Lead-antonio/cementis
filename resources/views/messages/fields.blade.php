
<!-- Destinataire Field -->
<div class="form-group col-sm-6">
    {!! Form::label('destinataire', __('models/messages.fields.destinataire').':') !!}
    {!! Form::text('destinataire', null, ['class' => 'form-control']) !!}
</div>

<!-- Api Field -->
<div class="form-group col-sm-6">
    {!! Form::label('api', __('models/messages.fields.api').':') !!}
    <select class="form-control" name="api">
        <option value="VONAGE">Vonage</option>
        <option value="TWILIO">Twilio</option>
    </select>
</div>

<!-- Contenu Field -->
<div class="form-group col-sm-12">
    {!! Form::label('contenu', __('models/messages.fields.contenu').':') !!}
    {!! Form::textarea('contenu', null, ['class' => 'form-control']) !!}
</div>