@extends('layouts.app')

@section('content')
<!-- Content Header (Page header) -->
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">@lang('models/dashboards.header.index')</h1>
            </div><!-- /.col -->
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="">@lang('models/dashboards.header.home')</a></li>
                    <li class="breadcrumb-item active">@lang('models/dashboards.header.index')</li>
                </ol>
            </div><!-- /.col -->
        </div><!-- /.row -->
    </div><!-- /.container-fluid -->
</div>
<!-- /.content-header -->

<!-- Main content -->
<section class="content">
    <div class="container-fluid">

        <div class="row">
            <div class="clearfix hidden-md-up"></div>

            
            {{-- <div class="col-12 col-sm-6 col-md-6">
                <div class="info-box mb-3">
                    <span class="info-box-icon bg-success elevation-1">
                        <i class="fas fa-star"></i>
                    </span>
                </div>
            </div> --}}
            {{-- <div class="col-12 col-sm-6 col-md-6">
                <div class="info-box mb-3">
                    <span class="info-box-icon bg-danger elevation-1">
                        <i class="fas fa-exclamation-triangle"></i>
                    </span>

                    <div class="info-box-content">
                        <span class="info-box-text">@lang('common.worst')</span>
                        <span class="info-box-number" >
                            @if(isset($dashboardInfo['scoring']->last()->nom) && $dashboardInfo['scoring']->first()->scoring_card != 0 && $dashboardInfo['scoring']->last()->scoring_card != 0)
                            {{$dashboardInfo['scoring']->last()->nom}}
                            @endif
                        </span>
                    </div>
                    <!-- /.info-box-content -->
                </div>
                <!-- /.info-box -->
            </div>
            <!-- /.col -->
            <div class="col-12 col-sm-6 col-md-6">
                <div class="info-box mb-3">
                    <span class="info-box-icon bg-success elevation-1">
                        <i class="fas fa-star"></i>
                    </span>

                    <div class="info-box-content">
                        <span class="info-box-text">@lang('common.top')</span>
                        <span class="info-box-number">
                            @if(isset($dashboardInfo['scoring']->first()->nom) && $dashboardInfo['scoring']->first()->scoring_card != 0 && $dashboardInfo['scoring']->last()->scoring_card != 0)
                                {{$dashboardInfo['scoring']->first()->nom}}
                            @endif
                        </span>
                    </div>
                    <!-- /.info-box-content -->
                </div>
                <!-- /.info-box -->
            </div> --}}

            <div class="col-12 col-sm-13 col-md-13">
                <div class="info-box mb-3">
                    <div class="info-box-content">
                        <span class="info-box-icon bg-light elevation-1">
                            <img src="{{ url('images/entreprise.png') }}" alt="Chauffeur" width="200"/>
                        </span>
                        <br>
                        <span class="info-box-text">Transporteurs:{{$totalTransporteurs}}</span>
                        <span class="info-box-number">
                            @if(isset($dashboardInfo['scoring']->first()->nom) && $dashboardInfo['scoring']->first()->scoring_card != 0 && $dashboardInfo['scoring']->last()->scoring_card != 0)
                                {{$dashboardInfo['scoring']->first()->nom}}
                            @endif
                        </span>
                    </div>
                    
                    <div class="info-box-content" style="align-items: center;">
                        <span class="info-box-icon bg-light elevation-1">
                            <img src="{{ url('images/livraison-rapide.png') }}" alt="Chauffeur" style="width:120%;"/>
                        </span>
                        <br>
            
                        <span class="info-box-text">Véhicules:{{ $totalVehicules }}</span>
                        <span class="info-box-number">
                            @if(isset($dashboardInfo['scoring']->first()->nom) && $dashboardInfo['scoring']->first()->scoring_card != 0 && $dashboardInfo['scoring']->last()->scoring_card != 0)
                                {{$dashboardInfo['scoring']->first()->nom}}
                            @endif
                        </span>
                    </div>
                    <div class="info-box-content" style="align-items: end;">
                        <span class="info-box-icon bg-light elevation-1">
                            <img src="{{ url('images/chauffeur.png') }}" alt="Chauffeur" width="400" height="80" />
                        </span>
                        <br>
                        <span class="info-box-text">Chauffeurs:{{ $totalChauffeurs }}</span>
                        <span class="info-box-number">
                            @if(isset($dashboardInfo['scoring']->first()->nom) && $dashboardInfo['scoring']->first()->scoring_card != 0 && $dashboardInfo['scoring']->last()->scoring_card != 0)
                                {{$dashboardInfo['scoring']->first()->nom}}
                            @endif
                        </span>
                    </div>
                </div>
            </div>
            
        </div>

        <div class="row">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h1 class="card-title" style="padding-left: 31px;">Meilleur Scoring </h1>
                        <div class="card-tools">
                            <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                <i class="fas fa-minus"></i>
                            </button>
                            <button type="button" class="btn btn-tool" data-card-widget="remove">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                    </div>

                    <div class="card-body">
                        <div class="card-body">
                            {{-- <h3 class="title-scoring">Meilleur Scoring</h3> --}}
                            @foreach ($best_scoring as $key => $item)
                                <div class="card rounded-card">
                                    <div class="card-body card-list ">
                                        <div class="number-circle">{{ $key + 1 }}</div>
                                        <strong> {{ $item->transporteur_nom }}</strong> - <span> {{ $item->driver }} : {{ $item->scoring_card }} </span>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                    <!-- /.card-header -->
                </div>
                <!-- /.card -->
            </div>

            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h1 class="card-title" style="padding-left: 31px;">Moins Bon Scoring </h1>

                        <div class="card-tools">
                            <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                <i class="fas fa-minus"></i>
                            </button>
                            <button type="button" class="btn btn-tool" data-card-widget="remove">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                    </div>

                    <div class="card-body">
                        <div class="card-body">
                            {{-- <h3 class="title-scoring">Moins Bon Scoring</h3> --}}
                            @foreach ($bad_scoring as $key => $item)
                                <div class="card rounded-card">
                                    <div class="card-body card-list ">
                                        <div class="number-circle-worst">{{ $key + 1 }}</div>
                                        <strong> {{ $item->transporteur_nom }}</strong> - <span> {{ $item->driver }} : {{ $item->scoring_card }} </span>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                    <!-- /.card-header -->
                </div>
                <!-- /.card -->
            </div>



            {{-- <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title">Meilleur Transporteur   </h5>

                        <div class="card-tools">
                            <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                <i class="fas fa-minus"></i>
                            </button>
                            <button type="button" class="btn btn-tool" data-card-widget="remove">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                    </div>

                    <div class="card-body">

                        <div class="row">
                            <div class="col-md-6">
                                <div class="card">
                                    <div class="card-header d-flex justify-content-between align-items-center">
                                        <div class="fill-icon">
                                            <i class="fas fa-trophy fa-lg" style="color: green"></i>
                                        </div>
                                        <h6 class="mb-0">Meilleur Scoring </h6>
                                       
                                    </div>
                                    <div class="card-body" style="text-align: center;">
                                        <p>Transporteur</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="card">
                                    <div class="card-header d-flex justify-content-between align-items-center">
                                        <div class="fill-icon">
                                            <i class="fas fa-exclamation-triangle" style="color: red"></i>
                                        </div>
                                        <h6 class="mb-0">Moins Bon Scoring</h6>
                                    </div>

                                    <div class="card-body" style="text-align: center;">
                                        <p>Transporteur</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                         <canvas id="driverStat" height="315" style="height: 180px; display: block; width: 462px;"  class="chartjs-render-monitor"></canvas>
                    </div>
                </div>
            </div>--}}
        </div> 


        <!-- /.row -->
        <div class="row mt-5">
            <div class="col-12 col-sm-6 col-md-6">
              <div class="card">
                <div class="card-header">
                    <h1 class="card-title" style="padding-left: 31px;">Nombre total de chauffeurs par transporteur</h1>
                    <div class="card-tools">
                        <button type="button" class="btn btn-tool" data-card-widget="collapse">
                            <i class="fas fa-minus"></i>
                        </button>
                        <button type="button" class="btn btn-tool" data-card-widget="remove">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                </div>

                <div class="card-body">
                  <div class="table-responsive">
                    <table class="table">
                      <thead>
                        <tr>
                          <th scope="col">Transporteur</th>
                          <th scope="col" class="text-center">Nombre de Chauffeur</th>
                        </tr>
                      </thead>
                      <tbody>
                        @foreach ($dashboardInfo['count_driver_transporteur'] as $item)
                                <tr>
                                    <td>{{$item->nom}}</td>
                                    <td class="text-center">{{$item->chauffeurs_count}}</td>
                                </tr>
                        @endforeach
                      </tbody>
                    </table>
                  </div>
                </div>
              </div>
            </div>
            <div class="col-12 col-sm-6 col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h1 class="card-title" style="padding-left: 31px;">Nombre total de véhicules par transporteur</h1>
                        <div class="card-tools">
                            <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                <i class="fas fa-minus"></i>
                            </button>
                            <button type="button" class="btn btn-tool" data-card-widget="remove">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                    </div>

                  <div class="card-body">
                    <div class="table-responsive">
                      <table class="table">
                        <thead>
                          <tr>
                            <th scope="col">Transporteur</th>
                            <th scope="col" class="text-center">Nombre de vehicules</th>
                          </tr>
                        </thead>
                        <tbody>
                            @foreach ($dashboardInfo['count_vehicule_transporteur'] as $item)
                                <tr>
                                    <td>{{$item->nom}}</td>
                                    <td class="text-center">{{$item->vehicule_count}}</td>
                                </tr>
                            @endforeach
                        </tbody>
                      </table>
                    </div>
                  </div>
                </div>
              </div>
          </div>
    

        {{-- <div class="row">

            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h1 class="card-title ">Transporteur  </h1>

                        <div class="card-tools">
                            <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                <i class="fas fa-minus"></i>
                            </button>
                            <button type="button" class="btn btn-tool" data-card-widget="remove">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                    </div>
                    <!-- /.card-header -->

                    <div class="card-body">
                        <div class="card-body">
                            <div id="jstree">

                            </div>
                        </div>
                    </div>
                   
                </div>
                <!-- /.card -->
            </div>

        </div> --}}

    </div>

    {{-- <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h1 class="card-title ">@lang('common.number_driver_stat') </h1>

                    <div class="card-tools">
                        <button type="button" class="btn btn-tool" data-card-widget="collapse">
                            <i class="fas fa-minus"></i>
                        </button>
                        <button type="button" class="btn btn-tool" data-card-widget="remove">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                </div>
                <!-- /.card-header -->

                <div class="card-body">
                    <canvas id="driverStat" height="315" style="height: 180px; display: block; width: 462px;"  class="chartjs-render-monitor"></canvas>
                    <!-- /.row -->
                </div>
               
            </div>
            <!-- /.card -->
        </div>
    </div> --}}
        <!-- /.row -->
    </div>
    <!--/. container-fluid -->
