<?php
session_start();
require_once '../includes/dbconfig.php';

$userid = $_SESSION['userid'];
?>
<style type="text/css" media="screen">
    .notifications {
        height: 92%;
        overflow-y: scroll;
    }
</style>

<div class="jumbotron notifications">
    <div class="alert alert-success">James sent you a request to chat</div>
    <div class="alert alert-danger">Your subscription request has been received and is being processes.</div>
    <div class="alert alert-info">Your mailing list has been updated to the current version. All new email addresses will be listed at the top and they will take priority when you send bulk emails</div>
</div>