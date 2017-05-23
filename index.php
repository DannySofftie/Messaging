<?php
session_start();
require_once 'includes/dbconfig.php';
if (isset($_SESSION['usermail'])) {
    // redirect to landing page
    header("Location: content/feeds.php");
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1.0, user-scalable=no" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="msapplication-tap-highlight" content="no" />
    <title>Messaging client</title>

    <link type="text/css" href="css/bootstrap.css" rel="stylesheet" media="screen, projection" />
    <link type="text/css" href="css/materialdesignicons.css" rel="stylesheet" media="screen, projection" />
    <link rel="stylesheet" type="text/css" href="css/animate.css" />
    <style>
        html, body {
            overflow-x: hidden;
            width: 100%;
            height: 100%;
        }
        /*scroll-bar webkit*/
        ::-webkit-scrollbar {
            width: 3px;
            background: transparent;
        }

        ::-webkit-scrollbar-thumb {
            background: lightblue;
        }

        .forms_holder {
            box-shadow: 1px 1px 4px 1px rgba(0,11,0,0.2);
            z-index: 9999;
            position: absolute;
            top: 12%;
            padding: 0;
        }

        .index_content {
            z-index: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0,0,0,0.6);
            position: absolute;
            top: 0;
        }

        .background_info {
            width: 100%;
            height: 100%;
            z-index: -1;
            color: orange;
        }

        .upper_left {
            position: absolute;
            top: 10px;
            left: 10px;
        }

        .upper_center {
            position: absolute;
            top: 10px;
            left: 42%;
        }

        .upper_right {
            position: absolute;
            top: 10px;
            right: 10px;
        }

        .bottom_left {
            position: absolute;
            bottom: 10px;
            left: 10px;
        }

        .bottom_right {
            position: absolute;
            bottom: 10px;
            right: 10px;
        }

        @keyframes background_texts {
            0% {
                transform: rotate(0deg);
                left: 0px;
            }

            25% {
                transform: rotate(20deg);
                left: 0px;
            }

            50% {
                transform: rotate(0deg);
                left: 500px;
            }

            55% {
                transform: rotate(0deg);
                left: 500px;
            }

            70% {
                transform: rotate(0deg);
                left: 500px;
                ;
            }

            100% {
                transform: rotate(-360deg);
                left: 0px;
            }
        }

        @-webkit-keyframes background_texts {
            0% {
                -webkit-transform: rotate(0deg);
                left: 0px;
            }

            25% {
                -webkit-transform: rotate(20deg);
                left: 0px;
            }

            50% {
                -webkit-transform: rotate(0deg);
                left: 500px;
            }

            55% {
                -webkit-transform: rotate(0deg);
                left: 500px;
            }

            70% {
                -webkit-transform: rotate(0deg);
                left: 500px;
            }

            100% {
                -webkit-transform: rotate(-360deg);
                left: 0px;
            }
        }

        @-moz-keyframes background_texts {
            0% {
                -moz-transform: rotate(0deg);
                left: 0px;
            }

            25% {
                -moz-transform: rotate(20deg);
                left: 0px;
            }

            50% {
                -moz-transform: rotate(0deg);
                left: 500px;
            }

            55% {
                -moz-transform: rotate(0deg);
                left: 500px;
            }

            70% {
                -moz-transform: rotate(0deg);
                left: 500px;
            }

            100% {
                -moz-transform: rotate(-360deg);
                left: 0px;
            }
        }

        @-o-keyframes background_texts {
            0% {
                transform: rotate(0deg);
                left: 0px;
            }

            25% {
                transform: rotate(20deg);
                left: 0px;
            }

            50% {
                transform: rotate(0deg);
                left: 500px;
            }

            55% {
                transform: rotate(0deg);
                left: 500px;
            }

            70% {
                transform: rotate(0deg);
                left: 500px;
            }

            100% {
                transform: rotate(-360deg);
                left: 0px;
            }
        }

        .background_texts {
            font-size: 30px;
            font-family: 'Satisfy', cursive;
            animation-name: background_textss;
            animation-duration: 4s;
            animation-iteration-count: infinite;
            animation-direction: alternate;
        }

        .validation {
            font-size: 11px;
            color: orange;
            padding: 0;
            margin: 0;
            display: none;
        }
    </style>
