@extends('layouts.app')

@section('content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-3">
                   <h1>Rapport du score card</h1>
                </div>
                <div class="col-sm-9">
                    <div class="form-row">
                        {{-- <div class="col-sm-2">
                            <a class="btn btn-success" href="{{ route('event.exportscoringcard') }}"> Exporter en Excel</a>
                        </div> --}}
                        <div class="col-sm-2 excel-button">
                            <a id="export-link" class="btn btn-success" 
                                href="{{ route('event.exportscoringcard', ['planning' => $selectedPlanning, 'alphaciment_driver' => $alphaciment_driver]) }}">
                                <i class="fas fa-file-excel"></i> Excel
                            </a>
                        </div>

                        <div class="col-sm-3">
                            <select class="form form-control" name="planning" id="planning" style="width: auto;">
                                <option value="">Veuillez-choisir le planning</option>
                                @foreach($import_calendar as $calendar)
                                    <option value="{{$calendar->id}}" {{ $calendar->id == $selectedPlanning ? 'selected' : '' }}>{{$calendar->name}}</option>    
                                @endforeach
                            </select>
                        </div>
                        <div class="col-sm-3">
                            <select class="form form-control" name="alphaciment_driver" id="alphaciment_driver" style="width: auto;">
                                <option value="">Veuillez-choisir camion calendrier</option>
                                <option value="oui" {{ $alphaciment_driver === 'oui' ? 'selected' : '' }}>Oui</option>
                                <option value="non" {{ $alphaciment_driver === 'non' ? 'selected' : '' }}>Non</option>

                            </select>
                        </div>
                        {{-- <div class="form-row" style="margin: 0% 0% 0% 1%;width: 62%;"> --}}
                        <div class="col-sm-4">
                            <div class="form-row" >
                                <div class="input-group">
                                    <input class="form-control" type="text" id="searchInput"  placeholder="Chauffeur, transporteur"  style="margin: 0% 1% 0% 0%">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="fas fa-search"></i></span>
                                    </div>
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
