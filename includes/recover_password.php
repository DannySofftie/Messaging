<?php
require_once 'dbconfig.php';
$inputEmail = "";
if(isset($_POST['email'])){
    $inputEmail = filter_var( $_POST['email'] , FILTER_VALIDATE_EMAIL);
}
$checkQuery = $conn->prepare("select * from regusers where email = :inputEmail");
$checkQuery->bindParam(":inputEmail" , $inputEmail);
$checkQuery->execute();
if($checkQuery->rowCount() == 0){
?>
<span class="alert alert-danger">Seems you are not registered yet</span>
<?php
}else{
?>
<?php

    $newPass = openssl_random_pseudo_bytes( 3 , $cstrong);
    $newPass = bin2hex($newPass);
    $newPass = strtoupper($newPass);
    // send this code to email provided
    $options = [
        'cost' => 11,
        'salt' => mcrypt_create_iv(22, MCRYPT_DEV_URANDOM)
        ];
    $newPassHash = password_hash($newPass , PASSWORD_BCRYPT , $options);
    $updatePass = $conn->prepare("update regusers set password = :newPassHash, confirm_code=:newPass where email = :email");
    $updatePass->bindParam(":newPassHash" , $newPassHash);
    $updatePass->bindParam(":newPass" , $newPass);
    $updatePass->bindParam(":email" , $inputEmail);
    if($updatePass->execute()){
        if( mail($inputEmail , "Password recovery" , "Use this as your passowrd to login '$newPass'" , "From: ReachClients.me")){
            // give a message
?>
<style>
    #confirmText{
    display: none;
    }
</style>
<span class="alert alert-info">You will receive a code to your email. Use this code to login</span>
<?php
        }else{
?>
<span class="alert alert-danger">An error occured. Try again</span>
<?php
        }
    }
}
?>