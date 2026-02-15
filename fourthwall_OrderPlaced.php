<?php

//Note: Input from Streamer.bot is sane-itized by dict_fourthwall before it winds up here.

//TODO: Fourthwall Giveaways. I can send a test event to get the *data* but I don't know the *whens* of the triggers
//e.g.: Someone starts a giveaway, people enter,giveaway ends, winners are chosen, each individual winner claims their prize.
//What events does that pop? Don't need an alert for "giveaway ends" AND "winners are chosen."
//Don't need an event for winners are chosen if "individual winner claims prize" is a trigger.

function processData($data) {

	
	
	//if there was no merch, we gotta set our data
    extract($data);

	include("func_convertCurrency.php");
	$currencyData = convertCurrency($amount, $currency); //returns pretty, name

	$type = "cash"; //no shop/merch-specific alerts at this time. Straight cash, homie.
	$tier = 1;
	

	//tier upgrades
    if ($amount >= 10) { $tier++; }
    if ($amount >= 25) { $tier++; }


			
	//top text first
	$data["topText"] = "$user just dropped $currencyData[pretty] at the merch table!";


	$itemList = $qty0 . "x $item0";

	//check if there's three items, so we know if we're using "Item1, Item2, and more" or "Item1 and Item2"
	if (!empty($item2)) { $itemList = $itemList . ", " . $qty1 . "x $item1, and more!"; }
	else { $itemList = (!empty($item1)) ? $itemList . "and " . $qty1 . "x $item1" : $itemList; }


	$data["bottomText"] = "Loot list: $itemList";
	

	//make log msg
	$logMsg = "$data[timestamp] ::: ". strtoupper($eventSource) . " " . strtoupper($triggerName) . " ALERT ::: ";
	$logMsg = $logMsg . "$user - $currencyData[pretty] $currencyData[name]\r\n";
	$logMsg = ( !empty($data["bottomText"]) ) ? $logMsg . $data["bottomText"] . "\r\n" : $logMsg;
	$logMsg = ( !empty($message) ) ? $logMsg . $message . "\r\n" : $logMsg;
	$data["logMsg"] = $logMsg;


	//make Chat msg 
	$data["chatMsg"] = "$user grabs $currencyData[pretty] of loot from the !merch table! Thank you!";
	
	
	//data finalized, let's build an alert:
			
	try { $data = $data + pickAlert($type, $tier); } //adds alert information to the data array
	catch (Throwable $e) { itEffinDied( ($data ?? null), ($alert ?? null), $e); }

	try { $data = customizeText($data); }
	catch (Throwable $e) { itEffinDied( ($data ?? null), ($alert ?? null), $e); }

	try { packageData($data); }
	catch (Throwable $e) { itEffinDied( ($data ?? null), ($alert ?? null), $e); }
    return null;
		
    

}




?>


