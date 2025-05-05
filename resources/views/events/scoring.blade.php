@extends('layouts.app')

@section('content')
    {{-- <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-2">
                   <h1>SCORING CARD</h1>
                </div>
                
                <div class="col-sm-10">
                    <div class="row"> <!-- Ajout d'une row ici -->
                        @can('export.excel.scoring')    
                            <div class="col-md-3 col-sm-6 mb-2 excel-button"> <!-- Ajustement des tailles -->
                                <a id="export-link" class="btn btn-success w-100" 
                                    href="{{ route('export.excel.scoring', ['planning' => $selectedPlanning, 'alphaciment_driver' => $alphaciment_driver]) }}">
                                    <i class="fas fa-file-excel"></i> Excel
                                </a>
                            </div>
                        @endcan
                
                        <div class="col-md-3 col-sm-6 mb-2">
                            <select class="form-control" name="planning" id="planning">
                                <option value="">Veuillez choisir le planning</option>
                                @foreach($import_calendar as $calendar)
                                    <option value="{{$calendar->id}}" {{ $calendar->id == $selectedPlanning ? 'selected' : '' }}>
                                        {{$calendar->name}}
                                    </option>    
                                @endforeach
                            </select>
                        </div>
                
                        <div class="col-md-3 col-sm-6 mb-2">
                            <select class="form-control" name="alphaciment_driver" id="alphaciment_driver">
                                <option value="">Type de scoring card...</option>
                                <option value="oui" {{ $alphaciment_driver === 'oui' ? 'selected' : '' }}>Score alpha ciment</option>
                                <option value="non" {{ $alphaciment_driver === 'non' ? 'selected' : '' }}>Hors alpha ciment</option>
                            </select>
                        </div> 
                
                        <div class="col-md-4 col-sm-6 mb-2">
                            <div class="input-group">
                                <input class="form-control" type="text" id="searchInput" placeholder="Chauffeur, transporteur">
                                <div class="input-group-append">
                                    <span class="input-group-text"><i class="fas fa-search"></i></span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
            </div>
        </div>
    </section> --}}
    <section class="content-header py-4">
        <div class="container-fluid">
            <div class="card shadow-sm rounded">
                <div class="card-body">
                    <div class="row align-items-center justify-content-between">
                        <!-- Colonne gauche : Filtres et boutons -->
                        <div class="col-md-8">
                            <div class="d-flex flex-wrap align-items-center gap-3">
    
                                @can('export.excel.scoring')    
                                    <a id="export-link" class="btn btn-success d-flex align-items-center" 
                                       href="{{ route('export.excel.scoring', ['planning' => $selectedPlanning, 'alphaciment_driver' => $alphaciment_driver]) }}">
                                        <i class="fas fa-file-excel mr-2"></i> Exporter Excel
                                    </a>
                                @endcan
    
                                <select class="form-control custom-select w-auto" name="planning" id="planning">
                                    <option value="">Choisir un planning</option>
                                    @foreach($import_calendar as $calendar)
                                        <option value="{{$calendar->id}}" {{ $calendar->id == $selectedPlanning ? 'selected' : '' }}>
                                            {{$calendar->name}}
                                        </option>    
                                    @endforeach
                                </select>
    
                                <div class="input-group search-group w-auto">
                                    <input class="form-control" type="text" id="searchInput" placeholder="Rechercher...">
                                    <div class="input-group-append">
                                        <span class="input-group-text"><i class="fas fa-search"></i></span>
                                    </div>
                                </div>
                            </div>
                        </div>
    
                        <!-- Colonne droite : Titre -->
                        <div class="col-md-4 text-md-right text-center mt-3 mt-md-0">
                            <h1 class="scoring-title mb-0">SCORING CARD</h1>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    
    

    <div  class="content px-3">
        @include('events.scoring_filtre')
    </div>
    <style>
      .scoring-green {
          background-color: #6dac10; /* Vert */
          color: #000000; /* Couleur de texte */
      }
      .scoring-yellow {
          background-color: #f7d117; /* Jaune */
          color: #000000; /* Couleur de texte */
      }
      .scoring-orange {
          background-color: #f58720; /* Orange */
          color: #000000; /* Couleur de texte */
      }
      .scoring-red {
          background-color: #f44336; /* Rouge */
          color: #ffffff; /* Couleur de texte */
      }

      .excel-button{
        max-width: 8.666667%!important;
      }

      .scoring-title {
            font-size: 2rem;
            font-weight: 700;
            color: #1e293b; /* gris foncé élégant */
            text-transform: uppercase;
            margin-bottom: 0;
        }

        .search-group input {
            min-width: 250px;
        }

        .gap-3 > * {
            margin-right: 1rem;
            margin-bottom: 0.5rem;
        }

        @media (max-width: 768px) {
            .gap-3 > * {
                width: 100% !important;
                margin-right: 0;
            }

            .scoring-title {
                width: 100%;
                text-align: center;
                margin-bottom: 1rem;
            }
        }


    </style>

@endsection
