@extends('layouts.app')

@section('content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-2">
                   <h1>SCORING CARD</h1>
                </div>
                
                <div class="col-sm-10">
                    <div class="row"> <!-- Ajout d'une row ici -->
                        <div class="col-md-3 col-sm-6 mb-2 excel-button"> <!-- Ajustement des tailles -->
                            <a id="export-link" class="btn btn-success w-100" 
                                href="{{ route('event.exportscoringcard', ['planning' => $selectedPlanning, 'alphaciment_driver' => $alphaciment_driver]) }}">
                                <i class="fas fa-file-excel"></i> Excel
                            </a>
                        </div>
                
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
                                <option value="">Type de score...</option>
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

    </style>

@endsection
