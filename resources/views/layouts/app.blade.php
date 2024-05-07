<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>{{ config('app.name') }}</title>
    <meta content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no' name='viewport'>

    <!-- Remplacez le chemin avec le vôtre -->
    <link rel="icon" type="image/png" href="{{ asset('images/alpha_ciment.jpg') }}">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jstree/3.2.1/themes/default/style.min.css" />
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">

    <link href="https://cdn.jsdelivr.net/gh/gitbrent/bootstrap4-toggle@3.6.1/css/bootstrap4-toggle.min.css" rel="stylesheet">

    <!-- AdminLTE -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/admin-lte/3.0.5/css/adminlte.min.css" integrity="sha512-rVZC4rf0Piwtw/LsgwXxKXzWq3L0P6atiQKBNuXYRbg2FoRbSTIY0k2DxuJcs7dk4e/ShtMzglHKBOJxW8EQyQ==" crossorigin="anonymous" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@10/dist/sweetalert2.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">

    <!-- iCheck -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/icheck-bootstrap/3.0.1/icheck-bootstrap.min.css" integrity="sha512-8vq2g5nHE062j3xor4XxPeZiPjmRDh6wlufQlfC6pdQ/9urJkU07NM0tEREeymP++NczacJ/Q59ul+/K2eYvcg==" crossorigin="anonymous" />
    <!-- select2 -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" integrity="sha512-nMNlpuaDPrqlEls3IX/Q56H36qvBASwb3ipuo3MxeWbsQB1881ox0cRv7UPTgBlriqoynt35KjEwgGUeUXIPnw==" crossorigin="anonymous" />
    <!-- flag-icon-css -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/flag-icon-css/3.5.0/css/flag-icon.min.css" integrity="sha512-Cv93isQdFwaKBV+Z4X8kaVBYWHST58Xb/jVOcV9aRsGSArZsgAnFIhMpDoMDcFNoUtday1hdjn0nGp3+KZyyFw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <!-- tempusdominus-bootstrap-4 -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/tempusdominus-bootstrap-4/5.39.0/css/tempusdominus-bootstrap-4.min.css" integrity="sha512-3JRrEUwaCkFUBLK1N8HehwQgu8e23jTH4np5NHOmQOobuC4ROQxFwFgBLTnhcnQRMs84muMh0PnnwXlPq5MGjg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    @stack('third_party_stylesheets')
    <style>
        .dataTables_wrapper {
            margin: 20px;
        },

        
        #overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(128, 128, 128, 0.7); /* Couleur semi-transparente gris */
            z-index: 9998; /* Assure que l'overlay est au-dessus de tout autre contenu */
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        .transporteur-icon {
            color: rgb(61, 134, 203); /* Couleur bleue */
        }

        .top-icon {
            color: #eded35; /* Couleur verte */
        }

        .worst-icon {
            color: red; /* Couleur rouge */
        }

        .lds-dual-ring {
        display: inline-block;
        width: 64px;
        height: 64px;
        }
        .lds-dual-ring:after {
        content: " ";
        display: block;
        width: 46px;
        height: 46px;
        margin: 1px;
        border-radius: 50%;
        border: 5px solid #cef;
        border-color: #cef transparent #cef transparent;
        animation: lds-dual-ring 1.2s linear infinite;
        }
        @keyframes lds-dual-ring {
        0% {
            transform: rotate(0deg);
        }
        100% {
            transform: rotate(360deg);
        }
        }


        .required:after {
            content: '(*)';
            color: red;
            padding-left: 5px;
        },

        .nav-child{
            padding-left: 8px;
        },

        @keyframes spin {
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
        }

        .loader {
            display: none;
            border: 8px solid #f3f3f3;
            border-top: 8px solid #3498db;
            border-radius: 50%;
            width: 50px;
            height: 50px;
            position: fixed;
            top: 50%;
            left: 50%;
            margin-top: -25px;
            margin-left: -25px;
            z-index: 9999;
            animation: spin 1.2s linear infinite; /* Utilisez l'animation 'spin' pour faire tourner le loader */
        }

    </style>
    @stack('page_css')

</head>

