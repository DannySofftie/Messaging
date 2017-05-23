<?php 
require_once '../includes/dbconfig.php';
$userid = $_GET['userid'];

/*
1. Manage mailing lists
2. Manage contact numbers
3. Manage user profile
4. 
*/

?>
<style type="text/css">
	.manage_ .jumbotron{
		padding: 40px 10px;
	}
	.extra_info{
		padding: 100px 0;
	}
</style>
<div class="container manage_" style="height: 100%; overflow-y: scroll;">
	<span id="userid" style="display: none;"> <?php echo $userid  ?> </span>
	<div class="jumbotron row">
		<div class="col-lg-4 col-md-4">
			<h6>Manage your user profile easily and conveniently.</h6>
			<button class="btn btn-sm btn-outline-info text-uppercase" id="manage_my_profile">manage profile <span class="mdi mdi-account-settings-variant"></span></button>
		</div>
		<div class="col-lg-4 col-md-4">
			<h6>Manage your mailing list.</h6>
			<button class="btn btn-sm btn-outline-danger text-uppercase" id="manage_mailing_list">manage mailing list <span class="mdi mdi-contact-mail"></span></button>
		</div>
		<div class="col-lg-4 col-md-4">
			<h6>Manage your contact phone numbers.</h6>
			<button class="btn btn-sm btn-outline-warning text-uppercase" id="manage_contacts">manage contacts <span class="mdi mdi-contacts"></span></button>
		</div>
	</div>
	<div class="manage_defined">
		<div class="extra_info text-center">
			<h5>Manage your profile and data fast and securely.</h5>
			<span>Have your controls for your own profile, mailing list and contact information in one place.</span>
		</div>
	</div>
</div>


<script type="text/javascript">
	
	$(function(){
		var $userid = $('#userid').text();

		$('#manage_my_profile').click(function(event) {
			/* Act on the event */
			manageSelected($(this).attr('id'));
		});
		$('#manage_mailing_list').click(function(event) {
			/* Act on the event */
			manageSelected($(this).attr('id'));
		});
		$('#manage_contacts').click(function(event) {
			/* Act on the event */
			manageSelected($(this).attr('id'));
		});

		function manageSelected(selected){
			/* Act on the event */
			var $selected_option = selected;
			$.ajax({
				url: '../includes/manage-profile-inc.php',
				type: 'GET',
				dataType: 'text',
				data: {selected_option : $selected_option, userid : $userid},
			})
			.done(function(responseData) {
				$('.manage_defined').html(responseData);
			})
			.fail(function() {
				alert("Unexpected error has occured.");
			});
		}		
	});
</script>






