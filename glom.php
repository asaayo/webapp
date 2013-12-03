<?php
require "phplib/db.php";
//Glom everything from the DB periodically
$result = mysqli_query($connection,"SELECT * FROM reservations ORDER BY curtime");
$rowcount = mysqli_num_rows($result);
$names;
$times;
$return;
if($result !== false){
    for($x = 0; $x < $rowcount; $x++){
        $row = mysqli_fetch_array($result);
        $names[$x] = $row['username'];
        $times[$x] = $row['curtime'];
    }
}
$return[0] = $names;
$return[1] = $times;

echo json_encode($return);

?>
