<!-- Détails du Véhicule -->
<div class="card mb-4">
    <div class="card-header bg-primary text-white">
        <h4>{{ __('Détails du Véhicule') }}</h4>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-6 mb-3">
                <strong>{{ __('Nom') }} :</strong>
                <p>{{ $vehicule->nom }}</p>
            </div>
            <div class="col-md-6 mb-3">
                <strong>{{ __('IMEI') }} :</strong>
                <p>{{ $vehicule->imei ?? '-' }}</p>
            </div>
            <div class="col-md-6 mb-3">
                <strong>{{ __('Transporteur') }} :</strong>
                <p>{{ $vehicule->related_transporteur->nom ?? '-' }}</p>
            </div>
            <div class="col-md-6 mb-3">
                <strong>{{ __('Installation') }} :</strong>
                <p>{{ $vehicule->installation[0]->date_installation }}</p>
            </div>
        </div>
    </div>
</div>

<!-- Historique des Mises à Jour du Véhicule -->
<div class="card mb-4">
    <div class="card-header bg-secondary text-white">
        <h4>{{ __('Historique des Mises à Jour du Véhicule') }}</h4>
    </div>
    <div class="card-body">
        @if($vehicule_update->isEmpty())
            <p class="text-center text-muted">Aucune mise à jour trouvée pour ce véhicule.</p>
        @else
            <table class="table table-bordered table-striped">
                <thead class="thead-dark">
                    <tr>
                        <th>#</th>
                        <th>{{ __('Nom') }}</th>
                        <th>{{ __('IMEI') }}</th>
                        <th>{{ __('Transporteur') }}</th>
                        <th>{{ __('Description') }}</th>
                        <th>{{ __('Date d\'installation') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($vehicule_update as $index => $update)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $update->nom }}</td>
                            <td>{{ $update->imei ?? '-' }}</td>
                            <td>{{ $update->transporteur->nom ?? ''}}</td>
                            <td>{{ $update->description ?? '-' }}</td>
                            <td>{{ $update->date_installation ? \Carbon\Carbon::parse($update->date_installation)->format('d/m/Y') : '-' }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
    </div>
</div>
