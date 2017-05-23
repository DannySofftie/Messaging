<?php
session_start();
require_once '../includes/dbconfig.php';

$userid = $_SESSION['userid'];
$msg_mode = "SAVE";
?>
<style type="text/css" media="screen">
    .saved_message_holder {
        clear: both;
        padding: 20px;
        box-shadow: 1px 1px 2px 1px rgba(0,0,0,0.1);
        margin-bottom: 10px;
    }

    .message_options {
        float: right;
    }

    .message_options {
        font-size: 5px;
    }

    .loader {
        position: fixed;
        left: 49%;
        top: 180px;
    }

        .loader img {
            border-radius: 40%;
        }

    .text_color {
        color: teal;
    }
</style>
<div class="container">

    <div class="loader" style="display: none;">
        <img src="../images/preloader/25.gif" alt="" />
    </div>
    <div class="all_saved">
        <?php
        if(isset($_GET['fetchAllSaved'])){
        ?>
        <div class="jumbotron text-center">
            <span class="alert alert-info">All your saved messages</span>
        </div>
        <?php
            try {
                $selectQuery = $conn->prepare("SELECT * from messages where fkuser_id = :fkuser_id and msg_mode = :msg_mode and delete_status=0");
                $selectQuery->bindParam(":fkuser_id" , $userid);
                $selectQuery->bindParam(":msg_mode" , $msg_mode);
                $selectQuery->execute();
                // display message if no messages are saved
                if($selectQuery->rowCount() == 0){
        ?>
        <div class="text-center" style="padding: 150px 0;">
            <span class="alert alert-danger">Seems there are no messages saved at this time</span>
        </div><?php
                }else{
              ?>
        <table class="table table-success" id="savedMessages">
            <thead>
                <tr>
                    <th>Message</th>
                    <th>Delete</th>
                </tr>
            </thead>
            <tbody>

                <?php
                    while ($selectQueryRow = $selectQuery->fetch()) {
                ?>
                <tr>
                    <td>
                        <?php echo $selectQueryRow['message'];  ?>
                    </td>
                    <td>
                        <span msgid=" <?php echo $selectQueryRow['id'];?>" class="btn btn-sm btn-outline-danger delete mdi mdi-delete"></span>
                    </td>
                </tr>
                <?php
                    }
                ?>
            </tbody>
        </table>
        <?php
                }
            }
            catch (PDOException $e) {
                echo "An error occured ".$e->getMessage();
            }
        ?>
    </div>
    <?php
        }
    ?>
</div>
<?php
if(isset($_GET['deleteMessage'])){
    // receive the passed parameter and if delete, remove that message from db
    try{
        $messageID = $_GET['deleteMessage'];
        $deleteQuery = $conn->prepare("update messages set delete_status=1 where id=:deleteMessage and fkuser_id=:userid");
        $deleteQuery->bindParam(":deleteMessage" , $messageID);
        $deleteQuery->bindParam(":userid" , $userid);
        if($deleteQuery->execute()){
            echo "Deleted";
        }else{
            echo "Failed to delete";
        }
    }
    catch(PDOException $e){
        echo "Error occured ".$e->getMessage();
    }
}

?>

<!-- TO ENABLE JQUERY INTELLISENSE
<%
if(false)
{ %>
<script src="../Scripts/jquery-2.1.0-vsdoc.js" type="text/javascript"></script>
<% } %>
 END JQUERY INTELLISENSE FILE -->

<script type="text/javascript">

    $(function () {

        $('#savedMessages').dataTable();

        $('.saved_message_holder').click(function (event) {
            /* Act on the event */
            var $message = $(event.target).closest('.message_body').text();
        });

        $('.mdi-delete').click(function (event) {
            /* Act on the event */
            $messageId = $(this).attr('msgid');
            $.ajax({
                url: 'saved-messages.php?deleteMessage=' + $messageId,
                method: 'GET',
                async: false,
                success: function (data) {
                    // to do if delete
                    $('.delete_status').html(data);
                    $.get('saved-messages.php?fetchAllSaved=true', function (all_saved) {
                        // fetch all the messages again after successful delete
                        $('.all_saved').html(all_saved);
                    });
                },
                error: function () {
                    alert("An error occured");
                }
            });
        });
    })
</script>