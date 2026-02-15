<?php

function kofiShopOrder($merch) {


	$merchList = array(); //holds list of pretty names
	$shoppingList = explode(",",$merch); //splits up list of ugly names
	$count = count($shoppingList); //item count
	$x = 0;
	$y = 0;

	//no way to do get these automatically...
	$ugly = 	[
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
				
		
	$pretty =	[
				
			
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
			if ($y === 1) { array_push($buildText, "and $y other match"); }
			elseif ($y >= 2) { array_push($buildText, "and $y other matches"); }
			elseif ($y === 0) { $joiner = " and "; }

			$merchText = implode($joiner, $merchList);

			
			$merchData["merchList"] = $merchList;
			$merchData["trunc"] = $y;
			$merchData["text"] = $merchText . "!"; //the list of two doesn't have a way to attach the ! lol

			return $merchData;
			

}


include("kofi_Donation.php");

?>