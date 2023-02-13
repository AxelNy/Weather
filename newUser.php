<?
	session_start();
	ob_start();
	
	//if(!isset($_SESSION["logged_in"]) || $_SESSION["logged_in"] !== true)
	//{
		//header("Location: login.php");
	//}
?>
<!DOCTYPE HTML>
<html>
<head>
<meta charset="utf-8">
<title>Ny användare</title>
<style>
	fieldset{
		width:250px;
		border: solid;
		border-radius:25px;
		padding:15px;
		background-color:#FFF;
		margin-left: auto ;
		margin-right: auto ;	
	}
	h2{
		text-align:center;
		color:black;
	
	}
	form{
  		left: 34%;
}
</style>
</head>

<body>
<h2>Skapa ny admin</h2>
<?

	$user="";
	$pass="";

	echo '<form name="newuser" method="post" action="newUser.php?a=yes">';
		echo '<fieldset>';
			echo '<p>';
				echo '<label for="username">Användarnamn:</label>';
				echo '<input type="text" name="username" required>';
			echo '</p>';
			echo '<p>';
				echo '<label for="password">Lösenord:</label><br>';
				echo '<input type="password" name="password" required>';
			echo '</p>';
			echo '<p>';
				echo '<label for="password2">Upprepa lösenordet:</label>';
				echo '<input type="password" name="password2" required>';
			echo '</p>';
			echo '<p>';
				echo '<input type="submit" value="Skapa"';
			echo '</p>';
		echo '</fieldset>';
	echo '</form>';
	
	#filtreringsfunktion
	function cleanData($data){
		$data = strip_tags($data);
		$data = htmlspecialchars($data);
		return $data;	
	}
	
	#Detta händer om a finns, då man klickar på skapa-knappen
	if(isset($_GET['a'])&&$_GET['a']=='yes'){
		require"dbconn.php";
		
		if(empty($_POST['username']) || empty($_POST['password']) || empty($_POST['password2'])){
			echo "<p class='error'>Du måste fylla i alla fält</p>";	
		}else if(strcmp($_POST['password'], $_POST['password2']) !==0){
			echo "<p class='error' style='text-align:center;'>Lösenordet matchar inte</p>";	
		}else{
			$user = cleanData($_POST['username']);
			$pass = cleanData($_POST['password']);	

			
			#krypterar lösenordet med ARGON2I
			$pass = password_hash($pass, PASSWORD_ARGON2I);	
			
			$sql = "INSERT INTO userAcc(user, pass) VALUES('$user', '$pass')";
			
			$dbconn->query($sql);
			
			$dbconn->close()	;
			header("Location: index.php");
			exit;
		}
		
	}

?>
</body>
</html>