<?php
$SUPER_START = microtime(true);
//error_log("Reached reservation");
//contains DB connection stuff
require "phplib/db.php";
$mysqli->select_db("btrum818_db");
//required for twilio (SMS service) to function
require "twilio-php-master/Services/Twilio.php";
//account information for twilio
$AccountSid = "AC169d5a950282cc349f94af7987f21c67";
$AuthToken = "61e010b9fb2fcd0b6b4acd5faf9b0dd2";
$client = new Services_Twilio($AccountSid, $AuthToken);
//used for debugging, appends to the web page 
//var_dump($_POST);
//restaurant name
$name = $_POST['name'];
//restaurant phone number, not used currently
//usable to create unique table in the database for
//managing different restaurants
$no = $_POST['number'];
//google maps identifier for the restaurant
$id = $_POST['identifier'];
//user's phone number, used to send text message
$userno = $_POST['user'];
//"MY" phone number
$twiliono = "484-240-4354";
//creates the actual sms request and sends it off 
//$sms = $client->account->messages->sendMessage($twiliono, $userno, "Reservation request for $name, confirm? (reply with C or c)");
//make sure they're not already in the table

//MySQL version
$deletestart = microtime(true);
$result = mysqli_query($mysqli,"DELETE * FROM temp t WHERE t.username=$userno");
echo"Delete: ";
finaltimer($deletestart);
$insertstart = microtime(true);
$result = mysqli_query($mysqli,"INSERT INTO temp (username)VALUES ($userno)");
echo"Insert: ";
finaltimer($insertstart);
//MySQLi prepared statement version
/*
$deletestart = microtime(true);
if($stmt= $mysqli->prepare("DELETE FROM temp  WHERE username=?")){
    $stmt->bind_param("s",$userno);
    $stmt->execute();
}else{
    echo "Failed to prepare delete statement: $mysqli->error \n";
}
echo "Delete timer: ";
finaltimer($deletestart);
$insertstart = microtime(true);
if($stmt2 = $mysqli->prepare("Insert INTO temp (username) VALUES (?)")){
    $stmt2->bind_param("s",$userno);
    $stmt2->execute();
}else{
    echo "Failed to prepare insert statement: $mysqli->error \n";
}
echo"Insert timer: ";
finaltimer($insertstart);
 */
$mysqli->close();

echo"Reservation timer: ";
finalTimer($SUPER_START);
function finalTimer($start){
    $end = microtime(true);
    //echo "$start \n";
    //echo "$end \n";
    echo $end - $start . "s \n";
}
?>
