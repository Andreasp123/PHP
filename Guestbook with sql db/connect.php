<?php


$server = 'localhost';
$user = 'root';
$pass = '';
$db = 'transactiondb';
$connection = new mysqli($server, $user, $pass, $db) or die("unable");


$name = $_POST['name'];
$mail = $_POST['email'];
$homepage = $_POST['homepage'];
$comment = $_POST['comment'];
$time = date("Y-m-d H:i:s");

$target_dir = "images/";
$filename = $_FILES["image"]["name"];
$target_file = $target_dir . ($filename);
move_uploaded_file($_FILES["image"]["tmp_name"], $target_file); 
$image_name = $_FILES['image']["name"]; //egentligen filename
$mime = pathinfo($image_name,PATHINFO_EXTENSION);


//$image = file_get_contents($_FILES['image']['tmp_name']);
//$image_name = addslashes($_FILES['file']['image']);
//echo $image;



//insert into db
$sql = $connection->prepare("INSERT INTO comments (`name`,`mail`,`homepage`,`comment`,`time`) VALUES (?,?,?,?,?)");
$sql->bind_Param('sssss',$name,$mail,$homepage,$comment,$time);
$sql->execute();
if($sql){
	$sql->close();
  if(!insertPictures($connection, $target_file, $mime, $time)){
	  rollback($connection, $name,$mail,$homepage,$comment,$time);
	  //la till content
  }
} else {
	die("ERROR: Could not connect. " . mysqli_connect_error());
}

//insert pictures if first query is successful
function insertPictures($connection, $target_file, $mime, $time){
	 
	$sql = $connection->prepare("INSERT INTO pictures (picture, mime, time)  VALUES (?,?,?)");
	$sql->bind_Param('sss',$target_file,$mime, $time);
	$sql->execute();
	if($sql){
		$sql->close();
		return true;
	} else {
		$sql->close();
		return false;
	}
	 
	// gamla nedan
	/*
	$sql= "INSERT INTO pictures (picture, mime, time) VALUES ('$image_content','$mime','$time')";
	$query=mysqli_query($connection,$sql);
	if($query){
		//return true;
	} else{
		return false;
	}
	*/
}

//do a rollback if insertion of picture failed
function rollback($name,$mail,$homepage,$comment,$time){
	$sql = "DELETE FROM comments WHERE name = '$name' AND mail = '$mail' AND time = '$time'";
	$query=mysqli_query($connection,$sql);
	
}


$all_comments = file_get_contents('comments.html');
$html_pieces = explode("<!--===xxx===-->", $all_comments);

//fetcha från databasen
$counter = 1;
$fetch = "SELECT name, mail, homepage, comment, time FROM comments";
$comments = $connection->query($fetch);
    while($row = $comments->fetch_assoc()) {
		$comm = $html_pieces[1];
		$comm = str_replace('---count---', $counter, $comm);
		$comm = str_replace('---timestamp---', $row["time"], $comm);
		$comm = str_replace('---website---', $row["homepage"], $comm);
		$comm = str_replace('---author---', $row["name"], $comm);
		$comm = str_replace('---email---', $row["mail"], $comm);
		$comm = str_replace('---comment---', $row["comment"], $comm);
		
		//fetch image that goes with the comment
		$time = $row["time"];
		$fetchImage = "SELECT picture FROM pictures WHERE time = '$time'";
		$picture = $connection->query($fetchImage);
		while($roww = $picture->fetch_assoc()) {
			//echo "<img src='Image/".$roww['picture']."' />";
			//echo "<img src=\"image.php?id=".$roww['picture']."\"></td></tr>";
			$im = stripslashes($roww["picture"]);
			$comm = str_replace('---image---', $im, $comm);
		}
		
        echo $comm;
		$counter++;
    }
	$connection->close();

?>

