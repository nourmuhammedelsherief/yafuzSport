$(document).ready(function() {
    var marker;
    var map;

    var lat = $('#lat').val();
    var long = $('#long').val();

    var lat_val;
    var long_val;

    if (lat !== '' && long !== '') {
        lat_val = parseFloat(lat);
        long_val = parseFloat(long);
    }
    else {
        lat_val = 24.710374406523634;
        long_val = 46.682441104815666;
    }

    var pos = {lat: lat_val, lng: long_val};
    var geocoder = new google.maps.Geocoder;


    map = new google.maps.Map(document.getElementById('div_map'), {
        zoom: 14,
        center: pos
    });
    marker = new google.maps.Marker({
        draggable: true,
        position: pos,
        map: map
    });

    google.maps.event.addListener(marker, 'dragend', function (event) {
        $("#lat").val(this.getPosition().lat());
        $("#long").val(this.getPosition().lng());
        latLong = {lat: this.getPosition().lat(), lng: this.getPosition().lng()};
    });

    if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(function (positions) {
            lat = positions.coords.latitude;
            lng = positions.coords.longitude;
            $("#lat").val(lat);
            $("#long").val(lng);
            if(marker){
                marker.setMap(null);
            }
            var latLong = {lat: lat, lng: lng};
            geocoder.geocode({'location': latLong}, function (results, status) {
                if (status === 'OK') {
                    if (results[1]) {
                        // $("#map_address").val(results[1].formatted_address);
                        map.setCenter(new google.maps.LatLng(lat, lng));
                        marker = new google.maps.Marker({
                            draggable: true,
                            position: latLong,
                            map: map
                        });
                        map.setZoom(14);
                        google.maps.event.addListener(marker, 'dragend', function (event) {
                            $("#lat").val(this.getPosition().lat());
                            $("#long").val(this.getPosition().lng());
                            latLong = {lat: this.getPosition().lat(), lng: this.getPosition().lng()};
                        });
                    }
                }
            });
        });
    }
});