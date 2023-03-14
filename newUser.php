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
<link rel="stylesheet" href="styles.css">
<link rel="shortcut icon" href="media/logo2.png";>
<meta charset="utf-8">
<title>Sign Up</title>
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
<h2>Sign Up</h2>
<?

	$user="";
	$pass="";

	echo '<form name="newuser" method="post" action="newUser.php?a=yes">';
		echo '<fieldset>';
			echo '<p>';
				echo '<label for="username">Username:</label><br>';
				echo '<input type="text" name="username" required>';
			echo '</p>';
			echo '<p>';
				echo '<label for="password">Password:</label><br>';
				echo '<input type="password" name="password" required>';
			echo '</p>';
			echo '<p>';
				echo '<label for="password2">Repeat Password:</label>';
				echo '<input type="password" name="password2" required>';
			echo '</p>';
			echo '<p>';
				echo '<input type="submit" value="Submit"';
			echo '</p>';
		echo '</fieldset>';
	echo '</form>';
	
	echo'<form name="signin" method="post" action="login.php" style="text-align: center">';
		echo '<p>Already have a account?</p>';
		echo'<input type="submit" name="gogo" value="Login">';
	echo'</form>';
	
	#filtreringsfunktion
	function cleanData($data){
		$data = strip_tags($data);
		$data = htmlspecialchars($data);
		return $data;	
	}
	
	$user = cleanData($_POST['username']);
	$pass = cleanData($_POST['password']);
	
	#Detta händer om a finns, då man klickar på skapa-knappen
	if(isset($_GET['a'])&&$_GET['a']=='yes'){
		require"dbconn.php";
		
		//$userSQL = 'SELECT user FROM userAcc WHERE user ="'.$user.'"';
		//$result = $dbconn->query($userSQL);
		//if ($result !=NULL){
			//echo "<p class='error'>Username is taken</p>";
			//$dbconn->close();
		//}
		if(empty($_POST['username']) || empty($_POST['password']) || empty($_POST['password2'])){
			echo "<p class='error'>Error! No EMPTY FIELDS!</p>";	
		}else if(strcmp($_POST['password'], $_POST['password2']) !==0){
			echo "<p class='error' style='text-align:center;'>Passwords don't match</p>";	
		}else{
				

			
			#krypterar lösenordet med ARGON2I
			$pass = password_hash($pass, PASSWORD_ARGON2I);	
			
			$sql = "INSERT INTO userAcc(user, pass) VALUES('$user', '$pass')";
			
			$dbconn->query($sql);
			
			$dbconn->close();
			header("Location: index.php");
			exit;
		}
		
	}

?>
</body>
</html>