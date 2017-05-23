<?php

require_once 'dbconfig.php';

$username = ucwords(strtolower($_GET['username']));
$phone = null;
if (isset($_GET['phonne'])) {
	$phone = "+254" . substr($_GET['phone'] , -9);
}
$email = $_GET['email'];
$password = $_GET['password'];

// generate a crypt secure random confirmation code
$random_bytes = openssl_random_pseudo_bytes(3, $cstrong);
$rand_to_hex = bin2hex($random_bytes);
$confirm_code = strtoupper($rand_to_hex);

// hash password using bcrypt algo
$options = [
'cost' => 11,
'salt' => mcrypt_create_iv(22, MCRYPT_DEV_URANDOM),
];

$hashPassword = password_hash($password, PASSWORD_BCRYPT, $options); 

// now register

$userInsertSQL = $conn->prepare("insert into regusers(email, password, confirm_code,phone_number, fname) values(:email, :password, :confirm_code,:phone ,:username)");
$userInsertSQL->bindParam(":email" , $email);
$userInsertSQL->bindParam(":password" , $hashPassword);
$userInsertSQL->bindParam(":username" , $username);
$userInsertSQL->bindParam(":phone" , $phone);
$userInsertSQL->bindParam(":confirm_code" , $confirm_code);

if ($userInsertSQL->execute()) {

	mail($email , 'ReachClients.ME Confirmation code' . $confirm_code , 'From: ReachClients.ME' );
	/*

	REPLACE WITH SMS SENDING API

	*/

	/*

	EMAIL TO SEND CODE TO GO HERE

	*/
	?>

	<span class="alert alert-success">Successfully registered. Verification code sent to your email</span>
	<?php
	
} else {

	?>

	<span class="alert alert-warning">Registration failed. Try again</span>

	<?php
}

?>

