<?php
//contains db connection stuff
require "phplib/db.php";
//used for debugging, written to the debug console
//required for twilio (SMS service) to function
require "twilio-php-master/Services/Twilio.php";
//account information for twilio
$AccountSid = "AC169d5a950282cc349f94af7987f21c67";
$AuthToken = "61e010b9fb2fcd0b6b4acd5faf9b0dd2";
//$client object represents a request from me
$client = new Services_Twilio($AccountSid, $AuthToken);
//number we're sending the message to
$userno = $_POST['userno'];
//"MY" phone number
$twiliono = "484-240-4354";
//make sure they're still in the DB before we send the text message
$result = $mysqli->query("SELECT * FROM reservations WHERE username = $userno");
$count = mysqli_num_rows($result);
if($count > 0){
    //creates the actual sms request and sends it off
    $sms = $client->account->messages->sendMessage($twiliono, $userno, "Your reservations is ready!");
    $mysqli->query("DELETE FROM reservations WHERE username = $userno" );
    $mysqli->query("INSERT INTO tableready (username) VALUES ($userno)" );
    echo'Kicked ', $userno, ' out of the database and sent a message';
}else{
    //They weren't found in the database, should probably refresh
    echo $userno, ' isn\'t in the database, glom again.';
}
    
?>
