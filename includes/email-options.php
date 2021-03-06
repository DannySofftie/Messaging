<?php
session_start();
require_once ('dbconfig.php');
$userid = $_SESSION['userid'];
$messageContent = $_GET['messageContent'];

?>

<span id="userid" style="display: none;"> <?php echo $userid; ?> </span> 
<style>
    .email_preview{
        width: 100%;
        height: 100%;
        position: fixed;
        top: 0;
        left: 0;
        background-color: rgba(0,0,0,0.4);
        display: none;
    }
    .email_preview .card{
        margin: 100px;
    }
    #close_preview{
        position: absolute;
        top: 70px;
        right: 70px;
        z-index: 99999;
    }
</style>

<?php
try{

	// list of all emails
	$emailQuery = $conn -> prepare("SELECT * from contact_addresses where added_by = :userid");
	$emailQuery->bindParam(":userid", $userid);
	$emailQuery->execute();

	// output and use all the email addresses
	if ($emailQuery->rowCount() == 0) {
		

?>

		<!-- NO EMAIL ADDRESSES FOR THIS USER -->
		<span class="alert alert-danger">No email addresses exist.</span>

		<?php
	}else{

        ?>

		<!-- EMAIL ADDRESSES FOR A SPECIFIC USER -->
		<div class="container row">
			<div class="row">
				<span class="alert alert-info col-ld-12 col-md-12 col-sm-12">You have <span class="badge badge-info"><?php print $emailQuery->rowCount();  ?></span> email contacts.</span>
			</div>
			<div>
				<h6>Select how to send emails</h6>
                <span class="btn btn-sm btn-danger" id="preview_email"><span class="mdi mdi-eye-outline"></span></span><br />
				<span class="btn btn-sm btn-outline-primary text-uppercase" id="send_to_all">Email All <i class="mdi mdi-email-alert"></i></span>
				<span class="btn btn-sm btn-outline-danger text-uppercase" id="filter">Apply filter <i class="mdi mdi-image-filter-center-focus-weak"></i></span>
				<div class="dropdown-divider">
					
				</div>
				<div class="filter-emails animated swing">
					<h6>Select email address to send to:</h6>
					<form class="float-right send_filtered_email" method="POST">
						<div class="form-group">
							<input type="hidden" id="messageContent" value=" <?php echo $messageContent; ?> " />
						</div>
						<div class="form-group">
							<select class="custom-select custom-select-sm" name="choosen_email" id="choosen_email">
								<?php 

        while ($emailQueryRow = $emailQuery->fetch()) {


                                ?>

									<option value="<?php echo $emailQueryRow['email'];  ?>"> <?php echo $emailQueryRow['email'];  ?></option>


									<?php

        }

                                    ?> 
							</select>
						</div>
						<div class="form-group text-right">
							<button type="submit" class="btn btn-sm btn-outline-success text-uppercase">Confirm Send <span class="mdi mdi-email"></span></button>
						</div>
					</form>
                    
				</div>
               
			</div>
            <div id="connectivity" class="animated jello row" ></div>
			<div class="email-result">
               
				<!-- 


				RESULT WHEN EMAILS ARE SENT WILL BE DISPLAYED HERE


			-->

		</div>
		<div class="loader" style="display: none;">
			<img src="../images/preloader/25.gif" alt="" style="margin: 20px 0;">
		</div>
	</div>
	<?php
    }

}
catch(PDOException $e){

	// output the error with message
	echo "Error ".$e->getMessage();

}
    ?>
<spa
<div class="email_preview">
    <span id="close_preview" class="btn text-danger">&times;</span>
    <div class="card">
        <div class="email_holder">

        </div>
    </div>
</div>
<script type="text/javascript">
    $( function () {
        var $messageContent = $( '#messageContent' ).val();
		// check online connectivity
		if ( navigator.onLine == false ) {
		    $( '#connectivity' ).html( "<span class='alert alert-danger'>Your internet connection is down.</span>" );
		}
		$('.filter-emails').css('display', 'none');

		$('#filter').on('click', function(event) {

			event.preventDefault();
			/* Act on the event */
			$('.filter-emails').css('display', 'block');

		});

		$( '#preview_email' ).click( function () {
		    $.get( '../includes/html_email.php?message_content=' + $messageContent, function ( data ) {
		        $( '.email_preview' ).fadeIn( 'slow', function () {
		            $( '.email_holder' ).html( data );
		        } );
		    } );
		} );

		$( '#close_preview' ).click( function () {
		    $( '.email_preview' ).fadeOut( 'slow' );
		} )

		// prepare message content to pass via url


		// submit filtered emails and message
		$('form').submit(function(event) {
			/* Act on the event */
			event.preventDefault();
			$('.email-result').html( $('.loader').html() );

			var $emailSelected = $('#choosen_email').val();

			$.get( '../includes/html_email.php?message_content=' + $messageContent, function ( data ) {
			    // now send message to selected recipient
			var xhttp = new XMLHttpRequest();
			xhttp.open('GET','../includes/email-options-send.php?messageContent=' + $messageContent + '&emailSelected=' + $emailSelected);
			xhttp.onreadystatechange = function(){
				if(this.readyState == 4 && this.status == 200){

					$('.email-result').html(xhttp.responseText);

				}
			};
			xhttp.send();
			} );


		});


		// send to all email recipients
		$('#send_to_all').click(function(event) {
			/* Act on the event */
			$('.email-result').html( $('.loader').html() );
			var $send_to_all = "allow";
			var $userid = $('#userid').text();

			var xhttpAll = new XMLHttpRequest();
			xhttpAll.open('GET','../includes/email-options-send.php?messageContent=' + $messageContent + '&send_to_all=' + $send_to_all + '&userid=' + $userid);
			xhttpAll.onreadystatechange = function(){
				if(this.readyState == 4 && this.status == 200){

					$('.email-result').html(xhttpAll.responseText);

				}
			};
			xhttpAll.send();
		});

	});
</script>