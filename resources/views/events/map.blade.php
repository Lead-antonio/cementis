<!DOCTYPE html>
<html>
<head>
    <title>Google Maps</title>
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyA1f_TK4EnA9ZIQIv6_o5piA48iW8tuHoQ"></script>
</head>
<body>
    <div id="map" style="height: 400px; width: 100%;" 
        data-latitude="{{ $latitude }}" 
        data-longitude="{{ $longitude }}">
    </div>


    <script>
        var latitude = document.getElementById('map').getAttribute('data-latitude');
        var longitude = document.getElementById('map').getAttribute('data-longitude');

        var map = new google.maps.Map(document.getElementById('map'), {
            center: {lat: parseFloat(latitude), lng: parseFloat(longitude)},
            zoom: 15
        });

        var marker = new google.maps.Marker({
            position: {lat: parseFloat(latitude), lng: parseFloat(longitude)},
            map: map
        });
    </script>
</body>
</html>
