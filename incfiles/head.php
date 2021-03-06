<?php
defined('_IN_JOHNCMS') or die('Error: restricted access');

$headmod = isset($headmod) ? mysql_real_escape_string($headmod) : '';
$textl = isset($textl) ? $textl : $set['copyright'];
$keywords = isset($keywords) ? htmlspecialchars($keywords) : $set['meta_key'];
$description = isset($description) ? htmlspecialchars($description) : $set['meta_desc'];

echo'<!DOCTYPE html>' .
    "\n" . '<html lang="' . core::$lng_iso . '">' .
    "\n" . '<head>' .
    "\n" . '<meta charset="utf-8">' .
    "\n" . '<meta http-equiv="X-UA-Compatible" content="IE=edge">' .
    "\n" . '<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=2.0, user-scalable=yes">' .
    "\n" . '<meta name="HandheldFriendly" content="true">' .
    "\n" . '<meta name="MobileOptimized" content="width">' .
    "\n" . '<meta content="yes" name="apple-mobile-web-app-capable">' .
    "\n" . '<meta name="Generator" content="JohnCMS, http://johncms.com">' .
    "\n" . '<meta name="keywords" content="' . $keywords . '">'.
    "\n" . '<meta name="description" content="' . $description . '">'.
	"\n" . '<link rel="preconnect" href="https://fonts.gstatic.com">'.
    "\n" . '<link href="https://fonts.googleapis.com/css2?family=Josefin+Sans&display=swap" rel="stylesheet">'.
    "\n" . '<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.5.0/css/font-awesome.min.css">'.
    "\n".'<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">'.
    "\n" . '<link rel="stylesheet" href="' . $set['homeurl'] . '/theme/' . (!$user_id ? 'Star' : $set_user['skin']) . '/style.css?i='.rand(0, 99999999).'">' .
    "\n" . '<script type="text/javascript" src="'.$set['homeurl'].'/js/jquery.js"></script>' .
    "\n" . '<link href="https://www.cssscript.com/demo/alert-confirm-toast-cute/style.css" rel="stylesheet" type="text/css">' .
    "\n" . '<script src="https://www.cssscript.com/demo/alert-confirm-toast-cute/cute-alert.js"></script>' .
    "\n" . '<script type="text/javascript" src="http://ajax.microsoft.com/ajax/jquery.validate/1.7/jquery.validate.min.js"></script>'.
    "\n" . '<script src="https://js.pusher.com/7.0/pusher.min.js"></script>'.
    "\n" . '<link rel="shortcut icon" href="' . $set['homeurl'] . '/favicon.ico">' .
    "\n" . '<link rel="alternate" type="application/rss+xml" title="RSS | ' . $lng['site_news'] . '" href="' . $set['homeurl'] . '/rss/rss.php">' .
    "\n" . '<title>' . $textl . '</title>' .
		"\n". "<style>body { font-family: 'Josefin Sans', sans-serif; }</style>".
    "\n" . '</head><body><div class="maintxt">' . core::display_core_errors();

