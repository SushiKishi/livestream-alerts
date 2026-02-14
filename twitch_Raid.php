<?php



function processData($data) {

	
	
	extract($data);
	
	$tier = 1;

	
	$data["topText"] = "$user and $viewers viewers run in to make the save!";

	//Wrestler picking time
	
	try { $data = $data + pickAlert("raid", $tier); } //adds alert information to the data array
	catch (Throwable $e) { itEffinDied( ($data ?? null), ($alert ?? null), $e); }
	
	try { $data  = customizeText($data); }
	catch (Throwable $e) { itEffinDied( ($data ?? null), ($alert ?? null), $e); }

	if (empty($data["topText"])) {

		$data["topText"] = "$user cheers $bits bits!";
		if (strpos($data["flavorText"], "_") === 0) { $data["topText"] . str_ireplace("_", " ", $data["flavorText"]); }
		else { "$data[flavorText] $data[topText]"; }

	}
	
	//this is where you process bottom text, if there was any.

	$data["lineCount"] = 1;


	//make log msg
	$logMsg = "$data[timestamp] ::: ". strtoupper($eventSource) . " ALERT ::: $user - $viewers\r\n";
	$data["logMsg"] = $logMsg;


	//make chatmsg -- the work was done above in this case but usually it's "built" here.

	$data["chatMsg"] = $data["topText"];
	

	//Finalize alert text, then package the data for export
	
	try { packageData($data); }
	catch (Throwable $e) { itEffinDied( ($data ?? null), ($alert ?? null), $e); }
	
    return null;


}




?>