<body class="hold-transition sidebar-mini layout-fixed">

    <div class="wrapper">
        <!-- Main Header -->
        <nav class="main-header navbar navbar-expand navbar-white navbar-light">
            <!-- Left navbar links -->
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
                </li>
            </ul>

            <ul class="navbar-nav ml-auto">
                {{-- <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <span class="flag-icon flag-icon-{{Config::get('languages')[App::getLocale()]['flag-icon']}}"></span> {{ Config::get('languages')[App::getLocale()]['display'] }}
                    </a>
                    <div class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
                        @foreach (Config::get('languages') as $lang => $language)
                        @if ($lang != App::getLocale())
                        <a class="dropdown-item" href="{{ route('lang.switch', $lang) }}"><span class="flag-icon flag-icon-{{$language['flag-icon']}}"></span> {{$language['display']}}</a>
                        @endif
                        @endforeach
                    </div>
                </li> --}}
                <li class="nav-item dropdown user-menu">
                    <a href="#" class="nav-link dropdown-toggle" data-toggle="dropdown">
                        <img src="{{url('images/avatars.png')}}" class="user-image img-circle elevation-2" alt="User Image">
                        <span class="d-none d-md-inline">{{ Auth::user()->name }}</span>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
                        <!-- User image -->
                        <li class="user-header bg-primary">
                            <img src="{{url('images/cementis.jpg')}}" class="img-circle elevation-2" alt="User Image">
                            <p>
                                {{ Auth::user()->name }}
                                <small>Member since {{ Auth::user()->created_at->format('M. Y') }}</small>
                            </p>
                        </li>
                        <!-- Menu Footer-->
                        <li class="user-footer">
                            <a href="{{route('users.profile')}}" class="btn btn-default btn-flat">Profile</a>
                            <a href="#" class="btn btn-default btn-flat float-right" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                Sign out
                            </a>
                            <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                @csrf
                            </form>
                        </li>
                    </ul>
                </li>
            </ul>
        </nav>

        <!-- Left side column. contains the logo and sidebar -->
        @include('sweetalert::alert')
        @include('layouts.sidebar')

    

        <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper">
            
            {{-- <div id="loader" class="loader"></div> --}}
            {{-- <div id="overlay"></div> --}}
            {{-- <div class="overlay" id="overlay"></div> --}}

            {{-- <div class="loader-wrapper" id="loader-wrapper"> --}}
                
            {{-- </div> --}}

            {{-- <div id="lds-default" ></div> --}}
            
            <div class="loader" id="load_test"></div>


            <section class="content">
                <div class="lds-default" id="lds-default">
                    <div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div>
                </div>
                <div id="overlay"></div>
                {{-- <div id="loader" class="loaders"></div> --}}

                @yield('content')
            </section>
        </div>

        <!-- Main Footer -->
        <footer class="main-footer">
            <div class="float-right d-none d-sm-block">
                
            </div>
            <strong>Droits d'auteur &copy; 2023 <a> M-Tec</a>.</strong> Tous droits réservés.
        </footer>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js" integrity="sha512-bLT0Qm9VnAYZDflyKcBaQ2gg0hSYNQrJ8RilYldYQ1FxQYoCLtUjuuRuZo+fjqhx/qtq/1itJ0C2ejDxltZVFg==" crossorigin="anonymous"></script>

    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js" integrity="sha384-9/reFTGAW83EW2RDu2S0VKaIzap3H66lZH81PoYlFhbGU+6BZp6G7niu735Sk7lN" crossorigin="anonymous"></script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/js/bootstrap.min.js" integrity="sha384-w1Q4orYjBQndcko6MimVbzY0tgp4pWB4lZ7lr30WKz0vr/aWKhXdBNmNb5D92v7s" crossorigin="anonymous"></script>

    <script src="https://cdn.jsdelivr.net/npm/bs-custom-file-input/dist/bs-custom-file-input.min.js"></script>

    <!-- AdminLTE App -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/admin-lte/3.0.5/js/adminlte.min.js" integrity="sha512-++c7zGcm18AhH83pOIETVReg0dr1Yn8XTRw+0bWSIWAVCAwz1s2PwnSj4z/OOyKlwSXc4RLg3nnjR22q0dhEyA==" crossorigin="anonymous"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.27.0/moment.min.js" integrity="sha512-rmZcZsyhe0/MAjquhTgiUcb4d9knaFc7b5xAfju483gbEXTkeJRUMIPk6s3ySZMYUHEcjKbjLjyddGWMrNEvZg==" crossorigin="anonymous"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/tempusdominus-bootstrap-4/5.39.0/js/tempusdominus-bootstrap-4.min.js" integrity="sha512-k6/Bkb8Fxf/c1Tkyl39yJwcOZ1P4cRrJu77p83zJjN2Z55prbFHxPs9vN7q3l3+tSMGPDdoH51AEU8Vgo1cgAA==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

    <script src="https://cdn.jsdelivr.net/gh/gitbrent/bootstrap4-toggle@3.6.1/js/bootstrap4-toggle.min.js"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js" integrity="sha512-2ImtlRlf2VVmiGZsjm9bEyhjGW4dU7B6TNwh/hx/iSByxNENtj3WVE6o/9Lj4TJeVXPi4bnOIMXFIJJAeufa0A==" crossorigin="anonymous"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-switch/3.3.4/js/bootstrap-switch.min.js" integrity="sha512-J+763o/bd3r9iW+gFEqTaeyi+uAphmzkE/zU8FxY6iAvD3nQKXa+ZAWkBI9QS9QkYEKddQoiy0I5GDxKf/ORBA==" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
    
    {{-- <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script> --}}
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>


    <script src="https://cdnjs.cloudflare.com/ajax/libs/jstree/3.2.1/jstree.min.js"></script>




    <!--
    <script src="https://cdnjs.cloudflare.com/ajax/libs/turbolinks/5.0.0/turbolinks.js" integrity="sha512-P3/SDm/poyPMRBbZ4chns8St8nky2t8aeG09fRjunEaKMNEDKjK3BuAstmLKqM7f6L1j0JBYcIRL4h2G6K6Lew==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script type="text/javascript">
        $(function() {
            Turbolinks.start();
        })
    </script>
    -->

    <script type="text/javascript">
        $(document).ready(function() {
            // Masquer le loader et l'overlay lorsque la page est chargée
            $('#overlay').hide();
            $('#load_test').hide();
        });

        function submitForm() {
            // Afficher le loader
            $('#overlay').show();
            $('#load_test').show();
            // $('#lds-default').show();
            return true; // Permettre la soumission du formulaire
        }
    </script>
    
    <script type="text/javascript">
        $(function() {
            bsCustomFileInput.init();
        });

        $("input[data-bootstrap-switch]").each(function() {
            $(this).bootstrapSwitch('state', $(this).prop('checked'));
        });
        setInterval(function() {
            $.get("{{url('/checkOnline')}}", function($rs) {
                if ($('#user_online').length)
                    $('#user_online').html($rs);
                console.log($rs);
            })
        }, 10000);


        document.addEventListener("DOMContentLoaded", function() {
            document.getElementById('select-all').addEventListener('change', function () {
                var selectAllCheckbox = this; // Stockez une référence à la case à cocher "Sélectionner tout"
                var checkboxes = document.querySelectorAll('.select-checkbox');
                // console.log("checkboxes",checkboxes);
                checkboxes.forEach(function (checkbox) {
                    checkbox.checked = selectAllCheckbox.checked;
                });
            });

            var selectCheckboxes = document.querySelectorAll('.select-checkbox');
            selectCheckboxes.forEach(function(checkbox) {
                checkbox.addEventListener('change', function() {
                    var allChecked = true;
                    selectCheckboxes.forEach(function(cb) {
                        if (!cb.checked) {
                            allChecked = false;
                        }
                    });
                    document.getElementById('select-all').checked = allChecked;
                });
            });
        });


        // Fonction pour modifier ou ajouter le transporteur_id selectionné dans la table transporteur
        function update_transporteurid(id){

            var selectedValues = [];
            var checkboxes = document.querySelectorAll('.select-checkbox:checked');
            checkboxes.forEach(function(checkbox) {
                selectedValues.push(checkbox.value);
            });

            if(selectedValues.length === 0){

                Swal.fire({
                    title: 'Message',
                    text:  'Veuillez selectionner un ou plusieurs chauffeurs!',
                    icon: 'info',
                    showCancelButton: false,
                    showConfirmButton: false,
                    timer: 2000
                });

            }else{

                submitForm();

                var csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

                $.ajax({
                    type: 'POST',
                    url: '/admin/chauffeur/updatetransporteur', 
                    headers: {
                        'X-CSRF-TOKEN': csrfToken 
                    },

                    data: {
                        transporteur_id : id,
                        chauffeur: selectedValues
                    },

                    success: function (response) {
                        window.location.reload();

                        Swal.fire({
                            title: 'Succès!',
                            text:  'Validation efféctuée',
                            icon: 'success',
                            showCancelButton: false,
                            showConfirmButton: false,
                            timer: 2000
                        });

                        $('#overlay').hide();
                        $('#load_test').hide();

                    },
                    error: function (xhr, status, error) {
                        alert('Erreur lors de la mise à jour de l\'état du client : ' + error);
                        // Afficher un message d'erreur ou effectuer d'autres actions si nécessaire
                    }
                });

            }

        }

        
        var filterElement = document.getElementById('filter');

        if (filterElement) {
            filterElement.addEventListener('change', function () {
                var transporteurId = this.value;
                console.log("value",transporteurId);

                // Faites appel à la fonction de filtrage AJAX
                filterChauffeurs(transporteurId);
            });
        }

        
        function filterChauffeurs(transporteurId) {
            fetch('/admin/chauffeur/filtre', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ transporteur_id: transporteurId })
            })
            .then(response => response.json())
            .then(data => {
                // Mettez à jour la table avec les données filtrées
                updateTable(data);
            })
            .catch(error => {
                console.error('Erreur:', error);
            });
        }


        function updateTable(data) {
            // Mettez à jour le contenu de la table avec les données filtrées
            var tbody = document.querySelector('.table tbody');
            tbody.innerHTML = '';

            data.forEach(function (chauffeur) {
                var row = `<tr>
                    <td><input type="checkbox" class="select-checkbox" name="selected_chauffeurs[]" value="${chauffeur.id}"></td>
                    <td>${chauffeur.rfid}</td>
                    <td>${chauffeur.nom}</td>
                    <td>${chauffeur.transporteur ? chauffeur.transporteur.nom : ''}</td>
                </tr>`;
                tbody.insertAdjacentHTML('beforeend', row);
            });
        }

        

    </script>