</head>
<body>
    <div class="background_info">
        <div style="transform: rotate(330deg); padding: 60px" class="background_texts">
            <span>Reveal the power of computers in doing work for you</span>
        </div>
        <div class="upper_left">
            <span class="mdi mdi-email-secure mdi-48px mdi-rotate-315 text-danger"></span>
            <span style="padding: 100px; font-family: 'Gabriola';">Send emails from within the app</span>
        </div>
        <div class="upper_center">
            <span class="mdi mdi-access-point-network mdi-48px  mdi-rotate-45 text-primary"></span>
        </div>
        <div class="background_texts text-center" style="transform: rotate(20deg);">
            <span>Chat with your connected business partners, clients and friends from within the app.</span>
        </div>
        <div class="upper_right">
            <span style=" font-family: 'Gabriola'; padding: 100px 100px 0 0">Calls your business partners from within the app</span>
            <span class="mdi mdi-phone-forward mdi-48px text-warning"></span>
            <br />
        </div>
        <div class="bottom_left">
            <span class="mdi mdi-message-text mdi-48px mdi-rotate-45 text-primary"></span>
            <span style="padding: 100px 0 0 100px; font-family: 'Gabriola';">Use our comprehensive bulk messaging system</span>
        </div>
        <div class="bottom_right">
            <span style="padding: 100px 100px 0 0; font-family: 'Gabriola';">Video call your clients</span>
            <span class="mdi mdi-video mdi-48px mdi-rotate-315 text-info"></span>
        </div>
    </div>

    <div class="index_content">
        <div class="col-lg-5 col-md-6 offset-lg-3 offset-md-2 animated fadeIn forms_holder">
            <div class="card sign_up_here login_form">
                <div class="card-header bg-info">
                    <h6>Sign up on ReachClients.me to reach your next milestone in your business.</h6>
                </div>
                <div class="card-block">
                    <div class="text-center">
                        <button class="btn btn-sm btn-outline-info">
                            Login with facebook
                            <span class="mdi mdi-facebook"></span>
                        </button>
                        <button class="btn btn-sm btn-outline-danger">
                            Google login
                            <span class="mdi mdi-google"></span>
                        </button>
                    </div>
                    <form action="includes/sign-in-inc.php" class="loginForm" method="post">
                        <div class="form-group">
                            <label>Email address</label>
                            <div class="input-group">
                                <span class="input-group-addon">
                                    <span class="mdi mdi-email"></span>
                                </span>
                                <input type="text" autofocus="autofocus" name="email" class="form-control" placeholder="someone@example.com" />
                            </div>
                        </div>
                        <div class="form-group">
                            <label>Password</label>
                            <div class="input-group">
                                <span class="input-group-addon">
                                    <span class="mdi mdi-lock"></span>
                                </span>
                                <input type="password" name="password" class="form-control" placeholder="*********" required="required" />
                            </div>
                        </div>
                        <div class="form-group text-right">
                            <button type="submit" class="btn btn-sm btn-info">
                                Sign in
                                <span class="mdi mdi-login"></span>
                            </button>
                        </div>
                    </form>
                </div>
                <div class="card-footer text-center">
                    <button class="btn btn-sm btn-outline-info register">
                        Not registered?
                        <span class="mdi mdi-human-greeting"></span>
                    </button>
                    <button class="btn btn-sm btn-outline-warning" id="recover_pwd">
                        Recover password
                        <span class="mdi mdi-lock-reset"></span>
                    </button>
                </div>
            </div>
            <div class="card register_here animated" style="display: none;">
                <div class="card-header bg-info register_status">
                    <h6>Register here to access this powerful platform</h6>
                </div>
                <div class="card-block" style="padding: 10px 80px 0 80px">
                    <div class="text-center" style="margin-bottom: 6px">
                        <button class="btn btn-sm btn-outline-info">
                            Sign with facebook
                            <span class="mdi mdi-facebook"></span>
                        </button>
                        <button class="btn btn-sm btn-outline-danger">
                            Sign with Google
                            <span class="mdi mdi-google"></span>
                        </button>
                    </div>
                    <form class="register_form" method="POST">
                        <div class="form-group">
                            <div class="input-group">
                                <span class="input-group-addon">
                                    <span class="mdi mdi-account"></span>
                                </span>
                                <input type="text" id="username" name="username" class="form-control" value="" placeholder="username eg: sofftie" />
                            </div>
                            <i class="validation" id="usernameValidate">username must be 5 characters long</i>
                        </div>
                        <div class="form-group">
                            <div class="input-group">
                                <span class="input-group-addon">
                                    <span class="mdi mdi-email"></span>
                                </span>
                                <input type="email" name="email" id="email" class="form-control" value="" placeholder="email eg: someone@example.com" />
                            </div>
                            <i class="validation" id="emailValidate">email must be in the form eg: someone@gmail.com</i>
                        </div>
                        <div class="form-group">
                            <div class="input-group">
                                <span class="input-group-addon">
                                    <span class="mdi mdi-cellphone-basic"></span>
                                </span>
                                <input type="tel" name="phone" id="phone" class="form-control" value="" placeholder="optional phone number eg: 0712345678" />
                            </div>
                            <i id="nonNumber" style="color: red; font-size: 11px; display: none;">phone number must be numbers only</i>
                            <i class="validation" id="phoneValidate">phone number must be 10 characters or more</i>
                        </div>
                        <div class="form-group">
                            <div class="input-group">
                                <span class="input-group-addon">
                                    <span class="mdi mdi-lock-open-outline"></span>
                                </span>
                                <input type="password" id="password" name="password" class="form-control" value="" placeholder="password" />
                            </div>
                            <i class="validation" id="passwordValidate">password must be 5 charcaters or more</i>
                        </div>
                        <div class="form-group">
                            <div class="input-group">
                                <span class="input-group-addon">
                                    <span class="mdi mdi-lock-outline"></span>
                                </span>
                                <input type="password" id="confirmPwd" class="form-control" value="" placeholder="confirm password" />
                            </div>
                            <i class="validation" id="passwordVerify">password mismatch error</i>
                        </div>
                        <div class="form-group text-right">
                            <span class="loading" style="display: none;">
                                <img src="images/preloader/25.gif" alt="" style="width: 28px; height: 28px" />
                            </span>
                            <button type="submit" id="registerClient" id="submitForm" class="btn btn-sm btn-info">
                                Confirm sign up
                                <span class="mdi mdi-check-circle"></span>
                            </button>
                        </div>
                    </form>
                </div>
                <div class="card-footer"></div>
            </div>

            <!--  PASSWORD RECOVERY  -->
            <div class="card recover_password" style="display: none">
                <div class="card-header bg-info">
                    <h6>Password recovery.</h6>
                </div>
                <div class="card-block">
                    <div class="text-center">
                        Enter the email address you used to sign up. We will send you a new password to recover your account.
                    </div>
                    <form action="includes/sign-in-inc.php" class="recover_passwordForm" method="post">
                        <div class="form-group">
                            <div class="input-group">
                                <span class="input-group-addon">
                                    <span class="mdi mdi-email"></span>
                                </span>
                                <input type="text" autocomplete="off" autofocus="autofocus" id="recoverEmail" name="email" class="form-control" placeholder="someone@example.com" />
                            </div>
                        </div>

                        <div class="form-group text-right">
                            <span id="recoverStatus"></span>
                            <button type="submit" name="confirmEmail" class="btn btn-sm btn-info">
                                Send password
                                <span class="mdi mdi-login"></span>
                            </button>
                        </div>
                    </form>
                </div>
                <div class="card-footer text-center">
                    <button class="btn btn-sm btn-outline-info register">
                        Not registered?
                        <span class="mdi mdi-human-greeting"></span>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!--     SCRIPTS     -->
    <script src="js/jquery-3.1.1.js"></script>
    <script src="js/materialize.js"></script>
    <script src="js/main.js"></script>
    <!--  END OF SCRIPTS  -->

    <script type="text/javascript">
        $(function () {

            $('.register').click(function (event) {
                /* Act on the event */
                $('.sign_up_here').slideUp(1000, function () {
                    $('.register_here').slideDown(1000, function () {
                        // register form
                    });
                });
            });

            $('#recover_pwd').click(function (event) {
                $('.sign_up_here').slideUp(1000, function () {
                    $('.recover_password').slideDown(1000, function () {

                    });
                });
            });

            $('.recover_passwordForm').submit(function (event) {
                event.preventDefault();
            });

            $('#recoverEmail').click(function () {
                $(this).keyup(function () {
                    var $inputEmail = $(this).val().trim();
                    $.post('includes/recover_password.php?', $('.recover_passwordForm').serialize(), function (data) {
                        $('#recoverStatus').html(data);
                    });
                });
            });

            $('input').addClass('text-info');

            function validateInputData() {
                var $input = $(event.target).closest('input');
                $('#submitForm').prop('disabled', true);
                $input.keyup(function (event) {
                    var $textLenth = $(this).val().trim();
                    if ($input.attr('id') === 'username') {
                        if (($textLenth.length) < 5) {

                            $('#usernameValidate').show();
                        } else {
                            $('#usernameValidate').hide();
                        }
                    } else if ($input.attr('id') === 'email') {
                        $input.attr('autocomplete', 'off');
                        var $email = $(this).val().trim();
                        var $emailRegex = /^\b[A-Z0-9._%-]+@[A-Z0-9.-]+\.[A-Z]{2,4}\b$/i;
                        if (!$emailRegex.test($email)) {
                            $('#emailValidate').show();
                        } else {
                            $('#emailValidate').hide();
                        }
                    } else if ($input.attr('id') === 'phone') {
                        var $phoneNumber = $(this).val().trim();
                        if (isNaN($phoneNumber)) {
                            $('#nonNumber').show();
                            $('#phoneValidate').hide();
                        } else {
                            $('#nonNumber').hide();
                            if (($phoneNumber.length) < 10) {
                                $('#phoneValidate').show();
                            } else {
                                $('#phoneValidate').hide();
                            }
                        }
                    } else if ($input.attr('id') === 'password') {
                        var $password = $('#password').val();

                        (($password.length) < 5) ? $('#passwordValidate').show() : $('#passwordValidate').hide();

                        $('#confirmPwd').click(function (event) {

                            $(this).keyup(function (event) {

                                var $confirmPwd = $('#confirmPwd').val();
                                if ($password != $confirmPwd) {
                                    $('#passwordVerify').show();
                                    $('button[type=submit]').prop('disabled', true);
                                } else {
                                    $('#passwordVerify').hide();
                                    $('button[type=submit]').prop('disabled', false);
                                }
                            });
                        });
                    }
                });
            }

            $('.input-group').click(function (event) {
                /* Act on the event */
                // CALL VALIDATE DATA FUNCTION
                validateInputData();
            });

            $('#registerClient').click(function (event) {
                /* Act on the event */
                $('.loading').show();
                $('.register_form').submit(function (event) {
                    /* Act on the event */
                    event.preventDefault();
                    $.ajax({
                        url: 'includes/register-client-inc.php?' + $('.register_form').serialize(),
                        method: 'POST',
                        async: false,
                        success: function (data) {
                            $('.loading').hide();
                            $('.register_status').html(data);

                            // then redirect after 5 seconds to login page
                            setTimeout(function () {
                                window.location = 'index.php';
                            }, 5000);
                        },
                        error: function () {
                            alert("Registration failed");
                        },
                        contentType: false,
                        processData: false
                    })
                });
            });

        })
    </script>
</body>
</html>
