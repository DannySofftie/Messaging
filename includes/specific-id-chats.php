<?php

require_once 'dbconfig.php';

$userid = $_GET['userid'];
$friend_id = $_GET['friend_id'];

try {

    // SELECT * from (select * from chat_messages where (friend_id = :friend_id or friend_id= :userid) and (my_id= :friend_id or my_id= :userid) as t order by chat_time desc limit 5 ) as t order by chat_time desc
  $chatMsgFetchQuery = $conn->prepare("SELECT * from (select * from chat_messages as t where (friend_id = :friend_id or friend_id= :userid) and (my_id= :friend_id or my_id= :userid) order by 
    chat_time desc limit 9)  as t order by chat_time asc");
  $chatMsgFetchQuery->bindParam(":userid",$userid);
  $chatMsgFetchQuery->bindParam(":friend_id",$friend_id);
  $chatMsgFetchQuery->execute();

  if ($chatMsgFetchQuery->rowCount() == 0) {
    //IF THERE ARE NO MESSAGES YET
    ?>
    <div class="text-center" style="padding: 30% 0%;">
      <span class="alert alert-info">Seems there are no messages yet. Be the first to say HI</span>
    </div>
    <?php
  }
  while($chatMsgFetchQueryRow = $chatMsgFetchQuery->fetch()){
    $tempTime = substr($chatMsgFetchQueryRow['chat_time'] , -8);
    $timeSent = substr($tempTime , 0 , 5);
    if( $chatMsgFetchQueryRow['my_id'] == $userid ){

      ?>
      <!-- FLOAT MESSAGE TO RIGHT -->
      <div class="message-text-box alertright row float-right bg-faded">
       <!-- <i ><img src="../images/profile.png" class="prof-image float-right"></i> -->
       <div class="float-left">
         <span><?php   echo $chatMsgFetchQueryRow['message'];  ?></span><br>
         <span class="text-warning" style="font-size: 8px">
           <?php
           echo $timeSent; 
           ?> 
         </span>
       </div>
     </div>
     <?php

   }
   else{

    ?>
    <!-- FLOAT MESSAGE TO LEFT -->
    <div class="message-text-box alertleft row float-left">
       <!-- <i ><img src="../images/profile.png" class="prof-image float-left"></i> -->
       <div class="float-left">
         <span><?php   echo $chatMsgFetchQueryRow['message'];  ?></span><br>
         <span class="text-warning" style="font-size: 8px">
           <?php
           echo $timeSent; 
           ?> 
         </span>
       </div>
   </div>

   <?php

 }
}
}catch (PDOException $e) {

  echo $e->getMessage();
} 

?>

</div>
