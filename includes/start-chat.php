<?php
ob_flush();
session_start();

require_once 'dbconfig.php';

//  user and friend id initialized
$friend_id = intval($_GET['friend_id']);

$userid = $_SESSION['userid'];

// update public ip address every time start chat is initialised
$ip = !empty($_SERVER['HTTP_CLIENT_IP']) ? $_SERVER['HTTP_CLIENT_IP'] : (!empty($_SERVER['HTTP_X_FORWARDED_FOR']) ? $_SERVER['HTTP_X_FORWARDED_FOR'] : $_SERVER['REMOTE_ADDR']);
echo  $ip;

?>
<!-- 
    <link type="text/css" href="../css/bootstrap.css" rel="stylesheet" media="screen, projection" />
    <link type="text/css" href="../css/materialdesignicons.css" rel="stylesheet" media="screen, projection" />
    <link type="text/css" href="../css/custom-style.css" rel="stylesheet" media="screen, projection" /> 
-->
<style type="text/css" media="screen">
    body {
        /*background-color: #edeff2;*/
        font-family: "Roboto", sans-serif;
    }


    .menu_content {
        height: 8%;
        box-shadow: 1px 2px 15px 3px rgba(0,0,0,0.1);
    }

    .messages {
        width: 100%;
        height: 100%;
        overflow-y: scroll;
        overflow-x: hidden;
        font-size: 12px;
        padding: 5px;
    }

    .bottom_wrapper {
        width: 100%;
        position: absolute;
        bottom: 0;
        background-color: transparent;
    }

    .message-text-box {
        text-align: left;
        max-width: 70%;
        min-width: 60%;
        clear: both;
    }

    .header-image {
        height: 100%;
        width: 35px;
        border-radius: 49%;
    }

    #message-send-status {
        position: fixed;
        top: 75px;
        display: none;
    }

    .bounceOutDown {
        animation-duration: 4s;
    }

    .alertright {
        margin: 2px -2px;
        padding: 6px;
        border-radius: 4px;
        background: #336693;
        color: white;
    }

    .alertleft {
        text-align: left;
        margin: 2px 0;
        padding: 6px;
        border-radius: 4px;
        -moz-border-radius: 4px;
        background-color: #336670;
        color: white;
    }

    #message_content {
        width: 100%;
        border-color: transparent;
        resize: none;
    }

    pre {
        color: white;
    }
</style>

<?php

try{
    // get the friends name and profile picture
    $frQuery = $conn->prepare("SELECT * from regusers where id = :friend_id");
    $frQuery->bindParam(":friend_id",$friend_id);
    $frQuery->execute();
    $frQueryRow = $frQuery->fetch();
?>

<!-- fkuser_id, msg_time and message -->

<!-- fkuser_id to determine who saved the message(float left or right depending on this) float right for fkuser_id=userid, and left for fkuser_id=friends_id -->
<div class="animated fadeIn" style="height: 85%;">
    <div class="menu_content bg-faded">
        <?php
    if (!empty($frQueryRow['prof_image'])) {
        ?>
        <img src=" <?php   echo $frQueryRow['prof_image']  ?>" class="header-image float-left" alt="" />
        <?php
    }else{
        ?>
        <img src="../images/profile.png" class="header-image float-left" />
        <?php
    }
        ?>
        <div class="text-center">
            <?php


    if (empty($frQueryRow['fname']) and empty($frQueryRow['nickname'])) {
        echo "No username ";
    }
    echo $frQueryRow['fname'] . " " . $frQueryRow['nickname'];

            ?>
            <div class="float-right" style="margin-right: 10px;">
                <span id="request_call" class="btn btn-sm btn-outline-info">
                    <i class="mdi mdi-phone"></i>
                </span>
                <span class="btn btn-sm btn-outline-success">
                    <i class="mdi mdi-google-drive"></i>
                </span>
                <span class="btn btn-sm btn-outline-warning">
                    <i class="mdi mdi-content-save-all"></i>
                </span>
            </div>
        </div>
    </div>
    <div class="content_holder">
        <span id="friend_id" style="display: none;">
            <?php  echo $friend_id;  ?>
        </span>
        <span id="userid" style="display: none;">
            <?php  echo $userid;   ?>
        </span>
        <div class="messages">
            <div id="message-send-status" class="alert alert-info animated bounceOutDown">
                <span>Successfully sent</span>
            </div>

            <!--  MESSAGES TO BE LOADED HERE VIA AJAX CALL  -->

            <div class="loader text-center">
                <img src="../images/preloader/25.gif" alt="" />
            </div>
        </div>
        <div class="bottom_wrapper" style="width: 100%;">
            <form class="form-inline row" style="width: 100%;" method="get" id="text-message">
                <div class="form-group">
                    <input type="hidden" name="friend_id" value="<?php echo $friend_id ?>" />
                </div>
                <div class="form-group col-lg-11 has-warning">
                    <input type="text" class="form-control" id="message_content" name="message_content" placeholder="Type your message here..." autocomplete="off" />
                </div>
                <div class="form-group col-lg-1">
                    <button type="submit" class="btn btn-success">
                        <span class="mdi mdi-send"></span>
                    </button>
                </div>
            </form>
        </div>
    </div>
    <?php
}
catch(PDOException $e){
	// catch the errors and throw them
    echo "Error on fetch ".$e->getMessage()."<br />";

}

    ?>
</div>
<script src="../js/jquery-3.1.1.js"></script>
<script src="../js/bootstrap.js"></script>
<script type="text/javascript">

    $(function () {

        (function recentChat() {
            var $friend_id = $('#friend_id').text();
            var $userid = $('#userid').text();
            $.get('../includes/specific-id-chats.php?friend_id=' + $friend_id + '&userid=' + $userid, function (data) {
                /*optional stuff to do after success */
                $('.messages').html(data);
                setTimeout(recentChat, 3000);
            });
        })();

        // submit text message
        $('#text-message').submit(function (event) {
            /* Act on the event */
            event.preventDefault();

            $.ajax({
                url: '../includes/text-message-save.php',
                method: 'GET',
                data: $('form').serialize(),
                dataType: 'text',
                success: function (data) {
                    // show success message
                    $('#message-send-status').css({
                        display: 'block'
                    });
                    // set message input box to empty
                    $('#message_content').val("");
                }
            })
        });

        // make request to NodeJs for call initiation
        $('#request_call').click(function () {

            // will take these keys from database
            var $initiator_key = 's76hchjghye7y8u9yuchb7eyry78c';
            var $receiver_key = '8c7y8ryu8u8cbuyey7684bync8e8bc';

            var $url = 'http://169.254.116.14:7880/initiate?initiator_crypto=' + $initiator_key + '&receiver_crypto=' + $receiver_key;
            window.open($url, "_blank", "location=0,toolbar=no,scrollbars=no,resizable=yes,status=no,titlebar=0,top=45,left=200,width=800,height=600");
        });
        // end
    })
</script>

