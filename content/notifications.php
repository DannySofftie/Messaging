
<?php
session_start();
require_once '../includes/dbconfig.php';

$userid = $_SESSION['userid'];
$unconfirmedStatus = 0;

// query to compare values and return connection request notifications
$compareQuery = $conn -> prepare("select * from friends_list where friend_id = :userid and request_status = :unconfirmedStatus");
$compareQuery -> bindParam(":userid" , $userid);
$compareQuery ->bindParam (":unconfirmedStatus" , $unconfirmedStatus);
$compareQuery->execute();

if(isset($_GET['fetchAll'])){
?>

<style>
    .response {
        clear: both;
        text-align: right;
    }

    .notifications {
        height: 93%;
        overflow-y: scroll;
        overflow-x: hidden;
    }

        .notifications .image_holder {
            height: 100px;
            overflow-y: scroll;
        }
</style>

<div class="notifications">

    <?php
    while($compareQueryRow = $compareQuery->fetch()){
        // display user details with confirm button
        $requestId = $compareQueryRow['my_id'];
        $requestDetailsQueryRow = $conn->prepare("select * from regusers where id = :requestId");
        $requestDetailsQueryRow -> bindParam(":requestId" , $requestId);
        $requestDetailsQueryRow->execute();
        $requestDetailsQueryRow = $requestDetailsQueryRow->fetch();
        // now display the user details
        $userData = new stdClass();
        $userData -> fullname = $requestDetailsQueryRow['fname'] . " " . $requestDetailsQueryRow['nickname'];
        $userData -> about = $requestDetailsQueryRow['about'];
        $userData -> image = $requestDetailsQueryRow['prof_image'];

    ?>
    <!--   CONNECTION REQUEST DETAILS   -->

    <div class="card">
        <span class="requestId" style="display: none;"><?php  echo $requestId  ?></span>
        <span class="curr_user_id" style="display: none;"><?php  echo $userid  ?></span>
        <div class="card-block row">
            <div class="col-lg-2 col-md-2 col-sm-2">
                <!-- hold user profile image -->
                <div class="image_holder">
                    <img src="<?php echo $userData->image ?>" style="width: 100%; border-radius: 10%; cursor: pointer" />
                </div>
            </div>
            <div class="col-lg-8 col-md-8 col-sm-8">
                <!--  hold user about info  -->
                <h5>About</h5>
                <i>
                    <?php  echo $userData->about  ?>
                </i>
            </div>
            <div class="col-lg-2 col-md-2 col-sm-2 response">
                <!-- hold response status -->
                <span class="btn btn-sm btn-info confirm_request">
                    Admit &nbsp;
                    <span class="mdi mdi-checkbox-marked-circle-outline"></span>
                </span>
                <span class="btn btn-sm btn-danger decline_request">
                    Decline &nbsp;
                    <span class="mdi mdi-account-key"></span>
                </span>
            </div>
        </div>
    </div>
    <?php
    }
    ?>
</div>
<?php
}

?>

<script>
    $( function () {

        // confirm friend request
        $( '.confirm_request' ).click( function () {

            var $targetCard = $( event.target ).closest( '.card' );

            var $requestId = $( event.target ).closest( '.card' ).find( '.requestId' );
            $requestId = $requestId.text().trim();

            var $curr_user_id = $( event.target ).closest( '.card' ).find( '.curr_user_id' );
            $curr_user_id = $curr_user_id.text().trim();

            $.post( '../includes/request_response.php?confirmRequest=true',
                {
                    my_id : $requestId,
                    friend_id : $curr_user_id
                },
                function (data) {

                    // success /* DO ACTION */
                    /*
                        REFRESH NOTIFICATIONS FETCH
                    */
                   alert(data)
                } );
        } )

        // decline request
        $( '.decline_request' ).click( function () {

            var $targetCard = $( event.target ).closest( '.card' );

            var $requestId = $( event.target ).closest( '.card' ).find( '.requestId' );
            $requestId = $requestId.text().trim();

            var $curr_user_id = $( event.target ).closest( '.card' ).find( '.curr_user_id' );
            $curr_user_id = $curr_user_id.text().trim();

            $.post( '../includes/request_response.php?declineRequest=true',
            {
                my_id: $requestId,
                friend_id: $curr_user_id
            },
            function (data) {
                // success /* DO ACTION */
                /*
                    REFRESH NOTIFICATIONS FETCH
                */
                alert( data );
                $.get( 'notifications.php?fetchAll=true', function () {
                    // returns true
                } );
            } );

        } )
    } )
</script>