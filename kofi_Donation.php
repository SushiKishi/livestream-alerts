<?php

//TODO: 



function processData($data) {

	
    extract($data);

	include("func_convertCurrency.php");
	$currencyData = convertCurrency($amount, $currency); //returns pretty, name

	$type = "cash";
	$tier = 1;
	

	//tier upgrades
    if ($amount >= 10) { $tier++; }
    if ($amount >= 25) { $tier++; }


	$chatMsg = "$from tips $currencyData[pretty]";

	
	//merch
	if (!empty($merch)) {
			
			$merchData = kofiShopOrder($merch);  // returns merchList, trunc, text
			$data["topText"] = "$from becomes head booker for $currencyData[pretty]!";
			$data["bottomText"] = "On the card: $merchData[text]";
			$data["merchList"] = $merchData["merchList"];
			$data["merchTrunc"] = $merchData["trunc"];	

			$data["chatMsg"] = "$from tips $currencyData[pretty] and books $merchData[text]";
			$chatMsg = "$from tips $currencyData[pretty] and books $merchData[text]";
			
			
	}

	//no merch
	else {

		$data["topText"] = "$from tips $currencyData[pretty]!";
		$chatMsg = $chatMsg . " via Ko-fi!";
		


	}

	try { $data = $data + pickAlert($type, $tier); } //adds alert information to the data array
	catch (Throwable $e) { itEffinDied( ($data ?? null), ($alert ?? null), $e); }

	try { $data = customizeText($data); }
	catch (Throwable $e) { itEffinDied( ($data ?? null), ($alert ?? null), $e); }

	//make log msg
	$logMsg = "$data[timestamp] ::: ". strtoupper($eventSource) . " " . strtoupper($triggerName) . " ALERT ::: ";
	$logMsg = $logMsg . "$from - $currencyData[pretty] $currencyData[name]\r\n";
	$logMsg = ( !empty($bottomText) ) ? $logMsg . $bottomText . "\r\n" : $logMsg;
	$logMsg = ( !empty($message) ) ? $logMsg . $message . "\r\n" : $logMsg;
	$data["logMsg"] = $logMsg;


	//make Chat msg 
	$data["chatMsg"] = $chatMsg;

	try { packageData($data); }
	catch (Throwable $e) { itEffinDied( ($data ?? null), ($alert ?? null), $e); }
    return null;
		
    

}




?>


