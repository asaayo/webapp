<?php
    $user = "asaayo_default";
    $pw = "KfFsrKHV%36&";
    $table = "asaayo_numbers";
    
    $connection = mysqli_connect("localhost", $user, $pw, $table);
    if(mysqli_connect_errno($connection)){
        echo "Failed to connect to MySQL: " . mysqli_connect_error();
    }
    function geolocation($ip){
        //JSON Request with an IP address returns an array
        $url = "http://freegeoip.net/json/".$ip;
        $geo = json_decode(file_get_contents($url), true);
        //Array contains keys for city, region_code, region_name, metrocode, zipcode, longitude, country_name, country_code, ip, latitude. 
        return $geo;
    }
    $ip = $_SERVER["REMOTE_ADDR"];
    $info= geolocation($ip);
?>
<!doctype HTML>
<html>
    <head>
    <title>WebApp</title>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
        <link rel="stylesheet" type="text/css" href="css/default.css" media="screen and (min-device-width: 481px)"/>
        <link rel="stylesheet" type="text/css" href="css/mobile.css" media="only screen and (-webkit-min-device-pixel-ratio: 2)"/>
        <script type="text/javascript" src="http://maps.googleapis.com/maps/api/js?&libraries=places&sensor=false&key=AIzaSyA5DJty9iEXMIPtaSzyqoX1lpDdK4GQPyA"></script>
<script>
    var success = false;
    var urlp1="http://maps.googleapis.com/maps/api/staticmap?center=";
    var urlp2="&zoom=12&size=400x400&sensor=true&markers=color:red%7C";
    var finalLat;
    var finalLng;
    var map;
    var service;
    var infowindow;

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
        var finalLat = <?= $info['latitude'];?>;
        finalLat = finalLat.toFixed(7);
        var finalLng = <?= $info['longitude'];?>;
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
    function toNext(){
        /*
        document.getElementById("p1").style.display=("none");
        document.getElementById("p2").style.display=("inline");
        document.getElementById("p2").className ="bodyclass";
        /*
        if(success)
            document.getElementById("map").src=urlp1 + loc1 + urlp2 + loc1;
        else
            document.getElementById("map").src=urlp1 + loc2 + urlp2 + loc2;
            */
    }
    function initialize(){
        var curLoc = new google.maps.LatLng(finalLat, finalLng);
        map = new google.maps.Map(document.getElementById('placesMap'),{
            center: curLoc,
            zoom: 13
        });
        var request = {
            location: curLoc,
            radius: 10000,
            maxPriceLevel: 3,
            types: ['restaurant'],
            rankby: 'distance'
        };
        infowindow = new google.maps.InfoWindow();
        var service = new google.maps.places.PlacesService(map);
        service.nearbySearch(request, callback);   
    };
    function callback(results, status){
        /*
        var returned = google.maps.places.PlacesServiceStatus;
        var txt;
        for (x in returned)
            txt += x + " " + returned[x] + "\n";
        alert(txt);
        */
        if (status == google.maps.places.PlacesServiceStatus.OK) {
            for(var i = 0; i < results.length; i++){
                createMarker(results[i]);
            }   
        }
    }
    function createMarker(place){
        var placeLoc = place.geometry.location;
        var marker = new google.maps.Marker({
            map: map,
            position: placeLoc
        });

        google.maps.event.addListener(marker, 'click', function() {
            infowindow.setContent(place.name);
            infowindow.open(map, this);
        });
    }
        </script>
    </head>
    <body>
        <div id="header">
               <h1 class="left">A website by a guy</h1>
        <p class="righty">An experience in webdev</p>
        </div>
        <div id="headerbar" class="extendfull">
            <a href="index.php">Main page</a>
            <a href="webapp.php">WebApp</a>
            <a href="assets/resume.pdf">R&eacute;sum&eacute;</a>
        </div>
        <div id="p1" class="mainbody">
            <form>
                Latitude:<input type="text" name="Latitude" id="latitude" value="" class="lefty">
                Longitude:<input type="text" name="Longitude" id="longitude" value="">
                <input type="button" value='Find me some grub!' id='findme' onclick='javascript:findLocation();'/><br/>
            </form>
            <div id="placesMap" style="width:80%; clear:both; float:left; height:600px;"></div>
        </div>
        
        <footer>
            <div id="footerbar" class="extendfull">
                Footer content &copy; a guy<br/>
                By reading this, your soul becomes property of a guy.
            </div>
        </footer>
    </body>
</html>