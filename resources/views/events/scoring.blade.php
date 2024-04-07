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

        <div class="d-flex justify-content-center">
          <div class="card w-50">
            <div class="card-body">
              <h5 class="card-title">{!! Form::label('chauffeur', 'Choisir un chauffeur :') !!}</h5>
              <p class="card-text">{!! Form::select('chauffeur', ['' => 'Sélectionnez un chauffeur'] + array_combine($drivers, $drivers), null, ['class' => 'form-control','id' => 'chauffeurSelect']) !!}</p>
              {!! Form::button('Voir', ['class' => 'btn btn-primary custom-class', 'id' => 'voirButton', 'onsubmit' => 'return submitForm();']) !!}
            </div>
          </div>
        </div>

        <div class="row">
            <div class="col-sm-12">
                <div id="resultats">
                </div>
            </div>
        </div>
    </div>

    <style>
      .custom-class {
        margin: 0% 0% 0% 50%;
      }
    </style>

    @include('events.script')
@endsection