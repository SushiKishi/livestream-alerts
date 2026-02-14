# SushiKishi's Alerts backend

Powered by Streamer.bot and some sort of web server backend (e.g. XAMPP).


1) Streamer.bot calls Alert.php, using a Web Query containing all the information needed for the alert in question
2) Alert.php processess the source (e.g. Twitch, YouTube) and type of trigger (e.g. Cheer, Subscription), and runs that particular PHP file.
3) Generate default alert text and determine what type and tier of alert we are working with - e.g. Tips over $5 = tier 1, Tips over $15 = Tier 3, Merch Sales converted to cash/tip alerts, whatever the case may be.
4) Pick from the available alert audio choices for that type and tier of alert
5) Load that wrestler's alert background, and customized text options (if set).
6) Store the alert text in `/live/<insert text options here>.txt`, store the audio- and logging-based data in `/live/alert.json`


### TODO:




### Notes:


### License:
This is mostly to backup my own data and keep track of changes going forward. Do whatever you'd like with the code itself.  If you use it, give credit if you feel like it. Or don't.

Audio credits available in CREDITS.md.