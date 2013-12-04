<?php
    header('Content-Type: text/javascript');
    function geolocation($ip){
        //JSON Request with an IP address returns an array
        $url = "http://freegeoip.net/json/".$ip;
        $geo = json_decode(file_get_contents($url), true);
        //Array contains keys for city, region_code, region_name, metrocode, zipcode, longitude, country_name, country_code, ip, latitude. 
        return $geo;
    }
    $ip = $_SERVER["REMOTE_ADDR"];
    $info= geolocation($ip);
    $lat = $info['latitude'];
    $lng = $info['longitude'];
    echo"var finalLat = ",$lat,"\n";
    echo"var finalLng = ",$lng,"\n";
    require('places.js');
?>