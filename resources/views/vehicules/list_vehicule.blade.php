@extends('layouts.app')

@section('content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                   <h1>@lang('models/vehicules.plural')</h1>
                </div>
                <div class="col-sm-6">
                    <a class="btn btn-primary float-right"
                       href="{{ route('vehicules.create') }}">
                         @lang('crud.add_new')
                    </a>
                </div>
            </div>
        </div>
    </section>

    <div class="content px-3">

        @include('flash::message')

        <div class="clearfix"></div>

        <div class="card">
            <div class="card-body p-0">
                {{-- <table class="table">
                    <thead>
                      <tr>
                        <th scope="col">Immatriculation</th>
                      </tr>
                    </thead>
                    <tbody>
                    @foreach ($vehicule as $item)
                      <tr>
                        <th scope="row">{{$item}}</th>
                      </tr>
                    @endforeach
                    </tbody>
                </table> --}}
                {{-- @include('vehicules.table') --}}

                {{-- <div class="card-footer clearfix float-right">
                    <div class="float-right">
                        
                    </div>
                </div> --}}
                @push('third_party_stylesheets')
                    @include('layouts.datatables_css')
                @endpush

                {!! $dataTable->table(['width' => '100%', 'class' => 'table table-striped table-bordered']) !!}

                @push('third_party_scripts')
                    @include('layouts.datatables_js')
                    {!! $dataTable->scripts() !!}
                @endpush
            </div>
        </div>
    </div>

@endsection
