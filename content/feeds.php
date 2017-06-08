<?php
session_start();
require_once('../includes/dbconfig.php');
$currUserQuery = $conn->prepare("select * from regusers where email = :usermail");
$currUserQuery->bindParam(":usermail" , $_SESSION['usermail']);
$currUserQuery->execute();

if (!isset($_SESSION['usermail'])) {

	header("Location: ../index?notloggedin");

} else {

?>

<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1.0, user-scalable=no" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="msapplication-tap-highlight" content="no" />
    <title>Talk, chat, email and call online</title>

    <link type="text/css" href="../css/bootstrap.css" rel="stylesheet" media="screen, projection" />
    <link rel="stylesheet" type="text/css" href="../css/dataTables.bootstrap.css" />
    <link type="text/css" href="../css/materialdesignicons.css" rel="stylesheet" media="screen, projection" />
    <link type="text/css" href="../css/custom-style.css" rel="stylesheet" media="screen, projection" />
    <script type="text/javascript" src=""></script>
    <style type="text/css" media="screen">
        .nav-top li {
            display: inline-block;
            list-style-type: none;
        }

        .card4 {
            margin: 10px 0;
        }

        .btn {
            cursor: pointer;
        }

        li {
            cursor: pointer;
        }

            li:hover {
                color: green;
            }

        h5 {
            font-size: 13px;
        }
    </style>

</head>
<body>
    <!-- PRELOADER CAN BE PLACED HERE -->
    <div class="animated fadeIn" id="navigation">
        <div class="bg-success">
            <!--  MENU CONTENTS TO GO HERE -->
            <div class="">
                <ul class="nav-top">
                    <li>
                        <span class="btn btn-success" id="dashboard" title="All feeds">
                            <sp class="mdi mdi-home mdi-18px"></sp>
                        </span>
                    </li>
                    <li>
                        <span class="btn btn-success" id="fullcsreen" title="Enable fullscreen">
                            <sp class="mdi mdi-fullscreen mdi-18px"></sp>
                        </span>
                    </li>
                    <li>
                        <span class="btn btn-success" id="messages-chat" title="Chat section">
                            <sp class="mdi mdi-transit-transfer mdi-18px"></sp>
                        </span>
                    </li>
                    <li>
                        <span class="btn btn-success" id="notifications" title="See notifications">
                            <sp class="mdi mdi-bell-ring mdi-18px"></sp>
                            <sp class="badge badge-warning">3</sp>
                        </span>
                    </li>
                    <li>
                        <span class="btn btn-success" id="gmail-emails" title="Manage your gmail">
                            <sp class="mdi mdi-email mdi-18px"></sp>
                            <sp class="badge badge-danger">gmail</sp>
                        </span>
                    </li>
                    <li>
                        <span class="btn btn-success" id="add-friends" title="Connect to other users">
                            <sp class="mdi mdi-lan-connect mdi-18px"></sp>
                            <sp class="badge badge-primary">c</sp>
                        </span>
                    </li>
                    <ul class="float-right hidden-sm-down">
                        <li>
                            <span class="btn btn-success">
                                <sp class="mdi mdi-help-circle mdi-18px"></sp>
                            </span>
                        </li>
                        <li>
                            <a href="../includes/terminate-session.php" class="btn btn-sm btn-danger">
                                <sp class="mdi mdi-logout mdi-18px"></sp>
                            </a>
                        </li>
                    </ul>

                </ul>
            </div>
        </div>
    </div>
    <?php  while($currUserQueryRow = $currUserQuery->fetch()){
    ?>

    <span id="curr_user_id" style="display:none">
        <?php  echo $currUserQueryRow['id']  ?>
    </span>
   
    <?php
           }
    ?>
    <!-- MAIN SECTION -->
    <div class="col-lg-12 col-md-12" id="main-wrapper">

        <!-- ALL CONTENT TO BE LOADED HERE BY AJAX -->
        <div>
            <div class="text-center" style="padding-top: 100px">
                <img src="../images/preloader/25.gif" alt="" />
            </div>
        </div>

    </div>


    <div id="preloader_" style="display: none;">
        <div class="text-center" style="padding-top: 100px">
            <img src="../images/preloader/25.gif" alt="" />
        </div>
    </div>

    <!--     SCRIPTS     -->
    <script src="../js/jquery-3.1.1.js"></script>
    <script src="../js/bootstrap.js"></script>
    <script src="../js/main.js"></script>
    <script>
        $( function () {
            
            var $cont = $( '#preloader_' ).html();
            $( '.nav-top li' ).click( function ( event ) {
                /* Act on the event */
                $( '.nav-top li span' ).removeClass( 'btn-info' );
                $( '.nav-top li span' ).addClass( 'btn-success' );
                var $curr_li = $( event.target ).closest( 'li span' );
                $curr_li.removeClass( 'btn-success' );
                $curr_li.addClass( 'btn-info' );
            } );

            $.get( '../includes/dashboard-inc.php', function ( data ) {
                /*optional stuff to do after success */
                //$('#main-wrapper').html($cont);
                $( '#main-wrapper' ).html( data );
            } );

            $( '#dashboard' ).click( function ( event ) {
                /* Act on the event */
                $( '#main-wrapper' ).html( $cont );
                location.reload();
            } );

            function goToFile( $url ) {
                $( '#main-wrapper' ).html( $cont );
                $.get( $url, function ( data ) {
                    /*optional stuff to do after success */
                    $( '#main-wrapper' ).html( data );
                } );
            }

            $( '#messages-chat' ).click( function ( event ) {
                /* Act on the event */
                goToFile( 'chat-section.php' );
            } );

            $( '#add-friends' ).click( function ( event ) {
                /* Act on the event */
                goToFile( 'add-friends.php' );
            } );

            $( '#notifications' ).click( function ( event ) {
                /* Act on the event */
                goToFile( 'notifications.php' );
            } );

            $( '#gmail-emails' ).click( function ( event ) {
                /* Act on the event */
                goToFile( 'gmail-emails.php' );
            } );

            // call listener
            ( function callListener() {
                var $userid = $( '#curr_user_id' ).text().trim();
                //192.168.43.164  localhost
                var $currentTime = ( new Date(( new Date(( new Date( new Date() ) ).toISOString() ) ).getTime() - ( ( new Date() ).getTimezoneOffset() * 60000 ) ) ).toISOString().slice( 0, 19 ).replace( 'T', ' ' )
                
                console.log( $currentTime )
                var $url = 'http://localhost:7880/checkCallStatus?user_id=' + $userid + '&call_start_time=' + $currentTime;
                // url to file that checks for a record in database for the current user
                // the file returns true of false
                /*
                IF FALSE, THE SIGNALING SERVER CONTINUES EXECUTION AFTER EVERY THREE SECONDS
                IF TRUE, THE SERVER RETURNS JSON DATA WITH PARAMS ( current_user_key , end_user_key )
                THESE TWO KEYS WILL BE USED TO CREATE A URL, WHICH OPENS ON A NEW WINDOW TO ACCEPT THE CALL CONNECTION
                */
                $.get( $url, function ( data ) {
                    /*optional stuff to do after success */
                    console.log( data )
                    if ( data === 'continue' ) {
                        setTimeout( callListener, 3000 );
                    } else {
                        // request these two keys to initialize socket
                        $.get( '../includes/request_call_keys.php?user_id=' + $userid, function ( data ) {

                            // receive json data
                            var receivedData = data.toString()
                            receivedData = JSON.parse( receivedData )
                            var initiator_key = receivedData.initiator_key
                            var receiver_key = receivedData.receiver_key
                            console.log( 'From ' + initiator_key + ' to me and my key ' + receiver_key )
                            var $url = 'http://localhost:7880/initiate?initiator_crypto=' + receiver_key + '&receiver_crypto=' + initiator_key;
                            window.open( $url, "_blank", "location=0,toolbar=no,scrollbars=no,resizable=yes,status=no,titlebar=0,top=35,left=150,width=900,height=640" );

                        } )
                        console.log( "An active peer is requesting a call." )
                    }
                } );

            } )();
        } );
    </script>
</body>
</html>
<?php

}
?>