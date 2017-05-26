<style>
    .preloader_holder {
        position: absolute;
        left: 45%;
        margin: 10px;
    }
</style>

<?php
session_start();

date_default_timezone_set("Africa/Nairobi");

$userid = $_SESSION['userid'];
require_once 'dbconfig.php';

if (isset($_GET['postContent'])) {
	// post feed to database
	$postContent = $_GET['postContent'];


	if (!empty($_FILES['post_image']['name'])) {
		try {
			// do a post with the post image selected
			$postImage = '../file_uploads/'.basename($_FILES['post_image']['name']);

			if (move_uploaded_file($_FILES['post_image']['tmp_name'], $postImage)) {
				$postInsert = $conn->prepare("INSERT into post_feeds (user_id, post_text, post_image) values (:user_id, :post_text, :post_image)");
				$postInsert->bindParam(":user_id" , $userid);
				$postInsert->bindParam(":post_text" , $postContent);
				$postInsert->bindParam(":post_image" , $postImage);
				if ($postInsert->execute()) {
                    // successfully posted
?>
<span class="alert alert-info">Successfully posted with image!</span>
<?php
				}else {
                    // throw an error
?>
<span class="alert alert-danger">Failed to post with file!</span>
<?php
				}
			}else {
?>
<span class="alert alert-warning">Your image is large than 2 MBs</span>
<?php
			}
		}
        catch (PDOException $e) {
			echo 'Error encountered ' .$e->getMessage();
		}
	}else{

        // post without the image selected
		$postInsert = $conn->prepare("INSERT into post_feeds (user_id, post_text) values (:user_id, :post_text)");
		$postInsert->bindParam(":user_id" , $userid);
		$postInsert->bindParam(":post_text" , $postContent);
		if ($postInsert->execute()) {
            // successfully posted
?>
<span class="alert alert-info">Successfully posted without image!</span>
<?php
		}else {
            // throw an error
?>
<span class="alert alert-danger">Failed to post your post!</span>
<?php
		}

	}


}
/*

FETCH ALL THE POSTS WHEN SUCCESSFUL POST OCCURS FOR EVERY NEW POST

 */

