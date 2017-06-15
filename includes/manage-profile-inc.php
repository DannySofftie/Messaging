<?php
session_start();
$userid = $_SESSION['userid'];
require_once 'dbconfig.php';
// delete email when called
if (isset($_GET['emailId'])) {
	$emailId = $_GET['emailId'];
	$conn->query("delete from contact_addresses where id = '$emailId' and added_by = '$userid'");
	exit;
}
?>
<style type="text/css">

	.user_image{
		height: 100px;
		width: 100%;
	}
	.user_image img{
		height: 100%;
		width: 100%;
	}
	.about_user,#current_username,#email,#phone_number,#curr_bs_name,#curr_bs_catg,#curr_bs_address,#curr_bs_email,#curr_bs_phone,#curr_bs_desc,#curr_bs_time{
		font-size: 12px;
	}
	#curr_bs_name:hover,#curr_bs_catg:hover,
	#curr_bs_address:hover,#curr_bs_email:hover,
	#curr_bs_phone:hover,#curr_bs_desc:hover,#curr_bs_time:hover,
	#email:hover,.about_user:hover,#phone_number:hover,.user_name:hover{
		color: teal;
		cursor: pointer;
	}
	#new_bs_name,#new_bs_address,#new_bs_email,#new_bs_phone,#new_bs_desc,#new_bs_time,
	#updated_about_me, #update_profile , .username_holder ,#email_holder,#phone_holder{
		font-size: 13px;
		display: none;
	}
	.user_info_section .row{
		padding: 0 1px;
	}
	#select_image{
		height: 70%;
		background-image: url('../images/camera.jpg');
		background-repeat: no-repeat;
		background-position: center center;
		background-size: cover;
	}
	strong,h5{
		font-size: 14px;
	}

</style>
<?php 

