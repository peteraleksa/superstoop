var map;
var markers = [];
var infoWindow;
var locationSelect;

//
// load()
// this is all the setup stuff that needs to run when the page loads
//

function load() {
      map = new google.maps.Map(document.getElementById("map"), {
        center: new google.maps.LatLng(40, -100),
        zoom: 4,
        mapTypeId: 'roadmap',
        mapTypeControlOptions: {style: google.maps.MapTypeControlStyle.DROPDOWN_MENU}
      });
      infoWindow = new google.maps.InfoWindow();

      locationSelect = document.getElementById("locationSelect");
      locationSelect.onchange = function() {
        var markerNum = locationSelect.options[locationSelect.selectedIndex].value;
        if (markerNum != "none"){
          google.maps.event.trigger(markers[markerNum], 'click');
        }
      };
}

//
// searchLocations()
// this gets the latitude and longitude for the address that the user entered 
// it is used as the onclick function and calls searchLocationsNear() with 
// the location data from the geocode
//

function searchLocations() {
  var address = document.getElementById("addressInput").value;
  var geocoder = new google.maps.Geocoder();
  geocoder.geocode({address: address}, function(results, status) {
    if (status == google.maps.GeocoderStatus.OK) {
      searchLocationsNear(results[0].geometry.location);
    } else {
      //alert(address + ' not found');
    }
  });
}

// 
// downloadUrl()
// this is used to get the xml from the php search function
//
 
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

//
// searchLocationsNear()
// this function calls downloadUrl and sets the search process in motion,
// then parses the xml results that are returned and calls
// createMarker() to create the actual map markers based on the results
//

function searchLocationsNear(center) {
  clearLocations();

  var radius = document.getElementById('radiusSelect').value;
  var searchUrl = 'sales_search_results.php?address_lat=' + center.lat() + '&address_long=' + center.lng() + '&radius=' + radius; 

  downloadUrl(searchUrl, function(data) {
  	var xml = parseXML(data);
	//debug
	//alert(xml);

  	var eventNodes = xml.documentElement.getElementsByTagName("event");
  	var bounds = new google.maps.LatLngBounds();
  	
	// debug
	//alert(eventNodes.length);
	
	parser = new DOMParser();

 	for (var i = 0; i < eventNodes.length; i++) {

    		var event_id = xml.getElementsByTagName("event_id")[i].childNodes[0].nodeValue;
    		var title = xml.getElementsByTagName("title")[i].childNodes[0].nodeValue;
    		var date = xml.getElementsByTagName("date")[i].childNodes[0].nodeValue;
    		var category = xml.getElementsByTagName("category")[i].childNodes[0].nodeValue;
		var address = xml.getElementsByTagName("address_num")[i].childNodes[0].nodeValue
			+ ' ' + xml.getElementsByTagName("address_street")[i].childNodes[0].nodeValue
			+ ' ' + xml.getElementsByTagName("address_city")[i].childNodes[0].nodeValue
			+ ', ' + xml.getElementsByTagName("address_state")[i].childNodes[0].nodeValue
			+ ' ' + xml.getElementsByTagName("address_zip")[i].childNodes[0].nodeValue;
 
		if(xml.getElementsByTagName("distance")[i].childNodes[0].nodeValue)	
    			var distance = parseFloat(xml.getElementsByTagName("distance")[i].childNodes[0].nodeValue);

    		var latlng = new google.maps.LatLng(
  	      	parseFloat(xml.getElementsByTagName("address_lat")[i].childNodes[0].nodeValue),
        	parseFloat(xml.getElementsByTagName("address_long")[i].childNodes[0].nodeValue)
		);
		
		if(xml.getElementsByTagName("owner"))
			var owner = xml.getElementsByTagName("owner")[i].childNodes[0].nodeValue;
		if(xml.getElementsByTagName("ownerUsername")[i])
			var ownerUsername = xml.getElementsByTagName("owner_username")[i].childNodes[0].nodeValue;		

		var markerCreated = false;
			for(var j = 0; j < 3; j++) {

				//var element = document.getElementsByName("saleType")[j];
				//if(j == 2) {
				//	var escapedCat = element.value;
				//	escapedCat.replace("'", "&#39;");
				//}
				//alert("Category " + j + ": " + category);
				//alert("From form " + j + ": " + escapedCat);	


				//if(!markerCreated 
				//   && element.checked 
                                //   && category == element.value)	
				//{
					createOption(title, distance, i);
					createMarker(latlng, title, address, owner, ownerUsername, event_id, date, category, distance);
                        		bounds.extend(latlng);
					markerCreated = true;
				//}
			}
		//}
  	}

  	map.fitBounds(bounds);

 	});
 }

//
// createMarker()
// this function creates a marker for the event
//

function createMarker(latlng, title, address, owner, ownerUsername, id, date, category, distance) {
  var html = "<a href=\"./sale.php?id=" + id + "\" ><b>" + title + "</b></a>" 
		+ "<br/> By: " + owner +  "<br/>" + ownerUsername
		+ "<br/>" + address
		+ "<br/> Category: " + category 
		+ "<br/>Date: " + date; 
  
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

//
// createOption()
// this function creates dropdown options for the map results
// the google maps api suggested using this design for mobile compatibility
//

function createOption(title, distance, num) {
  var option = document.createElement("option");
  option.value = num;
  option.innerHTML = title + "(" + distance.toFixed(1) + ")";
  locationSelect.appendChild(option);
}

//
// clearLocations()
// clears the markers, options, location
//

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

// 
// parseXML()
// this parses a string to xml using the DOMParser object

function parseXML(str) {
      if (window.ActiveXObject) {
        var doc = new ActiveXObject('Microsoft.XMLDOM');
        doc.loadXML(str);
        return doc;
      } else if (window.DOMParser) {
        return (new DOMParser).parseFromString(str, 'text/xml');
      }
    }

//
// doNothing()
// this function is good for nothing
//

function doNothing() {}
