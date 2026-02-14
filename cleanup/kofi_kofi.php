<?php

//TODO: 



function processData($alertData) {

	
    extract($alertData);

	$currencyName = $currency; //fallback

	$fmt = new NumberFormatter('en_US', NumberFormatter::CURRENCY);
	$fmt->setTextAttribute(NumberFormatter::CURRENCY_CODE, $currency);
	$fmt->setAttribute(NumberFormatter::FRACTION_DIGITS, 0);
	$prettyNumber = $fmt->formatCurrency($amount, $currency);

	$amountText = "$user tipped $prettyNumber!";
	//if: $merch, then $amountText is changed at end of merch loop.
	


	//get name of currency for alert summary
	$urls = [

    	"https://cdn.jsdelivr.net/npm/@fawazahmed0/currency-api@latest/v1/currencies.json",
    	"https://latest.currency-api.pages.dev/v1/currencies.json",
    	"https://cdn.jsdelivr.net/npm/@fawazahmed0/currency-api@latest/v1/currencies.json",
    	"https://latest.currency-api.pages.dev/v1/currencies.json"

  	];

	foreach ($urls as $url) {

		$currencyTable = json_decode(file_get_contents($url), true);

		if (!empty($currencyTable[strtolower($currency)])) { 
			
		
			$currencyName = $currencyTable[strtolower($currency)];
			break;
			
			}

	}

	//end currency stuff

	//so the bot sends merch = null if no merch, but it parses as a string...oi
	$merch = ($merch === "null") ? null : $merch;


	
	//merch?
	if (!empty($merch)) {

		$merchList = array();
		$shoppingList = explode(",",$merch);
		$count = count($shoppingList);
		$x = 0;
		$y = 0;

		//no way to do this automagically.
		$ugly = [
				"4d433778ba", //TEST ITEM\\
				"5f652f63f0", //Squash Match Stream\\
				"3432cd83a4", //Midcard Match Stream\\
				"c4af58a1b5", //Main Event Stream\\
				"dfa9e4fca7", //Casual Playthrough Stream\\
				"4b99ba71-4fbb-45f5-be15-0990f3bf1a7d", //Holo-Chibi Art Stream\\
				"63918708-8b42-4d5e-8b0c-dd491f9d081f", //12-hour Speedrunning Challenge\\
				"a1b2c3d4e5", //test data
				"1a2b3c4d5e", //test data
				"abc123456" //test data

		];
				
		
		$pretty = [
				
				
				/*4d433778ba*/ "test item",
				/*5f652f63f0*/ "Squash Match Stream",
				/*3432cd83a4*/ "Midcard Match Stream",
				/*c4af58a1b5*/ "Main Event Stream",
				/*dfa9e4fca7*/ "Casual Playthrough Stream",
				/*4b99ba71-4fbb-45f5-be15-0990f3bf1a7d*/ "Holo-Chibi Art Stream",
				/*63918708-8b42-4d5e-8b0c-dd491f9d081f*/ "12-hour Speedrunning Challenge",
				"Test Shirt",
				"Test Pants",
				"Test Alert"

		]; //end pretty names

		foreach ($shoppingList as $item) {

			$x++;

			if ($x <= 2) { //only get two item names, everything else truncated
				
				$itemName = str_replace($ugly, $pretty, $item);
				array_push($merchList, $itemName);
				
			}

			else { $y++; } //how many items truncated?
		
		} //end foreach

			$buildText = $merchList;
			$joiner = ", ";
			if ($y === 1) { array_push($buildText, "and $y other match!"); }
			elseif ($y >= 2) { array_push($buildText, "and $y other matches!"); }
			elseif ($y === 0) { $joiner = " and "; }

			$bottomText = "On the card: " . implode($joiner, $merchList);
			
			$amountText = "$user spent $prettyNumber to become head booker!";

	} // end merch processing

	
    $tier = 1;

	//tier upgrades
    if ($amount >= 10) { $tier++; }
    if ($amount >= 20) { $tier++; }


	
	$data["user"] = ($public) ? $user : "Anonymous";
	$data["amount"] = "$amount";
	$data["tier"] = $tier;
	$data["amountText"] = $amountText;
	$data["bottomText"] = (!empty($bottomText)) ? $bottomText : null;
	$data["type"] = "cash";  //There are no "merch" type alerts, it's straight cash, homie.
	$data["source"] = $source;
	$data["message"] = ($public) ? $message : "Message not publicized.";
	$data["merchList"] = (!empty($merchList)) ? $merchList : null; //I goofed up these variables names and im too lazy to fix
	$data["merch"] = (!empty($merchList)) ? true : false; //saves so much headache later to have this T/F
	$data["eventSource"] = $eventSource;
		
    return $data; //return: user, amount, tier, amount (just the value), amountText, bottomText, merchList, source, message

}




?>


