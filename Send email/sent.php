<?php
require 'PHPMailerAutoload.php';
$mail = new PHPMailer;

$mail->IsSMTP();
$mail->Host = 'smtp.gmail.com';
$mail->Port = 465;
//465
$mail->SMTPAuth = true;
$mail->Username = 'your@mail';
$mail->Password = 'removed';
$mail->SMTPSecure = 'ssl';
$mail->SMTPOptions = array(
  'ssl' => array(
    'verify_peer' => false,
    'verify_peer_name' => false,
    'allow_self_signed' => true
  )
);

	if(htmlspecialchars($_POST["password"]) == "1234"){
		$recipient = htmlspecialchars($_POST['to']);
		$tester = ($_POST["to"]);
		
		$mail->from = htmlspecialchars($_POST["from"]);
		$mail->addAddress($recipient, 'name');
		//htmlspecialchars($_POST['to']);
		$mail->addCC = htmlspecialchars($_POST["cc"]);
		$mail->addBCC = htmlspecialchars($_POST["bcc"]);
		$mail->Subject = htmlspecialchars($_POST["subject"]);
		$mail->Body = htmlspecialchars($_POST["message"]);
		$mail->isHTML(true);
	}
	
	if($mail->send()){
		foreach($_POST as $key => $value){
			if($key == "message"){
				echo $value;
			}
			
		}
	}
	else{
		echo "Error: " . $mail->ErrorInfo;
		
	}
	

?>