$selected_option = $_GET['selected_option'];
try{
	// current user information
	$userQuery = $conn->prepare("SELECT * from regusers where id = :userid");
	$userQuery->bindParam(":userid" , $userid);
	$userQuery->execute();
	$userQueryRow = $userQuery->fetch();

	// current user contacta addresses
	$currUserContQuery = $conn->prepare("SELECT * from contact_addresses where added_by = :userid");
	$currUserContQuery->bindParam(":userid" , $userid);
	$currUserContQuery->execute();
	$currUserContQueryRow = $currUserContQuery->fetch();

	// current user skills
	$userBSkillQuery = $conn->prepare("SELECT bs_skills.id, bs_skills.skill_name, user_bs_skills.user_id, user_bs_skills.bs_skill_id from bs_skills right join user_bs_skills on bs_skills.id = user_bs_skills.bs_skill_id where user_bs_skills.user_id = :userid");
	$userBSkillQuery->bindParam(":userid" , $userid);
	$userBSkillQuery->execute();
	$userBSkillQueryRow = $userBSkillQuery->fetch();

	// all business skills
	$bsSkillsQuery = $conn->query("SELECT * from dmclient.bs_skills order by skill_name");
	$bsSkillsQuery->execute();

	// business information section
	$bsInfoQuery = $conn->prepare("SELECT * from bs_categories right join user_bs_info on bs_categories.id=user_bs_info.bs_cat_id  where user_bs_info.owner_id = :userid");
	$bsInfoQuery->bindParam(":userid" , $userid);
	$bsInfoQuery->execute();
	$bsInfoQueryRow = $bsInfoQuery->fetch();

}
catch(PDOException $e){
	echo "Error ".$e->getMessage();
}
if ($selected_option == 'manage_my_profile') {
	
?>
	<div class="container animated fadeIn row">
		<div class="user_info_section col-lg-7 col-md-7 row">
			<h5>Profile information.</h5>
			<h6 class="text-muted">It's good to know that the information you provide here will be available to other business persons in this platform.
				Giving more on this specific field will in this case be used as brand name for your business.
			</h6>
			<div class="col-lg-5 col-md-5 row">
				<div class="user_image col-lg-6 col-md-6" title="Current profile image">	
					<?php
    if (empty($userQueryRow['prof_image'])) {

                    ?>
						<img src="../images/profile.png" alt="">
						<?php					
    }else {

                        ?>
						<img src=" <?php  echo $userQueryRow['prof_image']  ?>" alt="">
						<?php
    }
                        ?>	
				</div>
				<div class="col-lg-6 col-md-6">
					<div style="height: 100px;  width: 100%">
						<form enctype="multipart/form-data" style="height: 100%" class="form_update_data" method="POST">
							<input type="hidden" name="profile_check">
							<input type="file" id="select_image" name="select_image" class="custom-file text-hide">
							<img id="uploaded_image" src="#" alt="" style="width: 100%; height: 100%; display: none;">
							<input type="hidden" name="oldfileName" value="<?php  echo $userQueryRow['prof_image']  ?>" placeholder="">
						</form>
					</div>
				</div>				
				<div class="" title="User name that is visible to the public" style="clear: both;">
					<form class="form_update_data" method="POST" enctype="multipart/form-data">
						<div class="form-group has-feedback">
							<input type="text" class="username_holder form-control form-control-sm" name="fname" value=" <?php  echo $userQueryRow['fname']  ?>" >
						</div>
						<div class="form-group has-feedback">
							<input type="text" class="username_holder form-control form-control-sm" name="nickname" value=" <?php  echo $userQueryRow['nickname']  ?>" >
						</div>
						<?php 
    if (empty($userQueryRow['fname'])) {
                        ?>
							<div class="form-group has-feedback">
								<input type="text" class="form-control form-control-sm" name="fname" placeholder="First name">
							</div>
							<div class="form-group has-feedback">
								<input type="text" class="form-control form-control-sm" name="sname" placeholder="Nick name">
							</div>
							<?php
    }

                            ?>
						<span id="current_username" class="user_name"> <?php   echo $userQueryRow['fname'] . " " . $userQueryRow['nickname'] ?> </span>
						
					</form>
				</div>
				<form class="form_update_data" method="POST" enctype="multipart/form-data" style="width: 100%;">
					<div class="form-group has-feedback" style="clear: both;">
						<span id="email"> <?php  echo $userQueryRow['email']  ?></span>
						<input type="text" id="email_holder" name="email" class="form-control form-control-sm" value=" <?php  echo $userQueryRow['email']  ?> " placeholder="someone@example.com">
					</div>
				</form>
				<form class="form_update_data" method="POST" enctype="multipart/form-data">
					<div class="form-group has-feedback">
						<?php if (empty($userQueryRow['phone_number'])) {
                        ?>
							<input type="text" name="phone_number" class="form-control form-control-sm" placeholder="0712345678">
							<?php
                              }else {
                            ?>
							<span id="phone_number"> <?php  echo $userQueryRow['phone_number']  ?></span>
							<input type="text" id="phone_holder" name="phone_number" class="form-control form-control-sm" value=" <?php  echo $userQueryRow['phone_number']  ?> ">
							<?php
                              }
                            ?>						
					</div>
				</form>				
			</div>
			<div class="col-lg-7 col-md-7">
				<strong class="text-muted">Business skills</strong><br>
				<h6 class="text-muted">Make your publicly visible profile fancy by telling your most proficient area in business.</h6>
				<div id="business_skills" class="float-right"  style="display: none; clear: both;">
					<p>
						<form class="form_update_data" method="POST" enctype="multipart/form-data">
							<select class="form-control text-gray-dark form-control-sm custom-select-sm animated fadeIn business_skills" name="business_skills">
								<?php 

    if (!empty($userBSkillQueryRow['bs_skill_id'])) {
                                ?>
									<option value=" <?php  echo $userBSkillQueryRow['bs_skill_id']   ?>">Choose here</option>
									<?php
    }
    while ($bsSkillsQueryRow = $bsSkillsQuery->fetch()) {
                                    ?>
									<option value=" <?php  echo $bsSkillsQueryRow['id']  ?> ">  <?php echo $bsSkillsQueryRow['skill_name']  ?> </option>
									<?php
    }
                                    ?>
							</select>
						</form>
					</p>
				</div>
				<div style="clear: both;">
					<?php  if (empty($userBSkillQueryRow['bs_skill_id'])) {
                    ?>
						<span class="btn btn-sm btn-outline-warning text-uppercase go_ahead">go ahead <span class="mdi mdi-account-edit"></span></span>
						<?php
                           }else {
                        ?>
						<div class="current_p_area">
							<h6>Current area of proficieny</h6>
							<p class="text-info"> <?php echo $userBSkillQueryRow['skill_name'] ?> </p>
							<p><span class="btn btn-sm btn-outline-warning text-uppercase edit_bskill">edit <span class="mdi mdi-link"></span></span></p>
						</div>
						<?php
                           }

                        ?>
				</div>
				<hr>
				<div class="about_user col-lg-12 col-md-12" title="Change this default text." style="width: 100%; clear: both;">
					<strong class="text-muted change_about_me">About me</strong>
					<p id="current_about_me"><?php echo $userQueryRow['about'] ?></p>
					<div class="form-group has-feedback" style="width: 100%">
						<form class="form_update_data" method="POST" enctype="multipart/form-data">
							<textarea rows="3" id="updated_about_me" name="updated_about_me" class="form-control"> <?php echo $userQueryRow['about'] ?></textarea>
						</form>
					</div>
				</div>
			</div>
			
			<div class="col-lg-12 col-md-12 text-right">
				<span id="ajax_update_result">
					<img src="../images/preloader/25.gif" alt="preloader" id="ajax_preloader" style="width: 30px; height: 30px; display: none;">
				</span>
				<button id="update_profile" class="btn btn-sm btn-outline-info text-uppercase">update <span class="mdi mdi-bookmark-check"></span></button>
			</div>
		</div>
		<!-- BUSINESS INFORMATION -->
		<!--
		 bs_name, bs_logo, bs_description, bs_wrk_time,
		 bs_address, bs_contact_mail, bs_contact_phone, bs_map_address
		-->
		<div class="business_address col-lg-5 col-md-5" style="padding: 0; margin: 0">
			<h5>Business information</h5>
			<div class="row">
				<div class="col-lg-6 col-md-6">
					<form enctype="multipart/form-data" method="POST" class="bs_form_data">
						<div class="bs_name form-group has-feedback">
							<h5>Business name</h5>
							<?php if (empty($bsInfoQueryRow['bs_name'] )) {
                            ?>
								<input type="text" class="bs_name_input form-control form-control-sm" name="bs_name" placeholder="Business Name">
								<?php
                                  }else {
                                ?>
								<span id="curr_bs_name"><?php  echo $bsInfoQueryRow['bs_name']  ?></span>
								<input type="text" id="new_bs_name" class="form-control form-control-sm" name="bs_name" value="<?php  echo $bsInfoQueryRow['bs_name']  ?> " placeholder="">
								<?php								
                                  }
                                ?>
						</div>
					</form>
					<form enctype="multipart/form-data" method="POST" class="bs_form_data">
						<div class="bs_category form-group has-feedback">
							<h5>Business category</h5>
							<div id="new_bs_catg" style="display: none; clear: both;">
								<select class="form-control form-control-sm custom-select-sm animated fadeIn bs_category_input" name="bs_category">
									<option value="<?php  if(!empty($bsInfoQueryRow['bs_cat_id']))  echo $bsInfoQueryRow['bs_cat_id'] ?>">Choose here</option>
									<?php 
    $bsCategory = $conn->query("SELECT * from bs_categories order by cat_name");
    while ($bsCategoryRow = $bsCategory->fetch()) {
                                    ?>
										<option value="<?php  echo $bsCategoryRow['id'] ?>"> <?php  echo $bsCategoryRow['cat_name']  ?></option>
										<?php							    
    }
                                        ?>								
								</select>
							</div>
							<?php if (empty($bsInfoQueryRow['bs_cat_id'] )) {
                            ?>
								<span id="set_category" class="btn btn-sm btn-outline-info text-uppercase">set <span class="mdi mdi-link"></span></span>
								<?php
                                  }else {
                                ?>
								<span id="curr_bs_catg" class="text-info"> <?php echo $bsInfoQueryRow['cat_name']  ?> </span>
								<?php
                                  }
                                ?>						
						</div>
					</form>
					<form enctype="multipart/form-data" method="POST" class="bs_form_data">
						<div class="bs_address form-group has-feedback">
							<h5>Business address</h5>
							<?php if (empty($bsInfoQueryRow['bs_address'])) {
                            ?>
								<input type="text" class="bs_address_input form-control form-control-sm" name="bs_address" placeholder="Address info">
								<?php

                                  }else {
                                ?>
								<span id="curr_bs_address"><?php echo $bsInfoQueryRow['bs_address'] ?></span>
								<input type="text" id="new_bs_address" class="form-control form-control-sm" name="bs_address" value="<?php echo $bsInfoQueryRow['bs_address'] ?>" placeholder="">
								<?php								
                                  }
                                ?>
						</div>
					</form>
					<form enctype="multipart/form-data" method="POST" class="bs_form_data">
						<div class="bs_email form-group has-feedback">
							<h5>Business email</h5>
							<?php if (empty($bsInfoQueryRow['bs_contact_mail'] )) {
                            ?>
								<input type="email" class="bs_email_input form-control form-control-sm" name="bs_email" placeholder="someone@example.com">
								<?php
                                  }else {
                                ?>
								<span id="curr_bs_email"> <?php  echo $bsInfoQueryRow['bs_contact_mail']  ?> </span>
								<input type="email" id="new_bs_email" value="<?php  echo $bsInfoQueryRow['bs_contact_mail']  ?>" class=" form-control form-control-sm" name="bs_email" >
								<?php								
                                  }
                                ?>
						</div>
					</form>
					<form enctype="multipart/form-data" method="POST" class="bs_form_data">
						<div class="bs_contact_phone form-group has-feedback">
							<h5>Business phone</h5>
							<?php if (empty($bsInfoQueryRow['bs_contact_phone'] )) {
                            ?>	
								<input type="text" class="bs_phone_input form-control form-control-sm" name="bs_contact_phone" placeholder="0712345678">
								<?php								
                                  }else {
                                ?>
								<span id="curr_bs_phone"> <?php echo $bsInfoQueryRow['bs_contact_phone']  ?></span>
								<input type="text" id="new_bs_phone" value="<?php echo $bsInfoQueryRow['bs_contact_phone']  ?>" class=" form-control form-control-sm" name="bs_contact_phone" >
								<?php
                                  }
                                ?>
						</div>
					</form>
				</div>
				<div class="col-lg-6 col-md-6">
					<form enctype="multipart/form-data" method="POST" class="bs_form_data">
						<div class="bs_description form-group has-feedback">
							<h5>Business Description</h5>
							<?php if (empty($bsInfoQueryRow['bs_description'] )) {
                            ?>
								<h6>Describe your business in a few words to help other users learn about you quickly</h6>
								<textarea class="bs_description_input form-control form-control-sm" name="bs_description" rows="3" placeholder="Business description...."></textarea>
								<?php								
                                  }else {
                                ?>
								<span id="curr_bs_desc" > <?php echo $bsInfoQueryRow['bs_description'] ?></span>
								<textarea id="new_bs_desc" class="form-control form-control-sm" name="bs_description" rows="3"><?php echo $bsInfoQueryRow['bs_description'] ?></textarea>
								<?php								
                                  }
                                ?>
						</div>
					</form>
					<form enctype="multipart/form-data" method="POST" class="bs_form_data">
						<div class="bs_wrk_time form-group">
							<h5>Opening hours</h5>
							<?php if (empty($bsInfoQueryRow['bs_wrk_time'] )) {
                            ?>
								<input type="time" class="form-control form-control-sm" name="bs_wrk_time" value="08:00:00" placeholder="">
								<?php								
                                  }else {
                                ?>
								<span id="curr_bs_time"> <?php   echo $bsInfoQueryRow['bs_wrk_time'] ?></span>
								<input type="time" id="new_bs_time" class="form-control form-control-sm" name="bs_wrk_time" value="<?php   echo $bsInfoQueryRow['bs_wrk_time'] ?>" placeholder="">
								<?php								
                                  }
                                ?>
						</div>
					</form>
					<form enctype="multipart/form-data" method="POST" class="bs_form_data">
						<div class="bs_map_address">
							<input type="hidden" name="bs_check_form" value="somevalue" placeholder="">
							<p class="text-muted">Google maps location</p>
						</div>
					</form>
					<div class="form-group float-right">
						<span id="save_bs_info" class="btn btn-sm btn-outline-info text-uppercase">update <span class="mdi mdi-update"></span></span>
					</div>
					
				</div>

			</div>
			<div  style="clear: both;">
				<span id="response_holder">
					<img src="../images/preloader/25.gif" alt="preloader" id="ajax_preloader2" style="width: 30px; height: 30px; display: none;">
				</span>
			</div>	
		</div>
	</div>
	<?php
}
if ($selected_option == 'manage_mailing_list'){
	
    ?>
	<div class="animated fadeIn">	
		<div>		
			<span id="addNewContact" class="btn btn-sm btn-outline-primary text-uppercase">add new <span class="mdi mdi-account-plus"></span></span>
		</div><hr style="visibility: hidden;">

		<div class="addContactEmail" style="display: none;">
			<div class="dropdown-divider"></div>
			<form class="form-inline" id="contactForm">
				<div class="input-group input-group-sm">
					<span class="input-group-addon input-group-sm" id="fname">
						<span class="mdi mdi-account"></span>
					</span>
					<input class="form-control form-control-sm"  placeholder="First name" type="text" name="fname">
				</div>
				<div class="input-group input-group-sm">
					<span class="input-group-addon input-group-sm" id="fname">
						<span class="mdi mdi-account-convert"></span>
					</span>
					<input class="form-control form-control-sm"  placeholder="Second name" type="text" name="sname">
				</div>
				<div class="input-group input-group-sm">
					<span class="input-group-addon input-group-sm" id="fname">
						<span class="mdi mdi-cellphone-basic"></span>
					</span>
					<input class="form-control form-control-sm" id="phone"  placeholder="0712345678" type="tel" name="phone">
				</div>
				<div class="input-group input-group-sm">
					<span class="input-group-addon input-group-sm" id="fname">
						<span class="mdi mdi-email-variant"></span>
					</span>
					<input class="form-control form-control-sm" placeholder="someone@example.com" type="email" name="email">
				</div>
			</form>
			<div class="dropdown-divider"></div>
			<span style="color: red; display: none;" id="phoneVerify">Phone number must have 10 digits or more</span><br>
			<span style="color: red; display: none;" id="emailVerify">Email address must be in the format someone@example.com</span>
			<div>
				<button type="button" class="btn btn-info btn-sm text-uppercase" id="saveContact">save <span class="mdi mdi-telegram"></span></button>
			</div>
		</div>
		<div class="outputText">
			<!-- RESULT WILL BE DISPLAYED HERE -->
		</div>
<?php
    $contQuery = $conn->prepare("SELECT * from contact_addresses where added_by = :userid");
    $contQuery->bindParam(":userid" , $userid);
    $contQuery->execute();
    if($contQuery->rowCount() == 0){
?>
<div class="text-center" style="padding: 100px 0;">
    <span class="alert alert-danger">Seems you have no contacts in your records</span>
</div>
        <?php }else{
        ?>
		<div id="tableContacts">
			<table class="table table-success table-hover" id="contactData" >
				<thead>
					<tr>
						<th>First name</th>
						<th>Second name</th>
						<th>Phone number</th>
						<th>Email address</th>
						<th>Trash</th>
					</tr>
				</thead>
				<tbody>
	<?php
        while ($contQueryRow = $contQuery->fetch()) {
    ?>

						<tr>
							<td> <?php  echo $contQueryRow['fname']  ?> </td>
							<td> <?php  echo $contQueryRow['sname']  ?> </td>
							<td> <?php  echo $contQueryRow['phone']  ?> </td>
							<td> <?php  echo $contQueryRow['email']  ?> </td>
							<td>
								<button class="btn btn-sm btn-outline-warning deleteEmail" id="<?php  echo $contQueryRow['id']  ?>">
									<span class="mdi mdi-delete"></span>
								</button> 
							</td>
						</tr>
						<?php
        }
                        ?>
				</tbody>
			</table>
		</div>
        <?php }  ?>
	</div>
	<?php

}

