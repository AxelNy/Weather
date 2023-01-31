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

$name2 = $_POST['city'];
$id2 = getIdByName($name2, $data2);

/*if ($id2) {
    echo "The id of '$name2' is: $id2";
} else {
    echo "No match found for '$name2'";
}*/
//---------------------------------------------------
$apiKey = "2725c8a36862eae1980d31e322f0e7d8";
//$cityId = "651299";
//$city = $_POST['city'];
$googleApiUrl = "http://api.openweathermap.org/data/2.5/weather?id=" . $id2. "&lang=en&units=metric&APPID=" . $apiKey;

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

?>

<!doctype html>
<html>
<head>
<title>Current Weather</title>

<style>
body {
    font-family: Arial;
    font-size: 0.95em;
    color: #929292;
	
}

.report-container1 {
	text-align: center;
    border: #E0E0E0 1px solid;
    padding: 20px 40px 40px 40px;
    border-radius: 2px;
    width: 550px;
    margin: 0 auto;
	
}
.report-container2 {
    border: #E0E0E0 1px solid;
    padding: 20px 40px 40px 40px;
    border-radius: 2px;
    width: 550px;
    margin: 0 auto;
	
}

.weather-icon {
    vertical-align: middle;
    margin-right: 20px;
}

.weather-forecast {
    color: #212121;
    font-size: 1.2em;
    font-weight: bold;
    margin: 20px 0px;
}

span.min-temperature {
    margin-left: 15px;
    color: #929292;
}

.time {
    line-height: 25px;
}
</style>

</head>
<body>
    <div class="report-container1">
		<h1>Current Weather</h1>
		<p>Enter location</p>
		<form method="post">
    		<input type='text' name="city" value="Vaasa"/>
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
        <h2><?php echo $data->name; ?> Weather Status</h2>
        <div class="time">
            <div><?php echo date("l g:i a", $currentTime); ?></div>
            <div><?php echo date("jS F, Y",$currentTime); ?></div>
            <div><?php echo ucwords($data->weather[0]->description); ?></div>
        </div>
        <div class="weather-forecast">
            <img
                src="http://openweathermap.org/img/w/<?php echo $data->weather[0]->icon; ?>.png"
                class="weather-icon" /> <?php echo $data->main->temp_max; ?>&deg;C<span
                class="min-temperature"><?php echo $data->main->temp_min; ?>&deg;C</span>
        </div>
        <div class="time">
            <div>Humidity: <?php echo $data->main->humidity; ?> %</div>
            <div>Wind: <?php echo $data->wind->speed; ?> km/h</div>
        </div>
    </div>


</body>
</html>