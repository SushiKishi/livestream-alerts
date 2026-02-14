<?php


function customizeText($data) {

	
	$data["stroke"] = "Y";	
	extract($data);
	
	
	//Does this wrestler override the base alert text? e.g.: customized raid alert text
	if (is_file("wrestlers/$wrestler/$triggerName.php")) { include("wrestlers/$wrestler/$triggerName.php"); }

	else {
		
		//if cash with no merch, text is pretty short. Randomizer it a bit!
		if ( $type === "cash" && empty($merch) ) {
			
			$flavorText = file("data/generic$tier.txt");
			shuffle($flavorText);
			$data["flavorText"] = trim($flavorText[0]);
			
		}

		//if "some other alert triggerName," change its generic text here

		
	}

	//apply fonts, change stroke, etc.
	if (is_file("wrestlers/$wrestler/style.php")) { include("wrestlers/$wrestler/style.php"); }

	
	//new items potentially being returned: stroke, flavorText, prefix, topText, bottomText, suffix
	//build individual wrestler styles / text overrides around this.
	return $data; 
	
	
}



?>


