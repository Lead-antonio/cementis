@extends('layouts.app')

@section('content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                  <h1> @lang('models/process.plural') </h1>
                </div>
                <div class="col-sm-6">
                    <a class="btn btn-primary float-right"
                       href="{{ route('process.create') }}">
                        Nouveau
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
                <div class="container">
                    <h1 class="text-center mb-5">Liste des étapes du scoring card</h1>
                    {{-- <div class="row g-4">
                        @foreach ($steps as $step)
                            <div class="col-md-2">
                                <div class="card h-100 shadow-sm">
                                    <div class="card-header text-white bg-primary text-center">
                                        <h5 class="mb-0">Étape {{ $step->order }}</h5>
                                    </div>
                                    <div class="card-body d-flex flex-column">
                                        <h5 class="card-title text-center mb-3">{{ $step->name }}</h5>
                                        <p class="card-text text-muted text-center">{{ $step->description }}</p>
                                        <div class="mt-auto text-center">
                                            <button 
                                                class="btn btn-primary btn-sm start-step" 
                                                data-step="{{ $step->order }}">
                                                Démarrer
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div> --}}
                    {{-- <div class="row g-4">
                        @php
                            $canStartNext = true; // Permet de contrôler si une étape est bloquée
                        @endphp
                        @foreach ($steps as $step)
                            @php
                                $progression = $step->currentProgression();
                                $isCompleted = $progression && $progression->is_completed;
                                $isBlocked = !$canStartNext; // L'étape est bloquée si les précédentes ne sont pas terminées
                            @endphp
                            <div class="col-md-2">
                                <div class="card h-100 shadow-sm 
                                    {{ $isCompleted ? 'bg-secondary text-white' : ($isBlocked ? 'bg-light' : '') }}">
                                    <div class="card-header text-center 
                                        {{ $isCompleted ? 'bg-dark' : ($isBlocked ? 'bg-secondary text-white' : 'bg-primary text-white') }}">
                                        <h5 class="mb-0">Étape {{ $step->order }}</h5>
                                    </div>
                                    <div class="card-body d-flex flex-column">
                                        <h5 class="card-title text-center mb-3">{{ $step->name }}</h5>
                                        <p class="card-text text-muted text-center">{{ $step->description }}</p>
                                        <div class="mt-auto text-center">
                                            @if ($isCompleted)
                                                <button class="btn btn-success btn-sm" disabled>Terminé</button>
                                            @elseif ($isBlocked)
                                                <button class="btn btn-secondary btn-sm" disabled>Bloqué</button>
                                            @else
                                                <button 
                                                    class="btn btn-primary btn-sm start-step" 
                                                    data-step="{{ $step->id }}">
                                                    Démarrer
                                                </button>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @php
                                // Si l'étape actuelle n'est pas terminée, bloquer les suivantes
                                $canStartNext = $isCompleted;
                            @endphp
                        @endforeach
                    </div> --}}
                    <div class="row g-4">
                        @php
                            $canStartNext = true; // Permet de contrôler si une étape est bloquée
                        @endphp
                        @foreach ($steps as $step)
                            @php
                                $progression = $step->currentProgression();
                                $status = $progression ? $progression->status : 'pending';
                                $isBlocked = !$canStartNext; // L'étape est bloquée si les précédentes ne sont pas terminées
                            @endphp
                            <div class="col-md-2">
                                <div class="card h-100 shadow-sm 
                                    {{ $status === 'completed' ? 'bg-secondary text-white' : ($isBlocked ? 'bg-light' : '') }}">
                                    <div class="card-header text-center 
                                        {{ $status === 'completed' ? 'bg-success' : ($isBlocked ? 'bg-secondary text-white' : 'bg-primary text-white') }}">
                                        <h5 class="mb-0">Étape {{ $step->order }}</h5>
                                    </div>
                                    <div class="card-body d-flex flex-column">
                                        <h5 class="card-title text-center mb-3">{{ $step->name }}</h5>
                                        <p class="card-text text-muted text-center">{{ $step->description }}</p>
                                        <div class="mt-auto text-center">
                                            @if ($status === 'completed')
                                                <button class="btn btn-success btn-sm" disabled>Terminé</button>
                                            @elseif ($status === 'in_progress')
                                                <button class="btn btn-warning btn-sm" disabled>En cours...</button>
                                            @elseif ($status === 'error')
                                                <button class="btn btn-danger btn-sm" disabled>Erreur</button>
                                            @elseif ($isBlocked)
                                                <button class="btn btn-secondary btn-sm" disabled>Bloqué</button>
                                            @else
                                                <button 
                                                    class="btn btn-primary btn-sm start-step" 
                                                    data-step="{{ $step->id }}">
                                                    Démarrer
                                                </button>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @php
                                // Bloquer les étapes suivantes si la précédente n'est pas terminée
                                $canStartNext = $status === 'completed';
                            @endphp
                        @endforeach
                    </div>
                    
                    
                </div>

                <div class="card-footer clearfix float-right">
                    <div class="float-right">
                        
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        document.querySelectorAll('.start-step').forEach(button => {
            button.addEventListener('click', function () {
                const step = this.dataset.step;
                console.log("STEP", step);
                // Envoyer une requête POST à Laravel
                fetch(`/process/${step}/run`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    // Afficher un pop-up avec le message de succès
                    Swal.fire({
                        icon: 'success',
                        title: 'Processus démarré',
                        text: data.message,
                        timer: 3000,
                        showConfirmButton: false
                    });
                })
                .catch(error => {
                    // Afficher un pop-up en cas d'erreur
                    Swal.fire({
                        icon: 'error',
                        title: 'Erreur',
                        text: "Une erreur s'est produite lors du démarrage du processus.",
                    });
                });
            });
        });
    </script>


@endsection