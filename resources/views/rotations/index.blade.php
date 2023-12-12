@extends('layouts.app')

@section('content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-6">
                  <div class="card border-success">
                    <div class="card-header">
                        <h3>L'objectif d'une rotation par zone</h3>
                    </div>
                    <div class="card-body text-success">
                      <div class="card-title" style="width: 100%;">
                        <ul class="list-group">
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                              <h5>Ibity</h5>
                              <span class="badge badge-success badge-pill"><h5>75 heures</h5></span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <h5>Tamatave</h5>
                              <span class="badge badge-success badge-pill"><h5>40 heures</h5></span>
                            </li>
                          </ul>
                      </div>
                      
                    </div>
                  </div>
                </div>
                <div class="col-sm-6">
                  <div class="card">
                    <div class="card-header">
                        <h3>Rapport de Rotations</h3>
                    </div>
                    <div class="card-body text-success">
                        <div class="card-title" style="width: 100%;">
                            <ul class="list-group">
                                <li class="list-group-item d-flex justify-content-between align-items-center text-danger">
                                <h5>Le véhicule portant l'immatriculation 3993AH a effectué :</h5>
                                <span class="badge badge-danger badge-pill"><h5>{{$totalRotationIbity}} heures</h5></span>
                                </li>
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    <h5> Le véhicule portant l'immatriculation 9345TK a effectué :</h5>
                                <span class="badge badge-success badge-pill"><h5>{{$totalRotationTamatave}} heures</h5></span>
                                </li>
                            </ul>
                        </div>
                     </div>
                    </div>
                </div>
              </div>
        </div>
    </section>

    <div class="content px-3">

        @include('flash::message')

        <div class="clearfix"></div>

        <div class="card">
            <div class="card-body p-0">
                @include('rotations.table')

                <div class="card-footer clearfix float-right">
                    <div class="float-right">
                        
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection


