<?php 
session_start();
$userid = $_SESSION['userid'];

require_once 'dbconfig.php';

if (isset($_GET['upvote'])) {
	
	$post_id = $_GET['post_id'];


}

if (isset($_GET['downvote'])) {
	
	$post_id = $_GET['post_id'];
	
}