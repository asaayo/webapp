<?php
////MySQL variant of connection setup
//date_default_timezone_set('America/New_York');
////db information
//$user = "btrum818";
//$pw = "1.e4c52.Nf3";
//$table = "btrum818_db";
//$connection = mysql_connect("localhost", $user, $pw, $table);
//if(!$connection){
//    echo "Failed to connect to MySQL: " . mysql_error()."\n";
//}else{
//    echo "Connected to DB\n";
//}
//MySQLi variant of connection setup
date_default_timezone_set('America/New_York');
//db information
$user = "btrum818";
$pw = "1.e4c52.Nf3";
$table = "btrum818_db";
$mysqli = mysqli_connect("localhost", $user, $pw, $table);
if(mysqli_connect_errno()){
    echo "Failed to connect " . mysqli_connect_errno();
}else{
    echo "Connected to DB\n";
}
?>
