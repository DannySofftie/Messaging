<?php
session_start();
require_once 'dbconfig.php';
$userid = $_SESSION['userid'];
?>

<style type="text/css" media="screen">
	/*FRIENDS PROFILE IMAGE*/
	.friend-prof-image img{
		width: 40px;
		height: 40px;
		border-radius: 48%;
	}
	.friends li{
		padding: 7px;
		margin: 2px;
		list-style: none;
	}
	/*ANIMATION FOR ONLINE INDICATION*/

	@keyframes online_status{
		0%{ background-color: rgba(0, 118, 66, 0.5); }
		25%{ background-color: hsla(120, 100%, 50%, 0.9); }
		50%{ background-color: rgba(0, 118, 66, 0.5); }
		75%{ background-color: hsla(120, 100%, 50%, 0.9); }
		100%{ background-color: rgba(0, 118, 66, 0.5); }
	}
	.online_status{
		width: 10px;
		height: 10px;
		border-radius: 48%;
		background-color: hsla(120, 100%, 50%, 0.5);
		animation-name: online_status;
		animation-duration: 2s;
		animation-iteration-count: infinite;
	}

	/*ANIMATION FOR OFFLINE INDICATION*/
	
	@keyframes offline_status{
		0%{ background-color: rgba(0,0,0,0.9); }
		25%{ background-color: rgba(0,0,0,0.5); }
		50%{ background-color: rgba(0,0,0,0.9); }
		75%{ background-color: rgba(0,0,0,0.5); }
		100%{ background-color: rgba(0,0,0,0.9); }
	}
	.offline_status{
		width: 10px;
		height: 10px;
		border-radius: 48%;
		background-color: rgba(0,0,0,0.4);
		animation-name: offline_status;
		animation-duration: 2s;
		animation-iteration-count: infinite;
	}

</style>

<?php
try {

	// to retrieve all the friends/connected users
	$friendsQuery = $conn->prepare("SELECT * from friends_filter where my_id= :userid");
	$friendsQuery->bindParam(":userid", $userid);
	$friendsQuery->execute(); 

	?>
	<!-- OUTPUT ALL DATA HERE -->
	<div class="animated slideInLeft friends" style="width: 100%">	
		<?php

		while ($friendsQueryRow = $friendsQuery->fetch()) {

			try{
				// to retrieve the last chat time
				$chatTimeQuery = $conn->prepare("SELECT * from (SELECT * from chat_messages as t  where (my_id = :userid and friend_id = :friend_id) or (friend_id = :userid and my_id = :friend_id) order by chat_time desc limit 1) as t order by chat_time asc");
				$chatTimeQuery->bindParam(":userid" , $userid);
				$chatTimeQuery->bindParam(":friend_id" , $friendsQueryRow['friend_id']);
				$chatTimeQuery->execute();
				$chatTimeQueryRow = $chatTimeQuery->fetch();

			}catch(PDOException $e){
				echo "Erro ".$e->getMessage();
			}
			
			$orderByTime = array(
				'chat_id' => $chatTimeQueryRow['chat_id'],
				'chat_time' => substr(substr($chatTimeQueryRow['chat_time'] , -8) , 0 ,5)
			);
			asort($orderByTime);
			
			// IN DEVELOPMENT TO SORT FRIENDS ON THE LAST TIME CHAT

			?>
			<span class="time"></span>
			<div class="targetDiv" style="margin: 1px;">
				<div class="btn btn-sm btn-outline-info specific-chat" style="width: 100%" id="<?php echo $friendsQueryRow['friend_id'] ?>">
					<span class="friend-prof-image float-left">
						<?php
						if (empty($friendsQueryRow['prof_image'])) {
							?>
							<img src="../images/profile.png" alt="profile image">
							<?php
						} else {

							?>
							<img src="<?php echo $friendsQueryRow['prof_image']; ?>" alt="profile image">
							<?php
						}
						?>
					</span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
					<span>
						<?php 
						if (empty($friendsQueryRow['fname'])) {

							?>
							<span style="color: rgba(0,0,0,0.4);">No username</span>

							<?php
						}else{
							echo $friendsQueryRow['fname'] . "<span>&nbsp;&nbsp;</span>" . $friendsQueryRow['nickname'];
						}
						?>
					</span>
					<span class="float-right" style="padding-top: 11px">
						<?php 
						if (empty($chatTimeQueryRow['chat_time'])) {
							?>
							<span style="font-size: 9px; color: black">N/A</span>
							<?php
						}
						?>
						<span style="font-size: 9px; color: black"><?php  echo substr(substr($chatTimeQueryRow['chat_time'] , -8) , 0 ,5);  ?></span>
					</span>
				</div>
			</div>
			<?php
		}
		?>		
	</div>
	<?php

// throw exception if an error on fetching data occurs
} catch (PDOException $e) {

	echo "Error " . $e->getMessage();

}

?>

<script type="text/javascript">

	// click on a chat 
	$('.specific-chat').click(function(event) {
		/* Act on the event */
		event.preventDefault();
		$('.targetDiv .specific-chat').removeClass('btn-info');
		$('.targetDiv .specific-chat').addClass('btn-outline-info');
		var $clickedDiv  = $(event.target).closest('.targetDiv .specific-chat');
		$clickedDiv.removeClass('btn-outline-info');
		$clickedDiv.addClass('btn-info');

		var $urlChat = '../includes/start-chat.php?friend_id=' + $(this).attr('id');
		var xhttp = new XMLHttpRequest();
		xhttp.open('GET',$urlChat);
		xhttp.onreadystatechange = function(){
			if(this.readyState == 4 && this.status == 200){
				$('#message-specific-view').html(xhttp.responseText);
			}
		};
		xhttp.send();
	});


	$('.specific-chat').click(function(event) {
		/* Act on the event */

		$(event.target).closest('.friends li').addClass('alert alert-info');

	});

	// update the last time a message was sent or received
	

</script>
