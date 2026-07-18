<style>
    #map{
        width: 400px;
        height: 400px;
    }
</style>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-F3w7mX95PdgyTmZZMECAngseQB83DfGTowi0iMjiWaeVhAn4FJkqJByhZMI3AhiU" crossorigin="anonymous">
<script src="https://maps.googleapis.com/maps/api/js?key={{ config('services.google_maps.key') }}&libraries=places&callback=initMap&channel=GMPSB_addressselection_v1_cABC" type="text/javascript"></script>

<div class="container">
    <h1>{{$animals->name}}</h1>
    <div class="map" id="map"></div>
</div>

<script>
    var lat = {{$animals->lat}};
    var lng = {{$animals->lng}};

    var map = new google.maps.Map(document.getElementById('map'), {
        center:{
            lat: lat,
            lng: lng
        },
        zoom: 18
    });

    var marker = new google.maps.Marker({
        position:{
            lat: lat,
            lng: lng
        },
        map: map
    });
</script>
