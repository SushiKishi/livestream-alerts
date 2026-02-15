# SushiKishi's Alerts backend

Powered by Streamer.bot and some sort of web server backend (e.g. XAMPP).


1) Streamer.bot calls Alert.php, using a Web Query containing all the information needed for the alert in question
2) Alert.php processess the source (e.g. Twitch, YouTube) and type of trigger (e.g. Cheer, Subscription), and runs that particular PHP file.
3) Generate default alert text and determine what type and tier of alert we are working with - e.g. Tips over $5 = tier 1, Tips over $15 = Tier 3, Merch Sales converted to cash/tip alerts, whatever the case may be.
4) Pick from the available alert audio choices for that type and tier of alert
5) Load that wrestler's alert background, and customized text options (if set).
6) Store the alert text in `/live/<insert text options here>.txt`, store the audio- and logging-based data in `/data/alert.json`.
7) OBS automatically refreshes text sources with the new text file contents. Streamer.bot updates the log and uses the timing data to play audio and turn sources on and off in OBS as needed.


### TODO:

General:
Generate 'default' text before checking for customized text. I didn't want to build the text lines twice but the logic doesn't flow as well this way now

Use $type where, well, you need $type

Fourthwall: Wont' be done until someone does a big giveaway on my channel lol

Figure out how to re-implement the Bizhawk Shuffler redeems so I can finally start that again. Oof.

I had a totally different raid alert that needed a background and foreground image, but now I don't...it's all just a dead branch of code. Either do something with this or cut out the dead weight


Raids:
Customized text
add raid-only wrestler options (e.g. the rock)


### Notes:


### License:
Do whatever you'd like with the code itself. It's mostly just for me to a) track changes, b) have a backup, and c) share my work if anyone was ever interested.

LICENSE.md applies only to the code.  The audio isn't mine and is credits in CREDITS.md. I cobbled together the images using photos from events and entrance videos.
