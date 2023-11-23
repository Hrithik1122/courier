<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
  <title>Courier Service Form</title>
  <style>
    body {
      font-family: Arial, sans-serif;
      margin: 0;
      padding: 0;
    }

    #map {
      height: 300px;
      width: 100%;
    }

    .form-container {
      max-width: 600px;
      margin: 20px auto;
      padding: 20px;
      border: 1px solid #ccc;
      border-radius: 8px;
    }

    .location-form {
      margin-bottom: 20px;
    }

    .receiver-location {
      margin-bottom: 10px;
    }

    .remove-receiver {
      cursor: pointer;
      color: red;
    }

    .hidden {
      display: none;
    }
  </style>
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

        <div id="map"></div>

        <button type="submit" class="btn btn-success">Submit</button>
    </form>
</div>

<script>
  // Google Maps Integration
  function initMap() {
    var map = new google.maps.Map(document.getElementById('map'), {
      center: {lat: -34.397, lng: 150.644},
      zoom: 8
    });
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

<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAE6mC8C87Qqe4sP0ZKvjNMvWUYvY9WmSI&callback=initMap" async defer></script>

<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.1/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
