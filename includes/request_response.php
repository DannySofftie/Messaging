<?php
require_once 'dbconfig.php';

// work on received data
if(isset($_GET['confirmRequest'])){
    sleep(2);
    $my_id = $_POST['my_id'];
    $current_user_id = $_POST['my_id'];
    $friend_id = $_POST['friend_id'];
    $friends_since = date('Y:m:d');

    try{

        $addQuery = $conn->prepare("INSERT into friends_list(my_id , friend_id , friends_since) values(:current_user_id , :friend_id , :friends_since)");
        $addQuery->bindParam(":current_user_id" ,$friend_id );
        $addQuery->bindParam(":friend_id" , $current_user_id);

        $addQuery->bindParam(":friends_since" , $friends_since);
        $addQuery->execute();

        $confirmQuery = $conn->prepare("update friends_list set request_status = 1 where (my_id = :my_id and friend_id = :friend_id) or (my_id = :friend_id and friend_id = :my_id) ");

        $confirmQuery->bindParam(":my_id" , $my_id);
        $confirmQuery->bindParam(":friend_id" , $friend_id);
        if($confirmQuery->execute()){
            echo "Confirmed request";
        }else{
            echo "Failed to confirm";
        }
    }
    catch(PDOException $e){
        echo 'Error '.$e->getMessage();
    }
}

if(isset($_GET['declineRequest'])){

    $my_id = $_POST['my_id'];
    $friend_id = $_POST['friend_id'];
    try{
        $confirmQuery = $conn->prepare("update friends_list set request_status = 2 where my_id = :my_id and friend_id = :friend_id");
        $confirmQuery->bindParam(":my_id" , $my_id);
        $confirmQuery->bindParam(":friend_id" , $friend_id);
        if($confirmQuery->execute()){
            echo "Declined successfully";
        }else{
            echo "Failed to decline";
        }
    }
    catch(PDOException $e){
        echo 'Error '.$e->getMessage();
    }
}

?>