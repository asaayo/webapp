<?php

//This file reads data that is to be put into a database in an attempt to prevent SQL injection
//If the data is legitimate, it will pass it along, otherwise it will flag it for a developer to check

//entryvalues stores the contents of the POST array passed to the file

//debugging
//var_dump($_POST);
//hardcoding some entries to make testing possible
$good_url = "reservation.php";
$bad_url = "suspect.php";
$entry_values = $_POST;

$counter = 0;
//Due to the way neural networks work, I have to have a way to convert strings to numerical input
//I choose to use to statistical significance of the occurence of characters in a string
//countarray[0] = alpha, countarray[1] = numerical, countarray[2] = special characters,
//countarray[3] = other/space, countarray[4] = number of characters
$countarray = array(0,0,0,0,0);

trainNeuralNet($entry_values, count($_POST)-2);

//setup the destination for the data once it has been read
//assumes a key 'destination' is passed via post, which is the location legitimate data should be passed to
//$url = $entry_values['destination'];
//$response = http_post_data($url, $entry_values);

function trainNeuralNet($data, $neuralNets){
    //Testing values here
    $input_test = "Test words here' OR '1'=1";
    //Values for setting up the neural network(s)
    $num_input = 5;
    $num_output = 1;
    $num_layers = 3;
    $num_neurons_hidden = 3;
    $desired_error = 0.0001;
    $max_epochs = 1000000;
    $epochs_between_reports = 1000;
    //get the keys of the array so we know which files to create/read
    $keys = array("name","number","identifier","user","bad","good");
    $input_file = "training data/".$keys[0]."~";
    $output_file = "trained nets/".$keys[0];
    echo $input_file."\n";
    //$ann[$neuralNets];
    
    //create neural networ (starting with one for testing)
    //more debugging
    //var_dump($data);
    $ann = fann_create_standard($num_layers, $num_input, $num_neurons_hidden, $num_output);
//    if(file_exists($output_file)){
//        testNeuralNet($data[$keys[0]], $output_file);
//    }
    if(fileHandler($output_file, $ann)){
        echo"ANN already exists!\n";
        //testNeuralNet($input_test, $output_file);
        testNeuralNet($data[$keys[0]], $output_file);
    }
    else if ($ann){
        echo "Created ANN!\n";
        fann_set_activation_function_hidden($ann, FANN_SIGMOID_SYMMETRIC);
        fann_set_activation_function_output($ann, FANN_SIGMOID_SYMMETRIC);
        
        if (fann_train_on_file($ann, $input_file, $max_epochs, $epochs_between_reports, $desired_error)){
            echo "Trained on data!\n";
            fileHandler($output_file, $ann);
            fann_destroy($ann);
            testNeuralNet($data[$keys[0]], $output_file);
        }else{
            echo "Training on file failed!\n";
        }             
    }else{
        echo "Could not create ANN!\n";
    }   
}

function testNeuralNet($data, $train_File){
    toCharArray($data);
    echo $data."\n";
    if(file_exists($train_File)){
        $ann = fann_create_from_file($train_File);
        if($ann){
            global $countarray;
            $input = $countarray;
            foreach($input as $key => $value)
                echo $key." => ".$value."\n";
            //all the debugging!
            //var_dump($countarray);
            $calc_output = fann_run($ann, $input);
            printf("Output: (%f)\n", $calc_output[0]);
            fann_destroy($ann);
            global $good_url, $bad_url, $entry_values;
            if($calc_output[0] >= .250){
                finishingUp($good_url, $entry_values);                
            }else{
                finishingUp($bad_url, $entry_values);
            }
        }
    }
    
}

//Filehandler handles the creation of the neural net files
//If file doesn't already exist, filehandler creates it
function fileHandler($filename, $ann){
    //returns true if file exists or is created, false otherwise
    if(file_exists($filename)){
        //echo"ANN already exists! \n";
        return true;
//        if(is_writable($filename)){
//            fann_save($ann, $filename);
//            echo("File successfully saved \n");
//        }else{
//            die("Cannot write to file to create ANN!\n".$filename."\n");
//        }
    }else{
        echo "File does not exist, creating! \n";
        $fh = fopen($filename, 'w');
        fclose($fh);
        chmod($filename, 0777);
        if(is_writable($filename)){
            fann_save($ann, $filename);
            echo("File successfully saved \n");
            return false;
        }else{
            die("Cannot write to file to create ANN!\n".$filename."\n");
            return false;
        }
    }
    
}

function toCharArray($input){
    $temp = str_split($input);
    $count = strlen($input);
    echo $count."\n";
    global $countarray;
    $countarray[4]=$count;
    foreach($temp as $value){        
        $tempvalue = ord($value);
        if(($tempvalue >= 65 && $tempvalue <= 90) || ($tempvalue >= 97 && $tempvalue <= 122)){
            $countarray[0]++;
        }else if ($tempvalue >= 48 && $tempvalue <= 57){
            $countarray[1]++;
        }else if ($tempvalue >= 33 && $tempvalue <= 127){
            $countarray[2]++;
        }else{
            $countarray[3]++;
        }
    }
    for($i = 0; $i <= 3; $i++){
        $countarray[$i] = $countarray[$i] / $count;
    }
    
}

function finishingUp($destination, $data){
    $data_string = "";
    echo ("Finishing up, destination: ".$destination."\n");
    foreach($data as $key=>$value) { $data_string .= $key.'='.$value.'&'; }
    rtrim($data_string,'&');
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, "http://acad.kutztown.edu/~btrum818/".$destination);
    curl_setopt($ch, CURLOPT_POST, count($data));
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
    $result = curl_exec($ch);
    if(curl_errno($ch)){
        echo 'Curl error: ' . curl_error($ch);
    }
    //echo $result;
}