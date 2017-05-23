<?php
session_start();
$userid = $_SESSION['userid'];
require_once 'dbconfig.php';
try {
	// fetch all the messages and return
    $fetchMessages = $conn->prepare("select * from messages where fkuser_id=:userid and send_status=1 order by msg_time asc");
    $fetchMessages->bindParam(":userid" , $userid);
    $fetchMessages->execute();

?>
<table class="table table-success table-hover" id="sent_messages">
    <thead>
        <tr>
            <th>Message</th>
            <th>Time saved</th>
            <th>Send mode</th>
            <th>Recipients</th>
        </tr>
    </thead>
    <tbody>
        <?php
    while($fetchMessagesRow = $fetchMessages->fetch()){
        // output result set
        ?>

        <tr>
            <td>
                <?php  echo $fetchMessagesRow['message']  ?>
            </td>
            <td>
                <?php
        $curr_date = new DateTime('now');
        $msg_time = new DateTime( $fetchMessagesRow['msg_time']);
        $interval = date_diff($curr_date , $msg_time);
        $correctTime = "";
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

        // display time saved
        print_r($correctTime);
                ?>
            </td>
            <td>
                <?php  echo $fetchMessagesRow['msg_mode']  ?>
            </td>
            <td>
                <?php  echo $fetchMessagesRow['recipients_no']  ?>
            </td>
        </tr>

        <?php
    }
        ?>
    </tbody>
</table>
<?php

}
catch (PDOException $e) {
    die("Error " . $e->getMessage());
}

?>

<script>
    $(function () {
        $('#sent_messages').dataTable();
    })
</script>