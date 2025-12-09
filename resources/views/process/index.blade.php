{{-- @extends('layouts.app')

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
</section> --}}


{{-- <div class="content px-3">

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
</div> --}}

@extends('layouts.app')

@section('content')
<section class="content-header">
    <div class="container-fluid">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h2 class="fw-bold text-dark mb-1">Traitement du Scoring Card</h2>
                <p class="text-muted mb-0">Suivez la progression de chaque étape du processus</p>
            </div>
            <a class="btn btn-primary shadow-sm px-4 py-2" href="{{ route('process.create') }}">
                <i class="fas fa-plus-circle me-2"></i> Nouveau Processus
            </a>
        </div>
    </div>
</section>

<div class="content px-3">
    @include('flash::message')

    <!-- Progress Overview Card -->
    {{-- <div class="row mb-4">
        <div class="col-12">
            <div class="card border-0 shadow-sm rounded-4 bg-gradient" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                <div class="card-body p-4 text-white">
                    <div class="row align-items-center">
                        <div class="col-md-8">
                            <h5 class="fw-bold mb-2">Progression Globale</h5>
                            <div class="progress bg-white bg-opacity-25" style="height: 10px; border-radius: 10px;">
                                @php
                                    $totalSteps = count($steps);
                                    $completedSteps = $steps->where(function($s) { 
                                        $p = $s->currentProgression(); 
                                        return $p && $p->status === 'completed'; 
                                    })->count();
                                    $progressPercent = $totalSteps > 0 ? ($completedSteps / $totalSteps) * 100 : 0;
                                @endphp
                                <div class="progress-bar bg-white" role="progressbar" style="width: {{ $progressPercent }}%"></div>
                            </div>
                        </div>
                        <div class="col-md-4 text-md-end mt-3 mt-md-0">
                            <h3 class="fw-bold mb-0">{{ $completedSteps }}/{{ $totalSteps }}</h3>
                            <small class="opacity-75">Étapes complétées</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div> --}}

    <!-- Stepper Card -->
    <div class="card border-0 shadow-sm rounded-4">
        <div class="card-body p-5">
            <div class="stepper-modern">
                @php $canStartNext = true; @endphp

                @foreach ($steps as $index => $step)
                    @php
                        $progression = $step->currentProgression();
                        $status = $progression ? $progression->status : 'pending';
                        $isBlocked = !$canStartNext;
                        $isLast = $loop->last;
                    @endphp

                    <div class="step-item {{ $status }} {{ $isBlocked ? 'blocked' : '' }}">
                        <!-- Step Number & Connector -->
                        <div class="step-indicator-wrapper">
                            <div class="step-number">
                                @if ($status === 'completed')
                                    <div class="icon-wrapper completed">
                                        <i class="fas fa-check"></i>
                                    </div>
                                @elseif ($status === 'in_progress')
                                    <div class="icon-wrapper in-progress">
                                        <i class="fas fa-spinner fa-spin"></i>
                                    </div>
                                @elseif ($status === 'error')
                                    <div class="icon-wrapper error">
                                        <i class="fas fa-exclamation-triangle"></i>
                                    </div>
                                @elseif ($isBlocked)
                                    <div class="icon-wrapper blocked">
                                        <i class="fas fa-lock"></i>
                                    </div>
                                @else
                                    <div class="icon-wrapper pending">
                                        <span class="step-num">{{ $step->order }}</span>
                                    </div>
                                @endif
                            </div>
                            @if (!$isLast)
                                <div class="step-connector {{ $status === 'completed' ? 'active' : '' }}"></div>
                            @endif
                        </div>

                        <!-- Step Content -->
                        <div class="step-content">
                            <div class="step-header">
                                <h5 class="step-title mb-1">{{ $step->name }}</h5>
                                @if ($progression && $progression->updated_at)
                                    <small class="text-muted">
                                        <i class="far fa-clock me-1"></i>
                                        {{ $progression->updated_at->diffForHumans() }}
                                    </small>
                                @endif
                            </div>

                            <div class="step-description mb-3">
                                @if ($status === 'completed')
                                    <span class="badge bg-success-subtle text-success px-3 py-2">
                                        <i class="fas fa-check-circle me-1"></i> Terminé avec succès
                                    </span>
                                @elseif ($status === 'in_progress')
                                    <span class="badge bg-warning-subtle text-warning px-3 py-2">
                                        <i class="fas fa-sync fa-spin me-1"></i> Traitement en cours...
                                    </span>
                                @elseif ($status === 'error')
                                    <span class="badge bg-danger-subtle text-danger px-3 py-2">
                                        <i class="fas fa-times-circle me-1"></i> Erreur détectée
                                    </span>
                                @elseif ($isBlocked)
                                    <span class="badge bg-secondary-subtle text-secondary px-3 py-2">
                                        <i class="fas fa-lock me-1"></i> Verrouillé - Complétez l'étape précédente
                                    </span>
                                @else
                                    <span class="badge bg-primary-subtle text-primary px-3 py-2">
                                        <i class="fas fa-circle-notch me-1"></i> Prêt à démarrer
                                    </span>
                                @endif
                            </div>

                            <div class="step-actions">
                                @if ($status === 'completed')
                                    <button class="btn btn-outline-success btn-sm rounded-pill" disabled>
                                        <i class="fas fa-check me-1"></i> Complété
                                    </button>
                                @elseif ($status === 'in_progress')
                                    <button class="btn btn-outline-warning btn-sm rounded-pill" disabled>
                                        <i class="fas fa-hourglass-half me-1"></i> En cours
                                    </button>
                                @elseif ($status === 'error')
                                    <button class="btn btn-outline-danger btn-sm rounded-pill" disabled>
                                        <i class="fas fa-redo me-1"></i> Réessayer
                                    </button>
                                @elseif ($isBlocked)
                                    <button class="btn btn-outline-secondary btn-sm rounded-pill" disabled>
                                        <i class="fas fa-lock me-1"></i> Verrouillé
                                    </button>
                                @else
                                    <button 
                                        class="btn btn-primary btn-sm rounded-pill px-4 start-step"
                                        data-step="{{ $step->id }}"
                                    >
                                        <i class="fas fa-play me-1"></i> Démarrer l'étape
                                    </button>
                                @endif
                            </div>
                        </div>
                    </div>

                    @php $canStartNext = $status === 'completed'; @endphp
                @endforeach
            </div>
        </div>
    </div>
