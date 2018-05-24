<html>
  <head>
	<meta name="viewport" content="initial-scale=1.0, user-scalable=no">
    <meta charset="utf-8">
    <style>
       #map { 
        height: 1000px;
        width: 100%;
       }
	   
	         #infowindow-content {
        display: none;
      }
      #map #infowindow-content {
        display: inline;
      }
    </style>
	 <script>
		function initMap(){getLocation()};	
		function getLocation() {
				/*alert("Getlocheiscion soch");*/
				if (navigator.geolocation) {
					navigator.geolocation.getCurrentPosition(function (position) { 
					//alert(position.coords.latitude + "-->" + position.coords.longitude);
					/*alert("Carica sta cazzo di mappa bibi");*/
					console.log(position.coords);
					var origin = {lat: position.coords.latitude, lng: position.coords.longitude};
					var map = new google.maps.Map(document.getElementById('map'), {
						zoom: 100,
						center: origin
						});
					var marker = new google.maps.Marker({
						position: origin,
						map: map,
						animation: google.maps.Animation.DROP
						});
					  var clickHandler = new ClickEventHandler(map, origin);
					});
					
					 //Classe per mettere il puntatore
					var ClickEventHandler = function(map,origin) {
					this.origin = origin;
					this.map = map;
					this.directionsService = new google.maps.DirectionsService;
					this.directionsDisplay = new google.maps.DirectionsRenderer;
					this.directionsDisplay.setMap(map);
					this.placesService = new google.maps.places.PlacesService(map);
					this.infowindow = new google.maps.InfoWindow;
					this.infowindowContent = document.getElementById('infowindow-content');
					this.infowindow.setContent(this.infowindowContent);

					// Listen for clicks on the map.
					this.map.addListener('click', this.handleClick.bind(this));
				  };

				  ClickEventHandler.prototype.handleClick = function(event) {
					console.log('You clicked on: ' + event.latLng);
					//latlon= event.latLng;
					document.getElementById("latlon").value=event.latLng;
					// If the event has a placeId, use it.
					if (event.placeId) {
					  console.log('You clicked on place:' + event.placeId);
					  event.stop();
					  this.calculateAndDisplayRoute(event.placeId);
					  this.getPlaceInformation(event.placeId);
					}
				  };

				  ClickEventHandler.prototype.calculateAndDisplayRoute = function(placeId) {
					var me = this;
					this.directionsService.route({
					  origin: this.origin,
					  destination: {placeId: placeId},
					  travelMode: 'WALKING'
					}, function(response, status) {
					  if (status === 'OK') {
						me.directionsDisplay.setDirections(response);
					  } else {
						window.alert('Directions request failed due to ' + status);
					  }
					});
				  };

				  ClickEventHandler.prototype.getPlaceInformation = function(placeId) {
					var me = this;
					this.placesService.getDetails({placeId: placeId}, function(place, status) {
					  if (status === 'OK') {
						me.infowindow.close();
						me.infowindow.setPosition(place.geometry.location);
						me.infowindowContent.children['place-icon'].src = place.icon;
						me.infowindowContent.children['place-name'].textContent = place.name;
						me.infowindowContent.children['place-id'].textContent = place.place_id;
						me.infowindowContent.children['place-address'].textContent = place.formatted_address;
						me.infowindow.open(me.map);
					  }
					});
				};
				
			}
        }
  </script>
  <script 
   	src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAMxTpQZ63XpaEp0HPV_7IbBOFQEv6Q0Y4&callback=initMap&libraries=places">
  </script>
  </head>
  <body>
	 <?php
		$latlon=$_POST["latlon"];
		$indirizzo_pagina="https://api.foursquare.com/v2/venues/search?ll=$latlon&client_id=SPCT4IEW2KZ2QI4BVHRPF42I40TCQEMQHWFHFHZEMBQYBGI5&client_secret=HISBBKGJ3RSH54EVH00YP3ZFLSXIO1ABH0H4VYH5LWXPN4WC";
		$data = json_decode($json);
		echo "<table align='center'>";
			echo "<tr>";
				echo "<th>ID</th>";
				echo "<th>NOME</th>";	
			echo "</tr>";
			echo "<tr>";
				echo "<td>";
					echo $data->response->venues[]->id;
				echo "</td>";
				echo "<td>";
					echo $data->response->venues[]->name;
				echo "</td>";
			echo "</tr>";	
		echo "</table>";
		echo "<form id='forma' method='post'>";
		echo "<input type='text' value='$latlon' name='latlon' id='latlon'/>";
		echo "</form>";
		echo "<div id='map' ></div>";
		
		echo "<div id='infowindow-content'>";
			echo "<img id='place-icon' src='' height='16' width='16'>";
			echo "<span id='place-name' class='title'></span></br>";
			echo "Place ID: <span id='place-id'></span></br>";
			echo "<span id='place-address'></span>";
		echo "</div>";
 ?>
  </body>
 </html>
