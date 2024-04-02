<div class="card" style="width: 20rem;">
    <div class="card-header text-center">
        <h3>Somme totale des points de pénalité</h3>
    </div>
    <div class="card-body">
        Total des points de pénalité pour le chauffeur : {{ $point_total }}
    </div>        
</div>


@foreach($results as $livraisonAvecEvenements)
    @if(count($livraisonAvecEvenements['evenements']) > 0)
        <div class="row mb-3">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header text-center">
                        <h3 class="card-title">Événement d'un trajet</h3>
                        <div class="card-tools">
                            <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                <i class="fas fa-minus"></i>
                            </button>
                            <button type="button" class="btn btn-tool" data-card-widget="remove">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-4"> 
                                <p><strong>Chauffeur :</strong> {{ $livraisonAvecEvenements['livraison']->rfid_chauffeur }}</p>
                                <p><strong>Camion :</strong> {{ $livraisonAvecEvenements['livraison']->camion }}</p>
                                <p><strong>Date de début :</strong> {{ $livraisonAvecEvenements['livraison']->date_debut }}</p>
                                <p><strong>Date de fin :</strong> {{ $livraisonAvecEvenements['livraison']->date_fin }}</p>
                            </div>
                            <hr>
                            <div class="col-md-7">
                                @foreach($livraisonAvecEvenements['evenements'] as $evenement)
                                    <ul class="list-group">
                                        <li class="list-group-item">Type : {{ $evenement->type }}, Description : {{ $evenement->description }}, Date : {{ $evenement->date }}, Point de pénalité : {{ $livraisonAvecEvenements['penalites'][$evenement->id] }}</li>
                                    </ul>
                                @endforeach
                            </div>
                            
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <style>
            hr{
                height:auto;
                width:.1vw;
                border-width:0;
                color:#000;
                background-color:#000;
                }
        </style>
    @endif
@endforeach
