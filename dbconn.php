<?php
	#Först lite variabler som används för att koppla upp
	$dbhost="localhost";
	$dbuser="axelniktge20a_weatherAdmin";
	$dbpwd="c}n(I-r{5I#H";
	$dbname="axelniktge20a_weather";
	
	#skapa en uppkoppling
	$dbconn = new mysqli($dbhost, $dbuser, $dbpwd, $dbname);
	
	#Kontrollerar att uppkopplingen är ok
	if($dbconn->connect_error){
		die("Connection failed:<br> ".$dbconn->connect_error);	
	}

?>