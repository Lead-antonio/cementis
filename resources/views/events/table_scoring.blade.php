@extends('layouts.app')

@section('content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                   <h1>Rapport du score card</h1>
                </div>
                <div class="col-sm-6">
                    {{-- <a class="btn btn-primary float-right"
                       href="{{ route('events.create') }}">
                         @lang('crud.add_new')
                    </a> --}}
                </div>
            </div>
        </div>
    </section>

    <div class="content px-3">

        

        <div class="clearfix"></div>

        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Scoring Information</h3>
            </div>
            <div class="card-body p-0">
                    <table class="table table-striped table-bordered" width="100%">
                        <thead>
                            <tr>
                                <th>Chauffeur</th>
                                <th>Événements</th>
                                <th>Date de l'évènement</th>
                                <th>Point de pénalité</th>
                                <th>Distance parcourue</th>
                                <th>Scoring Card</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if ($scoring->isEmpty())
                                <tr>
                                    <td colspan="5" style="text-align: center;">Pas d'élément à afficher</td>
                                </tr>
                            @else
                                @php
                                    $currentDriver = null;
                                    $totalPenalty = 0;
                                    $totalDistance = 0;
                                    $scoringCard = 0;
                                @endphp
                                @foreach ($scoring as $result)
                                    @if ($currentDriver !== $result->driver)
                                        @if ($currentDriver !== null)
                                            <tr class="total-row">
                                                <td colspan="3"><strong>Total :</strong></td>
                                                <td class="point-row"><strong>{{ $totalPenalty }}</strong></td>
                                                <td class="distance-row"><strong>{{ $totalDistance }} Km</strong></td>
                                                <td class="scoring-row"><strong>{{ number_format($totalDistance != 0 ? ($totalPenalty / $totalDistance) * 100 : 0, 2) }}
                                                </strong></td>
                                            </tr>
                                        @endif
                                        @php
                                            $currentDriver = $result->driver;
                                            $totalPenalty = 0;
                                            $totalDistance = 0;
                                        @endphp
                                    @endif
                                    <tr>
                                        <td>{{ $result->driver }}</td>
                                        <td>{{ $result->event }}</td>
                                        <td>{{ $result->date_event }}</td>
                                        <td>{{ $result->penalty_point }}</td>
                                        <td>{{ $result->distance }} Km</td>
                                    </tr>
                                    @php
                                        $totalPenalty += $result->penalty_point;
                                        $totalDistance += $result->distance;
                                    @endphp
                                @endforeach
                                <tr>
                                    <td colspan="3"><strong>Total :</strong></td>
                                    <td class="point-row"><strong>{{ $totalPenalty }}</strong></td>
                                    <td class="distance-row"><strong>{{ $totalDistance }} Km</strong></td>
                                    <td class="scoring-row"><strong>{{ number_format($totalDistance != 0 ? ($totalPenalty / $totalDistance) * 100 : 0, 2) }}
                                    </strong></td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                    
                    

                <div class="card-footer clearfix float-right">
                    <div class="float-right">
                        
                    </div>
                </div>
            </div>
        </div>

        {{-- <div class="card">
            <div class="card-header">
                <h3 class="card-title">Scoring Card de chaque chauffeur</h3>
            </div>
            <div class="card-body p-0">
                    <table class="table table-striped table-bordered" width="100%">
                        <thead>
                            <tr>
                                <th>Chauffeur</th>
                                <th>Transporteur</th>
                                <th>Point de pénalité totale</th>
                                <th>Distance parcourue totale</th>
                                <th>Score Card</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if ($total->isEmpty())
                                <tr>
                                    <td colspan="5" style="text-align: center;">Pas d'élément à afficher</td>
                                </tr>
                            @else
                                @foreach ($total as $result)
                                    <tr>
                                        <td>{{ $result->driver }}</td>
                                        <td>{{ $result->transporteur }}</td>
                                        <td>{{ $result->total_penalty_point }}</td>
                                        <td>{{ $result->total_distance }} km</td>
                                        <td>{{ $result->score_card }}</td>
                                    </tr>
                                @endforeach
                            @endif
                        </tbody>
                    </table>

                <div class="card-footer clearfix float-right">
                    <div class="float-right">
                        
                    </div>
                </div>
            </div>
        </div> --}}
    </div>

    <style>
        .scoring-row {
            background-color: #2b9ed3; /* Couleur de fond différente */
            color: #000000; /* Couleur de texte */
        }
        .point-row {
            background-color: #b4d32b; /* Couleur de fond différente */
            color: #000000; /* Couleur de texte */
        }
        .distance-row {
            background-color: #d38a2b; /* Couleur de fond différente */
            color: #000000; /* Couleur de texte */
        }
    </style>

@endsection