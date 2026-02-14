<?php


//TODO: Modify bot code to get currency used for donation, then convert
//TODO: change this so its more similar to the other alerts in terms of flow, verbiage, etc.


function processData($alertData) {

	$ugly = array();
	$pretty = array();
	$merchList = array();
	$message = null;
	$anyMerch = false;
	
	
    extract($alertData);
	unset($alertData);
	
	//initialize / set easy stuff
	$data["chatMsg"] = "Thank you to $donorName for $" . round($donorAmount, 2) . " for $charity! Use !charity and !donate to keep the momentum going!";
	$data["triggerName"] = $triggerName;
	$tier = 1;

	//tier upgrades
    if ($donorAmount >= 10) { $tier++; }
    if ($donorAmount >= 25) { $tier++; }


	$rewards = json_decode($donorRewards, true);
	//rewardID => rewardText, ffffffffffffffffffffuuuuuuuuuuuuuuuuu
	$c = count($rewards);
	if ($c > 0 ) { 
		
		$anyMerch = true;
		$anyDays = false;
		//$token comes from bot
		//$campaignID, $campaignType comes from stored value in ../keys
		
		$tiltifyKeys = json_decode($meta, true);
		
		extract($tiltifyKeys);

		//can't jsut look up reward id OH NO gotta look up the CAMPAIGN first then the ID then fuuuuuu
		//the bot knows the campaign ID so I guess now it has to tell this script what it is too to save a lookup
		//like ffs you know
		
		//get list of rewards for campaign...
		$url = "https://v5api.tiltify.com/api/public/campaigns/$CampaignId/rewards";
		
		$ch = curl_init();
		curl_setopt($ch,CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json', 'Authorization: Bearer ' . $token ));
		curl_setopt($ch,CURLOPT_POST, false);
		curl_setopt($ch,CURLOPT_RETURNTRANSFER, true); 
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
		
		$result = curl_exec($ch);
		$rewardData = json_decode ( $result, true );

		if (!$rewardData) { //fallback to cache
		
			$rewardData = json_decode( file_get_contents("data/tiltifyRewardCache.txt"), true );
		
		}

		else  { file_put_contents("data/tiltifyRewardCache.txt", $result); }
		
		foreach ($rewardData["data"] as $reward) { //turn big list of rewards into list of IDs and real names
		
			array_push($ugly, $reward["id"]);
			array_push($pretty, $reward["name"]);
		
		}
		
		
		
		$x = 0;
		$truncated = 0;

		foreach($rewards as $item) {
		
			$x++;

			if ($x <= 2) { //only get two item names, everything else truncated
				
				$itemName = str_replace($ugly, $pretty, $item);
				array_push($merchList, $itemName);
				if (str_contains(strtolower($itemName), "day")) { $anyDays = true; }
				
			}

			else { $truncated++; } //how many items truncated?
	
		}

		/*

		*/

		
		
		
	}// end if $rewards)


	//Now we know everything we need to choose our Alert.
	try { $data = $data + pickAlert($triggerName, $tier); } //adds alert information to the data array
	catch (Throwable $e) { itEffinDied( ($data ?? null), ($alert ?? null), $e); }
	
	try { $data  = customizeText($data); }
	catch (Throwable $e) { itEffinDied( ($data ?? null), ($alert ?? null), $e); }

	if (empty($data["topText"])) { //I had something more complicated here but sod it
		
			$data["topText"] = "$donorName donates $" . round($donorAmount, 2) . " to $charity!";

	}
	
	if (empty($data["bottomText"]) && $anyMerch ) {
		
		//TODO: add Booker for day/match text

		$joiner = ", ";
		if ($truncated === 1) { array_push($merchList, "and $truncated other match!"); }
		elseif ($truncated >= 2) { array_push($merchList, "and $truncated other matches!"); }
		elseif ($truncated === 0) { $joiner = " and "; }
		$data["bottomText"] = "Rewards: " . implode($joiner, $merchList);
		


	} //end bototm text build

	
	
	
	//make log msg
	$logMsg = "$data[timestamp] ::: ". strtoupper($eventSource) . " ALERT ::: $donorName - $" . round($donorAmount, 2) . "\r\n";
	$logMsg = ( !empty($bottomText) ) ? $logMsg . $bottomText . "\r\n" : $logMsg;
	$logMsg = ( !empty($message) ) ? $logMsg . $message . "\r\n" : $logMsg;
	$data["logMsg"] = $logMsg;

	//Finalize text and package outgoing data
	try { packageData($data); }
	catch (Throwable $e) { itEffinDied( ($data ?? null), ($alert ?? null), $e); }
    return null;

}




?>


