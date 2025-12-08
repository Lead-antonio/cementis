@extends('layouts.app')

@section('content')
<section class="content-header">
    <div class="container-fluid">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="fw-bold m-0">@lang('models/process.singular')</h1>
            <a href="{{ route('process.index') }}" class="btn btn-secondary px-4 shadow">
                <i class="fas fa-arrow-left me-1"></i> Retour
            </a>
        </div>
    </div>
</section>

<div class="content px-3">
    @include('adminlte-templates::common.errors')

    <div class="row justify-content-center">
        <div class="col-lg-6 col-md-8">

            <div class="card shadow-sm border-0">

                {!! Form::open(['route' => 'process.store']) !!}

                <div class="card-body">

                    <div class="row g-3">

                        <!-- LIGNE 1 : Ordre + Nom -->
                        <div class="col-md-3">
                            {!! Form::label('order', __('models/process.fields.order').':', ['class' => 'fw-bold']) !!}
                            {!! Form::number('order', null, ['class' => 'form-control', 'min' => '0', 'placeholder' => 'Ordre']) !!}
                        </div>

                        <div class="col-md-9">
                            {!! Form::label('name', __('models/process.fields.name').':', ['class' => 'fw-bold']) !!}
                            {!! Form::text('name', null, ['class' => 'form-control', 'placeholder' => 'Nom du process']) !!}
                        </div>

                        <!-- LIGNE 2 : Description -->
                        <div class="col-12">
                            {!! Form::label('description', __('models/process.fields.description').':', ['class' => 'fw-bold']) !!}
                            {!! Form::textarea('description', null, ['class' => 'form-control', 'rows' => 3, 'placeholder' => 'Description...']) !!}
                        </div>

                    </div>

                </div>

                <div class="card-footer">
                    <a href="{{ route('process.index') }}" class="btn btn-outline-secondary px-4">
                        @lang('crud.cancel')
                    </a>

                    {!! Form::submit('Save', ['class' => 'btn btn-primary px-4']) !!}
                </div>

                {!! Form::close() !!}

            </div>

        </div>
    </div>

</div>
@endsection
