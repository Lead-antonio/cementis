@if($point_total)
    <div class="d-flex justify-content-center">
        <div class="card" style="width: 35rem;">
            <div class="card-header text-center">
                <h2>Somme totale des points de pénalité</h2>
            </div>
            <div class="card-body">
                @if($point_total->total_point_penalite >= 5)
                    <div class="alert alert-danger d-flex align-items-center" role="alert">
                        <i class="fas fa-exclamation-triangle"></i>
                        <div>
                        Total des points de pénalité pour le chauffeur {{ $point_total->nom }} : {{ $point_total->total_point_penalite}}
                        </div>
                    </div>
                @else
                    <div class="alert alert-warning d-flex align-items-center" role="alert">
                        <i class="fas fa-exclamation-triangle"></i>
                        <div>
                        Total des points de pénalité pour le chauffeur {{ $point_total->nom }} : {{ $point_total->total_point_penalite}}
                        </div>
                    </div>
                @endif
                
            </div>        
        </div>
    </div>
@endif


@if($results[0]['evenements']->isNotEmpty())
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
                                    {{-- <p><strong>Chauffeur :</strong> {{ $livraisonAvecEvenements['livraison']->rfid_chauffeur }}</p> --}}
                                    <p><strong>Camion :</strong> {{ $livraisonAvecEvenements['livraison']->camion }}</p>
                                    <p><strong>Date de début :</strong> {{ $livraisonAvecEvenements['livraison']->date_debut }}</p>
                                    <p><strong>Date de fin :</strong> {{ $livraisonAvecEvenements['livraison']->date_fin }}</p>
                                </div>
                                <hr>
                                <div class="col-md-7">
                                    @foreach($livraisonAvecEvenements['evenements'] as $evenement)
                                        <ul class="list-group" style="margin: 0% 0% 1% 0%;">
                                            <li class="list-group-item">
                                                Chauffeur : {{getNameByRFID($evenement->chauffeur)}}, Type : {{ $evenement->type }}, Description : {{ $evenement->description }}, Date : {{ $evenement->date }}, Point de pénalité : {{ $livraisonAvecEvenements['penalites'][$evenement->id] }}
                                            </li>
                                        </ul>
                                    @endforeach
                                </div>
                                
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    @endforeach
@else
    <div class="row mb-3">
        <div class="col-md-12">
            <div class="alert alert-info" role="alert">
                Aucun événement déclenché durant ce trajet.
            </div>
        </div>
    </div>
@endif

<style>
    hr{
        height:auto;
        width:.1vw;
        border-width:0;
        color:#000;
        background-color:#000;
        }
</style>