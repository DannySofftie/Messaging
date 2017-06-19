
<style>
    .prof_cover {
        position: absolute;
        left: 35%;
        bottom: 14px;
        height: 87%;
        width: 200px;
        z-index: 99999;
    }

        .prof_cover img {
            border-radius: 50%;
            box-shadow: 1px 2px 20px 1px rgba(0,0,0,0.5);
        }

    .curr_user_holder {
        display: none;
    }

    table tr:nth-child(odd) {
        background-color: rgba(55, 121, 28, 0.27);
    }
</style>

<?php
session_start();
require_once 'dbconfig.php';
$loggedInUserId = $_SESSION['userid'];
$userid = $_POST['userid'];

// current user details
$userDeatails = $conn->query("select * from regusers where id = '$loggedInUserId'");
$userDeatails -> execute();
$userDeatailsRow = $userDeatails->fetch();
?>
<span id="curr_user_id" class="curr_user_holder">
    <?php  echo $userDeatailsRow['id']  ?>
</span>
<span id="curr_user_email" class="curr_user_holder">
    <?php  echo $userDeatailsRow['email']  ?>
</span>
<span id="curr_user_phone" class="curr_user_holder">
    <?php  echo $userDeatailsRow['phone_number']  ?>
</span>
<span id="curr_user_fname" class="curr_user_holder">
    <?php  echo $userDeatailsRow['id']  ?>
</span>
<span id="dest_user_id" class="curr_user_holder">
    <?php  echo $userid  ?>
</span>
<?php

if($loggedInUserId == $userid){

    $profQuery = $conn->query("select * from regusers join user_bs_info on regusers.id = user_bs_info.owner_id where regusers.id = '$userid'");
    $profQuery->execute();
    while($profQueryRow = $profQuery->fetch()){
        /*
         *
         * email, phone_number, prof_image, fname, nickname, about
         * bs_name, bs_logo, bs_description, bs_wrk_time, bs_address, bs_contact_mail, bs_contact_phone, bs_map_address         *
         *
         */
        $profData = new stdClass();
        $profData -> fullname = $profQueryRow['fname'].' '.$profQueryRow['nickname'];
        $profData -> phone = $profQueryRow['phone_number'];
        $profData -> email = $profQueryRow['email'];
        $profData -> image = $profQueryRow['prof_image'];
        $profData -> about = $profQueryRow['about'];
        $profData -> bsname = $profQueryRow['bs_name'];
        $profData -> bslog = $profQueryRow['bs_logo'];
        $profData -> bsdesc = $profQueryRow['bs_description'];
        $profData -> bstime = $profQueryRow['bs_wrk_time'];
        $profData -> bsaddress = $profQueryRow['bs_address'];
        $profData -> bsmail = $profQueryRow['bs_contact_mail'];
        $profData -> bsphone = $profQueryRow['bs_contact_phone'];
        $profData -> bsmap = $profQueryRow['bs_map_address'];

?>
<div class="card card1" style="height: 100%">
    <div class="card-block">
        <div class="profile_image" style="height: 200px; overflow-y: hidden; position:relative;">

            <div class="prof_cover">
                <img src="<?php echo $profData->image ?>" style="height: 100%; width: 100%;" />
            </div>
        </div>

        <div class="card-content row text-left" style="padding-top: 40px; font-size: 13px">
            <div class="col-lg-6 col-md-6">
                <h5>Personal details</h5>
                <div class="dropdown-divider"></div>
                <table style="">
                    <thead>
                        <tr>
                            <th></th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>
                                <strong>Name</strong>
                            </td>
                            <td>
                                <?php echo $profData->fullname  ?>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <strong>Phone number</strong>
                            </td>
                            <td>
                                <?php  echo $profData->phone  ?>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <strong>Email address</strong>
                            </td>
                            <td>
                                <?php  echo $profData->email  ?>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <strong>About</strong>
                            </td>
                            <td>
                                <?php  echo $profData->about  ?>
                            </td>
                        </tr>
                    </tbody>
                </table>
                <div class="dropdown-divider"></div>
                <div class="text-right">
                    <button class="btn btn-sm btn-primary">
                        Own profile view &nbsp;
                        <span class="mdi mdi-checkbox-marked-circle-outline"></span>
                    </button>
                </div>
            </div>
            <div class="col-lg-6 col-md-6">
                <h5>Business details</h5>
                <div class="dropdown-divider"></div>
                <table>
                    <thead>
                        <tr>
                            <th></th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>
                                <strong>Business name</strong>
                            </td>

                            <td>
                                <?php echo $profData->bsname  ?>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <strong>Description</strong>
                            </td>
                            <td>
                                <?php  echo $profData->bsdesc  ?>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <strong>Opening time</strong>
                            </td>
                            <td>
                                <?php  echo $profData->bstime  ?>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <strong>Business address</strong>
                            </td>
                            <td>
                                <?php  echo $profData->bsaddress  ?>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <strong>Business email</strong>
                            </td>
                            <td>
                                <?php  echo $profData->bsmail  ?>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <strong>Business phone</strong>
                            </td>
                            <td>
                                <?php  echo $profData->bsphone  ?>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<?php
    }
}else{

    $profQuery = $conn->query("select * from regusers join user_bs_info on regusers.id = user_bs_info.owner_id where regusers.id = '$userid'");
    $profQuery->execute();
    while($profQueryRow = $profQuery->fetch()){
        // put data in json and send back results
        $profData = new stdClass();
        $profData -> fullname = $profQueryRow['fname'].' '.$profQueryRow['nickname'];
        $profData -> phone = $profQueryRow['phone_number'];
        $profData -> email = $profQueryRow['email'];
        $profData -> image = $profQueryRow['prof_image'];
        $profData -> about = $profQueryRow['about'];
        $profData -> bsname = $profQueryRow['bs_name'];
        $profData -> bslog = $profQueryRow['bs_logo'];
        $profData -> bsdesc = $profQueryRow['bs_description'];
        $profData -> bstime = $profQueryRow['bs_wrk_time'];
        $profData -> bsaddress = $profQueryRow['bs_address'];
        $profData -> bsmail = $profQueryRow['bs_contact_mail'];
        $profData -> bsphone = $profQueryRow['bs_contact_phone'];
        $profData -> bsmap = $profQueryRow['bs_map_address'];

?>

<div class="card" style="height: 100%">
    <div class="card-block">
        <div class="profile_image" style="height: 200px; overflow-y: hidden; position:relative;">

            <div class="prof_cover">
                <img src="<?php echo $profData->image ?>" style="height: 100%; width: 100%;" />
            </div>
        </div>

        <div class="card-content row text-left" style="padding-top: 40px; font-size: 13px">
            <div class="col-lg-6 col-md-6">
                <h5>Personal details</h5>
                <div class="dropdown-divider"></div>
                <table>
                    <thead>
                        <tr>
                            <th></th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>
                                <strong>Name</strong>
                            </td>
                            <td>
                                <?php echo $profData->fullname  ?>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <strong>Phone number</strong>
                            </td>
                            <td>
                                <?php  echo $profData->phone  ?>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <strong>Email address</strong>
                            </td>
                            <td>
                                <?php  echo $profData->email  ?>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <strong>About</strong>
                            </td>
                            <td>
                                <?php  echo $profData->about  ?>
                            </td>
                        </tr>
                    </tbody>
                </table>
                <div class="dropdown-divider"></div>
                <div class="text-right">
                    <span>Receive future marketing emails from this business?</span>

                    <?php

        $subCheck = $conn->query("select * from contact_addresses where added_by = '$userid' and userid = '$loggedInUserId'");
        $subCheck->execute();
        if($subCheck->rowCount() == 0){
                    ?>
                    <button class="btn btn-sm btn-primary" id="subscribe">
                        Allow &nbsp;
                        <span class="mdi mdi-checkbox-marked-circle-outline"></span>
                    </button>

                    <?php

        }else{
                    ?>

                    <button class="btn btn-sm btn-danger" id="unsubscribe">
                        Unsubscribe &nbsp;
                        <span class="mdi mdi-cancel"></span>
                    </button>
                    <?php
        }
                    ?>

                </div>
            </div>
            <div class="col-lg-6 col-md-6">
                <h5>Business details</h5>
                <div class="dropdown-divider"></div>
                <table>
                    <thead>
                        <tr>
                            <th></th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>
                                <strong>Business name</strong>
                            </td>
                            <td>
                                <?php echo $profData->bsname  ?>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <strong>Description</strong>
                            </td>
                            <td>
                                <?php  echo $profData->bsdesc  ?>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <strong>Opening time</strong>
                            </td>
                            <td>
                                <?php  echo $profData->bstime  ?>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <strong>Business address</strong>
                            </td>
                            <td>
                                <?php  echo $profData->bsaddress  ?>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <strong>Business email</strong>
                            </td>
                            <td>
                                <?php  echo $profData->bsmail  ?>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <strong>Business phone</strong>
                            </td>
                            <td>
                                <?php  echo $profData->bsphone  ?>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<?php
    }
}
?>

