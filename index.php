<?php
	ob_start();
	session_start();
	


	date_default_timezone_set("Europe/Helsinki");

	//Utan detta funkar inte sidan, pga .json filen (vet ej om det är bästa sättet att fiaxa problemet)
	ini_set('memory_limit', '1024M');

	//citylist.json är en fil med lite info om städer
	$json = file_get_contents('citylist.json');
	$data2 = json_decode($json, true);

	if(isset($_POST['city']) && !empty($_POST['city'])) {
    	$_SESSION['city'] = $_POST['city'];
	}

	//functionen tar stads namnet söker i data efter stadnamnet och hämtar stadens ID
	//stadens id behövs för APIn skull
	function getIdByName($name2, $data2) {
		foreach ($data2 as $row2) {
			if ($row2['name'] == $name2) {
				return $row2['id'];
			}
		}
		return null;
	}
		
	//gör stad namnet användaren skriver in till rätt format
	$name3 = ucfirst(strtolower($_SESSION['city']));
	$name2 =  $name3;

	//$id2 = getIdByName($name2, $data2);

	//om användaren lämnar tomt visar sidan vädret för Korsholm
	if ($name2==""){
		$id2 = "651299";
	}else{
		$id2 = getIdByName($name2, $data2);
	}

	//---------------------------------------------------
	//api nyckeln för mitt openweathermap konto
	$apiKey = "2725c8a36862eae1980d31e322f0e7d8";
	//Url för vädret just nu
	$googleApiUrl = "https://api.openweathermap.org/data/2.5/weather?id=" . $id2. "&lang=en&units=metric&APPID=" . $apiKey;

	//hämtar data för vädret just nu
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
	//Url för framtida vädret
	$googleApiUrlFore = "https://api.openweathermap.org/data/2.5/forecast?id=" . $id2. "&lang=en&units=metric&APPID=" . $apiKey;

	//hämtar data för framtida vädret
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


	//function för att sätta datum i rätt format
	function realDate($date){
		$date2 = strtotime($date);
		return(date('D H:i', $date2));
	}
	//tar landets namn t.ex. fi
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
				<?php //<input type='text' name="city"/>;?>
				<input type='text' name="city" value="<?php echo isset($_SESSION['city']) ? $_SESSION['city'] : ''; ?>"/>
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
				<div><?php echo date("l g:i a", $currentTime + $sec); ?></div>
				<div><?php echo date("jS F, Y",$currentTime + $sec);?></div>
				<div><?php echo ucwords($data->weather[0]->description); ?></div>
			</div>
			<div class="weather-forecast">
				<img
					src="http://openweathermap.org/img/w/<?php echo $data->weather[0]->icon; ?>.png"
					class="weather-icon" /> <?php echo $data->main->temp_max; ?>&deg;C
					<span class="min-temperature"><?php echo $data->main->temp_min; ?>&deg;C</span>
					<div>Feels like: <?php echo $data->main->feels_like; ?>&deg;C</div> 
			</div>
			<div class="time">
				<div>Humidity: <?php echo $data->main->humidity; ?> %</div>
				<div>Wind: <?php echo $data->wind->speed; ?> M/s</div>
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
			<?php
			if(isset($_SESSION["logged_in"]) || $_SESSION["logged_in"] == true){
				echo '<form name="saveWeather" method="post" action="index.php">';
					echo '<input type="hidden" name="s" value="yes">';
					echo '<input type="submit" name="submit" value="Save">';
					echo '<img src="media/info.png" title="Saves most of the data from this page to the SAVED page." alt="Info" widht="25" height="25">';
				echo '</form>';
			}

			if(isset($_POST['s']) && $_POST['s']=='yes'){
				require"dbconn.php";
				$username3 = $_SESSION['username'];
    
    
    			$date1 = date("g:i a", $currentTime + $sec);
				$date2 = date("jS F, Y",$currentTime + $sec);

				$city = $data->name;
				$tempMIN = $data->main->temp_min;
				$tempMAX = $data->main->temp_max;
				$tempFEEL = $data->main->feels_like;
				$humidity = $data->main->humidity;
				$wind = $data->wind->speed;

				$sqlSave = "INSERT INTO saveddata(username, city, date1, date2, tempMin, tempMax, tempFeel, humidity, wind) VALUES('$username3', '$city','$date1', '$date2', '$tempMIN', '$tempMAX', '$tempFEEL', '$humidity', '$wind')";

				$dbconn->query($sqlSave);

				$dbconn->close();

				echo '<p>Data Saved!</p>';
				// Redirect to the same page after form submission
				header("Location: index.php");
				exit;
			}
			?>
		</div>
		<br>
	</body>
</html>
	

	
	
	
	
	