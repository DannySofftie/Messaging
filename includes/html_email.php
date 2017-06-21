<?php
session_start();
$userid = $_SESSION['userid'];
$message_content = $_GET['message_content'];
require_once 'dbconfig.php';
$bsQuery = $conn->query("select * from user_bs_info where owner_id = '$userid'");
$bsQuery->execute();
$bsQueryRow = $bsQuery->fetch();

?>

<div style="background-color:#f6f6f6;font-family:sans-serif;-webkit-font-smoothing:antialiased;font-size:14px;line-height:1.4;margin-top:0;margin-bottom:0;margin-right:0;margin-left:0;padding-top:0;padding-bottom:0;padding-right:0;padding-left:0;-ms-text-size-adjust:100%;-webkit-text-size-adjust:100%;">
    <table border="0" cellpadding="0" cellspacing="0" class="body" style="border-collapse:separate;mso-table-lspace:0pt;mso-table-rspace:0pt;background-color:#f6f6f6;width:100%;">
        <tr>
            <td style="font-family:sans-serif;font-size:14px;vertical-align:top;">&nbsp;</td>
            <td class="container" style="font-family:sans-serif;font-size:14px;vertical-align:top;display:block;Margin:0 auto !important;max-width:580px;padding-top:10px;padding-bottom:10px;padding-right:10px;padding-left:10px;width:580px;">
                <div class="content" style="box-sizing:border-box;display:block;Margin:0 auto;max-width:580px;padding-top:10px;padding-bottom:10px;padding-right:10px;padding-left:10px;">

                    <!-- START CENTERED WHITE CONTAINER -->
                    <span class="preheader" style="color:transparent;display:none;height:0;max-height:0;max-width:0;opacity:0;overflow:hidden;mso-hide:all;visibility:hidden;width:0;">This is preheader text. Some clients will show this text as a preview.</span>
                    <table class="main" style="border-collapse:separate;mso-table-lspace:0pt;mso-table-rspace:0pt;background-color:#fff;background-image:none;background-repeat:repeat;background-position:top left;background-attachment:scroll;border-radius:3px;width:100%;">

                        <!-- START MAIN CONTENT AREA -->
                        <tr>
                            <td class="wrapper" style="font-family:sans-serif;font-size:14px;vertical-align:top;box-sizing:border-box;padding-top:20px;padding-bottom:20px;padding-right:20px;padding-left:20px;">
                                <table border="0" cellpadding="0" cellspacing="0" style="border-collapse:separate;mso-table-lspace:0pt;mso-table-rspace:0pt;width:100%;">
                                    <tr>
                                        <td style="font-family:sans-serif;font-size:14px;vertical-align:top;">
                                            <p style="font-family:sans-serif;font-size:14px;font-weight:normal;margin-top:0;margin-bottom:0;margin-right:0;margin-left:0;Margin-bottom:15px;">Hi there check out this weeks products, <p>
                                            <p style="font-family:sans-serif;font-size:14px;font-weight:normal;margin-top:0;margin-bottom:0;margin-right:0;margin-left:0;Margin-bottom:15px;"><?php  echo $message_content ?></p>
                                            <table border="0" cellpadding="0" cellspacing="0" class="btn btn-primary" style="border-collapse:separate;mso-table-lspace:0pt;mso-table-rspace:0pt;box-sizing:border-box;width:100%;">
                                                <tbody>
                                                    <tr>
                                                        <td align="left" style="font-family:sans-serif;font-size:14px;vertical-align:top;padding-bottom:15px;">
                                                            <table border="0" cellpadding="0" cellspacing="0" style="border-collapse:separate;mso-table-lspace:0pt;mso-table-rspace:0pt;width:auto;">
                                                                <tbody>
                                                                    <tr>
                                                                        <td style="font-family:sans-serif;font-size:14px;vertical-align:top;border-radius:5px;text-align:center;background-color:#3498db;">
                                                                            <a href="http://localhost/desktop%20messaging/content/feeds" target="_blank" style="border-width:1px;border-style:solid;border-radius:5px;box-sizing:border-box;cursor:pointer;display:inline-block;font-size:14px;font-weight:bold;margin-top:0;margin-bottom:0;margin-right:0;margin-left:0;padding-top:12px;padding-bottom:12px;padding-right:25px;padding-left:25px;text-decoration:none;text-transform:capitalize;background-color:#3498db;border-color:#3498db;color:#ffffff;"><?php  echo $bsQueryRow['bs_name']  ?></a>
                                                                        </td>
                                                                    </tr>
                                                                </tbody>
                                                            </table>
                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                            <p style="font-family:sans-serif;font-size:14px;font-weight:normal;margin-top:0;margin-bottom:0;margin-right:0;margin-left:0;Margin-bottom:15px;"><strong> <?php  echo $bsQueryRow['bs_name']  ?> </strong> </p>
                                            <p style="font-family:sans-serif;font-size:14px;font-weight:normal;margin-top:0;margin-bottom:0;margin-right:0;margin-left:0;Margin-bottom:15px;"><?php  echo "We are loacted in ".$bsQueryRow['bs_address']  ?></p>
                                        </td>
                                    </tr>
                                </table>
                            </td>
                        </tr>

                        <!-- END MAIN CONTENT AREA -->
                    </table>

                    <!-- START FOOTER -->
                    <div class="footer" style="clear:both;padding-top:10px;text-align:center;width:100%;">
                        <table border="0" cellpadding="0" cellspacing="0" style="border-collapse:separate;mso-table-lspace:0pt;mso-table-rspace:0pt;width:100%;">
                            <tr>
                                <td class="content-block" style="font-family:sans-serif;vertical-align:top;color:#999999;font-size:12px;text-align:center;">
                                    <span class="apple-link" style="color:#999999;font-size:12px;text-align:center;"><?php  echo $bsQueryRow['bs_name'] . " , ". $bsQueryRow['bs_address'] . " , ".$bsQueryRow['bs_contact_mail'] . " , ".$bsQueryRow['bs_contact_phone'] ?></span>
                                    <br />You can unsubscribe any time
                                    <a href="http://localhost/desktop%20messaging/content/feeds" style="text-decoration:underline;color:#999999;font-size:12px;text-align:center;">Unsubscribe</a>.
                                </td>
                            </tr>
                            <tr>
                                <td class="content-block powered-by" style="font-family:sans-serif;vertical-align:top;color:#999999;font-size:12px;text-align:center;">
                                    Powered by
                                    <a href="http://localhost/desktop%20messaging/content/feeds" style="color:#999999;font-size:12px;text-align:center;text-decoration:none;"><?php   echo $bsQueryRow['bs_name']  ?></a>.
                                </td>
                            </tr>
                        </table>
                    </div>
                    <!-- END FOOTER -->

                    <!-- END CENTERED WHITE CONTAINER -->
                </div>
            </td>
            <td style="font-family:sans-serif;font-size:14px;vertical-align:top;">&nbsp;</td>
        </tr>
    </table>
</div>