<style>
       
    #lds-default {
         display: none;
         border: 8px solid #f3f3f3;
         border-top: 8px solid #3498db;
         border-radius: 50%;
         width: 50px;
         height: 50px;
         position: fixed;
         top: 50%;
         left: 50%;
         margin-top: -25px; 
         margin-left: -25px; 
         z-index: 9999;
         animation: lds-default 1.2s linear infinite;
     }

     #overlay {
         display: none;
         position: fixed;
         top: 0;
         left: 0;
         width: 100%;
         height: 100%;
         background-color: rgba(128, 128, 128, 0.7); 
         z-index: 9998;
     }


     @keyframes spin {
         0% { transform: rotate(0deg); }
         100% { transform: rotate(360deg); }
     }

     

        /* .lds-default,
        .lds-default div {
        box-sizing: border-box;
        }
        .lds-default {
        display: inline-block;
        position: relative;
        width: 80px;
        height: 80px;
        }
        .lds-default div {
        position: absolute;
        width: 6.4px;
        height: 6.4px;
        background: currentColor;
        border-radius: 50%;
        animation: lds-default 1.2s linear infinite;
        }
        .lds-default div:nth-child(1) {
        animation-delay: 0s;
        top: 36.8px;
        left: 66.24px;
        }
        .lds-default div:nth-child(2) {
        animation-delay: -0.1s;
        top: 22.08px;
        left: 62.29579px;
        }
        .lds-default div:nth-child(3) {
        animation-delay: -0.2s;
        top: 11.30421px;
        left: 51.52px;
        }
        .lds-default div:nth-child(4) {
        animation-delay: -0.3s;
        top: 7.36px;
        left: 36.8px;
        }
        .lds-default div:nth-child(5) {
        animation-delay: -0.4s;
        top: 11.30421px;
        left: 22.08px;
        }
        .lds-default div:nth-child(6) {
        animation-delay: -0.5s;
        top: 22.08px;
        left: 11.30421px;
        }
        .lds-default div:nth-child(7) {
        animation-delay: -0.6s;
        top: 36.8px;
        left: 7.36px;
        }
        .lds-default div:nth-child(8) {
        animation-delay: -0.7s;
        top: 51.52px;
        left: 11.30421px;
        }
        .lds-default div:nth-child(9) {
        animation-delay: -0.8s;
        top: 62.29579px;
        left: 22.08px;
        }
        .lds-default div:nth-child(10) {
        animation-delay: -0.9s;
        top: 66.24px;
        left: 36.8px;
        }
        .lds-default div:nth-child(11) {
        animation-delay: -1s;
        top: 62.29579px;
        left: 51.52px;
        }
        .lds-default div:nth-child(12) {
        animation-delay: -1.1s;
        top: 51.52px;
        left: 62.29579px;
        }
        @keyframes lds-default {
        0%, 20%, 80%, 100% {
            transform: scale(1);
        }
        50% {
            transform: scale(1.5);
        }
        } */
 </style>

    @stack('third_party_scripts')

    @stack('page_scripts')
</body>

</html>
