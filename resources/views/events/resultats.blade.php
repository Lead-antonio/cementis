@foreach($results as $livraisonAvecEvenements)
    <div class="row mb-3">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header text-center">
                    <h3>Informations</h3>
                </div>
                <div class="card-body">
                    <p><strong>Chauffeur:</strong> {{ $livraisonAvecEvenements['livraison']->rfid_chauffeur }}</p>
                    <p><strong>Camion:</strong> {{ $livraisonAvecEvenements['livraison']->camion }}</p>
                    <p><strong>Date de début:</strong> {{ $livraisonAvecEvenements['livraison']->date_debut }}</p>
                    <p><strong>Date de fin:</strong> {{ $livraisonAvecEvenements['livraison']->date_fin }}</p>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card">
                <div class="card-header text-center">
                    <h3>Liste d'événements</h3>
                </div>
                <div class="card-body">
                    @foreach($livraisonAvecEvenements['evenements'] as $evenement)
                            <div class="card-body">
                                <ul class="list-group">
                                    <li class="list-group-item">Type: {{ $evenement->type }}, Description: {{ $evenement->description }}, Date: {{ $evenement->date }}, Point de pénalité: {{ $livraisonAvecEvenements['penalites'][$evenement->id] }}</li>
                                </ul>
                            </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
@endforeach