</div>


<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://js.pusher.com/8.2.0/pusher.min.js"></script>
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

{{-- <style>
    .btn span, .btn i, .btn svg {
        display: inline !important;
        vertical-align: middle;
    }

    .btn {
        display: inline-flex !important;
        align-items: center;
        gap: 6px; 
    }

    .steps-container {
        display: flex;
        justify-content: center;
        align-items: center;
        gap: 60px;
        flex-wrap: wrap;
        padding: 20px;
        position: relative;
    }

    .step-block {
        display: flex;
        flex-direction: column;
        align-items: center;
        width: 5%;
        text-align: center;
    }

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

    .step-title {
        margin: 14px 0;
        font-weight: bold;
        font-size: 15px;
        color: #343a40;
    }

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

    .spinning {
        animation: spin 1s linear infinite;
    }


    @keyframes spin {
        100% { transform: rotate(360deg); }
    }
</style> --}}

<style>
/* Modern Stepper Styles */
.stepper-modern {
    position: relative;
}

.step-item {
    display: flex;
    gap: 2rem;
    margin-bottom: 2rem;
    transition: all 0.3s ease;
}

.step-item:last-child {
    margin-bottom: 0;
}

.step-indicator-wrapper {
    position: relative;
    display: flex;
    flex-direction: column;
    align-items: center;
    flex-shrink: 0;
}

.step-number {
    position: relative;
    z-index: 2;
}

.icon-wrapper {
    width: 60px;
    height: 60px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.5rem;
    font-weight: bold;
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
    transition: all 0.3s ease;
}

