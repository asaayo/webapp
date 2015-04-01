<?php

//var_dump($_POST);
//requred for database access
require("dp.php");
$mysqli->select_db("suspect");
//required for twilio (SMS service) to function
require "twilio-php-master/Services/Twilio.php";
//account information for twilio
$AccountSid = "AC169d5a950282cc349f94af7987f21c67";
$AuthToken = "61e010b9fb2fcd0b6b4acd5faf9b0dd2";
$client = new Services_Twilio($AccountSid, $AuthToken);
//build the suspect query to be inspected
//restaurant name
$name = $_POST['name'];
//restaurant phone number, not used currently
//usable to create unique table in the database for
//managing different restaurants
$no = $_POST['number'];
//google maps identifier for the restaurant
$id = $_POST['identifier'];
//developer's phone number, SET HERE
$DEV_NO = "6105734599";
//"MY" phone number
$twiliono = "484-240-4354";
//creates the actual sms request and sends it off 

$stmt = $mysql->query("INSERT INTO Suspect (query) values (?)");
$stmt->bind('s', $query);
$stmt->execute;

//$sms = $client->account->messages->sendMessage($twiliono, $DEV_NO, "Suspect query follows: ".$query);

?>