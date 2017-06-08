<?php
session_start();
$userid = $_SESSION['userid'];

require_once 'dbconfig.php';

if (isset($_GET['upvote'])) {

	$post_id = $_GET['post_id'];
    // check upvote for this user and respond appropriately
    $checkUpvote = $conn->prepare("select * from post_u_reactions where post_iden = :postid and user_iden = :userid");
    $checkUpvote -> bindParam(":postid" , $post_id);
    $checkUpvote ->bindParam(":userid" , $userid);
    $checkUpvote->execute();
    if($checkUpvote->rowCount() == 0){
        $upvoteQuery = $conn->prepare("insert into post_u_reactions (user_iden , post_iden , reaction) values(:userid , :postid , 1)");
        $upvoteQuery ->bindParam(":userid" , $userid);
        $upvoteQuery->bindParam(":postid" , $post_id);
        if($upvoteQuery->execute()){
?>
<span class="alert alert-info">Upvoted successfully</span>
<?php
        }else{
?>
<span class="alert alert-warning">Failed to upvote</span>
<?php
        }
    }else{
?>
<span class="alert alert-danger">You cannot react to a post twice</span>
<?php
    }
}

if (isset($_GET['downvote'])) {

	$post_id = $_GET['post_id'];
    $checkUpvote = $conn->prepare("select * from post_u_reactions where post_iden = :postid and user_iden = :userid");
    $checkUpvote -> bindParam(":postid" , $post_id);
    $checkUpvote ->bindParam(":userid" , $userid);
    $checkUpvote->execute();
    if($checkUpvote->rowCount() == 0){
        $upvoteQuery = $conn->prepare("insert into post_u_reactions (user_iden , post_iden , reaction) values(:userid , :postid , 0)");
        $upvoteQuery ->bindParam(":userid" , $userid);
        $upvoteQuery->bindParam(":postid" , $post_id);
        if($upvoteQuery->execute()){
?>
<span class="alert alert-info">Downvoted successfully</span>
<?php
        }else{
?>
<span class="alert alert-warning">Failed to downvote</span>
<?php
        }
    }else{
?>
<span class="alert alert-danger">You cannot react to a post twice</span>
<?php
    }
}