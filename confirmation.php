<?php
error_reporting(-1);
//Twilio replies are XML based
header("content-type: text/xml");
echo "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n";
//From is the number replying
$FROM = $_POST['From'];
//Stripping the + 
$FROM = substr($FROM,2);
//Body is the message
$BODY = $_POST['Body'];
$msg = "Timeout";
//db information
$user = "asaayo_default";
$pw = "KfFsrKHV%36&";
$table = "asaayo_numbers";
$connection = mysqli_connect("localhost", $user, $pw, $table);
if(mysqli_connect_errno($connection)){
    echo "Failed to connect to MySQL: " . mysqli_connect_error();
}
//Get the values in the table corresponding to the from number
$result = mysqli_query($connection,"SELECT * FROM temp WHERE username = $FROM");
//Make sure they're IN the table
$count = mysqli_affected_rows($connection);
//Should be replying with C/c to confirm reservation
if($BODY == "C" || $BODY == "c"){
    //Set the reply message
    $msg = "Thanks for confirming, we look forward to seeing you.";
    if($count > 0){
        if($result !== false){
            $row = mysqli_fetch_array($result);
            $time = $row['curtime'];
            //delete from temp table
            mysqli_query($connection, "DELETE FROM temp where username= $FROM");
            //make sure there are no duplicate entries
            //I know there are better ways of doing this but I want to get this working
            mysqli_query($connection, "DELETE FROM reservation WHERE username = $FROM");
            //insert into reservation table
            mysqli_query($connection, "INSERT INTO reservations (username)
                VALUES ($FROM)");
        }else
            $msg = "DB result invalid, helper monkeys have been dispatched to figure out what happened."; 
    }else{
        $msg = "You weren't found in our database, helper monkeys have been dispatched to figure out what happened.";
    }
}else
    $msg="Not hungry? Alright then, reservation cancelled";
?>
<Response>
    <Message><?= $msg ?></Message>
</Response>