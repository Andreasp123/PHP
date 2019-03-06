<?php
/*
Använder mig av en bootstrapper för htmlkoden. Det var sex produkter inlagda vilket jag tyckte var lagom
men det går så klart även att köra en explode och loopa fram godtyckligt antal produkter om man så vill.

*/

session_start();
$kundshop = file_get_contents("kundshop.html");

$server = 'localhost';
$user = 'root';
$pass = '';
$db = 'webshop';
$connection = new mysqli($server, $user, $pass, $db) or die("unable");

//return to login page if user it not signed in
	if(!isset($_COOKIE)){
		header("Location: login.php");
		exit;
	}
	
	if(isset($_SESSION['mail'])){
	$user = $_SESSION['mail'];
	} 
	
	
/* Check if user added product to cart. If so, product is saved to db for later use at checkout.
	Förmodligen inte den snyggaste lösningen men en fungerande sådan.
*/
	if($_POST != null){
		//if user sign up for email list
		if(isset($_POST['email_list']) || isset($_POST['test'])){
			$signUp = $_POST['email_list'];
			$sql = "INSERT INTO emaillist (email) VALUES '$signUp')";
		} 
		for($i = 0; $i < 7; $i++){
			if(isset($_POST['product' . $i])){
				echo 'product' . $i;
				$product = 'product' . $i;
				$sql = "INSERT INTO cart (email, product) VALUES ('$user', '$product')";
				$add_to_cart = mysqli_query($connection, $sql);
				
			}
		}
	} 
			
	

	

//fetch product images from database
		$counter = 1;
		$product_images = "SELECT ProductImage FROM products";
		$products = $connection->query($product_images);
		while($row = $products->fetch_assoc()) {
			$replace = '---product' . $counter . '---';
			$im = stripslashes($row["ProductImage"]);
			$image =  '<img src="data:image/png;base64, '.base64_encode($row["ProductImage"]).'"class="img-responsive" style="width:100%" alt="Image">';
			$kundshop = str_replace($replace, $image, $kundshop);
			$counter++;
		}

echo $kundshop;

?>