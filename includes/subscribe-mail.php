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
$userid = $_POST['userid'];
$usermail = $_POST['usermail'];
$userphone = $_POST['userphone'];
$username = $_POST['username'];
$destid = $_POST['destid'];

echo $userid , $usermail , $destid;


?>