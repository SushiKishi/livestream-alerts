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
    $dummy["topText"] = $dummy["topText"] . ( $data["eventSource"] ??  "NULL /" ) . " - ";
    $dummy["topText"] = $dummy["topText"] . ( $data["triggerName"] ?? "NULL" ); 
    file_put_contents("data/alert.json", json_encode($dummy));

    packageData($dummy);

    //wrestler, triggerName, lineCount, topText
    

     die;

}


//initialize stuff
include("func_pickAlert.php"); //in: $type, $tier || out: $data (ONLY file, delay, length, diff, wrestler, type, tier)
include("func_customizeText.php"); //in: $data || out: $data (+stroke, flavorText, prefix, topText, bottomText, suffix)
include("func_packageData.php"); //in: $data || out: no return, saves alert.json and alert text files

date_default_timezone_set("America/New_York");
$rawData = array(); //I don't think iterating and modifying$_GET directly is a good idea

foreach ($_GET as $k => $v) {

    //its a string query that even if we parsed for JSON would lose a few details (True/False)...
    //also gotta format some trigger text.
    if ($k === "eventSource") { $rawData[$k] = strtolower($v); }
    elseif ($k === "triggerName") { $rawData[$k] = str_replace(" ", "", ucwords($v) ); }
    elseif (strtolower($v) === "true") { $rawData[$k] = true; }
    elseif (strtolower($v) === "false") { $rawData[$k] = false; }
    else { $rawData[$k] = ( ( $v === "null" ) || ( $v === "%" . $k . "%" ) || ( empty($v) ) ) ? null : $v ; }

}

unset($_GET);

$rawData["timestamp"] = date("m.d.y - g:i a");


//process basic alert data, return: name, amount, tier, source, type, text
try {
    
    $filename = $rawData["eventSource"] . "_" . $rawData["triggerName"] . ".php";
    include($filename); 
    processData($rawData); 
    
}

catch (Throwable $e) { itEffinDied( ($rawData ?? null), ($alert ?? null), $e); }

?>