<?php
$html = file_get_contents("createuser.html");
$server = 'localhost';
$user = 'root';
$pass = '';
$db = 'webshop';
$connection = new mysqli($server, $user, $pass, $db) or die("unable");
echo $html;

if(isset($_POST['name']) && ($_POST['email']) && ($_POST['password'])){
	$name = $_POST['name'];
	$email = $_POST['email'];
	$password = $_POST['password'];
	//$sql = $connection->prepare("SELECT email FROM users WHERE email = (?)");
	//$sql->bind_Param('s', $email);
	//$sql->execute();
	$check_if_exists = ("SELECT email FROM users WHERE email = ('$email')");
	$result = mysqli_query($connection, $check_if_exists);
	if(mysqli_num_rows($result) > 0){
		echo "User already exist";
	} else{

		$create_user = $connection->prepare("INSERT INTO users (name, email, password) VALUES (?,?,?)");
		$create_user->bind_Param('sss', $name, $email, $password);
		$create_user->execute();
		header("Location: login.php");
		exit;
	}
	
}
$connection->close();


?>