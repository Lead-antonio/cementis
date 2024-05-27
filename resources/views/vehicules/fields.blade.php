
<!-- Nom Field -->
<div class="form-group col-sm-6">
    {!! Form::label('imei', __('models/vehicules.fields.imei').':', ['class' => 'required']) !!}
    {!! Form::text('imei', null, ['class' => 'form-control']) !!}
</div>

<!-- Id Client Field -->
<div class="form-group col-sm-6">
    {!! Form::label('id_transporteur', __('models/vehicules.fields.id_transporteur').':', ['class' => 'required']) !!}
    @if(isset($transporteur))
        @if($action === "edit")
            {!! Form::text('id_transporteur', $transporteur->nom, ['class' => 'form-control', 'disabled' => 'disabled']) !!}
        @endif
        @if($action === "create")
            <select name="id_transporteur" id="" class="form-control" required>
                <option value="">Choisissez un transporteur</option>
                @foreach($transporteur as $transporteurs):
                    <option value="{{$transporteurs->id}}">{{$transporteurs->nom}}</option>
                @endforeach
            </select>
        @endif
    @else
        {!! Form::text('id_transporteur', null, ['class' => 'form-control', 'required'=> 'required']) !!}
    @endif
</div>
<!-- Nom Field -->
<div class="form-group col-sm-6">
    {!! Form::label('nom', __('models/vehicules.fields.nom').':', ['class' => 'required']) !!}
    {!! Form::text('nom', null, ['class' => 'form-control']) !!}
</div>