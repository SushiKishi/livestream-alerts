<?php

/*
Note: oh my god whyyyyyyy does streamer.bot get this data the way it does genuinely toxic af to my vibes
because it uses argument names like "fw.variants[#].name", you can't make a query string from it.
because "fw.variants[0].name=Shirt" gets overriden by "fw.variants[0].quantity=2", so you only get fw.variants=2.
So you either manually convert it all to a query string which is probably the easier solution but
f-it let's build a dictionary to fix the query string.
I honestly thought about doing the entire alert system lke this anyway, convert all the various source data
into generic variable names, then running it all through an individual cash / charity / sub alert stream
but it seemed like more trouble than it was worth. Now that I've done everything else the other way though
here comes freaking fourthwall data to rain on my parade. i'm not coding this three different ways this week
i would like to stream sometime before the heat death of the unvierse.

Also, look -- &currency or someshit is an HTML entity. So when you use this in a browser,
currency turns into <some symbol>cy or somesuch, but it's on display only.

Streamer.bot figures it out just fine. But if you URL encode the echo/return here, then it breaks everything else.


*/


$q = urldecode($_SERVER['QUERY_STRING']);
$ugly = [

	"fw.variants[0].name",
	"fw.variants[0].quantity",
	"fw.variants[1].name",
	"fw.variants[1].quantity",
	"fw.variants[2].name",
	"fw.variants[2].quantity",
	"fw.username",
	"fw.currency",
	"fw.total",
	"fw.statmessageus",
	"fw.createdAt",
	"fw.gifts[0].winner",
	"fw.gifts[1].winner",
	"fw.gifts[2].winner",
	"fw.gifts[3].winner",
	"fw.offer.name"

];

//

$pretty = [

	"item0",
	"qty0",
	"item1",
	"qty1",
	"item2",
	"qty2",
	"user",
	"currency",
	"amount",
	"message",
	"timestamp",
	"winner0",
	"winner1",
	"winner2",
	"winner3",
	"giftName"
];

$q = str_replace($ugly, $pretty, $q);
echo (!empty($q)) ? $q : "There was no input.";
?>