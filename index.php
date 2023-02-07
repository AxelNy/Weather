<?php
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

//---------------------------------------------------
$apiKey = "2725c8a36862eae1980d31e322f0e7d8";
$googleApiUrl = "https://api.openweathermap.org/data/2.5/weather?id=" . $id2. "&lang=en&units=metric&APPID=" . $apiKey;

$ch = curl_init();

curl_setopt($ch, CURLOPT_HEADER, 0);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_URL, $googleApiUrl);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
curl_setopt($ch, CURLOPT_VERBOSE, 0);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
$response = curl_exec($ch);

curl_close($ch);
$data = json_decode($response);
$currentTime = time();
//---------------------------------------------------
//FORECAST-----
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



function realDate($date){
	$date2 = strtotime($date);
	return(date('D H:i', $date2));
}
$flag = $data->sys->country;
?>

<!doctype html>
<html>
<head>
<link rel="stylesheet" href="styles.css">
<link rel="shortcut icon" href="media/logo2.png";>
<title>Axel's Weather</title>
</head>
<body>
	<ul>
 		<li><a href="index.php">Home</a></li>
		<li><a href="forecast.php">Forecast</a></li>
 		<li style="float:right"><a class="active" href="#login.php">Login</a></li>
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
		<h1>Current Weather</h1>
        <h2><?php echo $data->name; ?> Weather Status <img width="28" height="21" 
	 	src="https://flagcdn.com/28x21/<?php echo strtolower($flag) ?>.png"/></h2>
		
        <div class="time">
            <div><?php echo date("l g:i a", $currentTime); ?></div>
            <div><?php echo date("jS F, Y",$currentTime); ?></div>
            <div><?php echo ucwords($data->weather[0]->description); ?></div>
        </div>
        <div class="weather-forecast">
            <img
                src="http://openweathermap.org/img/w/<?php echo $data->weather[0]->icon; ?>.png"
                class="weather-icon" /> <?php echo $data->main->temp_max; ?>&deg;C
				<span class="min-temperature"><?php echo $data->main->temp_min; ?>&deg;C</span>
        </div>
        <div class="time">
            <div>Humidity: <?php echo $data->main->humidity; ?> %</div>
            <div>Wind: <?php echo $data->wind->speed; ?> km/h</div>
    	</div>
		<br>
		<div class="row">
			<div class="column">
				<?php echo realDate($data3->list[0]->dt_txt);?>
				<br>
				<img src="http://openweathermap.org/img/w/<?php echo $data3->list[0]->weather[0]->icon; ?>.png"
                class="weather-icon" /> <?php echo $data3->list[0]->main->temp_max; ?>&deg;C
				<span class="min-temperature"><?php echo $data3->list[0]->main->temp_min; ?>&deg;C</span>
			</div>
			<div class="column">
				<?php echo realDate($data3->list[1]->dt_txt);?>
				<br>
				<img src="http://openweathermap.org/img/w/<?php echo $data3->list[1]->weather[0]->icon; ?>.png"
                class="weather-icon" /> <?php echo $data3->list[1]->main->temp_max; ?>&deg;C
				<span class="min-temperature"><?php echo $data3->list[1]->main->temp_min; ?>&deg;C</span>
			</div>
			<div class="column">
				<?php echo realDate($data3->list[2]->dt_txt);?>
				<br>
				<img src="http://openweathermap.org/img/w/<?php echo $data3->list[2]->weather[0]->icon; ?>.png"
                class="weather-icon" /> <?php echo $data3->list[2]->main->temp_max; ?>&deg;C
				<span class="min-temperature"><?php echo $data3->list[2]->main->temp_min; ?>&deg;C</span>
			</div>
		</div>
	</div>
	<br>
</body>
</html>
	

	
	
	
	
	