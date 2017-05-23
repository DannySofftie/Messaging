<?php 
require_once '../includes/dbconfig.php';
?>
<style type="text/css" media="screen">
	.all-emails{
		height: 100%;
		overflow-y: scroll;
	}
</style>

<div class="container bg-faded all-emails" style="background-color: white;">
	<div class="jumbotron row">
		<div class="col-lg-8 col-md-8 col-sm-8 text-center">
			Have all your gmail messages delivered into your phone.
			<h6>Allow us to your gmail account and we will make messaging an easy and convenient way for you.</h6>
		</div>
		<div class="col-lg-4 col-md-4 col-sm-4 ">
				<div><span>Total received mails</span> <span class="badge badge-info float-right">190</span></div>
				<div>Total mails sent <span class="badge badge-success float-right">23</span></div>
		</div>
	</div>

	<div class="container row">
		<div class="container col-lg-4 col-md-4 col-sm-4">
			Synchronize your gmail inbox.
			<p>
				<button id="sync" class="btn btn-sm btn-outline-info text-uppercase">sync inbox <span id="syncicon" class="mdi mdi-refresh"></span></button>
			</p>
			<div class="loader" style="display: none;">
				<img src="../images/preloader/25.gif" alt="">
			</div>
			<div class="container gmail_response" style="padding: 30px 0">
				
			</div>
		</div>
		<div class="container col-lg-8 col-md-8 col-sm-8">
			Your messages will be displayed here.
		</div>
	</div>
</div>

<script type="text/javascript">
	$(function(){

		$('#sync').click(function(event) {
			/* Act on the event */
			$('#syncicon').addClass('mdi-spin');
			$('.gmail_response').html( $('.loader').html() );

			var xhttp = new XMLHttpRequest();
			xhttp.open('GET' , '../includes/fetch-gmail.php');
			xhttp.onreadystatechange = function(){
				if (this.readyState == 4 && this.status == 200) {
					$('.gmail_response').html(xhttp.responseText);

					$('#syncicon').removeClass('mdi-spin');
				};
			};
			xhttp.send();
		});
	});
</script>
