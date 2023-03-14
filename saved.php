<?php
ob_start();
session_start();

if(!isset($_SESSION["logged_in"]) || $_SESSION["logged_in"] !== true)
	{
		header("Location: login.php");
	}

?>
<!doctype html>
<html>
<head>
<link rel="stylesheet" href="styles.css">
<meta charset="utf-8">
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
				echo '</ul>';
					
			}
		
	
	
	require "dbconn.php";
	$username3 = $_SESSION['username'];
	$sql = "SELECT * FROM saveddata WHERE username = '$username3' ORDER BY id ASC";
	
	$result = $dbconn->query($sql);
	
	if($result->num_rows >0){
		echo "<table border='1' cellpadding='5' cellspacing='0'>";
			echo "<tr>";
				echo "<th>ID</th>";
				echo "<th>Data1</th>";
				echo "<th>Date2</th>";
				echo "<th>TempMin</th>";
				echo "<th>TempMax</th>";
				echo "<th>FeelsLike</th>";
				echo "<th>Humidity</th>";
				echo "<th>Wind</th>";
				echo"<th>Delete?</th>";
			echo "</tr>";
			while($row = $result->fetch_assoc()){
				echo "<tr>";
					echo "<td>".$row['id']."</td>";
					echo "<td>".$row['date1']."</td>";
					echo "<td>".$row['date2']."</td>";
					echo "<td>".$row['tempMin']."</td>";
					echo "<td>".$row['tempMax']."</td>";
					echo "<td>".$row['tempFeel']."</td>";
					echo "<td>".$row['humidity']."</td>";
					echo "<td>".$row['wind']."</td>";
					echo"<td>
						 <a href='saved.php?d=yes&id=".$row['id']."'>Delete</a>
						 </td>";
			echo "</tr>";
		}
		echo "</table>";
	
	} else{
		echo "<p>Hittade inga poster!</p>";
	}
	
	$dbconn->close();
	
	if(isset($_GET['d']) && $_GET['d']=='yes'){
			require "dbconn.php";
			
			$sql = "DELETE FROM saveddata WHERE id=".$_GET['id'];
			
			$delete = $dbconn->query($sql);
			
			$dbconn->close();
			
			header("Location: saved.php");
			exit;	
		}
		?>
			
	
	
	
</body>
</html>