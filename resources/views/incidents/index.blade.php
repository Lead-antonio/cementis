@extends('layouts.app')
<link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/leaflet.awesome-markers/2.0.5/leaflet.awesome-markers.css">
@section('content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                   <h1>Rapport d'incident</h1>
                </div>
            </div>
        </div>
    </section>

    <div class="content px-3">

        @include('sweetalert::alert')

        <div class="row">
            <div class="col-sm-6">
                <div class="card shadow-sm mb-4">
                    <div class="card-header text-black text-lg font-semibold" style="background-color: #672d2a !important;color: white;">
                        <i class="fas fa-calendar-day me-2"></i> Résumé de la journée
                    </div>
                    <div class="card-body">
                        <div class="row g-3">
            
                            <div class="col-6 mb-4">
                                <div class="info-item">
                                    <div class="info-icon text-primary">
                                        <i class="fas fa-clock"></i>
                                    </div>
                                    <div class="info-content">
                                        <div class="title">Début</div>
                                        <div class="value">23:24:19</div>
                                    </div>
                                </div>
                            </div>
            
                            <div class="col-6 mb-4">
                                <div class="info-item">
                                    <div class="info-icon text-primary">
                                        <i class="fas fa-hourglass-end"></i>
                                    </div>
                                    <div class="info-content">
                                        <div class="title">Fin</div>
                                        <div class="value">01:21:33</div>
                                    </div>
                                </div>
                            </div>
            
                            <div class="col-6 mb-4">
                                <div class="info-item">
                                    <div class="info-icon text-info">
                                        <i class="fas fa-parking"></i>
                                    </div>
                                    <div class="info-content">
                                        <div class="title">Durée de repos</div>
                                        <div class="value">01h 18mn 54s</div>
                                    </div>
                                </div>
                            </div>
            
                            <div class="col-6 mb-4">
                                <div class="info-item">
                                    <div class="info-icon text-success">
                                        <i class="fas fa-stopwatch"></i>
                                    </div>
                                    <div class="info-content">
                                        <div class="title">Durée de conduite</div>
                                        <div class="value">01h 57mn 14s</div>
                                    </div>
                                </div>
                            </div>
            
                            <div class="col-6 mb-4">
                                <div class="info-item">
                                    <div class="info-icon text-success">
                                        <i class="fas fa-briefcase"></i>
                                    </div>
                                    <div class="info-content">
                                        <div class="title">Durée de travail</div>
                                        <div class="value">01h 57mn 14s</div>
                                    </div>
                                </div>
                            </div>
            
                            <div class="col-6 mb-4">
                                <div class="info-item">
                                    <div class="info-icon text-warning">
                                        <i class="fas fa-pause-circle"></i>
                                    </div>
                                    <div class="info-content">
                                        <div class="title">Durée des arrêts</div>
                                        <div class="value">01h 18mn 54s</div>
                                    </div>
                                </div>
                            </div>
            
                            <div class="col-6 mb-4">
                                <div class="info-item">
                                    <div class="info-icon text-secondary">
                                        <i class="fas fa-route"></i>
                                    </div>
                                    <div class="info-content">
                                        <div class="title">Vitesse Moyenne</div>
                                        <div class="value">21 km/h</div>
                                    </div>
                                </div>
                            </div>
            
                            <div class="col-6 mb-4">
                                <div class="info-item">
                                    <div class="info-icon text-danger">
                                        <i class="fas fa-bolt"></i>
                                    </div>
                                    <div class="info-content">
                                        <div class="title">Vitesse maximale</div>
                                        <div class="value">82 km/h</div>
                                    </div>
                                </div>
                            </div>
            
                            <div class="col-6 mb-4">
                                <div class="info-item">
                                    <div class="info-icon text-dark">
                                        <i class="fas fa-car"></i>
                                    </div>
                                    <div class="info-content">
                                        <div class="title">Déplacements</div>
                                        <div class="value">1</div>
                                    </div>
                                </div>
                            </div>
            
                            <div class="col-6 mb-4">
                                <div class="info-item">
                                    <div class="info-icon text-dark">
                                        <i class="fas fa-arrows-alt-h"></i>
                                    </div>
                                    <div class="info-content">
                                        <div class="title">Distance parcourue</div>
                                        <div class="value">41.24 km</div>
                                    </div>
                                </div>
                            </div>
            
                        </div>
                    </div>
                </div>
            </div>
            
            {{-- <div class="col-sm-6">
                <div class="card">
                    <div class="card-header bg-primary text-black text-lg font-semibold d-flex justify-content-between align-items-center">
                        <div class="d-flex align-items-center">
                            <i class="fas fa-map me-2"></i>  Carte - Résumé de la journée
                        </div>

                        <div class="d-flex align-items-center">
                            <i class="fas fa-layer-group me-3" style="cursor:pointer;" onclick="toggleLayerSelect()"></i>

                            <select id="layerSelect" class="form-control  d-inline-block" style="display: none; width: auto;" onchange="changeBaseLayer(this.value)">
                                <option value="OpenStreetMap">OpenStreetMap</option>
                                <option value="Satellite">Satellite</option>
                                <option value="Google Hybrid" selected>Google Hybrid</option>
                            </select>
                        </div>
                    </div>
                    <div class="card-body p-0">
                        <div id="map" style="height: 500px;"></div>
                        
                        
                        <div class="card-footer clearfix">
                            <div id="controls" class="controls">
                                <button class="btn" onclick="prev()">⏮</button>
                                <button class="btn" onclick="play()">▶</button>
                                <button class="btn" onclick="pause()">⏸</button>
                                <button class="btn" onclick="next()">⏭</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div> --}}
            <div class="col-sm-6">
                <div class="card">
                    <div class="card-header text-black text-lg font-semibold d-flex justify-content-between align-items-center" style="background-color: #672d2a !important;color: white;">
                        <!-- Partie gauche : titre avec icône -->
                        <div class="d-flex align-items-center " style="gap: 0.5rem;">
                            <i class="fas fa-map me-2"></i> Carte - Résumé de la journée
                        </div>
            
                        <!-- Partie droite : icône et select avec bon espacement -->
                        <div class="d-flex align-items-center" style="gap: 1rem;">
                            {{-- style="gap: 1rem;" --}}
                            <i class="fas fa-layer-group" style="cursor:pointer;" onclick="toggleLayerSelect()"></i>
                            <select id="layerSelect" class="form-control form-control-sm" style=" width: 150px;" onchange="changeBaseLayer(this.value)">
                                <option value="OpenStreetMap">OpenStreetMap</option>
                                <option value="Satellite">Satellite</option>
                                <option value="Google Hybrid" selected>Google Hybrid</option>
                            </select>
                        </div>
                    </div>
            
                    <div class="card-body p-0">
                        <div id="map" style="height: 500px;"></div>
            
                        <div class="card-footer clearfix">
                            <div class="button-group d-flex justify-content-center" style="gap: 17%;">
                                <button class="btn btn-info" type="button" onclick="prev()"><i class="fas fa-backward"></i> </button>
                                <button class="btn btn-success" type="button" onclick="play()"><i class="fas fa-play"></i></button>
                                <button class="btn btn-secondary" type="button" onclick="pause()"><i class="fas fa-pause"></i></button>
                                <button class="btn btn-info" type="button" onclick="next()"><i class="fas fa-forward"></i></button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
        </div>

        
    </div>
    <style>
        .info-item {
            display: flex;
            align-items: flex-start;
            gap: 12px;
        }

        .info-icon {
            font-size: 3rem;
            line-height: 1;
            margin-top: 2px;
        }

        .info-content .title {
            font-weight: 600;
            font-size: 0.95rem;
            margin-bottom: 2px;
        }

        .info-content .value {
            font-size: 0.875rem;
            color: #6c757d;
        }
        #controls {
            display: flex;
            justify-content: center;
            gap: 15px;
            margin-top: 10px;
        }

        #controls button {
            font-size: 24px;
            padding: 10px 20px;
            border: none;
            border-radius: 8px;
            background-color: white;
            color: white;
            cursor: pointer;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            transition: background-color 0.2s ease;
        }

        #controls button:hover {
            background-color: #7a7a7a;
        }

        .custom-popup {
            font-family: Arial, sans-serif;
            font-size: 14px;
            font-weight: bold;
            background-color: white;
            padding: 4px 8px;
            border: 2px solid #888;
            border-radius: 6px;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.2);
        }
    </style>
   <script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
   <script src="https://cdnjs.cloudflare.com/ajax/libs/leaflet.awesome-markers/2.0.5/leaflet.awesome-markers.js"></script>
   <script>
       const positions = @json($positions); // avec lat, long, heure, vitesse

        // Déclaration des couches de base
        const baseLayers = {
            "OpenStreetMap": L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '&copy; OpenStreetMap contributors'
            }),
            "Satellite": L.tileLayer('https://{s}.google.com/vt/lyrs=s&x={x}&y={y}&z={z}', {
                maxZoom: 20,
                subdomains: ['mt0', 'mt1', 'mt2', 'mt3'],
                attribution: '&copy; Google'
            }),
            "Google Hybrid": L.tileLayer('https://{s}.google.com/vt/lyrs=y&x={x}&y={y}&z={z}', {
                maxZoom: 20,
                subdomains: ['mt0', 'mt1', 'mt2', 'mt3'],
                attribution: '&copy; Google'
            })
        };

        // Initialisation de la carte avec la couche OpenStreetMap par défaut
        const map = L.map('map', {
            center: [positions[0].lat, positions[0].long],
            zoom: 17,
            layers: [baseLayers["Google Hybrid"]]
        });

        let i = 1; // on commence à 1 car on a déjà un point initial
        let interval = null;

        const customMarker = L.icon({
            iconUrl: '/images/truck-solid.svg',         // nom d’icône FontAwesome
            markerColor: 'red',  // couleur du marqueur : red, blue, green, orange, etc.
            iconSize: [30, 30],             // adapte la taille selon ton SVG
        });

        // Marqueur initial
        let marker = L.marker([positions[0].lat, positions[0].long], { icon: customMarker }).addTo(map);

        // Popup initial
        let popup = L.popup({
            closeButton: false,
            autoClose: false,
            closeOnClick: false,
            className: 'custom-popup',
            offset: L.point(0, -30)
        })
        .setLatLng([positions[0].lat, positions[0].long])
        .setContent(`${positions[0].heure} - ${positions[0].vitesse} km/h`)
        .openOn(map);

        // Initialiser une polyligne vide
        let drawnRoute = L.polyline([[positions[0].lat, positions[0].long]], {
            color: '#2ca02c',
            weight: 6,
            opacity: 0.8
        }).addTo(map);

        // Déclaration des couches supplémentaires (overlays)
        const overlays = {
            "Trajet": drawnRoute
        };

        // Ajout du contrôle de layers
        // L.control.layers(baseLayers, overlays, { collapsed: false }).addTo(map);

        function toggleLayerSelect() {
            const select = document.getElementById('layerSelect');
            select.style.display = (select.style.display === 'none') ? 'inline-block' : 'none';
        }

        function changeBaseLayer(layerName) {
            // Retire toutes les couches de base actuelles
            Object.values(baseLayers).forEach(layer => {
                if (map.hasLayer(layer)) {
                    map.removeLayer(layer);
                }
            });

            // Ajoute la nouvelle couche sélectionnée
            baseLayers[layerName].addTo(map);
        }

        function updateMarker() {
            const current = positions[i];
            marker.setLatLng([current.lat, current.long]);
            popup.setLatLng([current.lat, current.long]);
            popup.setContent(`${current.heure} - ${current.vitesse} km/h`);
            map.panTo([current.lat, current.long]);

            // Ajouter le nouveau point à la polyligne
            drawnRoute.addLatLng([current.lat, current.long]);
        }

        function moveMarker() {
            if (i < positions.length) {
                updateMarker();
                i++;
            } else {
                clearInterval(interval);

                // Ajouter un marqueur d'accident à la dernière position
                const last = positions[positions.length - 1];

                const accidentIcon = L.icon({
                    iconUrl: '/images/brust.svg', // Mets ici ton propre SVG ou image
                    iconSize: [35, 35],
                });

                L.marker([last.lat, last.long], { icon: accidentIcon })
                    .addTo(map)
                    .bindPopup('Accident détecté ici');
                }
        }

        function play() {
            if (!interval) interval = setInterval(moveMarker, 2000);
        }
        function pause() {
            clearInterval(interval);
            interval = null;
        }
        function next() {
            if (i < positions.length - 1) {
                i++;
                updateMarker();
            }
        }
        function prev() {
            if (i > 0) { // au moins deux points
                i--;
                drawnRoute.setLatLngs(positions.slice(0, i).map(p => [p.lat, p.long]));
                updateMarker();
            }
        }

   </script>
@endsection


