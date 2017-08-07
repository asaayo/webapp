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
        <link rel="stylesheet" type="text/css" href="css/default.css" media="screen and (min-device-width: 481px)">
        <link rel="stylesheet" type="text/css" href="css/mobile.css" media="only screen and (-webkit-min-device-pixel-ratio: 2)">
        <link href='http://fonts.googleapis.com/css?family=Titillium+Web' rel='stylesheet' type='text/css'>
        <script src="assets/jquery-2.0.3.min.js"></script>
        <script src="http://maps.googleapis.com/maps/api/js?&libraries=places&sensor=false&key=AIzaSyDI-yfR23sWWb5QdLsVxK0dImRt5OJckPU"></script>
        <script src="assets/places.php"></script>
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
            <form >
                <input type="hidden" name="Latitude" id="latitude" value="">
                <input type="hidden" name="Longitude" id="longitude" value="">
            </form>
            <div id="placesMap" class="center"></div>
            <form id="mobilenumber" class="center">
                Mobile Number:<input type="text" id='cell'/>
                <input id="cell" type="button" name="reserve" value="Reserve" onClick="makeReservation()"/>
            </form>
        </div>
        
        <!--<footer>
            <div id="footerbar" class="extendfull">
                Footer content &copy; a guy<br/>
                By reading this, your soul becomes property of a guy.
            </div>
        </footer>-->
        <div id="debug">
            
        </div>
    </body>
</html>