/*
-----------------------------------------------------------------
?????????????????? ????????????
-----------------------------------------------------------------
*/
$cms_ads = array();
if (!isset($_GET['err']) && $act != '404' && $headmod != 'admin') {
    $view = $user_id ? 2 : 1;
    $layout = ($headmod == 'mainpage' && !$act) ? 1 : 2;
    $req = mysql_query("SELECT * FROM `cms_ads` WHERE `to` = '0' AND (`layout` = '$layout' or `layout` = '0') AND (`view` = '$view' or `view` = '0') ORDER BY  `mesto` ASC");
    if (mysql_num_rows($req)) {
        while (($res = mysql_fetch_assoc($req)) !== FALSE) {
            $name = explode("|", $res['name']);
            $name = htmlentities($name[mt_rand(0, (count($name) - 1))], ENT_QUOTES, 'UTF-8');
            if (!empty($res['color'])) $name = '<span style="color:#' . $res['color'] . '">' . $name . '</span>';
            // ???????? ???????? ???????????? ???????????????????? ????????????, ???? ??????????????????
            $font = $res['bold'] ? 'font-weight: bold;' : FALSE;
            $font .= $res['italic'] ? ' font-style:italic;' : FALSE;
            $font .= $res['underline'] ? ' text-decoration:underline;' : FALSE;
            if ($font) $name = '<span style="' . $font . '">' . $name . '</span>';
            @$cms_ads[$res['type']] .= '<a href="' . ($res['show'] ? functions::checkout($res['link']) : $set['homeurl'] . '/go.php?id=' . $res['id']) . '">' . $name . '</a><br/>';
            if (($res['day'] != 0 && time() >= ($res['time'] + $res['day'] * 3600 * 24)) || ($res['count_link'] != 0 && $res['count'] >= $res['count_link']))
                mysql_query("UPDATE `cms_ads` SET `to` = '1'  WHERE `id` = '" . $res['id'] . "'");
        }
    }
}

/*
-----------------------------------------------------------------
?????????????????? ???????? ??????????
-----------------------------------------------------------------
*/
if (isset($cms_ads[0])) echo $cms_ads[0];

/*
-----------------------------------------------------------------
Logo di???n ????n - Tr???n V??n Ho??i - Star
-----------------------------------------------------------------
*/

echo '<center><a href="' . $set['homeurl'] . '" title="' . $set['copyright'] . '"><img src="' . $set['homeurl'] . '/theme/Star/images/logo.png" alt="' . $set['copyright'] . '" style="max-width:50%;margin: auto;"/></a></center>';

/*
-----------------------------------------------------------------
Header di???n ????n - Tr???n V??n Ho??i - Star
-----------------------------------------------------------------
*/

if ($user_id) {
    
    // Ki???m tra th??ng b??o
    $tb_total = mysql_result(mysql_query("SELECT COUNT(*) FROM `thongbao` WHERE `user_id` = '" . $user_id . "' AND `xem` = '0'"),0);
    
    // Header
    echo '<div class="header">' ,
        '<a href="' . $set['homeurl'] . '/users/profile.php?act=office" title="C?? nh??n"><i class="fa fa-user"></i> C?? Nh??n </a>' ,
        ' ??? <a href="' . $set['homeurl'] . '/stream" title="Th??ng b??o"><i class="fa fa-bullhorn"></i> Th??ng B??o ' . ($tb_total != 0 ? '<b style="color:red">[' . $tb_total . ']</b>' : '') . '</a>' ,
        ' ??? <a href="' . $set['homeurl'] . '/mail" title="H???p th??"><i class="fa fa-commenting-o"></i> Mail</a>' ,
        ' ??? <a href="' . $set['homeurl'] . '/store" title="C???a h??ng"><i class="fa fa-cart-plus"></i> C???a H??ng </a>' ,
        ' ??? <a href="' . $set['homeurl'] . '/exit.php" title="Tho??t"><i class="fa fa-power-off"></i> Tho??t</a>' ,
         '</div>';
         
    // Info User
    echo '<div class="list1">' ,
        '<img src="' . ((file_exists((ROOTPATH . 'files/users/avatar/' . $user_id . '.png'))) ? '' .$set['homeurl'] . '/files/users/avatar/' . $user_id . '.png" width="30" height="30" alt="50" class="avatar_head"/>' : '' . $set['homeurl'] . '/images/empty.png" width="30" height="30" alt="50" class="avatar_head"/>' ) . '' ,
        ($datauser['vip'] == 1 ? '<b style="color:red">[Vip]</b>' : ''),
        '<b class="' .functions::color_user($datauser['rights']) . '">' . $datauser['name'] . ' </b><span style="color:#"> - ' . functions::rank_user($datauser['rights']) . '</span> <br/>' ,
        '<i class="fa fa-refresh fa-spin" style="font-size:10px"></i> <span style="color:green;font-size: 11px;"> C?? Nh??n : <i class="fa fa-thumbs-o-up"></i> <b>' . $datauser['beliked'] . '</b>' ,
		' - <i class="fa fa-money"></i> <b>' . $datauser['vnd'] . '</b>' ,
		' - <i class="fa fa-bar-chart"></i> <b>' . $datauser['postforum'] . '</b></span><br/>' ,
		 '</div>';
		
	// M???t kh???u c???p 2
	if (empty($datauser['subpass']) && $headmod == 'mainpage') {
	    echo '<div class="phdr"><i class="fa fa-key" aria-hidden="true"></i> M???t kh???u c???p 2</div>';
	    echo '<div class="list1">B???n ch??a c???p nh???t m???t kh???u c???p 2, h??y c???p nh???t ngay cho m??nh n??o !! ^^!' ,
	        ' <i class="fa fa-hand-o-right" aria-hidden="true"></i> <a href="' . $set['homeurl'] . '/users/subpass.php" title="M???t kh???u c???p 2"><b style="color:red">C???p nh???t ngay</b></a>' ,
	         '</div>';
	}
}