.icon-wrapper.completed {
    background: linear-gradient(135deg, #10b981, #059669);
    color: white;
    animation: pulse-success 2s infinite;
}

.icon-wrapper.in-progress {
    background: linear-gradient(135deg, #f59e0b, #d97706);
    color: white;
    animation: pulse-warning 1.5s infinite;
}

.icon-wrapper.error {
    background: linear-gradient(135deg, #ef4444, #dc2626);
    color: white;
}

.icon-wrapper.blocked {
    background: linear-gradient(135deg, #9ca3af, #6b7280);
    color: white;
}

.icon-wrapper.pending {
    background: linear-gradient(135deg, #3b82f6, #2563eb);
    color: white;
}

.icon-wrapper:hover {
    transform: scale(1.1);
    box-shadow: 0 6px 20px rgba(0, 0, 0, 0.15);
}

.step-num {
    font-size: 1.5rem;
    font-weight: 700;
}

.step-connector {
    width: 4px;
    flex-grow: 1;
    background: #e5e7eb;
    margin: 0.5rem 0;
    border-radius: 2px;
    transition: all 0.5s ease;
}

.step-connector.active {
    background: linear-gradient(180deg, #10b981, #059669);
}

.step-content {
    flex-grow: 1;
    padding: 1rem;
    background: #f9fafb;
    border-radius: 1rem;
    border: 2px solid #e5e7eb;
    transition: all 0.3s ease;
}

.step-item.completed .step-content {
    background: linear-gradient(135deg, #f0fdf4, #dcfce7);
    border-color: #10b981;
}

.step-item.in_progress .step-content {
    background: linear-gradient(135deg, #fffbeb, #fef3c7);
    border-color: #f59e0b;
}

.step-item.error .step-content {
    background: linear-gradient(135deg, #fef2f2, #fee2e2);
    border-color: #ef4444;
}

.step-item.blocked .step-content {
    background: #f3f4f6;
    border-color: #d1d5db;
    opacity: 0.7;
}

.step-content:hover {
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
    transform: translateX(5px);
}

.step-title {
    font-size: 1.25rem;
    font-weight: 600;
    color: #1f2937;
}

.step-description {
    margin-top: 0.75rem;
}

.step-actions {
    margin-top: 1rem;
}

/* Animations */
@keyframes pulse-success {
    0%, 100% {
        box-shadow: 0 4px 15px rgba(16, 185, 129, 0.4);
    }
    50% {
        box-shadow: 0 4px 25px rgba(16, 185, 129, 0.7);
    }
}

@keyframes pulse-warning {
    0%, 100% {
        box-shadow: 0 4px 15px rgba(245, 158, 11, 0.4);
    }
    50% {
        box-shadow: 0 4px 25px rgba(245, 158, 11, 0.7);
    }
}

/* Responsive */
@media (max-width: 768px) {
    .step-item {
        gap: 1rem;
    }
    
    .icon-wrapper {
        width: 50px;
        height: 50px;
        font-size: 1.25rem;
    }
    
    .step-content {
        padding: 0.75rem;
    }
    
    .step-title {
        font-size: 1rem;
    }
}

/* Badge Styles */
.badge {
    font-weight: 500;
    font-size: 0.875rem;
    border-radius: 0.5rem;
}

.bg-success-subtle {
    background-color: #d1fae5 !important;
}

.bg-warning-subtle {
    background-color: #fef3c7 !important;
}

.bg-danger-subtle {
    background-color: #fee2e2 !important;
}

.bg-secondary-subtle {
    background-color: #f3f4f6 !important;
}

.bg-primary-subtle {
    background-color: #dbeafe !important;
}

/* Button Enhancements */
.btn-sm.rounded-pill {
    padding: 0.5rem 1.5rem;
    font-weight: 500;
    transition: all 0.3s ease;
}

.btn-primary.start-step:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 20px rgba(59, 130, 246, 0.4);
}
</style>

@endsection
