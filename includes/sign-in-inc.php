<?php
session_start();
require_once 'dbconfig.php';

$email = str_ireplace("'", "\'", $_POST['email']);
$password = $_POST['password'];

$loginQuery = $conn->prepare("select * from regusers where email = :email");
$loginQuery->bindParam(":email", $email);
$loginQuery->execute();

if (($row = $loginQuery->fetch()) && (password_verify($password, $row['password']))) {

	$_SESSION['usermail'] = $row['email'];
	$_SESSION['userid'] = $row['id'];
	header("Location: ../index?authenticated");

} else {

	header("Location: ../index?authfailed");
	
}

?>