/*
-----------------------------------------------------------------
Header Ch??a login - Tr???n V??n Ho??i - Star
-----------------------------------------------------------------
*/

if (!$user_id) {
    
    // Header
    echo '<div class="phdr"> Welcome! ' ,
        ' <a href="' . $set['homeurl'] . '/registration.php" title="????ng k??"><i class="fa fa-sign-in"></i> ????ng k?? </a>' ,
        ' ??? <a href="' . $set['homeurl'] . '/login.php" title="????ng nh???p"><i class="fa fa-sign-out"></i> ????ng Nh???p </a>' ,
         '</div>';
    echo '<div class="menu"><b>L??u ?? :</b> <font color="red">Di???n ????n ??ang ??? ch??? ????? Developer Test<br> N???u c?? l???i vui l??ng li??n h??? Admin ????? b??o l???i.</font></div>';

}

/*
-----------------------------------------------------------------
?????????????????? ???????? ??????????
-----------------------------------------------------------------
*/
if (!empty($cms_ads[1])) echo '<div class="gmenu">' . $cms_ads[1] . '</div>';

/*
-----------------------------------------------------------------
???????????????? ???????????????????????????? ??????????????????????
-----------------------------------------------------------------
*/
$sql = '';
$set_karma = unserialize($set['karma']);
if ($user_id) {
    // ?????????????????? ???????????????????????????? ????????????????????????????
    if (!$datauser['karma_off'] && $set_karma['on'] && $datauser['karma_time'] <= (time() - 86400)) {
        $sql .= " `karma_time` = '" . time() . "', ";
    }
    $movings = $datauser['movings'];
    if ($datauser['lastdate'] < (time() - 300)) {
        $movings = 0;
        $sql .= " `sestime` = '" . time() . "', ";
    }
    if ($datauser['place'] != $headmod) {
        ++$movings;
        $sql .= " `place` = '" . mysql_real_escape_string($headmod) . "', ";
    }
    if ($datauser['browser'] != $agn)
        $sql .= " `browser` = '" . mysql_real_escape_string($agn) . "', ";
    $totalonsite = $datauser['total_on_site'];
    if ($datauser['lastdate'] > (time() - 300))
        $totalonsite = $totalonsite + time() - $datauser['lastdate'];
    mysql_query("UPDATE `users` SET $sql
        `movings` = '$movings',
        `total_on_site` = '$totalonsite',
        `lastdate` = '" . time() . "'
        WHERE `id` = '$user_id'
    ");
} else {
    // ?????????????????? ???????????????????????????? ????????????
    $movings = 0;
    $session = md5(core::$ip . core::$ip_via_proxy . core::$user_agent);
    $req = mysql_query("SELECT * FROM `cms_sessions` WHERE `session_id` = '$session' LIMIT 1");
    if (mysql_num_rows($req)) {
        // ???????? ???????? ?? ????????, ???? ?????????????????? ????????????
        $res = mysql_fetch_assoc($req);
        $movings = ++$res['movings'];
        if ($res['sestime'] < (time() - 300)) {
            $movings = 1;
            $sql .= " `sestime` = '" . time() . "', ";
        }
        if ($res['place'] != $headmod) {
            $sql .= " `place` = '" . mysql_real_escape_string($headmod) . "', ";
        }
        mysql_query("UPDATE `cms_sessions` SET $sql
            `movings` = '$movings',
            `lastdate` = '" . time() . "'
            WHERE `session_id` = '$session'
        ");
    } else {
        // ???????? ?????? ???????????? ?? ????????, ???? ?????????????????? ????????????
        mysql_query("INSERT INTO `cms_sessions` SET
            `session_id` = '" . $session . "',
            `ip` = '" . core::$ip . "',
            `ip_via_proxy` = '" . core::$ip_via_proxy . "',
            `browser` = '" . mysql_real_escape_string($agn) . "',
            `lastdate` = '" . time() . "',
            `sestime` = '" . time() . "',
            `place` = '" . mysql_real_escape_string($headmod) . "'
        ");
    }
}

/*
-----------------------------------------------------------------
?????????????? ?????????????????? ?? ????????
-----------------------------------------------------------------
*/
if (!empty($ban)) echo '<div class="alarm">' . $lng['ban'] . '&#160;<a href="' . $set['homeurl'] . '/users/profile.php?act=ban">' . $lng['in_detail'] . '</a></div>';

/*
-----------------------------------------------------------------
???????????? ???? ??????????????????????????
-----------------------------------------------------------------
*/
if ($user_id) {
    $list = array();
    $new_sys_mail = mysql_result(mysql_query("SELECT COUNT(*) FROM `cms_mail` WHERE `from_id`='$user_id' AND `read`='0' AND `sys`='1' AND `delete`!='$user_id';"), 0);
	if ($new_sys_mail) $list[] = '<a href="' . $home . '/mail/index.php?act=systems">Tin nh???n h??? th???ng</a> (+' . $new_sys_mail . ')';
	$new_mail = mysql_result(mysql_query("SELECT COUNT(*) FROM `cms_mail` LEFT JOIN `cms_contact` ON `cms_mail`.`user_id`=`cms_contact`.`from_id` AND `cms_contact`.`user_id`='$user_id' WHERE `cms_mail`.`from_id`='$user_id' AND `cms_mail`.`sys`='0' AND `cms_mail`.`read`='0' AND `cms_mail`.`delete`!='$user_id' AND `cms_contact`.`ban`!='1' AND `cms_mail`.`spam`='0'"), 0);
	if ($new_mail) $list[] = '<a href="' . $home . '/mail/index.php?act=new">' . $lng['mail'] . '</a> (+' . $new_mail . ')';
    if ($datauser['comm_count'] > $datauser['comm_old']) $list[] = '<a href="' . core::$system_set['homeurl'] . '/users/profile.php?act=guestbook&amp;user=' . $user_id . '">' . $lng['guestbook'] . '</a> (' . ($datauser['comm_count'] - $datauser['comm_old']) . ')';
    $new_album_comm = mysql_result(mysql_query("SELECT COUNT(*) FROM `cms_album_files` WHERE `user_id` = '" . core::$user_id . "' AND `unread_comments` = 1"), 0);
    if ($new_album_comm) $list[] = '<a href="' . core::$system_set['homeurl'] . '/users/album.php?act=top&amp;mod=my_new_comm">' . $lng['albums_comments'] . '</a>';

    if (!empty($list)) echo '<div class="rmenu">' . $lng['unread'] . ': ' . functions::display_menu($list, ', ') . '</div>';
}
