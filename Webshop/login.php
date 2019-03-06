<?php
session_start();
$html = file_get_contents("login.html");
echo $html;
$server = 'localhost';
$user = 'root';
$pass = '';
$db = 'webshop';
$connection = new mysqli($server, $user, $pass, $db) or die("unable");



if($_POST != null){
$mail = $_POST['email'];
$password = $_POST['password'];
//$sql = $connection->prepare("SELECT password FROM users WHERE email = (?)");
//$fetch = "SELECT password FROM users WHERE email = '$mail'";
$result = mysqli_query($connection, "SELECT email, password FROM users WHERE email = '$mail' AND password = '$password'");
	if(mysqli_num_rows($result) > 0){
		$html = str_replace('login', 'kundshop', $html);
		$timeStamp = time()+10800;
		$_SESSION['mail'] = $mail;
		setcookie($mail, true, $timeStamp);
		header("Location: kundshop.php");
		exit;
		
	}
}
$connection->close();



?>