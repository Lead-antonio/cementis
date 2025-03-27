@extends('layouts.app')

@section('content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                {{-- <div class="col-sm-6">
                   @lang('models/chauffeurUpdateStories.plural')
                </div> --}}
            </div>
        </div>
    </section>

    <div class="content px-3">

        @include('flash::message')

        <div class="clearfix"></div>

        <div class="card">
            <h4 style="padding-left: 70px;padding-top: 27px;">Historique de validation</h4>
            <div class="card-body p-0">
                <div class="card-body" style="padding-left: 70px;padding-right:120px">
                    @foreach ($validations as $key => $item)
                        <div class="card rounded-card mb-3">
                            <a class="text-decoration-none text-dark">
                                <div class="card-body card-list">
                                    @php
                                        $icon = "fa-edit text-primary";
                                        $action = "création";
                                        if( $item->action_type == 'delete'){
                                            $icon = "fa-trash text-danger";
                                        }
                                        if( $item->action_type == 'create'){
                                            $icon = "fa-plus text-primary";
                                        }
                                        if( $item->action_type == 'update'){
                                            $icon = "fa-edit text-primary";
                                            $action = $item->observation;
                                        }
                                        
                                        if($item->model_type == "App\\Models\\Chauffeur"){
                                            $modele = "chauffeur";
                                        }
                                    @endphp
        
                                    @if ($item->action_type == 'create' || $item->action_type == 'update')
                                        <i class="fa {{ $icon }} icon-circle" style="font-size:20px;"></i>
                                        <span style="font-weight: 500">  {{ $item->operator->name ?? "" }} </span> demande la validation de la  <span style="font-weight: 500"> {{ $action }} du  {{ $modele }} : {{ $item->modifications['nom'] }} </span>
                                        <span class="float-right">
                                            @if ($item->status == 'pending')
                                                <button type="button" class='btn btn-primary saveButton'  data-id="{{ $item->id }}"   data-nouveau='@json($item)' 
                                                        style="border-radius: 72px;">
                                                    Valider
                                                </button>
                                                <button type="button" class='btn btn-danger refusButton'  data-id="{{ $item->id }}"   data-nouveau='@json($item)' style="border-radius: 72px;">
                                                    Refuser
                                                </button>
                                            @elseif ($item->status == 'approved')
                                                <span class="badge badge-pill badge-success badge-validation">Validé</span>
                                            @elseif ($item->status == 'rejected')
                                                <span class="badge badge-pill badge-danger badge-validation">Refusé</span>
                                            @endif
                                        </span>
                                    @elseif ($item->action_type == 'delete')
                                        <i class="fa {{ $icon }} icon-circle" style="font-size: 20px;"></i>
                                        <span style="font-weight: 500">  {{ $item->operator->name ?? "" }} </span> demande la validation de la  <span style="font-weight: 500"> suppression du {{ $modele }}  {{ $item->modifications['nom'] }}  </span>
                                        <span class="float-right">
                                            @if ($item->status == 'pending')
                                                <button type="button" class='btn btn-primary saveButton'  data-id="{{ $item->id }}"   data-nouveau='@json($item)' 
                                                        style="border-radius: 72px;">
                                                    Valider
                                                </button>
                                                <button type="button" class='btn btn-danger refusButton'  data-id="{{ $item->id }}"  data-nouveau='@json($item)'  style="border-radius: 72px;" >
                                                    Refuser
                                                </button>
                                            @elseif ($item->status == 'approved')
                                                <span class="badge badge-pill badge-success badge-validation">Validé</span>
                                            @elseif ($item->status == 'rejected')
                                                <span class="badge badge-pill badge-danger badge-validation">Refusé</span>
                                            @endif
                                        </span>
                                    @endif
        
                                    <div style="margin-top: 5px;padding-left: 54px;">
                                        <small> Date :  {{ $item->created_at->format('Y-m-d') }}  
                                            @if($item->admin)
                                                <span>  | Validateur : {{ $item->admin->name }} </span>
                                            @endif
                                        </small>
                                    </div>
                                </div>
                            </a>
                        </div>
                    @endforeach
                </div>
        
                <div class="card-footer clearfix float-right">
                    {{ $validations->links() }} <!-- Pagination si nécessaire -->
                </div>
        
                @if (count($validations) == 0 )
                    <div style="text-align: center">Aucun résultat trouvé.</div>
                @endif
            </div>
        </div>
        
        
    </div>

    @include('chauffeur_update_stories.validation_script')

    <style>
        .icon-circle {
            width: 40px;
            height: 40px;
            color: #fff;
            border-radius: 50%;
            display: inline-flex;
            justify-content: center;
            align-items: center;
            font-weight: bold;
            margin-right: 10px;
            background-color: #e0e0e0;
        }
        .badge-validation{
            padding-right: 1.6em;
            padding-left: 1.6em;
            border-radius: 14rem;
            padding-top: 14px;
            padding-bottom: 10px;
        }

        .custom-validation {
            max-width: 600px; /* Limite la largeur */
            margin-left: -19px;
            background: white
        }
    </style>
    

@endsection


