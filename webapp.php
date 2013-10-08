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
    foreach($info as $key => $value){
        echo "$key 0:  $value <br/>";
    }
?>
<!doctype HTML>
<html>
    <title>WebApp</title>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <link rel="stylesheet" type="text/css" href="css/default.css" media="screen and (min-device-width: 481px)">
        <link rel="stylesheet" type="text/css" href="css/mobile.css" media="only screen and (-webkit-min-device-pixel-ratio: 2)">
    </head>
    <body>
        <div id="header">
       	<h1 class="left">A website by a guy</h1>
        <p class="righty">An experience in webdev</p>
        </div>
        <div id="headerbar" class="extendfull">
            <a href="index.php">Main page</a>
            <a href="webapp.php">WebApp</a>
            <a href="">Link 3</a>
        </div>
        <div id="mainbody">
            <form>
                Phone number:<input type='text' name='phoneno' id='inputbox' size='9'><br/>
                Carrier:
                <select id='inputbox'>
                    <option value='ATT'>AT&AMP;T</option>  
                    <option value='VZ'>Verizon</option>
                    <option value='SP'>Sprint</option>
                </select>
            </form>
        </div>
        <footer>
            <div id="footerbar" class="extendfull">
                Footer content &copy; a guy<br/>
                By reading this, your soul becomes property of a guy.
            </div>
        </footer>
    </body>
</html>
