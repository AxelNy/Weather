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
<link rel="shortcut icon" href="media/logo2.png";>
<style>
	table.tableInfo {
    font-size: 1rem;
    font-weight: 400;
    line-height: 1.5;
    color: #212529;
    text-align: left;
    box-sizing: border-box;
    border-collapse: collapse;
    width: 100%;
    margin-bottom: 1rem;
    background-color: transparent;
    box-shadow: rgba(0, 0, 0, 0.35) 0px 5px 15px;
    border-radius: 10px;
}
table.tableInfo thead th {
    font-size: 1rem;
    line-height: 1.5;
    border-collapse: collapse;
    box-sizing: border-box;
    text-align: inherit;
    padding: .75rem;
    vertical-align: bottom;
    border-bottom: 2px solid #dee2e6;
    color: #fff;
    background-color: #212529;
    border-color: #32383e;
}
table.tableInfo td {
    font-size: 1rem;
    font-weight: 400;
    line-height: 1.5;
    color: #212529;
    text-align: left;
    border-collapse: collapse;
    box-sizing: border-box;
    padding: .75rem;
    vertical-align: top;
}
table.tableInfo tbody tr:nth-of-type(odd) {
    background-color: rgba(0, 0, 0, .05);
}

table.TableInfo th:first-of-type {
    border-top-left-radius: 10px;
}

table.TableInfo th:last-of-type {
    border-top-right-radius: 10px;
}

table.TableInfo tr:last-of-type td:first-of-type {
    border-bottom-left-radius: 10px;
}

table.TableInfo tr:last-of-type td:last-of-type {
    border-bottom-right-radius: 10px;
}
.table_container {
    max-width: 80vw;
    margin-left: auto;
    margin-right: auto;
}

</style>
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
		//echo "<table border='1' cellpadding='5' cellspacing='0'>";
		echo "<br>";
		echo "<div class='table_container'>";
		echo "<table class='tableInfo'>";
		
			echo "<tr>";
			    echo "<th>City</th>";
				echo "<th>Time</th>";
				echo "<th>Date</th>";
				echo "<th>TempMin</th>";
				echo "<th>TempMax</th>";
				echo "<th>FeelsLike</th>";
				echo "<th>Humidity</th>";
				echo "<th>Wind</th>";
				echo"<th>Delete?</th>";
			echo "</tr>";
			while($row = $result->fetch_assoc()){
				echo "<tr>";
				    echo "<td>".$row['city']."</td>";
					echo "<td>".$row['date1']."</td>";
					echo "<td>".$row['date2']."</td>";
					echo "<td>".$row['tempMin']."&deg;C</td>";
					echo "<td>".$row['tempMax']."&deg;C</td>";
					echo "<td>".$row['tempFeel']."&deg;C</td>";
					echo "<td>".$row['humidity']."%</td>";
					echo "<td>".$row['wind']."m/s</td>";
					echo"<td>
						 <a href='saved.php?d=yes&id=".$row['id']."'><img src='media/delete.png' alt='Delete' widht='25' height='25'>'</a>
						 </td>";
			echo "</tr>";
		}
		echo "</table>";
		echo "</div>";
		
	
	} else{
		echo "<p>Nothing Saved!</p>";
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