if (isset($_GET['fetchNew'])) {

?>
<div class="all_posts_holder">
    <?php
	// fetch new posts
	$currUserId = array($userid);
	$connectedUsers = array();
	$allUsers = array();
    $page_number = filter_var($_POST['page_number'] , FILTER_SANITIZE_NUMBER_INT ,FILTER_FLAG_STRIP_HIGH);
    $item_per_page = filter_var(8 , FILTER_SANITIZE_NUMBER_INT , FILTER_FLAG_STRIP_HIGH);
    $position = (($page_number - 1) * $item_per_page);

	// current date and time
	$date_a = new DateTime('now');


	try{

		$postsByUsers = array();
		$currUserArray = array($userid);
		$connectedUsersIds = array();

		// all posts with the 'poster(s)' data defined by user_id
		$usersPostJoin = $conn->prepare("SELECT regusers.prof_image, regusers.fname, regusers.nickname,post_feeds.user_id, post_feeds.post_text, post_feeds.post_time, post_feeds.post_image from regusers right join post_feeds on regusers.id = post_feeds.user_id");
		$usersPostJoin->execute();
		while($usersPostJoinRow = $usersPostJoin->fetch()){
			array_push($postsByUsers, $usersPostJoinRow['user_id']);
		}

		$friendsIdQuery = $conn->prepare("SELECT * from friends_list where my_id = :userid");
		$friendsIdQuery->bindParam(":userid" , $userid);
		$friendsIdQuery->execute();

		// push all connected users to connectedUsers array
		while($friendsIdQueryRow = $friendsIdQuery->fetch()){
			array_push($connectedUsersIds, $friendsIdQueryRow['friend_id']);
		}

		// merge arrays to include current user id and their posts
		$requiredPostsByUserId = array_merge($connectedUsersIds, $currUserId);

		// shuffle order of id's in the array
		shuffle($requiredPostsByUserId);

		foreach ($requiredPostsByUserId as $value) {

			// SELECT * from post_feeds where user_id = :value order by post_time desc
			$postFetchQuery = $conn->prepare("SELECT * from(SELECT regusers.prof_image, regusers.fname, regusers.nickname,post_feeds.post_id,post_feeds.user_id, post_feeds.post_text, post_feeds.post_time, post_feeds.post_image from regusers right join post_feeds on regusers.id = post_feeds.user_id order by post_feeds.post_time desc limit $position,$item_per_page) as t where user_id = :value order by post_time desc");
			$postFetchQuery->bindParam(":value" , $value);
			$postFetchQuery->execute();

			while ($postFetchQueryRow = $postFetchQuery->fetch()) {

				$date_b = new DateTime($postFetchQueryRow['post_time']);

				$interval = date_diff($date_a,$date_b);

				$correctTime = '';

				if ($interval->format('%m') < 1 && $interval->format('%d') < 1 && $interval->format('%h') < 1 && $interval->format('%i') < 1) {
					$correctTime = $interval->format('%s secs ago');
				}
				if ($interval->format('%m') < 1 && $interval->format('%d') < 1 && $interval->format('%h') < 1) {
					if ($interval->format('%i') < 1 && $interval->format('%s') >= 0) {
						$correctTime = $interval->format('%s secs ago');
					}
					elseif ($interval->format('%i') == 1) {
						$correctTime = $interval->format('%i min ago');
					}else{
						$correctTime = $interval->format('%i mins ago');
					}
				}
				if ($interval->format('%m') < 1 && $interval->format('%d') < 1 && $interval->format('%h') > 0) {
					$correctTime = $interval->format('%h hours %i mins ago');
				}
				if ($interval->format('%m') < 1 && $interval->format('%d') >= 1) {
					if ($interval->format('%d') == 1) {
						$correctTime = $interval->format('Yesterday at %h:%i hrs');
					}else{
						$correctTime = $interval->format('%d days ago');
					}
				}
				if ($interval->format('%m') >= 1) {
					$correctTime = $interval->format('%m months ago');
				}

		    	// DISPLAY ALL POSTS FROM CONNECTED USERS, INCLUDING CURRENT USER'S POST when there is no image
				if (empty($postFetchQueryRow['post_image'])) {


    ?>
    <div class="card  card-outline-warning postview">
        <div class="card-block">
            <h5 class="card-title text-info">
                <a href="">
                    <?php echo $postFetchQueryRow['fname'] . " " . $postFetchQueryRow['nickname']   ?>
                </a>
            </h5>
            <h6 class="card-subtitle mb-2 text-muted">
                <?php echo $correctTime; ?>
            </h6>
            <p class="card-text">
                <?php  echo $postFetchQueryRow['post_text'];  ?>
            </p>
            <span style="font-size: 9px" id="upvote">
                <span class="upvote" post_id=" <?php  echo $postFetchQueryRow['post_id'];  ?> ">
                    Upvote
                    <span class="mdi mdi-heart"></span>
                </span>&nbsp;
                <span class="downvote" post_id=" <?php  echo $postFetchQueryRow['post_id'];  ?> ">
                    Downvote
                    <span class="mdi mdi-bomb-off"></span>
                </span>
            </span>
            <span class="share_options float-right" post_id=" <?php echo $postFetchQueryRow['post_id'];  ?> ">
                <span class="social_share social_hide">
                    <span class="instagram_share btn btn-sm btn-outline-danger mdi mdi-instagram"></span>
                    <span class="twitter_share btn btn-sm btn-outline-info mdi mdi-twitter"></span>
                    <span class="facebook_share btn btn-sm btn-outline-primary mdi mdi-facebook"></span>
                </span>
                <span class="btn btn-sm btn-outline-info share_toggler">
                    <span class="mdi mdi-share-variant"></span>
                </span>
            </span>
        </div>
    </div>
    <?php
				}else {
					// DISPLAY WITH THE IMAGE STYLED TO DISPLAY WELL
					$imgPath = $postFetchQueryRow['post_image'];

    ?>

    <div class="card postview">
        <div class="card-img">
            <img src="<?php  echo $imgPath  ?> " alt="post display image" />
        </div>
        <div class="card-block">
            <h5 class="card-title text-info">
                <?php echo $postFetchQueryRow['fname'] . " " . $postFetchQueryRow['nickname']   ?>
            </h5>
            <h6 class="card-subtitle mb-2 text-muted">
                <?php echo $correctTime; ?>
            </h6>
            <p class="card-text">
                <?php  echo $postFetchQueryRow['post_text'];  ?>
            </p>
            <span style="font-size: 9px" id="upvote">
                <span class="upvote" post_id=" <?php  echo $postFetchQueryRow['post_id'];  ?> ">
                    Upvote
                    <span class="mdi mdi-heart"></span>
                </span>&nbsp;
                <span class="downvote" post_id=" <?php  echo $postFetchQueryRow['post_id'];  ?> ">
                    Downvote
                    <span class="mdi mdi-bomb-off"></span>
                </span>&nbsp;
                <span class="recommend" post_id=" <?php  echo $postFetchQueryRow['post_id'];  ?> ">
                    Recommend
                    <span class="mdi mdi-record-rec"></span>
                </span>
            </span>
            <span class="share_options float-right" post_id=" <?php  echo $postFetchQueryRow['post_id'];  ?> ">
                <span class="social_share social_hide">
                    <span class="instagram_share btn btn-sm btn-outline-danger mdi mdi-instagram"></span>
                    <span class="twitter_share btn btn-sm btn-outline-info mdi mdi-twitter"></span>
                    <span class="facebook_share btn btn-sm btn-outline-primary mdi mdi-facebook"></span>
                </span>
                <span class="btn btn-sm btn-outline-info share_toggler">
                    <span class="mdi mdi-share-variant"></span>
                </span>
            </span>
        </div>
    </div>
    <?php
				}
			}

		}
	}
    catch(PDOException $e){
		echo "Error ".$e->getMessage();
	}

}
    ?>
