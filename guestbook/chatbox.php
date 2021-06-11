<?php
define('_IN_JOHNCMS', 1);
$rootpath = '';
require('../incfiles/core.php');
if (isset($_POST['msg'])) {
   $msg = isset($_POST['msg']) ? functions::checkin(mb_substr(trim($_POST['msg']), 0, 5000)) : '';
        $from = $user_id ? $login : '';
   $error = array();
        $flood = FALSE;
        if (!isset($_POST['token']) || !isset($_SESSION['token']) || $_POST['token'] != $_SESSION['token']) {
            $error[] = $lng['error_wrong_data'];
        }
        if (!$user_id)
            $error[] = $lng['error_empty_name'];
        if (empty($msg))
            $error[] = $lng['error_empty_message'];
        if ($ban['1'] || $ban['13'])
            $error[] = $lng['access_forbidden'];
   $flood = functions::antiflood();
   if ($ban['1'] || $ban['13'])
       $error[] = $lng['access_forbidden'];

   if ($flood)
       $error = $lng['error_flood'] . ' ' . $flood . '&#160;' . $lng['seconds'];
   if (!$error) {
       $req = mysql_query("SELECT * FROM `guest` WHERE `user_id` = '$user_id' ORDER BY `time` DESC");
       $res = mysql_fetch_array($req);
       if ($res['text'] == $msg) {
           $error[] = 'error';
       }
   }


   if (!$error) {
       mysql_query("INSERT INTO `guest` SET
            `adm` = '$admset',
            `time` = '" . time() . "',
            `user_id` = '$user_id',
            `name` = '$from',
            `text` = '" . mysql_real_escape_string($msg) . "',
            `ip` = '" . core::$ip . "',
            `browser` = '" . mysql_real_escape_string($agn) . "'
       ");

   }else{
        echo "<script>
            var no = '<div id=check><img src=/images/del.png></div>';
            var t = setTimeout(function(){
                $(\"#CheckSend\").html(no);
                setTimeout('$(\"#check\").remove()', 1500);
            }, 700);
            function stopCount() {
                            $(\"#check\").remove();
            }
        </script>";
    }
}



$totalchat = mysql_result(mysql_query("SELECT COUNT(*) FROM `guest` WHERE `adm`='0'"), 0);



  if ($totalchat) {
    
       $req = mysql_query("SELECT `guest`.*, `guest`.`id` AS `gid`, `users`.`lastdate`, `users`.`id`, `users`.`rights`, `users`.`name`, `users`.`vip`
                    FROM `guest` LEFT JOIN `users` ON `guest`.`user_id` = `users`.`id`
                    WHERE `guest`.`adm`='0' ORDER BY `time` DESC LIMIT 10");
 

        while ($gres = mysql_fetch_assoc($req)) {
        $post = functions::checkout($gres['text'], 1, 1);
        if ($set_user['smileys'])
        $post = functions::smileys($post, $gres['rights'] ? 1 : 0);
?>
         <div class="list1"><?php echo (time() > $gres['lastdate'] + 300 ? '<i class="fa fa-toggle-off" style="color: #868686;"></i> ' : '<i class="fa fa-toggle-on" style="color:#5bc55a"></i> '); ?>
<?php
        if ($gres['vip']) 
            echo '<b style="color:red">[Vip]</b>';
        echo '<a href="/users/profile.php?user=' . $gres['id'] . '"><b class="' .functions::color_user($gres['rights']) . '">' . $gres['name'] . '</b></a>: ';
?>

<?php echo $post, '<br/>'; ?>
<?php echo '<font class="float-right" style="font-size:10px;color:green"><b>'.functions::display_date($gres['time']).' <i class="fa fa-history"></i></b></font></div>'; ?>


<?php
          ++$i;
 
        }       
  }
?>
