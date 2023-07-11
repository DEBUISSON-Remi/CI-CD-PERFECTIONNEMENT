<?php
require_once('./header.php');
require 'vendor/autoload.php';
header("Access-Control-Allow-Origin: *");
session_start();

// var_dump($_SESSION["user"]);
// var_dump($_SESSION["token"]);

?>
<div style="display: flex; flex-direction: column; align-items: center; justify-content: space-between;">
    <h1 class="title is-1">Add route</h1>
    <span class="tag is-info is-light p-5">Pour créer un itinéraire, cliquez sur différents points de la carte pour ajouter des points et former le tracé escompté. <br> Une fois le tracé complet, entrez un nom puis cliquez sur valider et votre tracé sera enregistré.</span>
    <div id="carte" style="margin-top: 10px;">
        <div id="map" class="box"></div>
    </div>
    <input type="text" id="name" name="name" class="input" maxlength="45" style="max-width: 70vw; margin: 10px 0;" placeholder="Nom de l'itinéraire">
    <span class="tag is-info is-light" id="message"></span>
    <div class="buttons" style="margin-top: 10px;">
        <button class="button is-light is-info" onclick="clearMap()">Clear</button>
        <button class="button is-primary" onclick="addRoute()">Valider</button>
    </div>
</div>

<link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css" />
<script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js"></script>
<script>
    var array_points = []

    function addRoute() {
        var name = document.getElementById('name').value;
        if (!name) {
            document.getElementById('message').innerHTML = "Name could not be empty";
            return;
        }
        let formData = new FormData();

        formData.append("name", name);
        formData.append("points", array_points.toString());

        // console.log(formData);
        // exit();
        fetch('http://localhost:4002/add_route.php', {
                method: 'POST',
                mode: 'cors',
                body: formData
            })
            .then(response => response.json().then(
                data => {
                    console.log(data.message);
                    document.getElementById('message').innerHTML = data.message;
                }
            ))
            
    }

    function clearMap() {
        map.remove();
        document.getElementById('carte').innerHTML = '<div id="map" class="box"></div>';
        initMap();
    }

    function initMap() {
        var center = [48.866667, 2.333333];
        var map = L.map('map').setView(center, 11);

        // Récupérer les données des stations Vélib
        fetch('https://opendata.paris.fr/api/records/1.0/search/?dataset=velib-disponibilite-en-temps-reel&q=&facet=name&facet=is_installed&facet=is_renting&facet=is_returning&facet=nom_arrondissement_communes&rows=100')
            .then(response => response.json())
            .then(data => {
                if (data && data.records) {
                    stations = data.records;
                    console.log(stations)

                    // Parcourir les stations et ajouter des marqueurs sur la carte
                    stations.forEach(station => {

                        var velibIcon = L.icon({ //add this new icon
                            iconUrl: 'https://th.bing.com/th/id/R.0218fd05bca7179c4c72bfe6e033ece4?rik=T4Vl6abrMTJjCw&pid=ImgRaw&r=0',

                            iconSize: [30, 30], // size of the icon
                        });

                        var stationName = station.fields.name + "<br><strong>capacity :</strong>" + station.fields.capacity;
                        var stationLat = station.fields.coordonnees_geo[0];
                        var stationLng = station.fields.coordonnees_geo[1];

                        var marker = L.marker([stationLat, stationLng], {}).setIcon(velibIcon).addTo(map);
                        marker.bindPopup(stationName);
                    });
                }
            })
            .catch(error => {
                console.log('Erreur lors de la récupération des données :', error);
            });


        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '© OpenStreetMap contributors'
        }).addTo(map);


        var startPoint = null;
        var endPoint = null;
        var routePoints = []; // Tableau pour stocker les points de l'itinéraire

        function addMarker(e) {
            if (!startPoint) {
                startPoint = e.latlng;
                L.marker(startPoint).addTo(map);
            } else if (!endPoint) {
                endPoint = e.latlng;
                L.marker(endPoint).addTo(map);
                array_points.push([startPoint, endPoint])

                var route = L.polyline([startPoint, endPoint], {
                    color: 'red'
                }).addTo(map);
                routePoints.push(startPoint); // Ajouter le point de départ à l'itinéraire

                var distance = startPoint.distanceTo(endPoint) / 1000; // Distance en kilomètres
                console.log('Distance : ' + distance.toFixed(2) + ' km');

                // Réinitialiser le point de départ pour le prochain itinéraire
                startPoint = endPoint;
                endPoint = null;
            }
        }

        function calculateTotalDistance() {
            var totalDistance = 0;

            for (var i = 0; i < routePoints.length - 1; i++) {
                var startPoint = routePoints[i];
                var endPoint = routePoints[i + 1];
                var segmentDistance = startPoint.distanceTo(endPoint) / 1000; // Distance en kilomètres
                totalDistance += segmentDistance;
            }

            console.log('Distance totale : ' + totalDistance.toFixed(2) + ' km');
        }

        function clearMap() {
            map.eachLayer(function(layer) {
                if (layer instanceof L.Marker) {
                    map.removeLayer(layer);
                }
            });

            startPoint = null;
            endPoint = null;
            routePoints = [];
        }


        map.on('click', addMarker);
        // document.getElementById('clearButton').addEventListener('click', clearMap);
    }
    initMap();
</script>
<?php
require_once('./footer.php');
?>