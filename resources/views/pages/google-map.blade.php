@extends('layouts.main')
@section('script')
<script>
        mapboxgl.accessToken = 'pk.eyJ1Ijoic2tpcHBlcmhvYSIsImEiOiJjazE2MjNqMjkxMTljM2luejl0aGRyOTAxIn0.Wyvywisw6bsheh7wJZcq3Q';
        var map = new mapboxgl.Map({
          container: 'map',
          //style: 'mapbox://styles/mapbox/streets-v11',
          center: {lat:14.133710046968305, lng:100.617101050143},
          zoom: 15
        });
        var test ='<?php echo $dataArray;?>';
        var dataMap = JSON.parse(test);

        dataMap.features.forEach(function(marker) {

            var el = document.createElement('div');
            el.className = 'marker';

            new mapboxgl.Marker(el)
                .setLngLat(marker.geometry.coordinates)
                .setPopup(new mapboxgl.Popup({ offset: 25 })
            .setHTML('<h3>' + marker.properties.name + '</h3><p>' + marker.properties.location + '</p>'))
                .addTo(map);
        });

    </script>
    <style>
        #map {
            width: 100%;
            height: 500px;
        }
        .marker {
            background-image: url('/images/point.png');
            background-repeat:no-repeat;
            background-size:100%;
            width: 50px;
            height: 100px;
            cursor: pointer;
        }
</style>
@endsection
@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-4">
            <h2>Google Map</h2>
            @if (session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
           <form action="{{route('store')}}" method="post" id="maps">
           @csrf
                <div class="form-group">
                    <label for="name">Title</label>
                    <input type="text" name="name" placeholder="name" class="form-control"/>
                </div>
                <div class="form-group">
                    <label for="city">Description</label>
                    <input type="text" name="city" placeholder="city" class="form-control"/>
                </div>
                <div class="form-group">
                    <label for="lat">lat</label>
                    <input type="text" name="lat" placeholder="lat" class="form-control"/>
                </div>
                <div class="form-group">
                    <label for="lng">lng</label>
                    <input type="text" name="lng" placeholder="lng" class="form-control"/>
                </div>
                <div class="form-group">
                    <input type="submit" name="submit" value="Add Map" class="btn btn-success"/>
                </div>
            </form>
        </div>
        <div class="col-md-8">
            <h2>Show google Map</h2>
            <div class="map" id="map"></div>
            <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBaAcT7gUSkl38sCZazn96anMb6ivCLXYA&libraries=places&callback=initMap&channel=GMPSB_addressselection_v1_cABC" async defer></script>
        </div>
    </div>
</div>
@endsection
