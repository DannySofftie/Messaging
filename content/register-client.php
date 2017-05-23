<?php

?>
<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1.0, user-scalable=no">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="msapplication-tap-highlight" content="no">
    <title>Register user</title>

    <link type="text/css" href="../css/materialize.css" rel="stylesheet" media="screen, projection" />
    <link type="text/css" href="../css/materialdesignicons.css" rel="stylesheet" media="screen, projection" />
    <link type="text/css" href="../css/custom-style.css" rel="stylesheet" media="screen, projection" />
    <style>
        html, body {
            height: 100%;
        }

        body {
            padding: 60px 0;
        }
    </style>
</head>

<body class="">
    <!-- Start Page Loading -->
    <div id="loader-wrapper">
        <div id="loader"></div>
        <div class="loader-section section-left"></div>
        <div class="loader-section section-right"></div>
    </div>
    <!-- End Page Loading -->

    <!-- //////////////////////////////////////////////////////////////////////////// -->

    <div class="row">
        <div class="col l5 m6 offset-l3 offset-m2 card">
            <form method="post" action="../includes/register-client-inc.php">

                <div class="card-content">
                    <div class="card blue accent-1">
                        <div class="card-content">
                            After registering, a confirmation code will be sent to your email. Use this code to confirm login when prompted.

                        </div>
                    </div>
                    <div class="input-field">
                        <i class="mdi mdi-email-secure prefix"></i>
                        <input type="email" name="email" id="email" required="required" autofocus="autofocus"/>
                        <label for="email">Email</label>
                    </div>
                    <div class="input-field">
                        <i class="mdi mdi-lock-open prefix"></i>
                        <input type="password" name="password" id="password" required="required"/>
                        <label for="password">Password</label>
                    </div>
                    <div class="input-field">
                        <i class="mdi mdi-lock-open prefix"></i>
                        <input type="password" id="passwordVerify" required="required"/>
                        <label for="passwordVerify">Verify Password</label>
                    </div>
                </div>
                <div class="card-action">
                    <button class="btn" type="submit" id="sign-up">Sign up <span class="mdi mdi-login"></span></button>
                </div>
            </form>
        </div>
    </div>

    <!--     SCRIPTS     -->
    <script src="../js/jquery-3.1.1.js"></script>
    <script src="../js/materialize.js"></script>
    <script src="../js/main.js"></script>
</body>

</html>
