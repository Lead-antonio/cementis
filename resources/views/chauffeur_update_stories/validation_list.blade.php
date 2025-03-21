@extends('layouts.app')

@section('content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                {{-- <div class="col-sm-6">
                   @lang('models/chauffeurUpdateStories.plural')
                </div> --}}
            </div>
        </div>
    </section>

    <div class="content px-3">

        @include('flash::message')

        <div class="clearfix"></div>

        <div class="card">
            <h4 style="padding-left: 70px;padding-top: 27px;">Historique de mise à jour chauffeur</h4>
            <div class="card-body p-0">
                <div class="card-body" style="padding-left: 70px;padding-right:120px">
                    @foreach ($chauffeur_update as $key => $item)
                        <div class="card rounded-card mb-3">
                            <a class="text-decoration-none text-dark">
                                <div class="card-body card-list">
                                    {{-- <div class="number-circle">{{ $key + 1 }}</div> --}}
                                    @php
                                        $icon = "fa-edit text-primary";
                                        if( $item->chauffeur_update_type_id == 4){
                                            $icon = "fa-trash text-danger";
                                        }
                                        if( $item->chauffeur_update_type_id == 5){
                                            $icon = "fa-plus text-primary";
                                        }
                                    @endphp

                                    @if ($item->chauffeur_update_type_id == 5 )
                                        <i class="fa {{ $icon }} icon-circle" style="font-size:20px;"></i>
                                        <span style="font-weight: 500">  {{ $item->modifier->name ?? "" }} </span>  demande la validation de la <span style="font-weight: 500"> {{ $item->chauffeur_update_type->name }} : {{ $item->nom ?? "" }}
                                        <span class="float-right">
                                            @if ($item->validation == 1)
                                                <button type="button" class='btn btn-primary saveButton'  data-id="{{ $item->id }}"   data-ancien='@json($item->chauffeur)' 
                                                        data-nouveau='@json($item)' 
                                                        style="border-radius: 72px;">
                                                    Valider
                                                </button>
                                                <button type="button" class='btn btn-danger refusButton'  data-id="{{ $item->id }}"   data-nouveau='@json($item)' style="border-radius: 72px;">
                                                    Refuser
                                                </button>
                                            @elseif ($item->validation == 2)
                                                <span class="badge badge-pill badge-success badge-validation">Validé</span>
                                            @elseif ($item->validation == 3)
                                                <span class="badge badge-pill badge-danger badge-validation">Refusé</span>
                                            @endif
                                        </span>
                                    @else
                                        <i class="fa {{ $icon }} icon-circle" style="font-size: 20px;"></i>
                                        <span style="font-weight: 500">  {{ $item->modifier->name ?? "" }} </span>   demande la validation <span style="font-weight: 500"> {{ $item->chauffeur_update_type->name }} : {{ $item->nom }}</span> possedant RFID : <span style="font-weight: 500"> {{ $item->rfid }}</span>
                                        <span class="float-right">
                                            @if ($item->validation == 1)
                                                <button type="button" class='btn btn-primary saveButton'  data-id="{{ $item->id }}"   data-ancien='@json($item->chauffeur)' 
                                                        data-nouveau='@json($item)' 
                                                        style="border-radius: 72px;">
                                                    Valider
                                                </button>

                                                <button type="button" class='btn btn-danger refusButton'  data-id="{{ $item->id }}"  data-nouveau='@json($item)'  style="border-radius: 72px;" >
                                                    Refuser
                                                </button>
                                            @elseif ($item->validation == 2)
                                                <span class="badge badge-pill badge-success badge-validation">Validé</span>
                                            @elseif ($item->validation == 3)
                                                <span class="badge badge-pill badge-danger badge-validation">Refusé</span>
                                            @endif
                                        </span>
                                    @endif

                                    <div style="margin-top: 5px;padding-left: 54px;"> <!-- Placer la date un peu plus bas -->
                                        <small> Date :  {{ $item->created_at->format('Y-m-d') }} à {{ $item->created_at->format('H:m') }} </small>
                                    </div>
                                </div>
                            </a>
                        </div>
                    @endforeach
                </div>
    
                <div class="card-footer clearfix float-right">
                    {{ $chauffeur_update->links() }} <!-- Pagination si nécessaire -->
                </div>

                @if (count($chauffeur_update)== 0 )
                    <div style="text-align: center">Aucun résultat trouvé.</div>
                @endif

            </div>
        </div>
    </div>

    <script>

        function highlightIfChanged(oldValue, newValue) {
            return oldValue !== newValue 
                ? `<span style="color: #57c757; font-weight: 900;">${newValue}</span>` 
                : newValue;
        }

        // Action de pour afficher le refus 
        document.querySelectorAll('.refusButton').forEach(button => {
            button.addEventListener('click', function() {
                const chauffeurId = this.getAttribute('data-id');
                const nouveau = JSON.parse(this.getAttribute('data-nouveau'));
                console.log("refus ",chauffeurId );

                let commentaire = `
                    <div class='form-group ' style="margin-top: 10px;">
                        <textarea id="commentaireid" class="swal2-textarea" placeholder="Veuillez entrer votre description" required></textarea>
                    </div>
                `;
                Swal.fire({
                    title: 'Validation refus de ' + nouveau.chauffeur_update_type.name,
                    icon: 'warning',
                    html: commentaire,
                    showCancelButton: true,
                    confirmButtonText: 'Oui, refuser',
                    cancelButtonText: 'Annuler',
                    width: '600px',
                    customClass: {
                        validationMessage: 'custom-validation' // Ajoute une classe CSS personnalisée
                    },
                    preConfirm: () => {
                        const commentaire = document.getElementById('commentaireid').value.trim();
                        if (commentaire === '') {
                            Swal.showValidationMessage('Veuillez entrer un commentaire.');
                            return false; // Bloque la validation si le RFID est vide
                        }
                        return { commentairevalue: commentaire }; // Retourne le RFID saisi
                    }

                }).then((result) => {
                    if (result.isConfirmed) {
                        const commentaires = result.value?.commentairevalue || null; // Récupérer le RFID si présent
                      
                        // Faire la requête AJAX pour valider
                        fetch("{{ route('chauffeurUpdateStorie.validation') }}", {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            },
                            body: JSON.stringify({
                                id: chauffeurId,
                                validation: false,
                                commentaire: commentaires,
                            })
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                Swal.fire('Validé!', 'Le refus a été enregistré.', 'success')
                                    .then(() => location.reload());
                            } else {
                                Swal.fire('Erreur!', 'Une erreur est survenue lors de la validation.', 'error');
                            }
                        })
                        .catch(error => {
                            Swal.fire('Erreur!', 'Une erreur est survenue lors de la validation.', 'error');
                            console.error(error);
                        });
                    }
                });

            });
        });

        // Action de pour afficher la validation 
        document.querySelectorAll('.saveButton').forEach(button => {
            button.addEventListener('click', function() {
                const chauffeurId = this.getAttribute('data-id');
                const ancien = JSON.parse(this.getAttribute('data-ancien'));
                const nouveau = JSON.parse(this.getAttribute('data-nouveau'));
                const updateType = nouveau.chauffeur_update_type_id; // Type de mise à jour
    
                // Créer un tableau pour afficher les anciennes et nouvelles infos
                let infoTable = ``;
                if (updateType == 2 || updateType == 3 || updateType == 1) { 
                    infoTable = `
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Champs</th>
                                    <th>Ancienne Information</th>
                                    <th>Nouvelle Information</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>Nom</td>
                                    <td>${ancien.nom}</td>
                                    <td>${highlightIfChanged(ancien.nom, nouveau.nom)}</td>
                                </tr>
                                <tr>
                                    <td>Contact</td>
                                    <td>${ancien.contact}</td>
                                    <td>${highlightIfChanged(ancien.contact, nouveau.contact)}</td>
                                </tr>
                                <tr>
                                    <td>RFID</td>
                                    <td>${ancien.rfid}</td>
                                    <td>${updateType == 2 || updateType == 3 ? '<span style="color: #FFA500; font-weight: 900;">En attente</span>' : highlightIfChanged(ancien.rfid, nouveau.rfid)}</td>

                                </tr>
                                <tr>
                                    <td>Numéro Badge</td>
                                    <td>${ancien.numero_badge}</td>
                                    <td>${highlightIfChanged(ancien.numero_badge, nouveau.numero_badge)}</td>
                                </tr>
                                <tr>
                                    <td>RFID Physique</td>
                                    <td>${ancien.rfid_physique}</td>
                                    <td>${highlightIfChanged(ancien.rfid_physique, nouveau.rfid_physique)}</td>
                                </tr>
                                <tr>
                                    <td>Transporteur</td>
                                    <td>${ancien.related_transporteur.nom}</td>
                                    <td>${highlightIfChanged(ancien.related_transporteur.nom, nouveau.transporteur.nom)}</td>
                                </tr>
                            </tbody>
                        </table>
                    `;
                }

                if (updateType == 5) { 
                    infoTable = `
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Champs</th>
                                    <th>Information</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>Nom</td>
                                    <td>${nouveau.nom}</td>
                                </tr>
                                <tr>
                                    <td>Contact</td>
                                    <td>${ nouveau.contact}</td>
                                </tr>
                                <tr>
                                    <td>RFID</td>
                                    <td><span style="color: #FFA500; font-weight: 900;">En attente</span></td>

                                </tr>
                                <tr>
                                    <td>Numéro Badge</td>
                                    <td>${nouveau.numero_badge}</td>
                                </tr>
                                <tr>
                                    <td>RFID Physique</td>
                                    <td>${nouveau.rfid_physique ?? "<span style='color: #FFA500; font-weight: 900;'>En attente</span>"}</td>
                                </tr>
                                <tr>
                                    <td>Transporteur</td>
                                    <td>${nouveau.transporteur.nom}</td>
                                </tr>
                            </tbody>
                        </table>
                    `;
                }
    
                // Ajouter un champ de saisie pour le RFID selon le type de mise à jour
                let rfidField = '';
                if (updateType == 2 || updateType == 3) { 
                    rfidField = `
                        <div style="margin-top: 10px;">
                            <label for="rfidInput">Veuillez saisir le nouveau RFID :</label>
                            <input type="text" id="rfidInput" class="swal2-input" placeholder="Nouveau RFID" required>
                        </div>
                    `;
                }

                if (updateType == 5) { 
                    if(nouveau.rfid_physique == null){
                        rfidField = `
                            <div class='form-row' style="margin-top: 10px;">
                                <div class='form-group col-md-5' >
                                    <label for="rfidInput">RFID :</label>
                                    <input type="text" id="rfidInput" class="swal2-input" placeholder="RFID" required>
                                </div>
                                <div class='form-group col-md-7'>
                                    <label for="rfidphysiqueInput">Veuillez saisir le RFID PHYSIQUE:</label>
                                    <input type="number" id="rfidphysiqueInput" class="swal2-input" placeholder="RFID Physique" required>
                                </div>
                            </div>
                        `;
                    }else{

                        rfidField = `
                            <div class='form-group' style="margin-top: 10px;">
                                <label for="rfidInput">Veuillez saisir le RFID :</label>
                                <input type="text" id="rfidInput" class="swal2-input" placeholder="RFID" required>
                            </div>
                        `;
                    }
                }
    
                Swal.fire({
                    title: 'Confirmer la validation du ' + nouveau.chauffeur_update_type.name,
                    html: infoTable + rfidField,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Oui, valider',
                    cancelButtonText: 'Annuler',
                    width: '700px',
                    preConfirm: () => {
                        if (updateType == 2 || updateType == 3 ) {
                            const rfidValue = document.getElementById('rfidInput').value.trim();
                            if (rfidValue === '') {
                                Swal.showValidationMessage('Le champ RFID est requis.');
                                return false; // Bloque la validation si le RFID est vide
                            }
                            return { rfid: rfidValue }; // Retourne le RFID saisi
                        }

                        if (updateType == 5 && nouveau.rfid_physique == null) {
                            console.log('nouveau.rfid_physique',nouveau.rfid_physique)
                            const rfidValue = document.getElementById('rfidInput').value.trim();
                            const rfidPhysiqueValue = document.getElementById('rfidphysiqueInput').value.trim();
                            
                            if (rfidValue === '' || rfidPhysiqueValue === '') {
                                Swal.showValidationMessage('Veuillez remplir les formulaires.');
                                return false; // Bloque la validation si l'un des champs est vide
                            }
                            return { rfid: rfidValue, rfidphysique: rfidPhysiqueValue }; // Retourne les valeurs
                        } else if (updateType == 5) {
                            const rfidValue = document.getElementById('rfidInput').value.trim();
                            
                            if (rfidValue === '') {
                                Swal.showValidationMessage('Le champ RFID est requis.');
                                return false; // Bloque la validation si le RFID est vide
                            }
                            return { rfid: rfidValue }; // Retourne le RFID saisi
                        }

                        return true;
                    }
                }).then((result) => {
                    if (result.isConfirmed) {
                        const rfidValue = result.value?.rfid || null; // Récupérer le RFID si présent
                        const rfidPhysiqueValue = result.value?.rfidphysique || null; // Récupérer le RFID si présent

                        // Faire la requête AJAX pour valider
                        fetch("{{ route('chauffeurUpdateStorie.validation') }}", {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            },
                            body: JSON.stringify({
                                id: chauffeurId,
                                validation: true,
                                chauffeur_update_type: updateType,
                                rfid: rfidValue ,
                                rfidPhysique: rfidPhysiqueValue ,
                            })
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                Swal.fire('Validé!', 'La mise à jour a été validée avec succès.', 'success')
                                    .then(() => location.reload());
                            } else {
                                Swal.fire('Erreur!', 'Une erreur est survenue lors de la validation.', 'error');
                            }
                        })
                        .catch(error => {
                            Swal.fire('Erreur!', 'Une erreur est survenue lors de la validation.', 'error');
                            console.error(error);
                        });
                    }
                });
            });
        });
    </script>

    <style>
        .icon-circle {
            width: 40px;
            height: 40px;
            color: #fff;
            border-radius: 50%;
            display: inline-flex;
            justify-content: center;
            align-items: center;
            font-weight: bold;
            margin-right: 10px;
            background-color: #e0e0e0;
        }
        .badge-validation{
            padding-right: 1.6em;
            padding-left: 1.6em;
            border-radius: 14rem;
            padding-top: 14px;
            padding-bottom: 10px;
        }

        .custom-validation {
            max-width: 600px; /* Limite la largeur */
            margin-left: -19px;
            background: white
        }
    </style>
    

@endsection


