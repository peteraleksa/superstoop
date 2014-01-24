<script type="text/javascript">

function searchLocations() {
  var address = document.getElementById("address").value;
  var geocoder = new google.maps.Geocoder();
  geocoder.geocode({address: address}, function(results, status) {
    if (status == google.maps.GeocoderStatus.OK) {
      searchLocationsNear(results[0].geometry.location);
    } else {
      alert(address + ' not found');
    }
  });
}


function downloadUrl(url, callback) {
  var request = new XMLHttpRequest;
	
  request.onreadystatechange = function() {
  	if (request.readyState == 4) {
     		request.onreadystatechange = doNothing;
     		callback(request.responseText, request.status);
   	}
 };

 request.open('GET', url, true);
 request.send(null);

}

function searchLocationsNear(center) {
  clearLocations();

  var radius = document.getElementById('radiusSelect').value;
  var searchUrl = 'sales_search_results.php?origin_lat=' + center.lat() + '&origin_long=' + center.lng() + '&radius=' + radius;
 
  downloadUrl(searchUrl, function(data) {
  
  var xml = parseXml(data);
  var eventNodes = xml.documentElement.getElementsByTagName("event");
  var bounds = new google.maps.LatLngBounds();
  
  for (var i = 0; i < eventNodes.length; i++) {
    var event_id = eventNodes[i].getChildren("event_id");
    var title = eventNodes[i].getChildren("title");
    var date = eventNodes[i].getChildren("date");
    var address = eventNodes[i].getChildren("address_num")
			+ eventNodes[i].getChildren("address_street")
			+ eventNodes[i].getChildren("address_city")
			+ eventNodes[i].getChildren("address_state")
			+ eventNodes[i].getChildren("address_zip");
 
    var distance = parseFloat(eventNodes[i].getChildren("distance"));

    var latlng = new google.maps.LatLng(
        parseFloat(eventNodes[i].getChildren("address_lat")),
        parseFloat(eventNodes[i].getAttribute("address_long")));

    createOption(title, distance, i);
    createMarker(latlng, title, address);
    bounds.extend(latlng);
  }
  map.fitBounds(bounds);
 });
}

function createMarker(latlng, title, address) {
  var html = "<b>" + title + "</b> <br/>" + address;
  var marker = new google.maps.Marker({
    map: map,
    position: latlng
  });
  google.maps.event.addListener(marker, 'click', function() {
    infoWindow.setContent(html);
    infoWindow.open(map, marker);
  });
  markers.push(marker);
}

function createOption(title, distance, num) {
  var option = document.createElement("option");
  option.value = num;
  option.innerHTML = title + "(" + distance.toFixed(1) + ")";
  locationSelect.appendChild(option);
}

function clearLocations() {
  infoWindow.close();
  for (var i = 0; i < markers.length; i++) {
    markers[i].setMap(null);
  }
  markers.length = 0;

  locationSelect.innerHTML = "";
  var option = document.createElement("option");
  option.value = "none";
  option.innerHTML = "See all results:";
  locationSelect.appendChild(option);
  locationSelect.style.visibility = "visible";
}


</script>
