<?php 

require_once 'dbconfig.php';

$userid = $_GET['userid'];

$messageContent = $_GET['messageContent'];

$msg_mode = "SAVE";

try{

	$saveQuery = $conn->prepare("INSERT into messages (message, fkuser_id, msg_mode) values (:messageContent, :userid, :msg_mode)");
	$saveQuery->bindParam (":messageContent" , $messageContent);
	$saveQuery->bindParam (":userid" , $userid);
	$saveQuery->bindParam (":msg_mode" , $msg_mode);

	if ($saveQuery->execute()) {

		sleep(1);
		$getLastID = $conn->lastInsertId();
		$saveStatusChange = $conn->prepare("UPDATE messages set save_status = '1' where id = :getLastID");
		$saveStatusChange->bindParam(":getLastID" , $getLastID);

		if($saveStatusChange->execute()){

			?>

			<div class="alert alert-success alert-dismissible fade show" role="alert">
				<button type="button" class="close" data-dismiss="alert" aria-label="Close">
					<span aria-hidden="true">&times;</span>
					<span class="sr-only">Close</span>
				</button>
				<strong>Success</strong> Your message was saved successfully.
			</div>

			<?php
		}

	}
}catch(PDOException $e){

	echo "Error ".$e->getMessage();

}
?>
