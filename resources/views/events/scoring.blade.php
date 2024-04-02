@extends('layouts.app')

@section('content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-12">
                     <h1>Pénalités par chauffeur</h1>
                </div>
            </div>
        </div>
    </section>

    <div class="content px-3">

        @include('adminlte-templates::common.errors')

        <div class="card">
            <div class="row">
                <div class="col-sm-6">
                    <div class="card border-success">
                      <div class="card-header">
                          <h3>@lang('models/events.fields.chauffeur')</h3>
                      </div>
                      <div class="card-body">
                        <div class="card-title" style="width: 100%;">
                          <ul class="list-group">
                              <li class="list-group-item d-flex justify-content-between align-items-center">
                                <div class="mb-2 row">
                                  {!! Form::label('chauffeur', 'Choisir un chauffeur :') !!}
                                  <div class="col-sm-7">
                                    {!! Form::select('chauffeur', ['' => 'Sélectionnez un chauffeur'] + array_combine($drivers, $drivers), null, ['class' => 'form-control','id' => 'chauffeurSelect']) !!}
                                  </div>
                                </div>
  
                              </li>
                            </ul>
                        </div>
                        
                      </div>
                    </div>
                  </div>
                  <div class="col-sm-6">
                    <div class="card">
                      <div class="card-header">
                          <h3>Mois</h3>
                      </div>
                      @php
                            $mois = [
                                '' => 'Sélectionnez un mois',
                                1 => 'Janvier',
                                2 => 'Février',
                                3 => 'Mars',
                                4 => 'Avril',
                                5 => 'Mai',
                                6 => 'Juin',
                                7 => 'Juillet',
                                8 => 'Août',
                                9 => 'Septembre',
                                10 => 'Octobre',
                                11 => 'Novembre',
                                12 => 'Décembre'
                            ];
                        @endphp

                      <div class="card-body">
                          <div class="card-title" style="width: 100%;">
                              <ul class="list-group">
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                  <div class="mb-2 row">
                                    {!! Form::label('mois', 'Choisir le mois :') !!}
                                    <div class="col-sm-7">
                                        {!! Form::select('mois', $mois, null, ['class' => 'form-control', 'id' => 'moisSelect']) !!}
                                    </div>
                                  </div>
                                </li>
                              </ul>
                          </div>
                       </div>
                      </div>
                  </div>

                  {{-- <div class="row mt-3"> --}}
                    <div class="col-sm-12 text-center" style="padding: 0% 0% 1% 0%;">
                        {!! Form::button('Voir', ['class' => 'btn btn-primary', 'id' => 'voirButton']) !!}
                    </div>
                  {{-- </div> --}}

                </div>

                
            </div>

            

            <div class="row">
                <div class="col-sm-12">
                    <div id="resultats">
                    </div>
                </div>
            </div>
        </div>
    </div>

    @include('events.script')
@endsection