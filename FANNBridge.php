<?php

//This file reads data that is to be put into a database in an attempt to prevent SQL injection
//If the data is legitimate, it will pass it along, otherwise it will flag it for a developer to check

//entryvalues stores the contents of the POST array passed to the file

//debugging
var_dump($_POST);
$goodURL = $_POST['good'];
$badURL = $_POST['bad'];
$entryValues = NULL;
$counter = 0;

foreach ($_POST as $key => $value){
    $entryValues[$key] = $value;
}

trainNeuralNet($entryValues, count($_POST)-2);

//setup the destination for the data once it has been read
//assumes a key 'destination' is passed via post, which is the location legitimate data should be passed to
//$url = $entryvalues['destination'];
//$response = http_post_data($url, $entryvalues);

function trainNeuralNet($data, $neuralNets){
    //Values for setting up the neural network(s)
    $numInput = 2;
    $numOutput = 1;
    $numLayers = 3;
    $numNeuronsHidden = 3;
    $desiredError = 0.001;
    $maxEpochs = 500000;
    $epochsBetweenReports = 1000;
    #get the keys of the array so we know which files to create/read
    $keys = array_keys($data);
    $inputFile = "training data/".$keys[0];
    $outputFile = "trained nets/".$keys[0];
    #$ann[$neuralNets];
    
    #create neural networ (starting with one for testing)
    
    $ann = fann_create_standard($numLayers, $numInput, $numNeuronsHidden, $numOutput);
    
    if($ann){
        echo "Created ANN!\n";
        fann_set_activation_function_hidden($ann, FANN_SIGMOID_SYMMETRIC);
        fann_set_activation_function_output($ann, FANN_SIGMOID_SYMMETRIC);
        
        if (fann_train_on_file($ann, $inputFile, $maxEpochs, $epochsBetweenReports, $desiredError)){
            echo "Trained on data!\n";
            fileHandler($outputFile, $ann);
        }else{
            echo "Training on file failed!\n";
        }
                        
    }else{
        echo "Could not create ANN!\n";
    }
    

    
    
}

function testNeuralNet(){
    
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
        fileHandler($filename, $ann);
    }
    
}

function finishingUp($destination, $data){
    #acad doesn't support http_post_data, so I had to switch to using HTTP context
    #$response = http_post_data($goodurl, $data);
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
}