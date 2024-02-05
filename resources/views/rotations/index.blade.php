@extends('layouts.app')

@section('content')
    <section class="content-header">
      {{-- @dd($data['totalHours']); --}}
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-6">
                  <div class="card border-success">
                    <div class="card-header">
                        <h3>Véhicule à vérifier</h3>
                    </div>
                    <div class="card-body">
                      <div class="card-title" style="width: 100%;">
                        <ul class="list-group">
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                              <div class="form-group">
                                {!! Form::label('vehicule', 'Choisir le véhicule :') !!}
                                {!! Form::select('vehicule', ['' => 'Sélectionnez un véhicule'] + $vehicules, null, ['class' => 'form-control','id' => 'vehicleHandle']) !!}
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
                        <h3>Rapport de Rotations</h3>
                    </div>
                    <div class="card-body">
                        <div class="card-title" style="width: 100%;">
                            <ul class="list-group" id="rotation-report">
                              <li class="list-group-item d-flex justify-content-between align-items-center">
                                <h5>
                                  
                                </h5>
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

@include('rotations.script')


