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
                                {{-- <th>Score Card</th> --}}
                            </tr>
                        </thead>
                        <tbody>
                            @if ($scoring->isEmpty())
                                <tr>
                                    <td colspan="5" style="text-align: center;">Pas d'élément à afficher</td>
                                </tr>
                            @else
                                @foreach ($scoring as $result)
                                    <tr>
                                        <td>{{ $result->driver }}</td>
                                        <td>{{ $result->event }}</td>
                                        <td>{{ $result->date_event }}</td>
                                        <td>{{ $result->penalty_point }}</td>
                                        <td>{{ $result->distance }} Km</td>
                                        {{-- <td>{{ $result->score_card }}</td> --}}
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
        </div>

        <div class="card">
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
        </div>
    </div>

@endsection