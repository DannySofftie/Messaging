<?php

require_once 'dbconfig.php';
sleep(2);
/*
 *
userid
usermail
userphone
username
destid
 *
 */
if(isset($_POST['subscribe'])){
    $userid = $_POST['userid'];
    $usermail = $_POST['usermail'];
    $userphone = $_POST['userphone'];
    $username = $_POST['username'];
    $destid = $_POST['destid'];

    $subQuery = $conn -> prepare("insert into contact_addresses(fname, phone, email, added_by, userid) values(:fname , :phone, :email , :added_by , :userid)");
    $subQuery->bindParam(":fname" , $username);
    $subQuery->bindParam(":phone" , $userphone);
    $subQuery->bindParam(":email" , $usermail);
    $subQuery->bindParam(":added_by" , $destid);
    $subQuery->bindParam(":userid" , $userid);
    if($subQuery->execute()){
        echo "Subscribed";
    }else{
        echo "Failed";
    }
}

if(isset($_POST['unsubscribe'])){
    $userid = $_POST['userid'];
    $destid = $_POST['destid'];

    $unsubQuery = $conn->query("delete from contact_addresses where added_by = '$destid' and userid = '$userid'");
    if($unsubQuery->execute()){
        echo "Unsubscribed";
    }else{
        echo "Failed";
    }
}

?>