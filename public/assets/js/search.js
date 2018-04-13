var markers = [],
    map,
    SearchInfo = $('.search-info');

$(function () {
    $('#search_text').typeahead({

        source:  function (query, process) {

            if(query.length > 1){
                return $.get(autocomplete.searchCities, { query: query }, function (data) {
                    if(data.length > 0) {
                        return process(data);
                    }else{
                        alert('Please change city name')
                    }
                });
            }
        },
        afterSelect: function (obj) {
            return $.get(autocomplete.nearestCities, obj, function (data) {
                if(data.length){

                    SearchInfo.text(data.length+' nearest cities of '+obj.name);

                    RemoveAllMarkers(markers);
                    AddMarkers(data)
                }else{
                    alert('No more nearest cities')
                }
            });
        }
    });

});

// Removing all markers from map
function RemoveAllMarkers(markers) {
    for (var i = 0; i < markers.length; i++) {
        markers[i].setMap(null);
    }
}

// Adding new markers to map
function AddMarkers(data) {
    $('#nearestCities').removeClass('hide');
    var bounds  = new google.maps.LatLngBounds();
    for ( i = 0; i < data.length; i++) {
        var lat = data[i].latitude;
        var long = data[i].longitude;
        var latLng = new google.maps.LatLng(lat, long);
        var marker = new google.maps.Marker({
            position: latLng,
            map: map,
            label: data[i].name
        });

        markers.push(marker);
        map.setCenter(marker.getPosition());
        var loc = new google.maps.LatLng(lat,long);
        bounds.extend(loc);
    }
    map.fitBounds(bounds);
    map.panToBounds(bounds)
}

// initialize MAP
function initMap() {
    map = new google.maps.Map(document.getElementById('map'), {
        center: {lat: 40.177200, lng: 44.503490},
        zoom: 8
    });
}