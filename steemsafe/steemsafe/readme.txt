getting started.

****Pre-req
**********************
install mysql,apache, and php.
install youtube.dl
import the php structure into your mysql database.
	This can be done through phpmyadmin


****php.ini edits
************************
In the php.ini file find the following variables, and set the value accordingly
php.ini memory_limit 4G
php.ini max_execution_time=1800




****conf files
************************
[admin folder]/conf.conf

"path" would refer to the path to your htdoc folder on your machine.

"server" is the name of your domain name".  If you use non-standard port, include the port numbers.

[php]/achive.conf
price is the cost per MB.
	if you want a minimum price in the future, it will need to be hard coded.

appname is the name of your app.  Blarchive is probably going to be your appname.  so just replace oration with your bot

beneficiary is the account you want the payments to go to.

appsecret isn't used.

number_of_readerbots is the number of ccounts you will be running at all times.

http is the default domain name.  Change to your server name.

port is the port it wil be running on.  for http you should change to 80, for https change to 443.

databasejump is how far you want to skip ahead in the database.  This is mostly a debug feature to jump ahead when changes are made.  default should be 1

demo.  is this a demo, if so, then "yes". anything else is no.

dailylimit a reasonable 24 hour limit.  500 was set as an unrealistic cap for testing.  more reasonable caps may be 5.

eof just means end of file.  It is these on a line of its own for a reason.

****First files to load
****************************************************
blacklist.php

on command line type "php blacklist.php now1" To force the download of a wordfilter for [odd] days.
on command line type "php blacklist.php now1" To force the download of a wordfilter for [even] days.
you can type [untested] "php blacklist.php" which should download a new copy of the wordfilter automatically a day.

accounting.php
	This file reads your steem db page for transactions with numeric memos
		To run this file type "php accounting.php"
	This is a script that is supposed to run all the time.
	In the event of fast transactions, you may need to reduce the sleep time.
		Alternatively if during a long blackout, or unrecorded transactions no longer on the first page, you may need to create another accounting script that reads deeper.

startengines.php
	This file loads 2 bot files (untested on linux).  These bots are responsible for storing things to the block chain.
	to start type "php startengines [botnumber] [botname] [steemconnect access key]"
if this doesn't work on linux lemme know, and I'll try to run them on linux.  Please note, I will not have access to a linux box between oct 20-26.

to get the steemconnect access key.  Use your webbrowser, and visit your webserver/php/dourl.php

****user pages
*************************************
dourl.php
This is the main document.  Users can log in, log out, see the files they uploaded, and upload the files

index.php
This shows all the files that have been saved.
users can search.
If front end developer wants, they can limit the SQL script to something reasonable.


Other notes:
if bot1.php has problems, you may need to change the instances of ""http://127.0.0.1:8080/php.steem.php" to something on yourserver recognized by steemconnect.
when you can get things up and running, remove the text "(*testing you can't see server*)" from postengine.php