if ($selected_option == 'manage_contacts'){

    ?>
	<div class="container animated fadeIn text-center" style="padding: 100px 0 ;">
		<div  id="manage_contacts_section" >
			<h5>If you updated your contacts on the last section, there is no need of adding them here.</h5>
			<h5>Use this section only when adding bulk data</h5>
			<hr>
			<h5>You have bulk data?</h5>
			<button class="btn btn-sm btn-outline-info text-uppercase" id="yes_i_have">yes <span class="mdi mdi-account-multiple-outline"></span></button>
		</div>
		<div id="content_show" class="animated " style="display: none;">
			<div>
				<h5>Choose how you want to upload your contacts, we only accept phone numbers and emails.</h5>
				<h5>You might want to check your files for correct formatting before uploading them here.</h5>
				<button class="btn btn-outline-success text-uppercase">import from csv <span class="mdi mdi-file-excel"></span></button>
				<button class="btn btn-outline-info text-uppercase">import from text file <span class="mdi mdi-note-text"></span></button>
			</div>
		</div>
	</div>
	<?php
}

    ?>


   
<script type="text/javascript">

	$(function(){ 

		var $userid = $('#userid').text();
		
		$( '#contactData' ).dataTable( {
            responsive: true
		} );
		
		$('.form-control').focus(function(event) {
			/* Act on the event */
			$('#update_profile').show();
		});


		$('.deleteEmail').click(function(event) {
			/* Act on the event */
			var $emailId = $(this).attr('id');
			
			$.ajax({
				url: '../includes/manage-profile-inc.php',
				type: 'GET',
				data: { emailId : $emailId },
				async: false,
				dataType: 'text',
				success: function(data){
					$.get('../includes/contact-addresses.php?fetchAll=true' , function(data) {
						//optional stuff to do after success 
					    $('#tableContacts').html(data);
					});
				},
				error: function(){
					alert("Could not be deleted");
				}
			})
		});

		
		$('#addNewContact').click(function(event) {
			/* Act on the event */
			$(this).fadeOut('slow', function() {
				$('.addContactEmail').fadeIn('slow');
				$('#tableContacts').fadeOut('slow');
				$('#saveContact').fadeIn('slow');
			});
			
		});

		$('#saveContact').prop('disabled' ,  true);
		$('.input-group').click(function(event) {
			/* Act on the event */
			var $currDiv = $(this);
			var $input = $(event.target).closest('input');
			$input.attr('spellcheck', 'false');
			$input.keyup(function(event) {
				/* Act on the event */
				var $textLenth = $(this).val().trim();
				if(($textLenth.length) > 2){
					$input.addClass('form-control-success');
					$currDiv.addClass('has-success');
				}else {
					$input.removeClass('form-control-success');
					$currDiv.removeClass('has-success');
				}
			});

			if ($input.attr('id') === 'phone') {
				$input.keyup(function(event) {
					/* Act on the event */
					var $textLenth = $(this).val().trim();
					if(($textLenth.length) < 10){
						$('#phoneVerify').show();
						$input.removeClass('form-control-success');
						$('#saveContact').prop('disabled' ,  true);
					}else {
						$('#saveContact').prop('disabled' ,  false);
						$('#phoneVerify').hide();
						$input.addClass('form-control-success');
					}
				});
			};

			if ($input.attr('name') === 'email') {

				$input.keyup(function(event) {
					/* Act on the event */
					var $email = $(this).val().trim();
					var $emailRegex = /^\b[A-Z0-9._%-]+@[A-Z0-9.-]+\.[A-Z]{2,4}\b$/i;
					if (!$emailRegex.test($email)) {
						$('#emailVerify').show();
						$input.removeClass('form-control-success');
						$('#saveContact').prop('disabled' ,  true);
					}else {
						$('#saveContact').prop('disabled' ,  false);
						$('#emailVerify').hide();
						$input.addClass('form-control-success');
					}
				});
			};
		});

		$('#saveContact').click(function(event) {
			/* Act on the event */
			$.ajax({
				url: '../includes/contact-addresses.php?saveRecord=' + 'saveRecord',
				type: 'POST',
				data: $('#contactForm').serialize(),
				async: false,
				success: function(data){
					$inputValue = $('#contactForm input').val();
					$inputValue = "";
					$('.outputText').html(data);
					$('#saveContact').hide();
					$('#addNewContact').fadeIn(1000);
					$('.addContactEmail').fadeOut(1000);
					$('.outputText').fadeOut(3000);
					$('#tableContacts').fadeIn(2000);
					$.get('../includes/contact-addresses.php?fetchAll=true' , function(data) {
						//optional stuff to do after success 
						$('.tableData').html(data);
					});
				},
				error: function(){
					alert("Error occured");
				}
			});
			
		});


		/* BEGINNING OF MANAGE user PROFILE d */

		$('.go_ahead').click(function() {
			/* Act on the event */
			var $content = $('#business_skills');
			
			$(this).hide();
			$content.fadeIn(2000);
			$('#update_profile').show();
		});
		$('#email').click(function(event) {
			/* Act on the event */
			$(this).hide();
			$('#email_holder').fadeIn(1000);
			$('#update_profile').show();
		});
		$('#phone_number').click(function(event) {
			/* Act on the event */
			$(this).hide();
			$('#phone_holder').fadeIn(1000);
			$('#update_profile').show();
		});
		$('.edit_bskill').click(function(event) {
			/* Act on the event */
			var $content = $('#business_skills');
			
			$('.current_p_area').hide();
			$content.fadeIn(2000);
			$('#update_profile').show();
		});
		$('#current_about_me').click(function() {
			/* Act on the event */
			$(this).hide();
			$('.change_about_me').text('Edit this text to your preferred About Me');
			$('#updated_about_me').fadeIn(2000);
			$('#updated_about_me').focus();
			$('#updated_about_me').keyup(function(event) {
				/* Act on the event */
				$('#update_profile').show();
			});
		});
		// function for image preview
		function readImageURL(input){
			if(input.files && input.files[0]){
				var reader = new FileReader();
				reader.onload = function(e) {
					/* Act on the event */
					$('#uploaded_image').attr('src' , e.target.result);
					$('#uploaded_image').show();
				}
				reader.readAsDataURL(input.files[0]);
			}
		}
		$('#select_image').change(function(input) {
			/* Act on the event */
			$('#select_image').hide();
			$('#update_profile').show();
			readImageURL(this);			
		});
		$('#current_username').click(function(event) {
			/* Act on the event */
			$(this).hide();
			$('.username_holder').fadeIn(1000);
			$('#update_profile').show();
		});
		// update profile with image and other descriptive texts
		$('#update_profile').click(function(event) {
			/* Act on the event */			
			$('#ajax_preloader').show();
			$.ajax({
				url: '../includes/update_profile.php?' + $('.form_update_data').serialize(),
				type: 'POST',
				async: false,
				data: new FormData($('.form_update_data')[0]),
				success: function(data){
					$('#ajax_update_result').html(data);
				},
				error: function(){
					$('#ajax_preloader').hide(2000);
				},
				cache: false,
				contentType: false,
				processData: false
			});	
		});

		/* END OF MANAGE PROFILE SECTION CONTROLS */

		/* BUSINESS INFORMATION */
		
		$('#save_bs_info').click(function(event) {
			/* Act on the event */
			
			$('#ajax_preloader2').show();
			$.ajax({
				url: '../includes/update_profile.php?' +  $('.bs_form_data').serialize(),
				type: 'POST',
				async: false,					
				success: function(data){
					$('#response_holder').html(data)
				},
				error: function(errorMessage){
					alert("Error occured");
					$('#ajax_preloader2').hide();
				},
				cache: false,
				contentType: false,
				processData: false					
			});
		});

		function makeEditable($tohide, $toshow){
			$tohide.hide();
			$toshow.fadeIn(1000);
		}
		$('#set_category').click(function(event) {
			/* Act on the event */
			makeEditable($(this) , $('#new_bs_catg'));
		});
		$('#curr_bs_name').click(function(event) {
			/* Act on the event */
			makeEditable($(this) , $('#new_bs_name'));
		});
		$('#curr_bs_catg').click(function(event) {
			/* Act on the event */
			makeEditable($(this) , $('#new_bs_catg'));
		});
		$('#curr_bs_address').click(function(event) {
			/* Act on the event */
			makeEditable($(this) , $('#new_bs_address'));
		});
		$('#curr_bs_email').click(function(event) {
			/* Act on the event */
			makeEditable($(this) , $('#new_bs_email'));
		});
		$('#curr_bs_phone').click(function(event) {
			/* Act on the event */
			makeEditable($(this) , $('#new_bs_phone'));
		});
		$('#curr_bs_desc').click(function(event) {
			/* Act on the event */
			makeEditable($(this) , $('#new_bs_desc'));
		});
		$('#curr_bs_time').click(function(event) {
			/* Act on the event */
			makeEditable($(this) , $('#new_bs_time'));
		});

		
		$('#yes_i_have').click(function(event) {
			/* Act on the event */
			$('#manage_contacts_section').removeClass('fadeIn');
			$('#manage_contacts_section').slideUp(2000 , function(){
				$('#content_show').css('display', 'block');
				$('#content_show').addClass('slideInUp');
			});
			
		});
	})
</script>