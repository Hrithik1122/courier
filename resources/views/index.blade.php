<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
  <link rel="stylesheet" href="{{ asset('style.css') }}">
  <title>Courier Service Form</title>
</head>
<body>

<div class="form-container">
    @if(session('success'))
        <div class="alert alert-success alert-dismissible">
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
                {{ session('success') }}
        </div>
    @endif
    <form action="{{ url('/location/submit') }}" method="post">
        @csrf
        <div class="location-form">
            <h2>Pickup Location</h2>
            <div class="form-group">
                <label for="pickup-location">Location:</label>
                <input type="text" class="form-control" id="pickup-location" name="name" required>
            </div>

            <div class="form-group">
                <label for="pickup-city">City:</label>
                <input type="text" class="form-control" id="pickup-city" name="city" required>
            </div>

            <div class="form-group">
                <label for="pickup-mobile">Mobile Number:</label>
                <input type="tel" class="form-control" id="pickup-mobile" name="mobile" required>
            </div>

            <div class="form-group">
                <label for="pickup-lat">Lat:</label>
                <input type="text" class="form-control" id="pickup-lat" name="lat" required>
            </div>

            <div class="form-group">
                <label for="pickup-lon">Lon:</label>
                <input type="text" class="form-control" id="pickup-lon" name="lon" required>
            </div>
        </div>

        <div class="location-form" id="receiver-form">
            <h2>Receiver Locations</h2>
            <button type="button" class="btn btn-primary" onclick="addReceiver()">Add Receiver</button>
        </div>

        <button type="submit" class="btn btn-success">Submit</button>
    </form>

        <input id="start-input" class="controls" type="text" placeholder="Enter start location">
        <input id="destination-input" class="controls" type="text" placeholder="Enter destination">

        <div id="map"></div>
        <div id="location-info"></div>
        <button onclick="calculateDistance()" class="btn btn-secondary">Calculate Distance</button>
</div>

<script>
  // Google Maps Integration
  var map;
        var startMarker;
        var destinationMarker;

        function initMap() {
            map = new google.maps.Map(document.getElementById('map'), {
                center: { lat: 20.5937, lng: 78.9629 },
                zoom: 5
            });

            var startInput = document.getElementById('start-input');
            var destinationInput = document.getElementById('destination-input');

            var startAutocomplete = new google.maps.places.Autocomplete(startInput);
            var destinationAutocomplete = new google.maps.places.Autocomplete(destinationInput);

            startMarker = new google.maps.Marker({
                map: map,
                title: 'Start Location',
                draggable: true
            });

            destinationMarker = new google.maps.Marker({
                map: map,
                title: 'Destination',
                draggable: true
            });

            startAutocomplete.addListener('place_changed', function () {
                var place = startAutocomplete.getPlace();
                updateMarker(startMarker, place.geometry.location);
                displayLocationInfo(place, 'start');
            });

            destinationAutocomplete.addListener('place_changed', function () {
                var place = destinationAutocomplete.getPlace();
                updateMarker(destinationMarker, place.geometry.location);
                displayLocationInfo(place, 'destination');
            });

            map.addListener('click', function (event) {
                // Allow the user to click on the map to set markers
                var marker;
                if (!startInput.value) {
                    marker = startMarker;
                    startInput.value = event.latLng.lat() + ', ' + event.latLng.lng();
                } else if (!destinationInput.value) {
                    marker = destinationMarker;
                    destinationInput.value = event.latLng.lat() + ', ' + event.latLng.lng();
                }

                updateMarker(marker, event.latLng);
                calculateDistance();
            });
        }

        function updateMarker(marker, location) {
            marker.setPosition(location);
            map.panTo(location);
        }

        function displayLocationInfo(place, type) {
            var locationInfo = document.getElementById('location-info');
            locationInfo.innerHTML = `
                <p><strong>${type === 'start' ? 'Start Location' : 'Destination'}:</strong> ${place.name}</p>
                <p><strong>Latitude:</strong> ${place.geometry.location.lat()}</p>
                <p><strong>Longitude:</strong> ${place.geometry.location.lng()}</p>
            `;
        }

        function calculateDistance() {
            var startLocation = startMarker.getPosition();
            var destinationLocation = destinationMarker.getPosition();

            var distance = haversine(startLocation.lat(), startLocation.lng(), destinationLocation.lat(), destinationLocation.lng());
            displayDistance(distance);
        }

        function haversine(lat1, lon1, lat2, lon2) {
            var R = 6371; // Radius of the Earth in kilometers
            var dLat = deg2rad(lat2 - lat1);
            var dLon = deg2rad(lon2 - lon1);
            var a =
                Math.sin(dLat / 2) * Math.sin(dLat / 2) +
                Math.cos(deg2rad(lat1)) * Math.cos(deg2rad(lat2)) *
                Math.sin(dLon / 2) * Math.sin(dLon / 2);
            var c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1 - a));
            var distance = R * c; // Distance in kilometers
            return distance;
        }

        function deg2rad(deg) {
            return deg * (Math.PI / 180);
        }

        function displayDistance(distance) {
            var locationInfo = document.getElementById('location-info');
            locationInfo.innerHTML += `
                <p><strong>Distance:</strong> ${distance.toFixed(2)} km</p>
            `;
        }

  // Receiver Locations Management
  var receiverCount = 0;

  function addReceiver() {
    receiverCount++;
    var container = document.createElement('div');
    container.classList.add('receiver-location');

    container.innerHTML = `
    <div class="form-group">
                            <label for="receiver-location-${receiverCount}">Location:</label>
                            <input type="text" class="form-control" name="receiver[${receiverCount}][name]" required>
                        </div>

                        <div class="form-group">
                            <label for="receiver-city-${receiverCount}">City:</label>
                            <input type="text" class="form-control" name="receiver[${receiverCount}][city]" required>
                        </div>

                        <div class="form-group">
                            <label for="receiver-mobile-${receiverCount}">Mobile Number:</label>
                            <input type="tel" class="form-control" name="receiver[${receiverCount}][mobile]" required>
                        </div>

                        <div class="form-group">
                            <label for="receiver-lat-${receiverCount}">Lat:</label>
                            <input type="text" class="form-control" name="receiver[${receiverCount}][lat]" required>
                        </div>

                        <div class="form-group">
                            <label for="receiver-lon-${receiverCount}">Lon:</label>
                            <input type="text" class="form-control" name="receiver[${receiverCount}][lon]" required>
                        </div>

                        <span class="remove-receiver" onclick="removeReceiver(${receiverCount})">Remove</span>
    `;

    document.getElementById('receiver-form').appendChild(container);
  }

  function removeReceiver(index) {
    var element = document.querySelector(`.receiver-location:nth-child(${index + 1})`);
    element.parentNode.removeChild(element);
    receiverCount--;
}

</script>

<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCKLIBoAca5ptn9A_1UCHNNrtzI81w2KRk&callback=initMap&libraries=places"
        async defer></script>

 <!-- Input for location search -->
<!--  <input id="pac-input" class="controls" type="text" placeholder="Search Location"> -->

<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.1/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
