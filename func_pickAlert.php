<?php


function pickAlert($type, $tier) {

	
	//get list of wrestlers at this type and tier
	$audioLoc = "audio/$type/$tier/";
	$audioChoices = array_diff(scandir($audioLoc), array('.', '..'));

	shuffle($audioChoices);
	$wrestler = $audioChoices[0];

	//get list of songs available for that wrestler (some wrestlers have mltiple tunes)
	$audioLoc = $audioLoc . $wrestler . "/";
	$audioChoices = array_diff(scandir($audioLoc), array('.', '..'));
	shuffle($audioChoices);


	//get list of versions of that song (i have duplicates of some songs)
	$audioLoc = $audioLoc . $audioChoices[0] . "/";
	$audioChoices = array_diff(scandir($audioLoc), array('.', '..'));
	shuffle($audioChoices);


	//finally, get the actual file name of the alert.
	$audioLoc = $audioLoc . $audioChoices[0] . "/";
	$audioChoices = array_diff(scandir($audioLoc), array('.', '..'));
	shuffle($audioChoices);
	$filename = $audioChoices[0];
	$alertAudio = $audioLoc . $filename;
	

	//get alert data from filename


	/*

		Everything before numerical data in file name is unused programattically.
		So, explode by underscore ('_'), pop until [0] is numeric, then:
		[0] - "walk up" / windup delay
		[1] = total length of alert audio

		Flow:
		-Turn filename into variables above.
		-Tell Streamer.bot to:
			Start audio
			Wait [0] seconds
			Display Alert for [1] - [0] = delay seconds
			"Courtesy padding" the delay to allow for transition, etc. (500ms, handle in bot)

	*/

	$removeEXT = explode(".", $filename, -1); //strip extension from filename, put in [0]
	$removeEXT = implode(".", $removeEXT); //put filename back together
	$alertInfo = explode("_", $removeEXT); //split by underscore

	//pop array until numerical data
	while ( count($alertInfo) > 0 && !is_numeric($alertInfo[0])) { array_shift($alertInfo); }


	$delay = $alertInfo[0] * 1000;
	$length = $alertInfo[1] * 1000;
	$diff = $length - $delay;

	$data["file"] = str_replace("/", "\\", $_SERVER["DOCUMENT_ROOT"] . "\\alerts\\$alertAudio");
	$data["delay"] = $delay;
	$data["length"] = $length;
	$data["diff"] = $diff;
	$data["wrestler"] = $wrestler;
	$data["type"] = $type;
	$data["tier"] = $tier;
	return $data;

}



?>


