var map;
var geocoder;
var markerCluster;

//Code to load the map with center point of Monterey MA
function initMap() {
	var monterey = {lat: 42.181613, lng: -73.215013};
    map = new google.maps.Map(document.getElementById('map'), {
		zoom: 10,
		center: monterey,
		mapTypeId: google.maps.MapTypeId.HYBRID,
		labels: true,
    });

	//collect customer data and geocoder object - declare geocoder as global
    var cusdata = JSON.parse(document.getElementById('data').innerHTML);
    geocoder = new google.maps.Geocoder();  
    codeAddress(cusdata);

    var allData = JSON.parse(document.getElementById('allData').innerHTML);
	showAllCustomers(allData);

	var searchData = JSON.parse(document.getElementById('searchData').innerHTML);
	showSearchedCustomer(searchData)
	
}


function showAllCustomers(allData) {
	//declare info window variable outside of loop to allow to clear when selecting other markers
	var infoWind = new google.maps.InfoWindow;

	Array.prototype.forEach.call(allData, function(data){
		var content = document.createElement('div');
		var strong = document.createElement('strong');
		
		strong.textContent = [data.lastName + ' ' + data.physicalAddress];
		content.appendChild(strong);

		//add image to infowindow - you are also able to add image path to mysql and then append dynamically
		var img = document.createElement('img');
		img.src = 'images/santahat.png';
		img.style.width = '50px';
		content.appendChild(img);

		var markers = [];

		//Create markers for customer locations and customize
		var iconBase = 'https://maps.google.com/mapfiles/kml/shapes/';
		var marker = new google.maps.Marker({
	      position: new google.maps.LatLng(data.latitude, data.longitude),
		  map: map,
		  icon: iconBase + 'homegardenbusiness.png'
		});

		markers.push(marker);
	
		//Create marker clusterer to group data
		markerCluster = new MarkerClusterer(map, markers, {
      imagePath: 'https://developers.google.com/maps/documentation/javascript/examples/markerclusterer/m'
	});
	

		// Add event listener to open info window and show customer name
	    marker.addListener('mouseover', function(){
	    	infoWind.setContent(content);
			infoWind.open(map, marker);
		
		//add event listener to zoom in to clicked customer
		google.maps.event.addListener(marker, 'click', function() {
				map.panTo(this.getPosition());
				map.setZoom(20);
				});  
		});
	}) 

}

//google maps geocoding code for address to collect lat lng from customer addresses
function codeAddress(cusdata) {
   Array.prototype.forEach.call(cusdata, function(data){
    	var address = data.lastName + ' ' + data.physicalAddress;
	    geocoder.geocode( { 'address': address}, function(results, status) {
	      if (status == 'OK') {
			map.setCenter(results[0].geometry.location);

//create object that collects the lat and lng data and pass function to update customers lat lng
	        var points = {};
	        points.id = data.id;
	        points.latitude = map.getCenter().lat();
	        points.longitude = map.getCenter().lng();
	        updateCustomersWithLatLng(points);
		
		//add code to check the result status from geocode request and if we get an OVER_QUERY_LIMIT error we try again after slight delay // Jay 20201208-1015
		} else if (status === google.maps.GeocoderStatus.OVER_QUERY_LIMIT) {    
            setTimeout(function() {
                codeAddress(cusdata);
            }, 100);
         } // else {
        //     alert("Geocode was not successful for the following reason:" 
        //           + status);
        // } 
	    });
	});
}

//create update customers function that updates db using ajax call
function updateCustomersWithLatLng(points) {
	$.ajax({
		url:"action.php",
		method:"post",
		data: points,
		success: function(res) {
			console.log(res)
		}
	})
	
}



//When search customers is clicked create function to zoom in to the searched customer
function showSearchedCustomer(searchData) {

	// var searchedCus = { ? };
	// map = new google.maps.Map(document.getElementById("map"), {
	// 	zoom: 20,
	// 	center: searchedCus,
	//   });

	//declare info window vairable outside of loop to allow to clear if selecting other markers
	var infoWind = new google.maps.InfoWindow;

	Array.prototype.forEach.call(searchData, function(data){
		var content = document.createElement('div');
		var strong = document.createElement('strong');
		
		strong.textContent = [data.lastName + ' ' + data.physicalAddress];
		content.appendChild(strong);

		//add image to infowindow - you are also able to add image path to mysql and then append dynamically
		var img = document.createElement('img');
		img.src = 'images/santahat.png';
		img.style.width = '50px';
		content.appendChild(img);

		//Create marker for searched customer location and customize
		var iconBase = 'https://maps.google.com/mapfiles/kml/shapes/';
		var marker = new google.maps.Marker({	
	      position: new google.maps.LatLng(data.latitude, data.longitude),
		  map: map,
		  icon: iconBase + 'homegardenbusiness.png'
	    });

		// Add event listner to open info window and show customer name
	    marker.addListener('mouseover', function(){
	    	infoWind.setContent(content);
			infoWind.open(map, marker);
		});
	});
}