</section>

<!-- /.content -->
@endsection

@push('third_party_scripts')
<!-- ChartJS -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.5.0/chart.min.js" integrity="sha512-asxKqQghC1oBShyhiBwA+YgotaSYKxGP1rcSYTDrB0U6DxwlJjU59B67U8+5/++uFjcuVM8Hh5cokLjZlhm3Vg==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
@endpush
@push('page_scripts')

<script>
// var userCheckinChart = new Chart(document.getElementById('userCheckinChart').getContext('2d'), @json($chartUserCheckin));

// var driverStat = new Chart(document.getElementById('driverStat').getContext('2d'), @json($chartDriver));

    $('#jstree').jstree({
        'core': {
            'data': {!! $transporteurData !!},
        },
        'types': {
            'default': {
                'icon': 'fa fa-truck transporteur-icon'
            },
            'transporteur': {
                'icon': 'fa fa-truck transporteur-icon' // Icône pour les transporteurs
            },
            'top': {
                'icon': 'fa fa-trophy top-icon' // Icône pour les éléments "Top"
            },
            'worst': {
                'icon': 'fa fa-arrow-circle-down worst-icon' // Icône pour les éléments "Worst"
            },
            'chauffeur': {
                'icon': 'fa fa-user' // Icône pour les chauffeurs
            }
        },
        'plugins': ['types']
    });



    var ctx = document.getElementById('driverStat').getContext('2d');
    var myChart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: @json($chartDriver['labels']),
            datasets: [{
                data: @json($chartDriver['data']),
                backgroundColor: [
                    'rgba(75, 192, 192, 0.2)',
                    'rgba(255, 99, 132, 0.2)',
                    'rgba(54, 162, 235, 0.2)',
                    'rgba(255, 206, 86, 0.2)',
                    'rgba(153, 102, 255, 0.2)',
                    'rgba(255, 159, 64, 0.2)'
                    // Ajoutez autant de couleurs que nécessaire ici
                ],
                borderColor: [
                    'rgba(75, 192, 192, 1)',
                    'rgba(255, 99, 132, 1)',
                    'rgba(54, 162, 235, 1)',
                    'rgba(255, 206, 86, 1)',
                    'rgba(153, 102, 255, 1)',
                    'rgba(255, 159, 64, 1)'
                    // Assurez-vous d'ajouter les couleurs de bordure correspondantes ici
                ],
                borderWidth: 1
            }]
        },
        options: {
            scales: {
                y: {
                    beginAtZero: true
                }
            },
            plugins: {
                legend: {
                    display: false
                }
            },
            // indexAxis: 'y', // Afficher les barres horizontalement
            elements: {
                bar: {
                    barPercentage: 0.8 // Ajuster la largeur des barres ici (0.5 signifie 50% de la largeur par défaut)
                }
            }
        }
    });
    
</script>

@endpush
