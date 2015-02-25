<?php
//connection information
require "phplib/db.php";
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
//Get the values in the table corresponding to the from number
$result = $mysql->query("SELECT * FROM temp WHERE username = $FROM");
//Make sure they're IN the table
$count = count($result);
//Should be replying with C/c to confirm reservation
if($BODY == "C" || $BODY == "c"){
    //Set the reply message
    $msg = "Thanks for confirming, we look forward to seeing you.";
    if($count > 0){
        if($result !== false){
            $row = mysqli_fetch_array($result);
            $time = $row['curtime'];
            //delete from temp table
            $mysql->query("DELETE FROM temp where username= $FROM");
            //make sure there are no duplicate entries
            //I know there are better ways of doing this but I want to get this working
            $mysql->query("DELETE FROM reservations WHERE username = $FROM");
            //insert into reservation table
            $mysql->query("INSERT INTO reservations (username)VALUES ($FROM)");
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