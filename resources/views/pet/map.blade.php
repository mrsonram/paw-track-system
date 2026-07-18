@extends('pet/theme')
@section('title', 'แผนที่สุนัข')
@include('pet/menu')
<head>
    <link rel="stylesheet" href="{{ asset('/google_map/css/style.css') }}"/>
    <script src="{{ asset('/google_map/js/app.js') }}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
    <style>
        #map{
            height: 700px;
            width: 100%;
        }
    </style>
</head>
<body>
    <!-- Masthead-->
<header class="masthead bg-primary text-white text-center">
    <div class="container d-flex align-items-center flex-column">
        <!-- Masthead Heading-->
        <h1 class="masthead-heading text-uppercase mb-0">แผนที่สุนัข</h1>
        <!-- Icon Divider-->
        <div class="divider-custom divider-light">
            <div class="divider-custom-line"></div>
            <div class="divider-custom-icon"><i class="fas fa-paw"></i></div>
            <div class="divider-custom-line"></div>
        </div>
        <!-- Masthead Subheading-->
        <p class="masthead-subheading font-weight-light mb-0"><h1>โครงการจัดการปัญหาสุนัข</h1></p>
    </div>
</header>
    <div id="map"></div>
    <script src="https://maps.googleapis.com/maps/api/js?key={{ config('services.google_maps.key') }}&libraries=places&callback=initMap&channel=GMPSB_addressselection_v1_cABC" async defer></script>

    <script>
        function initMap(){

            var option = {
                zoom : 18,
                center : {lat:14.13353837742699, lng:100.6142455376813}
            }

            var map = new google.maps.Map(document.getElementById('map'), option);

            var icon = {url:'source/location.png',
                scaledSize: new google.maps.Size(50, 50),
                origin: new google.maps.Point(0,0),
                anchor: new google.maps.Point(0, 0)
            };

            @foreach($animals as $animal)
            @if (isset($animal->lat))
                addMarker({
                    coords:{lat:{{ $animal->lat }}, lng:{{ $animal->lng }}},
                    content:'<h4>{{ $animal->name }}</h4>'
                });
            @endif
            @endforeach

            function addMarker(props){
                var marker = new google.maps.Marker({
                    position : props.coords,
                    map:map,
                    icon:icon,
                });

                if(props.iconImage){
                    marker.setIcon(props.iconImage);
                }

                if(props.content){
                    var infoWindow = new google.maps.InfoWindow({
                        content:props.content
                    });

                    marker.addListener('click', function(){
                        infoWindow.open(map, marker);
                    });
                }
            }
        }
    </script>
</body>
</html>
