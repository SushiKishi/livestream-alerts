<?php

//TODO: 



function convertCurrency($amount, $currency) {

	
	
	$fmt = new NumberFormatter('en_US', NumberFormatter::CURRENCY);
	$fmt->setTextAttribute(NumberFormatter::CURRENCY_CODE, $currency);
	$fmt->setAttribute(NumberFormatter::FRACTION_DIGITS, 0);
	$prettyNumber = $fmt->formatCurrency($amount, $currency);

	
	//get name of currency for alert summary
	$urls = [

    	"https://cdn.jsdelivr.net/npm/@fawazahmed0/currency-api@latest/v1/currencies.json",
		"https://latest.currency-api.pages.dev/v1/currencies.json"
    	

  	];

	foreach ($urls as $url) {

		if (empty($currencyName)) { 
			
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, "https://latest.currency-api.pages.dev/v1/currencies.json");
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			$output = curl_exec($ch);

			$currencyTable = json_decode($output, true);
			$currencyName = $currencyTable[strtolower($currency)] ?? null;
			
		}

	}


	$currencyData["pretty"] = $prettyNumber;
	$currencyData["name"] = $currencyName;

	return $currencyData;
	//end currency stuff

}




?>