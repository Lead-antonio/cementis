<!-- Détails du Véhicule -->
<!-- Détails du Chauffeur Actuel -->
<div class="card mb-4">
    <div class="card-header bg-primary text-white">
        <h4>{{ __('Détails du Chauffeur') }}</h4>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-6 mb-3">
                <strong>{{ __('Nom') }} :</strong>
                <p>{{ $chauffeur_actuel->nom }}</p>
            </div>
            <div class="col-md-6 mb-3">
                <strong>{{ __('RFID') }} :</strong>
                <p>{{ $chauffeur_actuel->rfid }}</p>
            </div>
            <div class="col-md-6 mb-3">
                <strong>{{ __('RFID PHYSIQUE') }} :</strong>
                <p>{{ $chauffeur_actuel->rfid_physique }}</p>
            </div>
            <div class="col-md-6 mb-3">
                <strong>{{ __('Numéro badge') }} :</strong>
                <p>{{ $chauffeur_actuel->numero_badge }}</p>
            </div>
            <div class="col-md-6 mb-3">
                <strong>{{ __('Transporteur') }} :</strong>
                <p>{{ $chauffeur_actuel->related_transporteur->nom }}</p>
            </div>
            <div class="col-md-6 mb-3">
                <strong>{{ __('Contact') }} :</strong>
                <p>{{ $chauffeur_actuel->contact }}</p>
            </div>
        </div>
    </div>
</div>


<!-- Historique des Mises à Jour du Chauffeur -->
<div class="card mb-4">
    <div class="card-header bg-secondary text-white">
        <h4>{{ __('Historique des Mises à Jour du Chauffeur') }}</h4>
    </div>
    <div class="card-body">
        @if($chauffeur_updates->isEmpty())
            <p class="text-center text-muted">Aucune mise à jour trouvée pour ce chauffeur.</p>
        @else
            <table class="table table-bordered table-striped">
                <thead class="thead-dark">
                    <tr>
                        <th>#</th>
                        <th>{{ __('Nom') }}</th>
                        <th>{{ __('RFID') }}</th>
                        <th>{{ __('RFID Physique') }}</th>
                        <th>{{ __('Numéro Badge') }}</th>
                        <th>{{ __('Transporteur') }}</th>
                        <th>{{ __('Date d\'installation') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($chauffeur_updates as $index => $update)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $update->nom }}</td>
                            <td>{{ $update->rfid ?? '' }}</td>
                            <td>{{ $update->rfid_physique ?? '' }}</td>
                            <td>{{ $update->numero_badge ?? '-' }}</td>
                            <td>{{ $update->transporteur->nom }}</td>
                            <td>{{ $update->date_installation }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
    </div>
</div>

