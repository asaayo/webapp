<?php
//db information
$user = "asaayo_default";
$pw = "KfFsrKHV%36&";
$table = "asaayo_numbers";
$connection = mysqli_connect("localhost", $user, $pw, $table);
if(mysqli_connect_errno($connection)){
    echo "Failed to connect to MySQL: " . mysqli_connect_error();
}
?>
