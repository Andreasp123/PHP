<?php
session_start();
$server = 'localhost';
$user = 'root';
$pass = '';
$db = 'webshop';
$connection = new mysqli($server, $user, $pass, $db) or die("unable");
$html = file_get_contents('cart.html');
$pieces = explode("<!--===xxx===-->", $html);
echo "<h1>Kundkorg</h1>";
/*
Fetching the products that were added to the cart from the database.
*/
if(!isset($_COOKIE)){
		header("Location: login.php");
		exit;
}
	
	/*
	If customer removes a product from cart
	The hardcoded 7 is because there is currently only 6 items avaliable to buy.
	Not the prettiest solution, admittedly.
	*/
	if(isset($_SESSION['mail'])){
	$user = $_SESSION['mail'];
	if($_POST != null){
		for($i = 0; $i < 7; $i++){
			if(isset($_POST['product' . $i])){
				$product = 'product' . $i;
				$sql = "DELETE FROM cart WHERE email = '$user' AND product = '$product'";
				$remove_from_cart = mysqli_query($connection, $sql);
				
			}
		}
	} 

	
	$sql = "SELECT product FROM cart WHERE email = '$user'";
	$cart = $connection->query($sql);
	while($row = $cart->fetch_assoc()) {
		$prod_piece = $pieces[1];
		$prod = $row['product'];
		
		$product_images = "SELECT ProductImage FROM products WHERE ProductName = '$prod'";
		$products = $connection->query($product_images);
		while($roww = $products->fetch_assoc()){
			$im = stripslashes($roww["ProductImage"]);
			$image =  '<img src="data:image/png;base64, '.base64_encode($roww["ProductImage"]).'"class="img-responsive" style="width:20%" alt="Image">';
			$prod_piece = str_replace('---picture---', $image, $prod_piece);
			$prod_piece = str_replace('--product--', $prod, $prod_piece);
			echo $prod_piece;

		}	
	}	
} 

?>