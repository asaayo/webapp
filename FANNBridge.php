<?php

//This file reads data that is to be put into a database in an attempt to prevent SQL injection
//If the data is legitimate, it will pass it along, otherwise it will flag it for a developer to check

//entryvalues stores the contents of the POST array passed to the file

//debugging
var_dump($_POST);
$good_url = $_POST['good'];
$bad_url = $_POST['bad'];
$entry_values = $_POST;
$counter = 0;

trainNeuralNet($entry_values, count($_POST)-2);

//setup the destination for the data once it has been read
//assumes a key 'destination' is passed via post, which is the location legitimate data should be passed to
//$url = $entry_values['destination'];
//$response = http_post_data($url, $entry_values);

function trainNeuralNet($data, $neuralNets){
    //Values for setting up the neural network(s)
    $num_input = 2;
    $num_output = 1;
    $num_layers = 3;
    $num_neurons_hidden = 3;
    $desired_error = 0.0001;
    $max_epochs = 1000000;
    $epochs_between_reports = 1000;
    #get the keys of the array so we know which files to create/read
    $keys = array_keys($data);
    $input_file = "training data/".$keys[0];
    $output_file = "trained nets/".$keys[0];
    #$ann[$neuralNets];
    
    #create neural networ (starting with one for testing)
    var_dump($data);
    $ann = fann_create_standard($num_layers, $num_input, $num_neurons_hidden, $num_output);
//    if(file_exists($output_file)){
//        testNeuralNet($data[$keys[0]], $output_file);
//    }
    if($ann){
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
    if(file_exists($train_File)){
        $ann = fann_create_from_file($train_File);
        if($ann){
            #echo ($data."\n");
            $input = array(-1, $data);
            $calc_output = fann_run($ann, $input);
            printf("test (%f, %s) -> (%f)\n", $input[0], $input[1], $calc_output[0]);
            fann_destroy($ann);
        }
    }
    
}

#Filehandler handles the creation of the neural net files
#If file doesn't already exist, filehandler creates the file then re-calls 
#itself to save the neural net
function fileHandler($filename, $ann){
    if(file_exists($filename)){
        if(is_writable($filename)){
            fann_save($ann, $filename);
            echo("File successfully saved \n");
        }else{
            die("Cannot write to file to create ANN!\n".$filename."\n");
        }
    }else{
        echo "File does not exist, creating! \n";
        $fh = fopen($filename, 'w');
        fclose($fh);
        chmod($filename, 0777);
        if(is_writable($filename)){
            fann_save($ann, $filename);
            echo("File successfully saved \n");
        }else{
            die("Cannot write to file to create ANN!\n".$filename."\n");
        }
    }
    
}

function finishingUp($destination, $data){
    #acad doesn't support http_post_data, so I had to switch to using HTTP context
    #$response = http_post_data($goodURL, $data);
    # Form our options
    
    $opts = array('http' =>
        array(
            'method'  => 'POST',
            'header'  => 'Content-type: application/x-www-form-urlencoded',
            'content' => $data
        )
    );
    # Create the context
    $context = stream_context_create($opts);
    # Get the response (you can use this for GET)
    $result = file_get_contents($destination, false, $context);
    echo $result."\n";
}