<?php
header("Content-type: text/html; charset=utf-8");
define('_IN_JOHNCMS', 1);
$rootpath = '';
require('../incfiles/core.php');
require('Pusher.php');


$options = array(
    'cluster' => 'ap1',
    'useTLS' => true
);
$pusher = new Pusher(
    '5590c5f02cb8403e5f17',
    '9a3419d93e5a6b9b85d9',
    '1215602',
    $options
);



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


        // $last_msg = mysql_fetch_assoc(mysql_query("SELECT `guest`.*, `guest`.`id` AS `gid`, `users`.`lastdate`, `users`.`id`, `users`.`rights`, `users`.`name`, `users`.`vip`
        // FROM `guest` LEFT JOIN `users` ON `guest`.`user_id` = `users`.`id`
        // WHERE `guest`.`adm`='0' ORDER BY `time` DESC LIMIT 1"));

        $post = functions::checkout($msg, 1, 1);
        if ($set_user['smileys'])
        $post = functions::smileys($post, 1);
        

        $dataUser = mysql_fetch_assoc(mysql_query("SELECT * FROM `users` WHERE `id`='".$user_id."'"));

        $data['data'] = array(
            'status' => true,
            'from' => $user_id,
            'color' => functions::color_user($dataUser['rights']),
            'name' => $dataUser['name'],
            'msg_id' => $_POST['msg_id'],
            'msg' => $post,
            'time' => functions::display_date(time())
        );
        $pusher->trigger('comcho', 'chatbox', $data);

    }else{
            // nothing in herre
            $data['data'] = array(
                'status' => false,
                'msg_id' => $_POST['msg_id'],
                'msg' => $error[0]
            );
            $pusher->trigger('comcho', 'chatbox', $data);
    
    }
}

?>
