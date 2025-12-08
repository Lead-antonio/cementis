@extends('layouts.app')

@section('content')
<section class="content-header">
    <div class="container-fluid">

        <!-- LIGNE : TITRE À GAUCHE — BOUTON À DROITE -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="fw-bold text-dark m-0">
                Étapes du traitement du scoring card
            </h2>

            <a class="btn btn-primary shadow px-4" href="{{ route('process.create') }}">
                <i class="fas fa-plus-circle me-1"></i> Nouveau
            </a>
        </div>

    </div>
</section>


<div class="content px-3">

    @include('flash::message')

    <div class="card shadow-lg border-0 rounded-4 mt-4">
        <div class="card-body p-4">

            <!-- ===================== STEPS CONTAINER ===================== -->
            <div class="steps-container">
                
                @php $canStartNext = true; @endphp

                @foreach ($steps as $step)
                    @php
                        $progression = $step->currentProgression();
                        $status = $progression ? $progression->status : 'pending';
                        $isBlocked = !$canStartNext;
                    @endphp

                    <div class="step-block">

                        <!-- STEP CIRCLE -->
                        <div class="
                            step-circle
                            {{ $status === 'completed' ? 'completed' : '' }}
                            {{ $status === 'in_progress' ? 'in-progress' : '' }}
                            {{ $isBlocked ? 'blocked' : '' }}
                        ">
                            @if ($status === 'completed')
                                <i class="fas fa-check"></i>
                            @elseif ($status === 'in_progress')
                                <i class="fas fa-spinner spinning"></i>
                            @else
                                <span>{{ $step->order }}</span>
                            @endif
                        </div>

                        <!-- TITLE -->
                        <p class="step-title">{{ $step->name }}</p>

                        <!-- BUTTON -->
                        @if ($status === 'completed')
                            <button class="btn btn-success btn-sm rounded-pill px-3" disabled>
                                <span>✔</span>
                                <span>Terminé</span>
                            </button>

                        @elseif ($status === 'in_progress')
                            <button class="btn btn-warning btn-sm rounded-pill px-3" disabled>
                                <i class="fas fa-sync-alt"></i> En cours
                            </button>

                        @elseif ($status === 'error')
                            <button class="btn btn-danger btn-sm rounded-pill px-3" disabled>
                                <i class="fas fa-times-circle"></i> Erreur
                            </button>

                        @elseif ($isBlocked)
                            <button class="btn btn-secondary btn-sm rounded-pill px-3" disabled>
                                <span>🔒</span>
                                 <span>Verrouillé</span>
                            </button>

                        @else
                            <button 
                                class="btn btn-primary btn-sm rounded-pill px-3 start-step shadow-sm"
                                data-step="{{ $step->id }}"
                            >
                                <span>🚀</span>
                                <span>Démarrer</span>
                            </button>

                        @endif
                    </div>

                    @if (!$loop->last)
                        <div class="step-line {{ $canStartNext ? 'active' : '' }}"></div>
                    @endif

                    @php $canStartNext = $status === 'completed'; @endphp
                @endforeach

            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
document.querySelectorAll('.start-step').forEach(button => {
    button.addEventListener('click', function () {
        const step = this.dataset.step;

        fetch(`/process/${step}/run`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            }
        })
        .then(response => response.json())
        .then(data => {
            Swal.fire({
                icon: 'success',
                title: data.process_name,
                text: data.message,
                confirmButtonText: 'Ok'
            }).then(() => window.location.reload());
        })
        .catch(() => {
            Swal.fire({
                icon: 'error',
                title: 'Erreur',
                text: "Une erreur s'est produite.",
            }).then(() => window.location.reload());
        });
    });
});
</script>

<style>
    .btn span, .btn i, .btn svg {
        display: inline !important;
        vertical-align: middle;
    }

    .btn {
        display: inline-flex !important;
        align-items: center;
        gap: 6px; /* espace entre icône + texte */
    }
/* --- STRUCTURED LAYOUT --- */
.steps-container {
    display: flex;
    justify-content: center;
    align-items: center;
    gap: 60px;
    flex-wrap: wrap;
    padding: 20px;
    position: relative;
}

/* --- STEP BLOCK --- */
.step-block {
    display: flex;
    flex-direction: column;
    align-items: center;
    width: 5%;
    text-align: center;
}

/* --- STEP CIRCLE --- */
.step-circle {
    width: 50px;
    height: 50px;
    border-radius: 50%;
    font-size: 20px;
    font-weight: bold;
    color: #fff;
    display: flex;
    justify-content: center;
    align-items: center;
    background: #6c757d;
    transition: 0.3s ease-in-out;
    box-shadow: 0 6px 12px rgba(0,0,0,0.15);
}

.step-circle.completed {
    background: #28a745;
}

.step-circle.in-progress {
    background: #ffc107;
    color: #000;
}

.step-circle.blocked {
    background: #b5b5b5;
}

.step-circle:not(.blocked):hover {
    transform: scale(1.1);
}

/* --- TITLE --- */
.step-title {
    margin: 14px 0;
    font-weight: bold;
    font-size: 15px;
    color: #343a40;
}

/* --- LINE BETWEEN STEPS --- */
.step-line {
    width: 50px;
    height: 5px;
    background: #bbb;
    border-radius: 10px;
    transition: 0.3s;
}

.step-line.active {
    background: #0d6efd;
}

/* --- LOADER --- */
.spinning {
    animation: spin 1s linear infinite;
}


@keyframes spin {
    100% { transform: rotate(360deg); }
}
</style>

@endsection
