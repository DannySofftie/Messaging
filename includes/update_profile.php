<?php
session_start();
require_once 'dbconfig.php';
$userid = $_SESSION['userid'];

// update user info
if(isset($_GET['profile_check'])){

	$business_skills = "";

	if (!empty($_GET['business_skills'])) {
		$business_skills = $_GET['business_skills'];
	}

	$updated_about_me = trim($_GET['updated_about_me']);
	$email = trim($_GET['email']);
	$phone_number = "";

	if (!empty($_GET['phone_number'])) {
		$phone_number = "+254" . substr(trim($_GET['phone_number']) , -9);
	}

	$fname = trim(ucwords(strtolower($_GET['fname'])));
	$nickname = trim(ucwords(strtolower($_GET['nickname'])));
	$oldfileName = $_GET['oldfileName'];

	/*
	FIRST CHECK IF THE USER HAS SOME SKILLS ALREADY IN DATABASE, INSERT IF NO RECORD FOUND, ELSE UPDATE
     */
	try{

		$checkQuery = $conn->prepare("SELECT * from user_bs_skills where user_id = :userid");
		$checkQuery->bindParam(":userid" , $userid);
		$checkQuery->execute();

		// insert business skills
		$bskillInsert = $conn->prepare("INSERT into user_bs_skills values (:userid , :business_skills)");
		$bskillInsert->bindParam(":userid" , $userid);
		$bskillInsert->bindParam(":business_skills" , $business_skills);

		// update business skill
		$bskillUpdate = $conn->prepare("UPDATE user_bs_skills set user_id = :userid, bs_skill_id = :business_skills where user_id = :userid");
		$bskillUpdate->bindParam(":userid" , $userid);
		$bskillUpdate->bindParam(":business_skills" , $business_skills);

		if(empty($_FILES['select_image']['name'])){

            /*
            UPDATE PROFILE OMITTING PROFILE IMAGE
             */
            $updateQuery = $conn->prepare("UPDATE regusers set about = :updated_about_me,email = :email , phone_number = :phone_number, fname = :fname , nickname = :nickname where id = :userid");
            $updateQuery->bindParam(":userid" , $userid);
            $updateQuery->bindParam(":updated_about_me" , $updated_about_me);
            $updateQuery->bindParam(":email" , $email);
            $updateQuery->bindParam(":phone_number" , $phone_number);
            $updateQuery->bindParam(":fname" , $fname);
            $updateQuery->bindParam(":nickname" , $nickname);

            if ($checkQuery->rowCount() == 0) {
                // do an insert for business skills
                $updateQuery->execute();
                $bskillInsert->execute();
                echo "<span class='alert alert-info'><strong>Complete! </strong> View changes after refresh.</span>";
            }else {
                // update business skills
                $updateQuery->execute();
                $bskillUpdate->execute();
                echo "<span class='alert alert-info'><strong>Complete! </strong> View changes after refresh.</span>";
            }

        }else {

            /*
            UPDATE PROFILE PLUS THE IMAGE
             */
            $imagePath = "../image_uploads/".$_FILES['select_image']['name'];
            $updateQuery = $conn->prepare("UPDATE regusers set prof_image = :imagePath, about = :updated_about_me,email = :email , phone_number = :phone_number, fname = :fname , nickname = :nickname where id = :userid");
            $updateQuery->bindParam(":imagePath" , $imagePath);
            $updateQuery->bindParam(":updated_about_me" , $updated_about_me);
            $updateQuery->bindParam(":userid" , $userid);
            $updateQuery->bindParam(":email" , $email);
            $updateQuery->bindParam(":phone_number" , $phone_number);
            $updateQuery->bindParam(":fname" , $fname);
            $updateQuery->bindParam(":nickname" , $nickname);

            // delete previous picture if exists
            if (file_exists($oldfileName)) unlink($oldfileName);

            if(move_uploaded_file($_FILES['select_image']['tmp_name'], $imagePath)){

                if ($checkQuery->rowCount() == 0) {
                    // do an insert for business skills
                    $updateQuery->execute();
                    $bskillInsert->execute();
                    echo "<span class='alert alert-info'><strong>Complete! </strong> View changes after refresh.</span>";

                }else {
                    // update business skills
                    $updateQuery->execute();
                    $bskillUpdate->execute();
                    echo "<span class='alert alert-info'><strong>Complete! </strong> View changes after refresh.</span>";
                }
            }
        }
    }
    catch(PDOException $e){
        echo "Error ".$e->getMessage();
    }
}
// update business info
if (isset($_GET['bs_check_form'])) {

	$bs_name = trim(ucwords(strtolower($_GET['bs_name'])));
	$bs_category = trim($_GET['bs_category']);
	$bs_address = trim($_GET['bs_address']);
	$bs_email = trim($_GET['bs_email']);
	$bs_contact_phone = "";

	if (!empty($_GET['bs_contact_phone'])) {
		$bs_contact_phone = "+254" . substr(trim($_GET['bs_contact_phone']) , -9);
	}

	$bs_description = trim($_GET['bs_description']);
	$bs_wrk_time = trim($_GET['bs_wrk_time']);

	/*
	$bs_logoPath = "../image_uploads/".$_FILES['bs_logo']['name'];
	if(move_uploaded_file($_FILES['bs_logo']['tmp_name']) , $bs_logoPath){

	}
     */
	try{
        // first check if a record for the user exists : insert ? else update
		$bsCheckQuery = $conn->prepare("SELECT * from user_bs_info where owner_id = :userid");
		$bsCheckQuery->bindParam(":userid" , $userid);
		$bsCheckQuery->execute();

        // insert query
		$bsInserQuery = $conn->prepare("INSERT into user_bs_info (owner_id, bs_cat_id, bs_name, bs_description, bs_wrk_time, bs_address, bs_contact_mail, bs_contact_phone) values(:userid,:bs_category,:bs_name,:bs_description,:bs_wrk_time,:bs_address,:bs_email,:bs_contact_phone)");
		$bsInserQuery->bindParam(":userid" , $userid);
		$bsInserQuery->bindParam(":bs_category" , $bs_category);
		$bsInserQuery->bindParam(":bs_name" , $bs_name);
		$bsInserQuery->bindParam(":bs_description" , $bs_description);
		$bsInserQuery->bindParam(":bs_wrk_time" , $bs_wrk_time);
		$bsInserQuery->bindParam(":bs_address" , $bs_address);
		$bsInserQuery->bindParam(":bs_email" , $bs_email);
		$bsInserQuery->bindParam(":bs_contact_phone" , $bs_contact_phone);

        // update query
		$bsUpdateQuery = $conn->prepare("UPDATE user_bs_info set bs_cat_id = :bs_category, bs_name = :bs_name, bs_description = :bs_description, bs_wrk_time = :bs_wrk_time, bs_address = :bs_address, bs_contact_mail = :bs_email, bs_contact_phone = :bs_contact_phone where owner_id = :userid");
		$bsUpdateQuery->bindParam(":bs_category" , $bs_category);
		$bsUpdateQuery->bindParam(":bs_name" , $bs_name);
		$bsUpdateQuery->bindParam(":bs_description" , $bs_description);
		$bsUpdateQuery->bindParam(":bs_wrk_time" , $bs_wrk_time);
		$bsUpdateQuery->bindParam(":bs_address" , $bs_address);
		$bsUpdateQuery->bindParam(":bs_email" , $bs_email);
		$bsUpdateQuery->bindParam(":bs_contact_phone" , $bs_contact_phone);
		$bsUpdateQuery->bindParam(":userid" , $userid);

		if ($bsCheckQuery -> rowCount() == 0) {

            // insert new record
			$bsInserQuery->execute();
			echo "<span class='alert alert-info'><strong>Success!</strong> Action complete.</span>";

		}else {

            // update existing info
			$bsUpdateQuery->execute();
			echo "<span class='alert alert-info'><strong>Success!</strong> Update complete.</span>";
		}
	}
    catch(PDOException $e){
		echo "Error ".$e->getMessage();
	}
}
