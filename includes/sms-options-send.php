<?php 

session_start();
error_reporting(0);
require_once 'dbconfig.php';
require_once 'AfricasTalkingGateway.php';
$userid = $_SESSION['userid'];
$messageContent = $_GET['messageContent'];


// get the url
$url = "http://".$_SERVER['SERVER_NAME'].$_SERVER['REQUEST_URI'];

if($_GET['premium'] == 'true'){
	
	/*

	EXECUTE SEND PREMIUM MESSAGES, FIRST CHECK USER CREDIT BALANCE

	*/
	try {

		// check user balance
		$balCheckQuery = $conn->prepare("SELECT * from regusers where id= :userid");
		$balCheckQuery->bindParam(":userid" , $userid);
		$balCheckQuery->execute();

		$balCheckQueryRow = $balCheckQuery->fetch();

		if ($balCheckQueryRow['messageBal'] <= 10) {
			
			// no credit balance
			?>

			<div style="margin: 25px 0;" class="animated shake">
				<span class="alert alert-danger">Credit balance below minimum.</span>
			</div>
			
			<?php

		}else {


			// CREATE FILE TO SAVE SEND LOG DATA
			if (!file_exists('logdata')) {

				mkdir('logdata', 1);

			}

			$dateSent = date("Y:m:d H:m:s");
			/*

			EXECUTE SEND PREMIUM MESSAGES

			*/
			$contactsRes = $conn->prepare("SELECT * from contact_addresses where added_by = :userid");
			$contactsRes->bindParam(":userid" , $userid);
			$contactsRes->execute();

			// INSERT MESSAGE SEND_STATUS FOR SENT MESSAGES
			$msg_mode = "SMS";
			$recipients_no = $contactsRes->rowCount();
			$msgInsertQuery = $conn->prepare("INSERT into messages(message, fkuser_id, msg_mode, recipients_no) values(:message,:fkuser_id,:msg_mode,:recipients_no)");
			$msgInsertQuery->bindParam(":message" , $messageContent);
			$msgInsertQuery->bindParam(":fkuser_id" , $userid);
			$msgInsertQuery->bindParam(":msg_mode" , $msg_mode);
			$msgInsertQuery->bindParam(":recipients_no" , $recipients_no);
			$msgInsertQuery->execute();

			while ($contactsResRow = $contactsRes->fetch()) {
				$username   = "Danny";
				$apikey     = "e1fa9c6dea495228dfa3a1d3abcb02de63ee84b32ab332961bd4a137b3d3afe5";

				$recipients = $contactsResRow['phone'];

				$message    = $messageContent;

			    // Specify your AfricasTalking shortCode or sender id
				// $from = "SENDER NAME";

				$gateway    = new AfricasTalkingGateway($username, $apikey);

				try 
				{

					$results = $gateway->sendMessage($recipients, $message);
					$logfileSucess = fopen('logdata/sendlogSuc.txt','a');



					fwrite($logfileSucess, $dateSent);

					foreach($results as $result) {
						// WRITE SUCCESS MESSAGES TO LOG FILE SUCCESS
						fwrite($logfileSucess ,"  Number: " .$result->number." Status: " .$result->status." MessageId: " .$result->messageId." Cost: "   .$result->cost."\n");
						// CLOSE LOG FILE AFTER WRITE IS COMPLETE
						fclose($logfileSucess);
					}

					$lastid = $conn->lastInsertId();
					$selectQuery = $conn->prepare("UPDATE messages set send_status = '1' where id = :lastid");
					$selectQuery->bindParam(":lastid" , $lastid);
					$selectQuery->execute();

					header("Location: sms-options-send.php?success");

				}catch ( AfricasTalkingGatewayException $e ){
					
					// WRITE ERROR MESSAGES TO LOG FILE
					$logfile = fopen('logdata/sendlog.txt','a');


					fwrite($logfile , $dateSent." Encountered an error while sending:  ".$e->getMessage());

					// CLOSE THE FILE
					fclose($logfile);
					?>

					<span class="alert alert-danger" style="margin: 30px 0;">Some errors have occured.</span>

					<?php
				}

    			 // DONE!!! 
			}

		}
		
	} catch (PDOException $e) {
		
		// show the error in case of any
		echo "Error ".$e->getMessage();
	}

}

if(strpos($url, 'success') !== false){

	?>


	<span class="alert alert-info" style="margin: 60px 0;">Success! Sent successfully.</span>


	<?php
}

if($_GET['premium'] == 'false'){
	
	/*

	EXECUTE SEND FREE SMS, CHECK NETWORK CONNECTIVITY

	*/
	try {

		$phoneNumbersQuery = $conn->prepare("SELECT * from contact_addresses where added_by = :userid");
		$phoneNumbersQuery->bindParam(":userid" , $userid);
		$phoneNumbersQuery->execute();
		while ($phoneNumbersQueryRow = $phoneNumbersQuery->fetch()) {

			$mailNumbers = array();
			array_push($mailNumbers , $phoneNumbersQueryRow['phone']);


				// convert numbers to their corresponding mail
			foreach ($mailNumbers as $number) {

				$modNum = substr($number , -9);
				$modNum = $modNum."@safaricom.com";
				mail( $modNum , "" , $messageContent , "From: Danny");

			}
			header("Location: sms-options-send.php?free");
			
		}

		    // mail function to go here
		    // mail();
	}catch (PDOException $e) {

		echo "Error ".$e->getMessage();

	}

}

if (strpos($url, 'free') !== false) {
 	
 	?>

 	<span style="color: red;"><strong>Alert!</strong> Executed successfully, but delivery not guaranteed.</span>

 	<?php
 } 



?>