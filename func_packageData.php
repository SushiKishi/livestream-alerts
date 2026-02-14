<?php

//it ain't want to work unless i wrapped this in a function and threw $data at it that way
function packageData($data) {


	$data["topText"] = ($data["prefix"] ?? null) . $data["topText"] . ($data["suffix"] ?? null);
	$lineCount = 1;

	if (!empty($data["bottomText"])) {
	
		$data["bottomText"] = ($data["prefix"] ?? null) . $data["bottomText"] . ($data["suffix"] ?? null);
		$lineCount++;

	}



    if ($data["wrestler"] === "__ERROR__" ) {  //then someting went wrong

        copy("data/errorBG.png", "live/bg.png");
        copy("data/blank.gif", "live/fg.gif");
        
        
        }
        

    else {

        if ($data["triggerName"] === "raid") {
        
                copy("data/raidFG.gif", "live/fg.gif");
                copy("data/raidBG.png", "live/bg.png");
            
        }
        
        else {
        
            copy("data/blank.gif", "live/fg.gif");
            copy("wrestlers/$data[wrestler]/bg.png", "live/bg.png");
            
        }

    }
        
    
    if ($lineCount === 1) {

        $textBlock["OneLine"] = $data["topText"];
        $textBlock["TopLine"]= "";
        $textBlock["BotLine"] = "";

    }

    else {

        $textBlock["OneLine"] = "";
        $textBlock["TopLine"] = $data["topText"];
        $textBlock["BotLine"] = $data["bottomText"];
        
    }

    $yn = ["Y", "N"];
    $lineOptions = ["TopLine","BotLine","OneLine"];
    
    foreach($lineOptions as $lineName) {

        foreach($yn as $yesNo) {

                $filename = "live/alert" . $lineName . $yesNo . ".txt";
                $contents = ($yesNo === $data["stroke"]) ? $textBlock[$lineName] : "";
                file_put_contents($filename, $contents);
                
            
        }

    }


    $needs = ["file", "delay", "length", "diff", "chatMsg", "logMsg", "stroke"];


    foreach ($needs as $need) {

        $alert[$need] = $data[$need] ?? null;

    }
    
    file_put_contents("data/alert.json", json_encode($alert)); //this gets passed to bot

}

?>