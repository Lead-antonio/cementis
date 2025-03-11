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
                            {!! Form::select('chauffeur_update_type_id', 
                                ['' => 'Veuillez choisir le type de mise à jour'] + $chauffeurUpdateTypes->toArray(),  
                                null, [
                                    'class' => 'form-control',
                                    'required' => true,
                                    'id' => 'chauffeur_update_type_id'
                                ]) !!}
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
                                {!! Form::select('transporteur_id', $transporteur, $chauffeur->transporteur_id, [
                                    'class' => 'form-control',
                                    'required' => true,
                                    'id' => 'transporteur_id'
                                ]) !!}
                                
                                {{-- Champ caché pour garantir l'envoi de la valeur même si le select est désactivé --}}
                                {!! Form::hidden('hidden_transporteur_id', $chauffeur->transporteur_id, ['id' => 'hidden_transporteur_id']) !!}
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


    <script>
        // Fonction pour rendre tous les champs en lecture seule
        function setAllFieldsReadOnly() {
            document.getElementById('nom').readOnly = true;
            document.getElementById('rfid').readOnly = true;
            document.getElementById('transporteur_id').disabled = true; // Select reste désactivé
            document.getElementById('rfid_physique').readOnly = true;
            document.getElementById('contact').readOnly = true;
            document.getElementById('numero_badge').readOnly = true;
        }

        // Fonction pour enlever le mode lecture seule pour un champ spécifique
        function setFieldEditable(fieldId) {
            if (fieldId === 'transporteur_id') {
                document.getElementById(fieldId).disabled = false;
            } else {
                document.getElementById(fieldId).readOnly = false;
            }
        }

        // Gérer le changement du type de mise à jour
        document.getElementById('chauffeur_update_type_id').addEventListener('change', function () {
            const type = this.value;
            setAllFieldsReadOnly(); // Mettre tous les champs en lecture seule par défaut

            if (type == 1) { // Changement propriétaire
                setFieldEditable('nom');
                setFieldEditable('transporteur_id');
                setFieldEditable('contact');
                setFieldEditable('numero_badge');
            } else if (type == 2) { // Changement RFID
                setFieldEditable('rfid_physique');
            } else if (type == 3) { // Changement transporteur
                setFieldEditable('transporteur_id');
                setFieldEditable('numero_badge');
                setFieldEditable('rfid_physique');
            }
        });

        // Synchroniser la valeur du champ caché avec le select, même s'il est désactivé
        document.getElementById('transporteur_id').addEventListener('change', function () {
            document.getElementById('hidden_transporteur_id').value = this.value;
        });

        // Appel initial pour désactiver tous les champs au chargement
        setAllFieldsReadOnly();

    </script>
    


@endsection