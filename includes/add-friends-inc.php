<?php 
date_default_timezone_set('Africa/Nairobi');
require_once 'dbconfig.php';
$friend_id = $_GET['friend_id'];

$current_user_id = $_GET['current_user_id'];

$friends_since = date('Y:m:d');
// add friend
try{
	$addQuery = $conn->prepare("INSERT into friends_list(my_id , friend_id , friends_since) values(:current_user_id , :friend_id , :friends_since)");
	$addQuery->bindParam(":current_user_id" , $current_user_id);
	$addQuery->bindParam(":friend_id" , $friend_id);
	$addQuery->bindParam(":friends_since" , $friends_since);
	if($addQuery->execute()){

		?>

		<div class="alert alert-info">
			<strong>Success!</strong> Friend added.
		</div>

		<?php
	}
}catch(PDOException $e){
	echo "<div class='row'>";
	echo "<span class='alert alert-danger col-lg-12 col-md-12'>Erorr ".$e->getMessage()."</span>";
	echo "</div>";
}


?>



