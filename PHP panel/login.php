<!DOCTYPE html>
<html>
<head>     
<link rel="stylesheet" href="style.css" type="text/css" />           
<title>Panda development project</title>
</head>

<body>
<?php

include "conf.php";

session_start();

if ($_POST) 
{
//генерация случайного текста
	function generateCode($length=6) {

    $chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHI JKLMNOPRQSTUVWXYZ0123456789";

    $code = "";

    $clen = strlen($chars) - 1;  
    while (strlen($code) < $length) {

            $code .= $chars[mt_rand(0,$clen)];  
    }

    return $code;

	}

	$error = false;
	$email = trim($_POST['email']);
	$password = trim($_POST['pass']);
	
	$errortext = "<p><b>The following errors occurred:</b></p><ul>";

	if($email == "")
	{
	$error = true;
	$errortext .= "<li>Please, fill out the field E-Mail</li>";
	} 
	
	if($password == "")
	{
	$error = true;
	$errortext .= "<li>lease, fill out the field Password</li>";
	} 
	
	$errortext .= "</ul></b>";
	
	if ($error)
	{
	echo($errortext);//Выводим текст ошибок.
	}
	
	
	if(!$error)
	{
		//Подключаемся к базе данных.
		$dbcon = mysql_connect($base_name, $base_user, $base_pass); 
		mysql_select_db($db_name, $dbcon);
		if (!$dbcon)
		{
			echo "<p>Error with connecion to MySQL!</p>".mysql_error(); exit();
		} else {
			if (!mysql_select_db($db_name, $dbcon))
			{
			echo("<p>No such database existing!</p>");
			}
		}
		// берём инфу о пользователе с таким мейлом.
		$result = mysql_query("SELECT * FROM ultra_secured_table_of_users WHERE email='$email'",$dbcon);
		$myrow = mysql_fetch_array($result);
		if (empty($myrow["email"])) {
			exit ("This email is not registered. <a href='index.html'>Try again</a>.");
		} else {
		
			if($myrow["password"] == md5($password))
			{
				date_default_timezone_set("Europe/Helsinki"); 
				$date = date('l jS \of F Y G:i:s');
				$hash = md5(generateCode(10));
				$ip = $_SERVER["REMOTE_ADDR"];
				$sql = mysql_query("UPDATE ultra_secured_table_of_users SET hash = '".$hash."', last_login = '".$date."', last_ip = '".$ip."' where email = '".$email."'", $dbcon);
				if (!$sql) {echo "Something weird with MySQL, try again.";}
				if ($sql)
				{
					 $_SESSION["email"] = $email;
					 $_SESSION["hash"] = $hash;
					 echo("You have successfully logged in to the panel!");
					 header("Location: panel.php");
				}
			}
			else
				echo("Wrong password my little friend");
			
		}
	}
}
?>
</body>
</html> 