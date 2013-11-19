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
        <script type="text/javascript" src="http://maps.googleapis.com/maps/api/js?libraries=places&sensor=true"></script>
        <script>
            var success = false;
            var loc1;
            var loc2;
            var map;
            var service;
            var infowindow;
            
            //findLocation takes two functions as its arguments
            function findLocation(){
                navigator.geolocation.getCurrentPosition(foundLocation, noLocation);
            }
            //foundLocation is used if location information is available
            function foundLocation(position){
                var lat = position.coords.latitude;
                var lon = position.coords.longitude;
                loc1 = lat+","+lon;
                var lat2 = <?= $info['latitude'];?>;
                var lon2 = <?= $info['longitude'];?>;
                loc2 = lat2+","+lon2;
                document.getElementById("latitude").value=lat;
                document.getElementById("longitude").value=lon;
                var url1 = "http://maps.googleapis.com/maps/api/staticmap?center=" + loc1 + "&zoom=12&size=400x400&sensor=false&markers=color:red%7C" + loc1;
                var url2 = "http://maps.googleapis.com/maps/api/staticmap?center=" + loc2 + "&zoom=12&size=400x400&sensor=false&markers=color:red%7C" + loc2;
                document.getElementById("map1").src = url1;
                document.getElementById("map2").src = url2;
                success=true;
            }
            //else noLocation is used
            function noLocation(){
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
                document.getElementById("mainbody").style.display=("none");
                document.getElementById("p2").style.display=("inline");
                document.getElementById("p2").className ="bodyclass";
            }
            /*
            function initialize(){
                if(success)
                    var curLoc = new google.maps.LatLng(loc1);
                else
                    var curLoc = new google.maps.LatLng(loc2);
                    map = new google.maps.Map(document.getElementById('map'),{center: curLoc;zoom: 15;});
                    var request = {location: curLoc;radius: '5000';types: ['restaurant']};
                    service = new google,maps.places.PlacesService(map);
                    service.nearbySearch(request, callback);   
            }
            function callback(results, status){
                if(status == google.maps.places.PlacesServiceStats.OK){
                    for(var i = 0; i < results.length; i++){
                        var place = results[i];
                        createMarker(results[i]);
                    }   
                }
            }*/
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
        <div id="mainbody" class="bodyclass">
            <p>
                This part of the application gets user location via HTML5's geolocation
            </p>
            <p class="righty">
                For reference, this image uses IP geolocation
            </p>
            <form>
                Latitude:<input type="text" name="Latitude" id="latitude" value=""><br/>
                Longitude:<input type="text" name="Longitude" id="longitude" value=""><br/>
                <input type="button" value='Find Me' id='findme' onclick='javascript:findLocation();'/><br/>
                <img id="map1" class="lefty" src="" />
                <img id="map2" class="righty" src="" />
            </form>
            <br style="clear:both"/>
            <p>Find restaurants near me:</P>
            <input type="button" value="Find Restaurants" id="restaurants" onclick="javascript:toNext();"/>
        </div>
        <div id="p2">
            
        </div>
        <footer>
            <div id="footerbar" class="extendfull">
                Footer content &copy; a guy<br/>
                By reading this, your soul becomes property of a guy.
            </div>
        </footer>
    </body>
</html>