</div>

<script type="text/javascript">
    $(function () {
        // social share
        $('.share_options').hover(function () {
            /* Stuff to do when the mouse enters the element */
            var $social_sites = $(event.target).closest('.share_options').find('.social_share');
            $social_sites.fadeIn(1000);
        }, function () {
            var $social_sites = $(event.target).closest('.share_options').find('.social_share');
            $social_sites.fadeOut(100);
        });

        $('.instagram_share').click(function (event) {
            /* Act on the event */
            var $post_id = $(event.target).closest('.share_options').attr('post_id');

        });
        $('.twitter_share').click(function (event) {
            /* Act on the event */
            var $post_id = $(event.target).closest('.share_options').attr('post_id');

        });
        $('.facebook_share').click(function (event) {
            /* Act on the event */
            var $post_id = $(event.target).closest('.share_options').attr('post_id');

        });

        $('.postview').hover(function () {
            /* Stuff to do when the mouse enters the element */
            $(this).addClass('bg-faded');
            $(this).css('cursor', 'pointer');
        }, function () {
            /* Stuff to do when the mouse leaves the element */
            $(this).removeClass('bg-faded');
        });

        $('.upvote').hover(function () {
            /* Stuff to do when the mouse enters the element */
            $(this).addClass('text-info');
        }, function () {
            /* Stuff to do when the mouse leaves the element */
            $(this).removeClass('text-info');
        });

        $('.downvote').hover(function () {
            /* Stuff to do when the mouse enters the element */
            $(this).addClass('text-danger');
        }, function () {
            /* Stuff to do when the mouse leaves the element */
            $(this).removeClass('text-danger');
        });
        $('.recommend').hover(function () {
            /* Stuff to do when the mouse enters the element */
            $(this).addClass('text-success');
        }, function () {
            /* Stuff to do when the mouse leaves the element */
            $(this).removeClass('text-success ');
        });

        // UPVOTING AND DOWNVOTING IMPLEMENTATION
        $('.upvote').click(function (event) {
            /* Act on the event */
            $(this).removeClass('text-info');
            $.ajax({
                url: '../includes/post-feeds-reactions.php?upvote=true' + '&post_id=' + $(this).attr('post_id'),
                method: 'GET',
                async: false,
                success: function () {
                    // change text color
                    var $currClicked = $(event.target).closest('#upvote .upvote');
                    $currClicked.attr('class', 'text-primary');
                },
                error: function () {
                    alert('Upvote failed')
                },
                cache: false,
                contentType: false,
                processData: false
            })
        });
        $('.downvote').click(function (event) {
            /* Act on the event */
            $(this).removeClass('text-danger');
            $.ajax({
                url: '../includes/post-feeds-reactions.php?downvote=true' + '&post_id=' + $(this).attr('post_id'),
                method: 'GET',
                async: false,
                success: function () {
                    // change text color
                    var $currClicked = $(event.target).closest('#upvote .downvote');
                    $currClicked.attr('class', 'text-warning');
                },
                error: function () {
                    alert('Downvote failed')
                },
                cache: false,
                contentType: false,
                processData: false
            })
        });

    })
</script>