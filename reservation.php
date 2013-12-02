<?php
require "../twilio-php-master/Services/Twilio.php";
$AccountSid = "AC169d5a950282cc349f94af7987f21c67";
$AuthToken = "61e010b9fb2fcd0b6b4acd5faf9b0dd2";
$client = new Services_Twilio($AccountSid, $AuthToken);
echo'hello world';
var_dump($_POST);
$name = $_POST['name'];
$no = $_POST['number'];
$id = $_POST['identifier'];
$userno = $_POST['user'];
$twiliono = "484-240-4354";
$user = "asaayo_default";
$pw = "KfFsrKHV%36&";
$table = "asaayo_numbers";
$connection = mysqli_connect("localhost", $user, $pw, $table);
if(mysqli_connect_errno($connection)){
    echo "Failed to connect to MySQL: " . mysqli_connect_error();
}
$sms = $client->account->messages->sendMessage($twiliono, $userno, "Reservation request for X, confirm? (reply with C or c)");
mysqli_query($connection,"INSERT INTO temp (username)
        VALUES ($userno)");
?>
