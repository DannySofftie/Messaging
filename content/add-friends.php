<?php
session_start();
require_once('../includes/dbconfig.php');
$userid = $_SESSION['userid'];

if (!isset($_SESSION['usermail'])) {

	header("Location: ../index.php?notloggedin");

} else {


	// intialize arrays to hold id's for all_users and current_friends
	$all_users_id = array();
	$my_friends_id = array();
	$curr_user = array($userid);

	// get current friends id's
	$my_friends_idQuery = $conn->prepare("SELECT * from friends_list where my_id = :userid");
	$my_friends_idQuery->bindParam(":userid" , $userid);
	$my_friends_idQuery->execute();
	while ($my_friends_idQueryRow = $my_friends_idQuery->fetch()) {
		array_push($my_friends_id, $my_friends_idQueryRow['friend_id']);
	}

	// get all users id's
	$all_users_idQuery = $conn->prepare("SELECT * from regusers");
	$all_users_idQuery->execute();
	while ($all_users_idQueryRow = $all_users_idQuery->fetch()) {
		array_push($all_users_id, $all_users_idQueryRow['id']);
	}

	// retrieve id's for all non_friends
	$non_friends_id = array_diff($all_users_id, $my_friends_id, $curr_user);
	$modified_ids = '';

?>

<style type="text/css" media="screen">
    .add-friends {
        height: 100%;
        overflow-y: scroll;
        z-index: 0;
        padding: 5px;
    }

    .user_image {
        width: 80px;
        height: 80px;
        overflow: hidden;
    }

        .user_image img {
            width: 100%;
            height: 100%;
        }

    .user_about {
        padding: 3px;
        font-size: 12px;
        height: auto;
    }

    .action_result {
        position: fixed;
        top: 150px;
        left: 30%;
        padding: 29px;
        z-index: 9999;
    }
</style>
<div class="jumbotron" style="height: 92%; overflow-y: scroll; overflow-x: hidden; padding: 10px">
    <!-- DISPLAY ALL THE USERS IN THE SYSTEM -->
    <div class="action_result"></div>
    <div class="current_user_id" style="display: none;">
        <?php  echo $userid  ?>
    </div>
    <div class="row add-friends">

        <?php
    for($value=0; $value <= count($non_friends_id); $value++) {
        foreach ($non_friends_id as $value) {
            $modified_ids = $value;
            // query to select all users in the system
            $userQuery = $conn->prepare("SELECT regusers.id, regusers.email, regusers.prof_image, regusers.fname, regusers.nickname, regusers.about from regusers where regusers.id = '$modified_ids'");
            $userQuery->execute();
            /*

            NOW LOOP THROUGH ALL NON FRIENDS

             */
            while ($userQueryRow = $userQuery->fetch()) {

        ?>
        <div class="per_user_box col-lg-3 col-md-3 ">
            <div class="user_image">
                <?php
                if (empty($userQueryRow['prof_image'])) {

                    // display default profile image

                ?>
                <img src="../images/profile.png" alt="default picture" />
                <?php

                }else {
                    echo "<img src=".$userQueryRow['prof_image'] ." alt='user image'/>";
                }
                ?>
            </div>
            <div class="user_name">
                <?php
                if (empty($userQueryRow['fname'])) {
                ?>
                <span style="color: rgba(0,0,0,0.4);">No username</span>
                <?php
                }else{
                    echo $userQueryRow['fname'] . '&nbsp;' . $userQueryRow['nickname'];
                }
                ?>
            </div>
            <div class="user_about text-muted">
                <div class="user_about_text">
                    <?php echo $userQueryRow['about']; ?>
                </div>
            </div>
            <div class="add_friend_options">
                <span class="btn btn-sm btn-outline-warning text-uppercase">not connected</span>
                <span id="<?php  echo $userQueryRow['id']; ?>" class="btn btn-sm btn-outline-success text-uppercase add_friend">
                    <span class="mdi mdi-account-plus add_status"></span>
                </span>
                <span friendid="<?php  echo $userQueryRow['id']; ?>" class="btn btn-sm btn-outline-info text-uppercase remove_friend">
                    <span class="mdi mdi-message"></span>
                </span>
            </div>
        </div>
        <?php
            }
        }

    }
        ?>
    </div>
</div>
<?php

}
?>
<script type="text/javascript">
    $(function () {

        $('.add_friend').click(function (event) {
            /* Act on the event */
            event.preventDefault();
            var $currSpan = $(event.target).closest('.add_status');
            var $friend_id = $(this).attr('id');
            var $current_user_id = $('.current_user_id').text();
            var $url = "../includes/add-friends-inc.php?friend_id=" + $friend_id + "&current_user_id=" + $current_user_id;

            $.get($url, function (data) {
                $('.action_result').fadeIn('slow', function () {
                    $('.action_result').html(data);
                });
                $('.action_result').fadeOut(3000);

                $currSpan.css({
                    'color': 'green'
                });
                $currSpan.removeClass('mdi-account-plus');
                $currSpan.addClass('mdi-checkbox-multiple-marked-circle');
            });
        });
    });
</script>

