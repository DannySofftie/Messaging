<?php
session_start();
require_once '../includes/dbconfig.php';

$userid = $_SESSION['userid'];
?>

<style type="text/css" media="screen">
    .alert {
        box-shadow: 1px 3px 25px 0 rgba(0,0,0,0.2);
    }
</style>
<div class="container">
    <div class="jumbotron text-center">
       <h6>All sent messages will be displayed here</h6>
    </div>
    <div class="message_box_">
        <!--    messages will be loaded here by default   -->
    </div>
</div>
<script type="text/javascript">
    $(function () {
        // get all the saved messages
        $.get('../includes/sent-messages-fetch.php', function (data) {
            $('.message_box_').html(data);
        });
    })
</script>