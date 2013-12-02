/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

var success = false;
var urlp1="http://maps.googleapis.com/maps/api/staticmap?center=";
var urlp2="&zoom=12&size=400x400&sensor=true&markers=color:red%7C";
var finalLat;
var finalLng;
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
function noLocation(){
    finalLat = finalLat.toFixed(7);
    finalLng = finalLng.toFixed(7);
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

function initialize(){
    var curLoc = new google.maps.LatLng(finalLat, finalLng);
    map = new google.maps.Map(document.getElementById('placesMap'),{
        center: curLoc,
        zoom: 13
    });
    var request = {
        location: curLoc,
        radius: 10000,
        query: 'restaurant'
    };
    infowindow = new google.maps.InfoWindow();
    service = new google.maps.places.PlacesService(map);
    service.textSearch(request, callbackSearch);   
};

function callbackSearch(results, status){
    if (status === google.maps.places.PlacesServiceStatus.OK) {
        for(var i = 0; i < results.length; i++){
            createMarker(results[i]);
        }   
    }
}
function createMarker(place){
    var marker = new google.maps.Marker({
        map: map,
        position: place.geometry.location
    });
    google.maps.event.addListener(marker, 'click', function() {
        infowindow.setContent(place.name);
        infowindow.open(map, this);
        var request ={reference: place.reference};
        service.getDetails(request, function(details, status){
            if(status === google.maps.places.PlacesServiceStatus.OK){
                placename = details.name;
                placeno = details.formatted_phone_number.replace(/[^\w]/gi, '');
                placeid = details.id;
                infowindow.setContent(details.name + "<br />" + details.formatted_address +"<br />" + details.website + "<br />" + details.formatted_phone_number + "<br/>" + details.international_phone_number);
                infowindow.open(map, this);
            }
        });
    });
}

function makeReservation(){
    userno = document.getElementById('cell').value.replace(/[^\w]/gi, '');
    if(userno === ""){
        alert("Unable to make reservation without a valid number");
    }else{
        $.ajax({
            type:"POST",
            url: "reservation.php",
            data: {name: placename, number: placeno, identifier: placeid, user: userno},
            success: function(data){
                $("#debug").html(data);}
        }).done(function(msg){
            console.log("Data saved: " + msg);
        });
    }
}
google.maps.event.addDomListener(window, 'load', findLocation());

