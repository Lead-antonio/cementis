@extends('layouts.app')

@section('content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-12">
                      <h1>@lang('models/chauffeurs.singular')</h1>
                </div>
            </div>
        </div>
    </section>

    <div class="content px-3">

        @include('adminlte-templates::common.errors')

        <div class="card">
            <div class="card-body">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" >Modifier les informations du chauffeur et ajouter un commentaire</h5>
                        
                    </div>
                    {!! Form::open(['route' => 'chauffeurUpdateStories.store', 'method' => 'post']) !!}
                    <div class="modal-body">
                        {{-- ID du Chauffeur caché --}}
                        {!! Form::hidden('chauffeur_id', $chauffeur->id) !!}

                        
                        <div class="form-group col-sm-6">
                            {!! Form::label('chauffeur_update_type_id', 'Type de mise à jour:') !!}
                            {!! Form::select('chauffeur_update_type_id', $chauffeurUpdateTypes, null, ['class' => 'form-control', 'required' => true]) !!}
                        </div>
                        <div class="form-row">
                            
                            <div class="form-group col-sm-6">
                                {!! Form::label('nom', 'Nom:') !!}
                                {!! Form::text('nom', $chauffeur->nom, ['class' => 'form-control']) !!}
                            </div>

                            
                            <div class="form-group col-sm-6">
                                {!! Form::label('rfid', 'RFID:') !!}
                                {!! Form::text('rfid', $chauffeur->rfid, ['class' => 'form-control']) !!}
                            </div>

                            <div class="form-group col-sm-6">
                                {!! Form::label('transporteur_id', 'Transporteur:') !!}
                                {!! Form::select('transporteur_id', $transporteur, $chauffeur->transporteur_id, ['class' => 'form-control', 'required' => true]) !!}
                            </div>

                            

                            <div class="form-group col-sm-6">
                                {!! Form::label('rfid_physique', 'RFID Physique:') !!}
                                {!! Form::text('rfid_physique', $chauffeur->rfid_physique, ['class' => 'form-control']) !!}
                            </div>


                            <div class="form-group col-sm-6">
                                {!! Form::label('contact', 'Contact:') !!}
                                {!! Form::text('contact', $chauffeur->contact, ['class' => 'form-control']) !!}
                            </div>

                            <div class="form-group col-sm-6">
                                {!! Form::label('numero_badge', 'Numéro de Badge:') !!}
                                {!! Form::text('numero_badge', $chauffeur->numero_badge, ['class' => 'form-control']) !!}
                            </div>
                        </div>

                        {{-- <div class="form-group">
                            {!! Form::label('commentaire', 'Commentaire:') !!}
                            {!! Form::textarea('commentaire', null, ['class' => 'form-control', 'rows' => 3, 'required' => true]) !!}
                        </div> --}}
                    </div>
                    <div class="modal-footer">
                        {!! Form::submit('Enregistrer', ['class' => 'btn btn-primary']) !!}
                    </div>
                    {!! Form::close() !!}
                </div>
            </div>
        </div>
    </div>
@endsection