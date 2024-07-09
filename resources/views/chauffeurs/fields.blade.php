<div class="form-group col-sm-6">
    {!! Form::label('nom', __('models/chauffeurs.fields.nom').':', ['class' => 'required']) !!}
    {!! Form::text('nom', null, ['class' => 'form-control','placeholder'=>'Nom']) !!}
</div>


<!-- Id Field -->
<div class="form-group col-sm-6">
    {!! Form::label('transporteur_id', __('models/vehicules.fields.id_transporteur').':', ['class' => 'required']) !!}
    @if(isset($transporteur))
        @if($action === "edit")
            {!! Form::text('transporteur_id', $transporteur->nom, ['class' => 'form-control', 'disabled' => 'disabled']) !!}
        @endif
        @if($action === "create")
            <select name="transporteur_id" id="" class="form-control" required>
                <option value="">Choisissez un transporteur</option>
                @foreach($transporteur as $transporteurs):
                    <option value="{{$transporteurs->id}}">{{$transporteurs->nom}}</option>
                @endforeach
            </select>
        @endif
    @else
        {!! Form::text('transporteur_id', null, ['class' => 'form-control', 'required'=> 'required']) !!}
    @endif
</div>

<!-- Rfid Field -->
<div class="form-group col-sm-6">
    {!! Form::label('rfid', __('models/chauffeurs.fields.rfid').':', ['class' => 'required']) !!}
    {!! Form::text('rfid', null, ['class' => 'form-control','placeholder'=>'Rfid']) !!}
</div>

<!-- Nom Field -->


<!-- Contact Field -->
{{-- <div class="form-group col-sm-6">
    {!! Form::label('contact', __('models/chauffeurs.fields.contact').':') !!}
    {!! Form::text('contact', null, ['class' => 'form-control']) !!}
</div> --}}