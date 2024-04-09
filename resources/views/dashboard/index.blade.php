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
        <!-- Info boxes -->
        <div class="row">
            {{-- <div class="col-12 col-sm-6 col-md-3">
                <div class="info-box">
                    <span class="info-box-icon bg-info elevation-1">
                        <i class="fas fa-users"></i></span>

                    <div class="info-box-content">
                        <span class="info-box-text">Users</span>
                        <span class="info-box-number">
                            {{$dashboardInfo['user_count']}}
                        </span>
                    </div>
                    <!-- /.info-box-content -->
                </div>
                <!-- /.info-box -->
            </div>
            <!-- /.col -->
            <div class="col-12 col-sm-6 col-md-3">
                <div class="info-box mb-3">
                    <span class="info-box-icon bg-danger elevation-1">
                        <i class="fas fa-user-shield"></i>
                    </span>
                    <div class="info-box-content">
                        <span class="info-box-text">Roles</span>
                        <span class="info-box-number">
                            {{$dashboardInfo['user_count']}}
                        </span>
                    </div>
                    <!-- /.info-box-content -->
                </div>
                <!-- /.info-box -->
            </div> --}}
            <!-- /.col -->

            <!-- fix for small devices only -->
            <div class="clearfix hidden-md-up"></div>

            <div class="col-12 col-sm-6 col-md-6">
                <div class="info-box mb-3">
                    <span class="info-box-icon bg-success elevation-1">
                        {{-- <i class="fas fa-shield-alt"></i> --}}
                        <i class="fas fa-star"></i>
                    </span>

                    <div class="info-box-content">
                        <span class="info-box-text">@lang('common.top')</span>
                        <span class="info-box-number">
                            @if(isset($dashboardInfo['scoring']->first()->nom))
                                {{-- {{$dashboardInfo['driverTop']->nom_chauffeur}} --}}
                                {{$dashboardInfo['scoring']->first()->nom}}
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
                    <span class="info-box-icon bg-danger elevation-1">
                        <i class="fas fa-exclamation-triangle"></i>
                    </span>

                    <div class="info-box-content">
                        <span class="info-box-text">@lang('common.worst')</span>
                        <span class="info-box-number" >@if(isset($dashboardInfo['scoring']->last()->nom)){{$dashboardInfo['scoring']->last()->nom}}@endif</span>
                    </div>
                    <!-- /.info-box-content -->
                </div>
                <!-- /.info-box -->
            </div>
            <!-- /.col -->
        </div>
        <!-- /.row -->

        {{-- <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title">User checkin/out</h5>

                        <div class="card-tools">
                            <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                <i class="fas fa-minus"></i>
                            </button>
                            <div class="btn-group">
                                <button type="button" class="btn btn-tool dropdown-toggle" data-toggle="dropdown">
                                    <i class="fas fa-wrench"></i>
                                </button>
                                <div class="dropdown-menu dropdown-menu-right" role="menu">
                                    <a href="#" class="dropdown-item">Action</a>
                                    <a href="#" class="dropdown-item">Another action</a>
                                    <a href="#" class="dropdown-item">Something else here</a>
                                    <a class="dropdown-divider"></a>
                                    <a href="#" class="dropdown-item">Separated link</a>
                                </div>
                            </div>
                            <button type="button" class="btn btn-tool" data-card-widget="remove">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body">
                        <canvas id="userCheckinChart" height="315" style="height: 180px; display: block; width: 462px;" width="808" class="chartjs-render-monitor"></canvas>
                        <!-- /.row -->
                    </div>
                    <!-- ./card-body -->
                    <div class="card-footer">
                    </div>
                    <!-- /.card-footer -->
                </div>
                <!-- /.card -->
            </div>
            <!-- /.col -->
        </div> --}}

        

        <div class="row">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h1 class="card-title ">@lang('common.scoring') </h1>

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
                        <div class="table-responsive">
                            <table class="table table-hover m-b-0 without-header" id="ticketsTable">
                                <tbody>
                                    @if($dashboardInfo['scoring']->isNotEmpty())
                                        @foreach ($dashboardInfo['scoring'] as $key => $item)
                                            <tr> 
                                                <td>
                                                    <div class="d-inline-block align-middle">
                                                        <img src="{{ asset('images/avatardash.png') }}" alt="user image" class="img-radius img-40 align-top m-r-15" width="10%">
                                                        <div class="d-inline-block" style="margin-top: 10px;margin-left: 16px;">
                                                            <h6>
                                                                {{$item->nom}} 
                                                                @if($key === 0)
                                                                    <span class="text-warning">&#9733;</span>
                                                                @endif
                                                            </h6>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td class="text-right">
                                                    <h6 class="f-w-700">
                                                        {{number_format($item->scoring_card, 2)}}
                                                    </h6>
                                                </td>
                                            </tr>
                                        @endforeach
                                    @else
                                        <tr> 
                                            <td class="text-center" colspan="2">
                                                Aucun élément trouvé
                                            </td>
                                        </tr>
                                    @endif
                                </tbody>
                            </table>
                        </div>
                    </div>
                   
                </div>
                <!-- /.card -->
            </div>


            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title">@lang('common.top_driver')  </h5>

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
                            <table class="table table-hover m-b-0 without-header" id="ticketsTable">
                                <tbody>
                                    @if($dashboardInfo['topDriver']->isNotEmpty())
                                        @foreach ($dashboardInfo['topDriver'] as $key => $item)
                                            <tr> 
                                                <td>
                                                    <div class="d-inline-block align-middle">
                                                        <img src="{{ asset('images/avatardash.png') }}" alt="user image" class="img-radius img-40 align-top m-r-15" width="10%">
                                                        <div class="d-inline-block" style="margin-top: 10px;margin-left: 16px;">
                                                            <h6>
                                                                {{$item->nom}} 
                                                                @if($key === 0)
                                                                    <span class="text-warning">&#9733;</span>
                                                                @endif
                                                            </h6>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td class="text-right">
                                                    <h6 class="f-w-700">
                                                        {{$item->total_penalite}}
                                                    </h6>
                                                </td>
                                            </tr>
                                        @endforeach
                                    @else
                                        <tr> 
                                            <td class="text-center" colspan="2">
                                                Aucun élément trouvé
                                            </td>
                                        </tr>
                                    @endif
                                </tbody>
                            </table>
                        </div>
                    </div>
                    
                </div>
                <!-- /.card -->
            </div>
            <!-- /.col -->
        </div>
    </div>

    <div class="row">
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
    </div>
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
                        barPercentage: 0 // Ajuster la largeur des barres ici (0.5 signifie 50% de la largeur par défaut)
                    }
                }
            }
        });
    
</script>

@endpush
