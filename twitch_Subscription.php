<?php

//TODO: gifts


function processData($data) {

	
    extract($data);

	
	
	$tier = intval(str_ireplace(["tier ", "prime"], ["", 1], $tier));
	$tierNames = file("data/tierNames.txt");
	$tierName = trim($tierNames[$tier]);

	$giftedSubShared = ( $totalGiftsShared ?? ( $totalSubsGiftedShared ?? false ) );
	$giftedSubCount =  ( $totalGifts ?? ( $totalSubsGifted ?? 0 ) );
	
	$bottomText = null; //will never be merch here. Really should name this $bottomText. It's always $bottomText.


	if (!empty($anonymous)) { $user = "Anonymous"; }

	

	if (str_contains(strtolower($triggerName), "gift")) { //start gifts


			
			$userTier = $tier;


			if ($triggerName === "GiftBomb") {

				$target = "$gifts Sushi Rolls";
				$userTier = $userTier + floor($gifts / 10);
				
			}

			
			if ($triggerName === "GiftSubscription") {

				$target = "$recipientUser";


				if ($monthsGifted > 1)  { $monthsText = " for $monthsGifted months"; } //mind the opening space, check the toptext later.
				
				$userTier = 0;
				if ($monthsGifted >= 3) { $userTier++; }
				if ($monthsGifted >= 6) { $userTier++; }
				if ($monthsGifted >= 12) { $userTier++; }

			}

			if ($giftedSubShared) { $userTier = $userTier + floor($giftedSubCount / 10); }
			$userTier = ($userTier >= 6) ? 6 : $userTier;
			$userTierName = trim($tierNames[$userTier]);

			$defaultText = "$user puts $target over as $tierName" . ( $monthsText ?? null ) . "!";
			$chatMsg = "$user is a $userTierName! They put $target over as $tierName" . ( $monthsText ?? null ) . "!";
			
	} //end gifts

	else { //non-gifted subs
	

		$userTier = $tier;
	
	
		if ($triggerName === "Subscription") {

			$defaultText = "$user is a first-time $tierName!";
			$chatMsg = $defaultText . " Welcome to the Sushi Rolls!";

		}


		if ($triggerName === "Resubscription") {
	
			if ($cumulative >= 3) { $userTier++; }
			if ($cumulative >= 6) { $userTier++; }
			if ($cumulative >= 9) { $userTier++; }
			if ($cumulative >= 12) { $userTier++; }
			if ($cumulative >= 18) { $userTier++; }
			$userTier = ($userTier >= 6) ? 6 : $userTier;

			$userTierName = trim($tierNames[$userTier]);
			$defaultText = "$user is a $cumulative-time $userTierName!";
			$chatMsg = $defaultText;
	
		}

	
	
	}
	

	
	//Wrestler picking time
	try { $data = $data + pickAlert("subs", $tier); } //adds alert information to the data array
	catch (Throwable $e) { itEffinDied( ($data ?? null), ($alert ?? null), $e); }
	//customize text
	try { $data  = customizeText($data); }
	catch (Throwable $e) { itEffinDied( ($data ?? null), ($alert ?? null), $e); }

	$data["topText"] = $data["topText"] ?? $defaultText;
	
	//this is where you process bottom text, if there was any.

	


	//make log msg
	$logMsg = "$data[timestamp] ::: ". strtoupper($eventSource) . " ALERT ::: $user - ";
	$logMsg = $logMsg . ( $target ?? "<no giftee>" ) . " - Tier$tier x " . ( $monthsGifted ?? "1" ) . "\r\n";
	$logMsg = ( !empty($bottomText) ) ? $logMsg . $bottomText . "\r\n" : $logMsg;
	$logMsg = ( !empty($message) ) ? $logMsg . $message . "\r\n" : $logMsg;
	$data["logMsg"] = $logMsg;


	//make chatmsg -- the work was done above in this case but usually it's "built" here.

	$data["chatMsg"] = $chatMsg;
	

	//Finalize alert text, then send all the data off to be packaged.
	
	try  { packageData($data); }
	catch (Throwable $e) { itEffinDied( ($data ?? null), ($alert ?? null), $e); }

    return null;


}




?>


