<style>
    #map{
        width: 350px;
        height: 250px;
    }
</style>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-F3w7mX95PdgyTmZZMECAngseQB83DfGTowi0iMjiWaeVhAn4FJkqJByhZMI3AhiU" crossorigin="anonymous">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script src="https://maps.googleapis.com/maps/api/js?key={{ config('services.google_maps.key') }}&libraries=places&callback=initMap&channel=GMPSB_addressselection_v1_cABC" type="text/javascript"></script>

<div class="container">
    <div class="col-sm-4">
        <h1>Add Location</h1>
        {{ Form::open(array('url'=>'/google/add', 'file'=>true)) }}
        <div class="form-group">
            <label for="name">Name</label>
            <input type="text" class="form-control input-sm" name="name">
        </div>
        <div class="form-group">
            <label for="map">Map</label>
            <input type="text" class="form-control input-sm" id="search">
            <div class="map" id="map"></div>
        </div>
        <div class="form-group">
            <label for="lat">Lat</label>
            <input type="text" class="form-control input-sm" name="lat" id="lat">
        </div>
        <div class="form-group">
            <label for="lng">lng</label>
            <input type="text" class="form-control input-sm" name="lng" id="lng">
        </div>
        <button class="btn btn-primary">Add Location</button>
        {{ Form::close() }}
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-/bQdsTh/da6pkI1MST/rWKFNjaCP5gBSY4sEBT38Q/9RBh9AH40zEOg7Hlq2THRZ" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js" integrity="sha384-W8fXfP3gkOKtndU4JGtKDvXbO53Wy8SZCQHczT5FMiiqmQfUpWbYdTil/SxwZgAN" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.1/dist/js/bootstrap.min.js" integrity="sha384-skAcpIdS7UcVUC05LJ9Dxay8AXcDYfBJqt1CJ85S/CFujBsIzCIv+l9liuYLaMQ/" crossorigin="anonymous"></script>

<script>
    var map = new google.maps.Map(document.getElementById('map'),{
        center:{
            lat:14.133710046968305,
            lng:100.617101050143
        },
        zoom:15
    });

    var marker = new google.maps.Marker({
        position:{
            lat:14.133710046968305,
            lng:100.617101050143
        },
        map: map,
        draggable: true
    });

    var searchBox = new google.maps.places.SearchBox(document.getElementById('search'));
    google.maps.event.addListener(searchBox, 'places_changed', function(){
        var places = searchBox.getPlaces();
        var bounds = new google.maps.LatLngBounds();
        var i, place;

        for(i=0; place=places[i]; i++){
            bounds.extend(place.geometry.location);
            marker.setPosition(place.geometry.location);
        }

        map.fitBounds(bounds);
        map.setZoom(15);
    });

    google.maps.event.addListener(marker, 'position_changed', function(){
        var lat = marker.getPosition().lat();
        var lng = marker.getPosition().lng();

        $('#lat').val(lat);
        $('#lng').val(lng);
    });
</script>
