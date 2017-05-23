<?php 

session_start();
// error_reporting(0);
require_once 'dbconfig.php';

$from = 'Kimmy Soft Inc';
$headers  = 'MIME-Version: 1.0' . "\r\n";
$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";

// Create email headers
$headers .= 'From: '.$from."\r\n".
'Reply-To: '.$from."\r\n" .
'X-Mailer: PHP/' . phpversion();

$messageContent = $_GET['messageContent']; 

$message = '<html><body style="position: relative;">';
// message html header
$message .= '<h3 style="color:teal;">Marketing Emails</h3>';
// initialize body message
$message .= '<p style="padding: 30px;">' . $messageContent . '</p>';
/*

	ADDITIONAL HTML CONTENT IF ANY(OPTIONAL)

	eg: $message .= '<p>more content and styling</p>';

*/
	$message .= '<footer style="position: absolute; bottom: 0px; width: 100%; height: 40px;">This is an auto-generated enail, it\'s not necessary to reply to it. <a href="https://kimmysoft-tech.netai.net">Kimmy Soft Inc </a></footer>';
	$message .= '</body></html>';


	?>

	<?php 

	if(!isset($_GET['send_to_all'])){
// get selected email address
		$emailSelected = $_GET['emailSelected'];
	/*

	MESSAGE CONTENT AND SELECTED EMAIL ADDRESS IS NOW READY. 

	EXECUTE MAIL FUNCTION AND GIVE FEEDBACK.

	*/
	if(mail($emailSelected , "Kimmy Soft Inc" , $message, $headers)){

		echo "Sent successfully";

	}else {

		echo "Sendind failed. Check your internet connectivity";

	}



}

if (isset($_GET['send_to_all'])) {
	$userid = $_GET['userid'];
	try{

		$emailQuery = $conn -> prepare("SELECT * from contact_addresses where added_by = :userid");
		$emailQuery->bindParam(":userid", $userid);
		$emailQuery->execute();

		while ($emailQueryRow = $emailQuery->fetch()) {

		    /*

			MESSAGE CONTENT AND ALL EMAIL ADDRESSES : NOW READY. 

			EXECUTE MAIL FUNCTION AND GIVE FEEDBACK.

			*/
			$mails = array();
			array_push($mails, $emailQueryRow['email']);

			foreach ($mails as $email) {

				if(mail($email , "Marketing Emails" , $message, $headers)){

					echo "Success <br />";
					// header("Location: email-options-send.php?allmailsent");
					
				}else {

					echo "Sending mail failed <br />";
					// header("Location: email-options-send.php?allmailsent");

				}
			}
		}
	}catch(PDOException $e){

		echo "Error ".$e->getMessage();

	}

}
?>
