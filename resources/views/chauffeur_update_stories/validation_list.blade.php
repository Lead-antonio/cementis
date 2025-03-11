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
            <h4 style="padding-left: 72px;padding-top: 27px;">Historique de mise à jour chauffeur</h4>
            <div class="card-body p-0">
                <div class="card-body" style="padding-left: 50px;padding-right:170px">
                    @foreach ($chauffeur_update as $key => $item)
                        <div class="card rounded-card mb-3">
                            <a class="text-decoration-none text-dark">
                                <div class="card-body card-list">
                                    <div class="number-circle">{{ $key + 1 }}</div>
                                    <strong>Demande de validation {{ $item->chauffeur_update_type->name }} : {{ $item->chauffeur->nom }}</strong>
                                    <span class="float-right">
                                        @if ($item->validation == false)
                                            <button type="button" class='btn btn-success saveButton'  data-id="{{ $item->id }}"   data-ancien='@json($item->chauffeur)' 
                                                    data-nouveau='@json($item)' 
                                                    style="border-radius: 72px;">
                                                Validé
                                            </button>
                                        @else
                                            <i class="fa fa-check"></i>
                                        @endif
                                    </span>

                                    <div style="margin-top: 5px;padding-left:42px"> <!-- Placer la date un peu plus bas -->
                                        <small>Date : {{ $item->created_at->format('Y-m-d') }}</small>
                                    </div>
                                </div>
                            </a>
                        </div>
                    @endforeach
                </div>
    
                <div class="card-footer clearfix float-right">
                    {{ $chauffeur_update->links() }} <!-- Pagination si nécessaire -->
                </div>
            </div>
        </div>
    </div>

    <script>

        function highlightIfChanged(oldValue, newValue) {
            return oldValue !== newValue 
                ? `<span style="color: #57c757; font-weight: 900;">${newValue}</span>` 
                : newValue;
        }

        document.querySelectorAll('.saveButton').forEach(button => {
            button.addEventListener('click', function() {
                const chauffeurId = this.getAttribute('data-id');
                const ancien = JSON.parse(this.getAttribute('data-ancien'));
                const nouveau = JSON.parse(this.getAttribute('data-nouveau'));
                const updateType = nouveau.chauffeur_update_type_id; // Type de mise à jour
    
                // Créer un tableau pour afficher les anciennes et nouvelles infos
                let infoTable = `
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
    
                // Ajouter un champ de saisie pour le RFID selon le type de mise à jour
                let rfidField = '';
                if (updateType == 2 || updateType == 3) { // 2: changement RFID, 3: changement transporteur
                    rfidField = `
                        <div style="margin-top: 10px;">
                            <label for="rfidInput">Veuillez saisir le nouveau RFID :</label>
                            <input type="number" id="rfidInput" class="swal2-input" placeholder="Nouveau RFID" required>
                        </div>
                    `;
                }
    
                Swal.fire({
                    title: 'Confirmer la validation du' + nouveau.chauffeur_update_type.name,
                    html: infoTable + rfidField,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Oui, valider',
                    cancelButtonText: 'Annuler',
                    width: '700px',
                    preConfirm: () => {
                        if (updateType == 2 || updateType == 3) {
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
                                rfid: rfidValue // Ajouter le RFID si saisi
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
    

@endsection


