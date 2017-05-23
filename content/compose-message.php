<?php 
session_start();
$userid = $_SESSION['userid'];
?>
<style type="text/css" media="screen">
	.compose{
		height: 100%;
		overflow: scroll;
	}
	.messages{
		height: 93%;
		overflow-y: scroll;
	}
	div.jumbotron{
		padding: 25px 20px;
	}

	::-webkit-scrollbar{
		width: 0px;
		background: transparent;
	}
	::-webkit-scrollbar-thumb{
		background-color: #ff0000;
	}
	#message_area{
		margin: 15px 0;
	}
	.warning_error{
		display: none;
	}
	
</style>

<div class="container compose">
	<div class="jumbotron col-lg-12 col-md-12 text-center row">
		<div class="col-lg-8">
			<h5>Compose your broadcast message here.</h5>
			<h6>Several options have been provided in here, choose how to reach your clients.</h6>
		</div>
		<div class="col-lg-4 text-left">
			<div><span class="mdi mdi-message-reply-text"></span> Total messages sent <span class="badge badge-primary float-right">178</span></div>
			<div><span class="mdi mdi-email"></span> Total emails sent <span class="badge badge-success float-right">92</span></div>
			<div><span class="mdi mdi-account-check"></span> Total clients with emails <span class="badge badge-primary float-right">39</span></div>
			<div><span class="mdi mdi-phone-log"></span> Total message balance for calls <span class="badge badge-warning float-right">29</span></div>
		</div>
	</div>
	<div class="container row">
		<div class="col-lg-8">
			<div class="container text-center text-muted">
				<span>Broadcast a message via: emails, sms messages and calls.</span><br>
				<p>

					<!-- VIEW MESSAGE HISTORY -->
					<span class="btn btn-sm btn-outline-success">Message history <i class="mdi mdi-message-text-outline"></i></span>

					<!-- VIEW EMAIL HISTORY -->
					<span class="btn btn-sm btn-outline-danger">Email history <i class="mdi mdi-email-variant"></i></span>

					<!-- VIEW CALL LOGS -->
					<span class="btn btn-sm btn-outline-info">Calls history <i class="mdi mdi-phone-log"></i></span>

					<!-- OPEN MODAL TO SEND A MESSAGE -->
					<span class="btn btn-sm btn-outline-warning">Request a feature <i class="mdi mdi-keyboard-tab"></i></span>
				</p>
			</div>
			<div class="container">
				<strong>Compose message</strong>
				<div>
					<span id="userid" style="display: none;"> <?php     echo $userid;     ?> </span>
					<textarea name="message_content" class="form-control" id="message_area" rows="5" placeholder="Compose message here........"></textarea>
				</div>
				<div class=" row">
					<div class="col-lg-6 col-md-6 row">
						<span class="alert alert-danger col-lg-12 col-md-12 col-sm-12 animated rubberBand warning_error"><strong>Warning!</strong> Cannot send blank message.</span>
					</div>
					<div class="col-lg-6 col-md-6  text-right">
						<span>Choose action</span>
						<span class="btn btn-sm btn-outline-info" id="send_mails"><i class="mdi mdi-email"></i></span>
						<span class="btn btn-sm btn-outline-warning" id="send_sms"><i class="mdi mdi-message-reply-text"></i></span>
						<span class="btn btn-sm btn-outline-success" id="call_phones"><i class="mdi mdi-phone-in-talk"></i></span>
						<span class="btn btn-sm btn-outline-primary" id="save_message"><i class="mdi mdi-arrow-down-bold-circle"></i></span>
					</div>
				</div>
			</div>
		</div>
		<div class="col-lg-4">
			<div class="jumbotron sending_options row" style="background-color: white;">
				<span>Choose how to send your message for further options.</span>
			</div>
		</div>

		<div class="loader" style="display: none;">
			<img src="../images/preloader/25.gif" alt="" style=" margin: 20px 0;">
		</div>
		
	</div>
</div>


<script type="text/javascript">
	$(function(){

		// autofocus message-area
		$('#message_area').focus();

		$('#send_mails').click(function(event) {
			/* Act on the event */
			if($('#message_area').val().trim() == '' | $('#message_area').val().trim() == null ){

				// blank message_content
				$('.warning_error').css('display', 'block').fadeOut(5000);

			}else{
				$('.sending_options').html( $('.loader').html() );

				var message = $('#message_area').val().trim();

				var xhttp = new XMLHttpRequest();
				xhttp.open('GET','../includes/email-options.php?messageContent=' + message);
				xhttp.onreadystatechange = function(){
					if(this.readyState == 4 && this.status == 200){
						$('.sending_options').html(xhttp.responseText);
					}
				};
				xhttp.send(); 
			}
			
		});

		$('#send_sms').click(function(event) {
			/* Act on the event */
			if($('#message_area').val().trim() == '' | $('#message_area').val().trim() == null ){

				// blank message_content
				$('.warning_error').css('display', 'block').fadeOut(5000);

			}else{
				$('.sending_options').html( $('.loader').html() );
				var message = $('#message_area').val().trim();

				var xhttpSMS = new XMLHttpRequest();
				xhttpSMS.open('GET','../includes/sms-options.php?messageContent=' + message);
				xhttpSMS.onreadystatechange = function(){

					if(this.readyState == 4 && this.status == 200){

						$('.sending_options').html(xhttpSMS.responseText);

					}
				};
				xhttpSMS.send();

			}
		});

		$('#call_phones').click(function(event) {
			/* Act on the event */
			if($('#message_area').val().trim() == '' | $('#message_area').val().trim() == null ){

				// blank message_content
				$('.warning_error').css('display', 'block').fadeOut(5000);

			}else{

				$('.sending_options').html( $('.loader').html() );

			}
		});

		// save_message 
		$('#save_message').click(function(event) {
			/* Act on the event */

			if($('#message_area').val().trim() == '' | $('#message_area').val().trim() == null ){

				// blank message_content
				$('.warning_error').css('display', 'block').fadeOut(5000);

			}else{

				$('.sending_options').html( $('.loader').html() );

				var $messageContent = $('#message_area').val().trim();
				var $userid = $('#userid').text();
				var $xhttpSave = new XMLHttpRequest();
				$xhttpSave.open('GET' , '../includes/save-message.php?messageContent=' + $messageContent + '&userid=' + $userid);
				$xhttpSave.onreadystatechange = function(){

					if (this.readyState == 4 && this.status == 200) {
						$('.sending_options').html($xhttpSave.responseText);
					};

				}
				$xhttpSave.send();
			}
		});
	});
</script>