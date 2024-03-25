@extends('layouts.app')

@section('content')
    <section class="content-header">
      {{-- @dd($data['totalHours']); --}}
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-6">
                  <div class="card border-success">
                    <div class="card-header">
                        <h3>@lang('models/rotations.fields.check_vehicle')</h3>
                    </div>
                    <div class="card-body">
                      <div class="card-title" style="width: 100%;">
                        <ul class="list-group">
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                              <div class="mb-2 row">
                                {!! Form::label('vehicule', __('models/rotations.fields.choose').':') !!}
                                <div class="col-sm-7">
                                  {!! Form::select('vehicule', ['' => 'Sélectionnez un véhicule'] + $vehicules, null, ['class' => 'form-control','id' => 'vehicleHandle']) !!}
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
                        <h3>@lang('models/rotations.fields.report')</h3>
                    </div>
                    <div class="card-body">
                        <div class="card-title" style="width: 100%;">
                            <ul class="list-group" id="rotation-report">
                              <li class="list-group-item d-flex justify-content-between align-items-center">
                                <h5>
                                   @lang('models/rotations.fields.no_vehicle') 
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


