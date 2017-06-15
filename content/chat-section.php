<?php
session_start();
require_once '../includes/dbconfig.php';
$userid = $_SESSION['userid'];

?>
<style type="text/css" media="screen">
    .message-folders {
        height: 100%;
        padding: 5px;
        box-shadow: 1px 2px 13px 3px rgba(0,0,0,0.1);
    }

        .message-folders li {
            list-style-type: none;
        }

    #list-folders span.btn {
        width: 100%;
        text-align: left;
        padding: 5px;
        margin: 1px 0;
    }

    .user_image_holder {
        width: 90px;
        height: 90px;
    }

        .user_image_holder img {
            width: 100%;
            height: 100%;
            border-radius: 50%;
        }
</style>
<div class="row" style="height: 100%;">
    <div class="col-lg-2 col-md-2 message-folders">
        <div>
            <div class=" text-center">

                <?php
				try{
					$userQuery = $conn->prepare("SELECT * from regusers where id = :userid");
					$userQuery->bindParam(":userid" , $userid);
					$userQuery->execute();
					while($userQueryRow = $userQuery->fetch()){
                ?>
                <div class="user_image_holder text-center">
                    <?php
                        if(!empty($userQueryRow['prof_image'])){
                    ?>
                    <img src=" <?php echo $userQueryRow['prof_image']; ?> " alt="profile image" />
                    <?php
                        }else {

                    ?>
                    <img src="../images/profile.png" alt="" />
                    <?php
                        }
                    ?>
                </div>
                <h6 class="text-uppercase">
                    <?php   echo $userQueryRow['fname'] . " " .$userQueryRow['nickname']  ?>
                </h6>
                <hr style="visibility: hidden;" />
                <h6 class="text-muted">
                    <?php   echo $userQueryRow['about']  ?>
                </h6>
                <?php
					}
				}
                catch(PDOException $e){
					echo "Error ".$e->getMessage();
				}

                ?>
            </div>
        </div>

        <ul class="list-group container" id="list-folders">
            <li id="all-chats">
                <span class="btn btn-sm btn-outline-info">
                    <span class="mdi mdi-wechat"></span>&nbsp; Chats
                    <span class="badge badge-info">26</span>
                </span>
            </li>
            <li id="gmail">
                <span class="btn btn-sm btn-outline-info">
                    <span class="mdi mdi-gmail"></span>&nbsp; Gmail
                    <span class="badge badge-danger">3</span>
                </span>
            </li>
            <span class="dropdown-divider"></span>
            <h6 class="text-muted">BULK SMS</h6>
            <li id="compose">
                <span class="btn btn-sm btn-outline-info">
                    <span class="mdi mdi-account-edit"></span>&nbsp; Compose
                </span>
            </li>
            <li id="sent-messages">
                <span class="btn btn-sm btn-outline-info">
                    <span class="mdi mdi-autorenew"></span>&nbsp; Sent
                </span>
            </li>
            <li id="saved-messages">
                <span class="btn btn-sm btn-outline-info">
                    <span class="mdi mdi-basket-fill"></span>&nbsp; Saved
                </span>
            </li>
            <li id="manage">
                <span class="btn btn-sm btn-outline-info">
                    <span class="mdi mdi-radio-tower"></span>&nbsp; Manage
                    <span class="badge badge-warning">new</span>
                </span>
            </li>
        </ul>

        <span id="userid" style="display: none;">
            <?php  echo $userid;   ?>
        </span>
    </div>
    <div class="col-lg-10 col-md-10 section-display" style="height: 100%;">

        <!-- SELECTED OPTION WILL DISPLAY ITS DATA HERE -->
    </div>
    <div id="preloader_" style="display: none;">
        <div class="text-center" style="padding-top: 100px">
            <img src="../images/preloader/25.gif" alt="" />
        </div>
    </div>

    <script type="text/javascript">
        $( function () {
            $.getScript( '../js/jquery-3.1.1.js', function () {
                //console.log('script loaded successfully')
            } )

            $.getScript( '../js/bootstrap.js', function () {
                //console.log('script loaded successfully')
            } )
            $.getScript( '../js/dataTables.min.js', function () {
                //console.log('script loaded successfully')
            } )
            $.getScript( '../js/jquery.dataTables.js', function () {
                //console.log('script loaded successfully')
            } )
            $.getScript( '../js/dataTables.bootstrap.js', function () {
                //console.log('script loaded successfully')
            } )
            
            // preloader image
            var $cont = $( '#preloader_' ).html();

            var $userid = $( '#userid' ).text();

            $( '.list-group li' ).on( 'hover', function ( event ) {
                event.preventDefault();
                /* Act on the event */
                var $curr_li = $( event.target ).closest( 'li span.btn' );
                $curr_li.removeClass( 'btn-outline-info' );
                $curr_li.addClass( 'btn-outline-warning' );
            } );
            $( '.list-group li' ).click( function ( event ) {
                /* Act on the event */
                $( '.list-group li span' ).removeClass( 'btn-info' );
                $( '.list-group li span' ).addClass( 'btn-outline-info' );
                var $curr_li = $( event.target ).closest( 'li span.btn' );
                $curr_li.removeClass( 'btn-outline-info' );
                $curr_li.addClass( 'btn-info' );
            } );

            // load to prepare chat section-display
            $.get( '../includes/autoload.php', function ( data ) {
                /*optional stuff to do after success */
                $( '.section-display' ).html( data );
            } );

            // function to display selected content
            function navigateToFile( $pathURL, $domElement ) {
                $( '.section-display' ).html( $cont );
                $.get( $pathURL, function ( data ) {
                    /*optional stuff to do after success */
                    $domElement.html( data );
                } );
            }

            //fetch list of all friends
            $( '#all-chats' ).click( function ( event ) {
                navigateToFile( '../includes/autoload.php', $( '.section-display' ) );

                var xhttp = new XMLHttpRequest();
                xhttp.open( 'GET', '../includes/friends-list.php' );
                xhttp.onreadystatechange = function () {
                    if ( this.readyState == 4 && this.status == 200 ) {
                        $( '#folder-specific-messages' ).html( xhttp.responseText );
                    }
                };
                xhttp.send();
            } );

            // VIEW INCOMING MESSAGES
            $( '#compose' ).click( function ( event ) {
                /* Act on the event */
                navigateToFile( 'compose-message.php', $( '.section-display' ) );
            } );

            // VIEW ALL SENT MESSAGES
            $( '#sent-messages' ).click( function ( event ) {
                /* Act on the event */
                navigateToFile( 'sent-messages.php', $( '.section-display' ) );
            } );

            // VIEW ALL SAVED MESSAGES
            $( '#saved-messages' ).click( function ( event ) {
                /* Act on the event */
                navigateToFile( 'saved-messages.php?userid=' + $userid + '&fetchAllSaved=true', $( '.section-display' ) );
            } );

            // VIEW MESSAGES IN GMAIL
            $( '#gmail' ).click( function ( event ) {
                /* Act on the event */
                navigateToFile( 'gmail-emails.php', $( '.section-display' ) );
            } );

            // MANAGE USER PROFILE AND FRIENDS, SETUP BULK MESSAGING AND SUCH
            $( '#manage' ).click( function ( event ) {
                /* Act on the event */
                navigateToFile( 'manage-profile.php?userid=' + $userid, $( '.section-display' ) );
            } );

        } )
    </script>
</div>