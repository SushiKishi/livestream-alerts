<?php

set_error_handler(function($errno, $errstr, $errfile, $errline) {
    // error was suppressed with the @-operator
    if (0 === error_reporting()) {
        return false;
    }
    
    throw new ErrorException($errstr, 0, $errno, $errfile, $errline);
});


function itEffinDied($data, $alert, $e) {

    //log error data
    $newError = date("m.d.y - g:i a") . " ::: Error occured!\r\n" . $e . "\r\n";
    $log = fopen("logs/err.log", "a");
    
    $newError = $newError . "CURRENT DATASET: \r\n" . ( json_encode($data) ?? "null data" )  . "\r\n";
    $newError = $newError . "CURRENT ALERT: \r\n" . ( json_encode($alert) ?? "null alert" ) . "\r\n\r\n";
    fwrite($log, $newError);
    fclose($log);

    //add to message log
    $newError = date("m.d.y - g:i a") . " ::: !!! Alert error occured! !!!\r\n\r\n";
    $log = fopen("logs/msg.log", "a");
    fwrite($log, $newError);
    fclose($log);

    //Create dummy alert so that you're alerted the alerts didn't alert
    $dummy["delay"] = 0;
    $dummy["diff"] = 5000;
    $dummy["file"] = null;
    $dummy["stroke"] = "Y";
    $dummy["lineCount"] = 1;
    $dummy["wrestler"] = "__ERROR__";
    $dummy["topText"] = "Error in alerts! " . ( $data["user"] ?? "NULL" ) . " - ";
    $dummy["topText"] = $dummy["topText"] . ( !$data["eventSource"] ??  "NULL /" ) . " - ";
    $dummy["topText"] = $dummy["topText"] . ( $data["triggerName"] ?? "NULL" ); 
    file_put_contents("data/alert.json", json_encode($dummy));

    packageData($dummy);

    //wrestler, triggerName, lineCount, topText
    

     die;

}

?>


