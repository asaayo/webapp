<?php
date_default_timezone_set('America/New_York');
//db information
$user = "btrum818";
$pw = "1.e4c52.Nf3";
$table = "btrum818_db";
$connection = mysql_connect("localhost", $user, $pw, $table);
if(!$connection){
    echo "Failed to connect to MySQL: " . mysql_error()."\n";
}else{
    echo "Connected to DB\n";
}
?>
