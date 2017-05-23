<?php 

session_start();
require_once 'dbconfig.php';
error_reporting(0);

$userid = $_SESSION['userid'];
$messageContent = "";
$messageContent = $_GET['messageContent'];

try {

	// select all phone numbers
	$phoneNoQuery = $conn -> prepare("SELECT * from contact_addresses where added_by = :userid");
	$phoneNoQuery->bindParam(":userid" , $userid);
	$phoneNoQuery->execute();


	if ( $phoneNoQuery->rowCount() == 0) {
		
		?>

		<!--


			MESSAGE THAT NO NUMBER(s) EXISTS 


		-->

		<span class="alert alert-warning col-lg-12 col-md-12 col-sm-12"><strong>Error! </strong> No phone numbers exist in your records.</span>


		<?php
	}else {
		

		?>

		<!--


			SHOW COUNT FOR THE PHONE NUMBERS THAT EXIST 


		-->
		<input type="hidden" id="messageContent" value=" <?php echo $messageContent; ?> " >
		<span class="alert alert-success col-lg-12 col-md-12 col-sm-12">You have  <span class="badge badge-success"><?php echo $phoneNoQuery->rowCount();  ?></span> phone numbers.</span>
		<ul>
			<li>Premium: Fast and convenient. Only available under subscription.</li>
			<li>Free: Free to send messages but slow and inconvenient. Message delivery is not guaranteed.</li>
		</ul>
		<button type="button" class="btn btn-sm btn-outline-danger text-uppercase" id="send_premium">send premium<span class="mdi mdi-currency-usd"></span></button>&nbsp;
		<button type="button" class="btn btn-sm btn-outline-info text-uppercase" id="send_free">send free <span class="mdi mdi-tag-text-outline"></span></button>

		
		<?php

	}
} catch (PDOException $e) {
	
	// output an error that occurs
	echo "Error ".$e->getMessage();

}


?>

<div class="loader" style="display: none;">
	<img src="../images/preloader/25.gif" alt="" id="sending">
</div>
<hr style="visibility: hidden;"><hr style="visibility: hidden;">
<div class="message_sent_status">

</div>

<script type="text/javascript">
	$(function(){

		var $messageContent = $('#messageContent').val();

		// show preloader 
		$('#send_premium').click(function(event) {
			/* Act on the event */
			$('.message_sent_status').html(
					$('.loader').html()
				);

			var $premium = 'true';

			// now send message to selected recipient
			var xhttp = new XMLHttpRequest();
			xhttp.open('GET','../includes/sms-options-send.php?messageContent=' + $messageContent + '&premium=' + $premium);
			xhttp.onreadystatechange = function(){
				if(this.readyState == 4 && this.status == 200){

					$('.message_sent_status').html(xhttp.responseText);

				}
			};
			xhttp.send(); 
		});
		$('#send_free').click(function(event) {
			/* Act on the event */
			$('.message_sent_status').html(
					$('.loader').html()
				);

			var $premium = 'false';

			// now send message to selected recipient
			var xhttpF = new XMLHttpRequest();
			xhttpF.open('GET','../includes/sms-options-send.php?messageContent=' + $messageContent + '&premium=' + $premium);
			xhttpF.onreadystatechange = function(){
				if(this.readyState == 4 && this.status == 200){

					$('.message_sent_status').html(xhttpF.responseText);

				}
			};
			xhttpF.send(); 

		});
		

	});
</script>