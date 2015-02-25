var success = false;
var urlp1="http://maps.googleapis.com/maps/api/staticmap?center=";
var urlp2="&zoom=12&size=400x400&sensor=true&markers=color:red%7C";
var map;
var service;
var infowindow;
var placename;
var placeno;
var placeid;

//findLocation takes two functions as its arguments
function findLocation(){
    navigator.geolocation.getCurrentPosition(foundLocation, noLocation);
}
//foundLocation is used if location information is available
function foundLocation(position){
    finalLat = position.coords.latitude.toFixed(7);
    finalLng = position.coords.longitude.toFixed(7);
    document.getElementById("latitude").value=finalLat;
    document.getElementById("longitude").value=finalLng;
    //var url1 = urlp1 + loc1 + urlp2 + loc1;
    //var url2 = urlp1 + loc2 + urlp2 + loc2;
    //document.getElementById("map1").src = url1;
    //document.getElementById("map2").src = url2;
    initialize();
}
//else noLocation is used
function noLocation(error){
    switch(error.code)
    {
        case error.PERMISSION_DENIED:
            alert("User denied Geolocation request.");
            break;
        case error.POSITION_UNAVAILABLE:
            alert("Location information unavailable");
            break;
        case error.TIMEOUT:
            alert("The request for user information timed out");
            break;
        case error.UNKNOWN_ERROR:
            alert("An unknown error occurred");
            break;
    }
    initialize();
}
/*
function toNext(){
    document.getElementById("p1").style.display=("none");
    document.getElementById("p2").style.display=("inline");
    document.getElementById("p2").className ="bodyclass";
    /*
    if(success)
        document.getElementById("map").src=urlp1 + loc1 + urlp2 + loc1;
    else
        document.getElementById("map").src=urlp1 + loc2 + urlp2 + loc2;
}*/

//initializes google map and places integration
function initialize(){
    console.log(finalLat + " " + finalLng);
    //current location is based on HTML5 geolocation, or if that fails, IP Geolocation
    var curLoc = new google.maps.LatLng(finalLat, finalLng);
    //the actual map, centered on the current location with zoom level
    map = new google.maps.Map(document.getElementById('placesMap'),{
        center: curLoc,
        zoom: 13
    });
    //right click allows you to recenter the map and resubmit query
    google.maps.event.addListener(map, "rightclick", function(event) {
    finalLat = event.latLng.lat();
    finalLng = event.latLng.lng();
    // re-init map with new lag/lng
    console.log(finalLat + " " + finalLng);
    initialize();
    });
    //the request to be made of google maps
    var request = {
        location: curLoc,
        radius: 10000,
        query: 'restaurant'
    };
    //infowindow is the little popup box when you click a marker
    infowindow = new google.maps.InfoWindow();
    //service is the request to make
    service = new google.maps.places.PlacesService(map);
    //makes an API call to google places using the previously defined request
    //callback method is used to handle results
    service.textSearch(request, callbackSearch);   
};

function callbackSearch(results, status){
    //if everything's good, we get 20 places as a response
    if (status === google.maps.places.PlacesServiceStatus.OK) {
        //throw them all into the map
        for(var i = 0; i < results.length; i++){
            createMarker(results[i]);
        }   
    }
}
//creates the actual markers for the places
function createMarker(place){
    //the map (previously defined) to place the marker on, and the location
    //to place it
    var marker = new google.maps.Marker({
        map: map,
        position: place.geometry.location
    });
    //adds a listener to the map to get clicks and pull extra details
    //to display when a marker is clicked
    google.maps.event.addListener(marker, 'click', function() {
        //pops up the previously mentioned infowindow
        infowindow.setContent(place.name);
        infowindow.open(map, this);
        //new request to get more detailed information about a place
        var request ={reference: place.reference};
        service.getDetails(request, function(details, status){
            if(status === google.maps.places.PlacesServiceStatus.OK){
                //store the details
                placename = details.name;
                placeno = details.formatted_phone_number.replace(/[^\w]/gi, '');
                placeid = details.id;
                infowindow.setContent(details.name + "<br />" + details.formatted_address +"<br />" + details.website + "<br />" + details.formatted_phone_number + "<br/>" + details.rating);
                infowindow.open(map, this);
            }
        });
    });
}

function makeReservation(){
    //pulls input user number and strips any formatting
    userno = document.getElementById('cell').value.replace(/[^\w]/gi, '');
    //basic error checking
    if(userno === ""){
        alert("Unable to make reservation without a valid number");
    }else{
        //jquery ajax request, much easier than writing it myself
        $.ajax({
            type:"POST",
            //reservation.php handles the beginning of the reservation process
            //handles user and location data, sends first text message and
            //inputs data into a temporary table
            //url: "reservation.php",
            //Changing this to pass through the FANN bridge
            //url: "FANNBridge.php",
            url:"reservation.php",
            data: {name: placename, number: placeno, identifier: placeid, user: userno, destination: "reservation.php"}
        }).done(function(msg){
            console.log("Data saved: " + msg);
        });
    }
}
google.maps.event.addDomListener(window, 'load', findLocation());

