var geocoder;
var map;

function initialize() {
	geocoder = new google.maps.Geocoder();
	var coords = new google.maps.LatLng(-34.397, 150.644);
	var mapOptions = {
		zoom: 8,
		center: coords,
		mapTypeId: google.maps.MapTypeId.ROADMAP
	}
	map = new google.maps.Map(document.getElementById("map-canvas"), mapOptions);
}

function codeAddress() {
	var address = document.getElementById("address");
	geocoder.geocode( { 'address' : address}, function(results, status) {
		if (status == google.maps.GeocoderStatus.OK) {
			map.setCenter(results[0].geometry.location);
			var marker = new google.maps.Marker({
				map: map,
				position: results[0].geometry.location
			});
		} else {
			alert("Geocode was not successful: " + status);
		}
	});
}

function getDistance(latA, longA, latB, longB) {

	var loc1 = new google.maps.LatLng(latA, longB);
	var loc2 = new google.maps.LatLng(latB, longB);
	
	var distance = new google.maps.DistanceMatrixService();
	distance.getDistanceMatrix(
		{
			origins: [loc1],
			destinations: [loc2],
			travelMode: google.maps.TravelMode.WALKING,
		}, callback);
	
}



