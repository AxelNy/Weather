<?
ob_start();
#startar sessionen
session_start();

?>
<!DOCTYPE HTML>
<html>
<head>
<meta charset="utf-8">
<link href="styles.css" rel="stylesheet" type="text/css">
<title>Sign in</title>
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
<h2>Sign in</h2>

<?
	$user="";
	$pass="";
	
	function cleanData($data){
		$data = strip_tags($data);
		$data = htmlspecialchars($data);
		return $data;	
	}
	
	if(isset($_POST['username']) && isset($_POST['password'])){
			require "dbconn.php";
			
			$user = cleanData($_POST["username"]);
			$pass = cleanData($_POST["password"]);
			
			$sql = "SELECT * FROM userAcc WHERE user='$user'";
			
			$result = $dbconn->query($sql);
			
			$row = $result->fetch_assoc();
			
			$hash = $row['pass'];
			
			$dbconn->close();
			
			if($result->num_rows==1 && password_verify($pass, $hash)){
				$_SESSION['username'] = $user;
				
				$_SESSION["logged_in"] = true;
				
				header("Location: index.php");
				exit;
					
			}else{
				$errormsg = "Fel användanamn eller lösenord";	
			}
			
			if(isset($errormsg)){
				echo"<p class='error'>".$errormsg."</p>";	
			}
	}
	
	echo'<form name="loginform" method="post" action="">';
		echo'<fieldset>';
		//echo'<legend>Sign in</legend><br>';
			echo'<label for="username">Username:</label><br>';
			echo'<input type="text" name="username" size="30" value="'.$user.'"><br>';
			echo'<label for="password">Password:</label><br>';
			echo'<input type="password" name="password" size="30" value="'.$pwd.'"><br><br>';
			echo'<input type="submit" name="submit" value="Sign in">';
		echo'</fieldset>';
		echo'</form>';
		
	echo'<form name="signup" method="post" action="newUser.php" style="text-align: center">';
		echo'<input type="submit" name="gogo" value="Sign up">';
	echo'</form>';
?>

</body>
</html>