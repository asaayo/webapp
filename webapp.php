<?php
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
        <script>
            //findLocation takes two functions as its arguments
            function findLocation(){
                navigator.geolocation.getCurrentPosition(foundLocation, noLocation);
            }
            //foundLocation is used if location information is available
            function foundLocation(position){
                var lat = position.coords.latitude;
                var lon = position.coords.longitude;
                document.getElementById("latitude").value=lat;
                document.getElementById("longitude").value=lon;
                var url = "http://maps.googleapis.com/maps/api/staticmap?center=" + lat + "," + lon + "&zoom=12&size=400x400&sensor=false&";
                url += "markers=color:red%7C" + lat + "," + lon;
                document.getElementById("Map").src = url;
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
        <div id="mainbody">
            <p>
                This part of the application gets user location via HTML5's geolocation
            </p>
            <form>
                Latitude:<input type="text" name="Latitude" id="latitude" value=""><br/>
                Longitude:<input type="text" name="Longitude" id="longitude" value=""><br/>
                <input type="button" value='Find Me' id='findme' onclick='javascript:findLocation();'/><br/>
                <img id ="Map" src="" />
            </form>
            
        </div>
        <footer>
            <div id="footerbar" class="extendfull">
                Footer content &copy; a guy<br/>
                By reading this, your soul becomes property of a guy.
            </div>
        </footer>
        <?php
            echo "Zipcode : $info[zipcode]";
        ?>
    </body>
</html>
