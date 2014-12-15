<?php

//This file reads data that is to be put into a database in an attempt to prevent SQL injection
//If the data is legitimate, it will pass it along, otherwise it will flag it for a developer to check

//entryvalues stores the contents of the POST array passed to the file

//debugging
var_dump($_POST);
$entryvalues = NULL;
$counter = 0;

foreach ($_POST as $key => $value){
    $entryvalues[$key] = $value;
}

testData($entryvalues);

//setup the destination for the data once it has been read
//assumes a key 'destination' is passed via post, which is the location legitimate data should be passed to
//$url = $entryvalues['destination'];
//$response = http_post_data($url, $entryvalues);

function testData($data){
    $goodurl = $data['destination'];
    $badurl = "suspect.php";
    //TODO: implement FANN, use counter to differentiate between variable(s) to test
    $response = http_post_data($goodurl, $entryvalues);
}