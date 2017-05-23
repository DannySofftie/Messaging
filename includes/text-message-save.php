<?php

session_start();
date_default_timezone_set("Africa/Nairobi");
require_once('dbconfig.php');

$userid = $_SESSION['userid'];
$friend_id = $_GET['friend_id'];
$dateSent = date("Y:m:d");
$timeSent = date("H:m");
// remove whitespaces
$message_content = trim($_GET['message_content']);

if(!empty($message_content)){

// TO CHAT_MESSAGES TABLE TO ALLOW CHATS
	try {

	// insert into table chat_messages to allow chats
		$chatMsgQuery = $conn->prepare("INSERT into chat_messages (my_id, friend_id,message,dateSent) values(:my_id , :friend_id, :message, :dateSent)");
		$chatMsgQuery->bindParam(":my_id", $userid);
		$chatMsgQuery->bindParam(":friend_id", $friend_id);
		$chatMsgQuery->bindParam(":message", $message_content);
		$chatMsgQuery->bindParam(":dateSent" , $dateSent);
		$chatMsgQuery->execute();

	} catch (PDOException $e) {

	// catch the message error
		echo "Error chat messages ".$e->getMessage();

	}
}

