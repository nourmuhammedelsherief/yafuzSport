$(document).ready(function() {
    var latLong;
    var marker;
    var map;

    var lat_val = 24.710374406523634;
    var long_val = 46.682441104815666;

    $('#lat').val(lat_val);
    $('#long').val(long_val);

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