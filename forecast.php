<?php
ob_start();
session_start();

ini_set('memory_limit', '1024M');

$json = file_get_contents('citylist.json');
$data2 = json_decode($json, true);

function getIdByName($name2, $data2) {
    foreach ($data2 as $row2) {
        if ($row2['name'] == $name2) {
            return $row2['id'];
        }
    }
    return null;
}
$name3 = ucfirst(strtolower($_POST['city']));
$name2 =  $name3;
if ($name2==""){
	$id2 = "651299";
} else{
	$id2 = getIdByName($name2, $data2);
}

//FORECAST-----
$apiKey = "2725c8a36862eae1980d31e322f0e7d8";
$googleApiUrlFore = "https://api.openweathermap.org/data/2.5/forecast?id=" . $id2. "&lang=en&units=metric&APPID=" . $apiKey;

$ch2 = curl_init();

curl_setopt($ch2, CURLOPT_HEADER, 0);
curl_setopt($ch2, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch2, CURLOPT_URL, $googleApiUrlFore);
curl_setopt($ch2, CURLOPT_FOLLOWLOCATION, 1);
curl_setopt($ch2, CURLOPT_VERBOSE, 0);
curl_setopt($ch2, CURLOPT_SSL_VERIFYPEER, false);
$response2 = curl_exec($ch2);

curl_close($ch2);
$data3 = json_decode($response2);

$flag = $data3->city->country;


function realDate($date){
	$date2 = strtotime($date);
	return(date('D H:i', $date2));
}
//function för att sätta ut datan med mindre kod
function forecastColumn($data3, $columnNumber){
	echo '<div class="column">';
	echo realDate($data3->list[$columnNumber]->dt_txt);		
	echo '<br> <img src="http://openweathermap.org/img/w/';
	echo $data3->list[$columnNumber]->weather[0]->icon;
	echo '.png" class="weather-icon" /> ';
	echo $data3->list[$columnNumber]->main->temp_max; 
	echo '&deg;C <span class="min-temperature">';
	echo $data3->list[$columnNumber]->main->temp_min;
	echo '&deg;C</span></div>';
}

?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Axel's Weather</title>
<link rel="stylesheet" href="styles.css">
<link rel="shortcut icon" href="media/logo2.png";>
</head>

<body>
	
		<ul>
			<li><a href="index.php">Home</a></li>
			<li><a href="forecast.php">Forecast</a></li>
			<?php
				if(!isset($_SESSION["logged_in"]) || $_SESSION["logged_in"] !== true){
					echo '<li style="float:right"><a class="active" href="login.php">Sign in</a></li>';
				}else{
					echo '<li style="float:left"><a href="saved.php">Saved</a></li>';
					echo '<li style="float:right"><a class="active" href="logout.php">Sign out</a></li>';
					echo '<li style="float:right">';
					echo '<a>',$_SESSION['username'],'</a>';
					echo '</li>';
					
				}
			?>
			
		</ul>
		<div class="report-container1">
			<h1>Axel's Weather</h1>
			<p>Enter location</p>
			<form method="post">
				<input type='text' name="city"/>
				<input type="submit" name="go"/>
			</form>
			<p>
				<?php 
				if (!$id2) {
					echo "No match found for '$name2'";
				}	 
				?>
			</p>
		</div>
		<br>
	<div class="report-container2">
		<h1>Weather Forecast <?php echo $data->name; ?></h1>
		<h2><?php echo $data3->city->name; ?> <img width="28" height="21" 
	 	src="https://flagcdn.com/28x21/<?php echo strtolower($flag) ?>.png"/></h2>
		
		<div class="row">
			<?php
				forecastColumn($data3, 0);
				forecastColumn($data3, 1);
				forecastColumn($data3, 2);
			?>
		</div>
		<div class="row">
			<?php
				forecastColumn($data3, 3);
				forecastColumn($data3, 4);
				forecastColumn($data3, 5);
			?>
		</div>
		<div class="row">
			<?php
				forecastColumn($data3, 6);
				forecastColumn($data3, 7);
				forecastColumn($data3, 8);
			?>
		</div>
		<div class="row">
			<?php
				forecastColumn($data3, 9);
				forecastColumn($data3, 10);
				forecastColumn($data3, 11);
			?>
		</div>
		<div class="row">
			<?php
				forecastColumn($data3, 12);
				forecastColumn($data3, 13);
				forecastColumn($data3, 14);
			?>
		</div>
		<div class="row">
			<?php
				forecastColumn($data3, 15);
				forecastColumn($data3, 16);
				forecastColumn($data3, 17);
			?>
		</div>
		<div class="row">
			<?php
				forecastColumn($data3, 18);
				forecastColumn($data3, 19);
				forecastColumn($data3, 20);
			?>
		</div>
		<div class="row">
			<?php
				forecastColumn($data3, 21);
				forecastColumn($data3, 22);
				forecastColumn($data3, 23);
			?>
		</div>
		<div class="row">
			<?php
				forecastColumn($data3, 24);
				forecastColumn($data3, 25);
				forecastColumn($data3, 26);
			?>
		</div>
		<div class="row">
			<?php
				forecastColumn($data3, 27);
				forecastColumn($data3, 28);
				forecastColumn($data3, 29);
			?>
		</div>
		<div class="row">
			<?php
				forecastColumn($data3, 30);
				forecastColumn($data3, 31);
				forecastColumn($data3, 32);
			?>
		</div>
		<div class="row">
			<?php
				forecastColumn($data3, 33);
				forecastColumn($data3, 34);
				forecastColumn($data3, 35);
			?>
		</div>
		<div class="row">
			<?php
				forecastColumn($data3, 36);
				forecastColumn($data3, 37);
				forecastColumn($data3, 38);
			?>
		</div>
		<div class="row">
			<?php
				forecastColumn($data3, 39);
			?>
		</div>
	</div>
</body>
</html>