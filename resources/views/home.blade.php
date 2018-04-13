@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row ">
            <div class="col-md-8">
                <div class="card">
                    <div class="search">
                        <input class="form-control" placeholder="Search city (min 2 characters)" id="search_text" type="text">
                    </div>
                </div>
            </div>
            <div class="col-md-12 hide" id="nearestCities">

                <h3 class="search-info"></h3>
                <div id="map"></div>
            </div>
        </div>
    </div>


@section('scripts')
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyD7WxlFjT35W-OovxL2N1vMnojAjGrwaT4&callback=initMap"
            async defer></script>
    <script src="{{asset('assets/js/search.js')}}"></script>
@endsection
@endsection