<script>
    $( '#subscribe' ).click( function () {
        // subscribe user to business future emails
        var $userid = $( '#curr_user_id' ).text().trim();
        var $useremail = $( '#curr_user_email' ).text().trim();
        var $userphone = $( '#curr_user_phone' ).text().trim();
        var $username = $( '#curr_user_name' ).text().trim();
        var $destid = $( '#dest_user_id' ).text().trim();
        $( '#subscribe span' ).addClass( 'mdi-spin' );
        $.post( '../includes/subscribe-mail.php?',
            {
                subscribe: true,
                userid: $userid,
                usermail: $useremail,
                userphone: $userphone,
                username: $username,
                destid: $destid
            },
        function ( data ) {
            if ( data == 'Subscribed' ) {
                $( '#subscribe' ).removeClass( 'btn-info' );
                $( '#subscribe' ).addClass( 'btn-success' );
                $( '#subscribe' ).text( 'Subscribed' );
            } else {
                $( '#subscribe' ).removeClass( 'btn-info' );
                $( '#subscribe' ).addClass( 'btn-danger' );
                $( '#subscribe' ).text( 'Failed' );
            }

        } );
    } );

    $( '#unsubscribe' ).click( function () {
        // subscribe user to business future emails
        var $userid = $( '#curr_user_id' ).text().trim();
        var $useremail = $( '#curr_user_email' ).text().trim();
        var $userphone = $( '#curr_user_phone' ).text().trim();
        var $username = $( '#curr_user_name' ).text().trim();
        var $destid = $( '#dest_user_id' ).text().trim();
        $( '#unsubscribe span' ).addClass( 'mdi-spin' );
        $.post( '../includes/subscribe-mail.php?',
            {
                unsubscribe: true,
                userid: $userid,
                destid: $destid
            },
        function ( data ) {
            if ( data == 'Unsubscribed' ) {
                $( '#unsubscribe' ).text( 'Success' );
            } else {
                $( '#unsubscribe' ).text( 'Failed' );
            }

        } );
    } );
</script>