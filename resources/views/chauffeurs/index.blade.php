@extends('layouts.app')

@section('content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                 <h1>@lang('models/chauffeurs.plural')</h1>
                </div>
                @can('chauffeurs.create')    
                    <div class="col-sm-6">
                        <a class="btn btn-primary float-right"
                        href="{{ route('chauffeurs.create') }}">
                        @lang('crud.add_new')
                        </a>
                    </div>
                @endcan
            </div>
        </div>
    </section>

    <div class="content px-3">

        @include('sweetalert::alert')

        <div class="clearfix"></div>

        <div class="card">
            <div class="card-body p-0">
                @include('chauffeurs.table')

                <div class="card-footer clearfix float-right">
                    <div class="float-right">
                        
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection


