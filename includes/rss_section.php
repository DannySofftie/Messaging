
<style>
    .rss_card {
        box-shadow: 0px 0px 7px 0px rgba(0,0,0,0.2);
        margin-bottom: 3px;
    }
</style>

<?php

require_once 'dbconfig.php';

if(isset($_POST['post_rss'])){

    $userid  = $_POST['userid'];
    $rss_title = $_POST['rss_title'];
    $rss_content = $_POST['rss_content'];

    $rssQuery = $conn->prepare('insert into rss_feeds(userid,rss_title,rss_content) values(:userid,:rss_title,:rss_content)');
    $rssQuery -> bindParam(":userid" , $userid);
    $rssQuery ->bindParam(":rss_title" , $rss_title);
    $rssQuery->bindParam(":rss_content" , $rss_content);

    if($rssQuery->execute()){
        // successfull
        echo 1;
    }else{
        echo 0;
    }
}

if(isset($_POST['fetch_rss'])){
    $rssFetch = $conn->query("select regusers.fname, regusers.nickname, rss_feeds.* from regusers right join rss_feeds on regusers.id=rss_feeds.userid order by rss_feeds.rss_date asc");
    $rssFetch->execute();

    function filterTime($date_b){
        $date_a = new DateTime('now');
        $date_b = new DateTime($date_b);
        $interval = date_diff($date_a,$date_b);

        $correctTime = '';

        if ($interval->format('%m') < 1 && $interval->format('%d') < 1 && $interval->format('%h') < 1 && $interval->format('%i') < 1) {
            $correctTime = $interval->format('%s secs ago');
        }
        if ($interval->format('%m') < 1 && $interval->format('%d') < 1 && $interval->format('%h') < 1) {
            if ($interval->format('%i') < 1 && $interval->format('%s') >= 0) {
                $correctTime = $interval->format('%s secs ago');
            }
            elseif ($interval->format('%i') == 1) {
                $correctTime = $interval->format('%i min ago');
            }else{
                $correctTime = $interval->format('%i mins ago');
            }
        }
        if ($interval->format('%m') < 1 && $interval->format('%d') < 1 && $interval->format('%h') > 0) {
            $correctTime = $interval->format('%h hours %i mins ago');
        }
        if ($interval->format('%m') < 1 && $interval->format('%d') >= 1) {
            if ($interval->format('%d') == 1) {
                $correctTime = $interval->format('Yesterday at %h:%i hrs');
            }else{
                $correctTime = $interval->format('%d days ago');
            }
        }
        if ($interval->format('%m') >= 1) {
            $correctTime = $interval->format('%m months ago');
        }
        return $correctTime;
    }

    while($rssFetchRow = $rssFetch->fetch()){
        // display all rss posts
?>
<div class="card rss_card" style="font-family: 'Trebuchet MS'">
    <div class="card-block">
        <h6 class="card-title text-info" title="<?php echo $rssFetchRow['fname']."&nbsp;".$rssFetchRow['nickname'] ?>">
            <?php echo $rssFetchRow['fname']."&nbsp;".$rssFetchRow['nickname'] ?>
        </h6>
        <div class="card-subtitle mb-2 text-muted" style="font-size: 11px">
            <?php echo filterTime($rssFetchRow['rss_date']) ?><br>
            <?php  echo $rssFetchRow['rss_title'] ?>
        </div>
        <div class="card-text">
            <h6>
                <?php echo $rssFetchRow['rss_content'] ?>
            </h6>
        </div>
    </div>
</div>
<?php
    }
}
?>