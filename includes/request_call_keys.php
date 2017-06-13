<?php
date_default_timezone_set("Africa/Nairobi");
require_once('dbconfig.php');
// error_reporting(0);
$userid = $_GET['user_id'];
$currTimeStamp = date("Y-m-d H:i:s" , strtotime("-2 minutes"));
$keysQuery = $conn->prepare("select * from call_handler where receiver_id = :userid and  call_status = 0 and call_start_time >= date_sub(now(),interval 1 minute)");
$keysQuery->bindParam(":userid" , $userid);
$keysQuery->execute();

while($keysQueryRow = $keysQuery->fetch()){
    /*
     * call_id, initiator_id, initiator_key, receiver_id, receiver_key, call_status, call_start_time
    */
    $returnData = new stdClass();
    $returnData -> call_id = $keysQueryRow['call_id'];
    $returnData -> initiator_id = $keysQueryRow['initiator_id'];
    $returnData -> initiator_key = $keysQueryRow['initiator_key'];
    $returnData -> receiver_id = $keysQueryRow['receiver_id'];
    $returnData -> receiver_key = $keysQueryRow['receiver_key'];
    $returnData -> call_start_time = $keysQueryRow['call_start_time'];

    $jsonData = json_encode($returnData);

    echo $jsonData;
}


?>