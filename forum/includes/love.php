<?php

/**
 * JohnCMS Version 6.2.2
 * Source: http://johncms.com
 * Editor, Moder: Trần Văn Hoài (Star).
 * Facebook: http://facebook.com/VanHoai.308
 * Gmail: TranVanHoai.9a1.cpt@gmail.com
 * JohnCMS Vietnam.
 * Vui lòng không xóa những ghi chú này để tôn trọng tác giả.
 */

defined('_IN_JOHNCMS') or die('Error: restricted access');

$star = functions::check($_GET['star']);
$dir = (int)intval($_GET['dir']);
$tmp = mysql_result(mysql_query("SELECT COUNT(*) FROM `forum` WHERE `type` = 'm' AND `id` = '$id' AND `refid` = '$dir'"),0);
$tam = mysql_result(mysql_query("SELECT COUNT(*) FROM `forum` WHERE `id` = '$id' AND `type` = 'm' AND `refid` = '$dir' AND `user_id` = '$user_id'"),0);
$page = (int)intval($_GET['page']);
$tmp2 = $set_forum['upfp'] ? 1 : ceil(mysql_result(mysql_query("SELECT COUNT(*) FROM `forum` WHERE `type` = 'm' AND `refid` = '$dir'" . ($rights >= 7 ? '' : " AND `close` != '1'")), 0) / $kmess);
if ($page > $tmp2) $page = $tmp2;
if ($page < 1) $page = 1;

if ((!$user_id) || (!$id) || ($tmp <= 0) || (($tam > 0) && ($star != 'wholove')))  {
    require('../incfiles/head.php');
    echo functions::display_error($lng['error_wrong_data']);
    require('../incfiles/end.php');
    exit;
}

switch ($star) {

	case 'good':
		// Like :3
			$num = mysql_result(mysql_query("SELECT COUNT(*) FROM `forum_loving` WHERE `topic` = '$id' AND `user_id` = '$user_id'"),0);
			if ($num == 1) { header('Location: ' . $set['homeurl'] .'/forum/index.php?id=' . $dir . '&page=' .$page); exit;}
			mysql_query("DELETE FROM `forum_loving` WHERE `topic` = '$id' AND `user_id` = '$user_id' ");
			$tmp = mysql_fetch_assoc(mysql_query("SELECT `user_id`, `refid`, `id` FROM `forum` WHERE `type` = 'm' AND `id` = '$id' AND `refid` = '$dir'"));
			mysql_query("INSERT INTO `forum_loving` SET
					`topic` = '" . $id . "',
					`user_id` = '" .$user_id . "',
					`from` = '" . $datauser['name'] . "',
					`time` = '" . time() . "'
				");
			
			// Lấy thông tin topic
		    $cal = mysql_fetch_assoc(mysql_query("SELECT `text` FROM `forum` WHERE `id` = '" . $tmp['refid'] . "' AND `type` = 't'"));
		    
		    // Nội dung thông báo
			if ($tmp['id'] == $tmp['refid']+1) {
			       $nd = '[url=' . $set['homeurl'] . '/forum/index.php?id=' . $tmp['refid'] . ']đã thích Chủ đề: ' . $cal['text'] . '[/url]';
			} else {
			    $nd = '[url=' . $set['homeurl'] . '/forum/index.php?id=' . $tmp['refid'] . '&page=' . $page . ']đã thích Bài viết của bạn tại Chủ đề: ' . $cal['text'] . '[/url]';
			}
            mysql_query("INSERT INTO `thongbao` SET
                    `text` = '" . $nd . "',
                    `user_id` = '" . $tmp['user_id'] . "',
                    `from` = '" . $user_id . "',
                    `time` = '" . time() . "',
                    `xem` = '0'
                ");		
			mysql_query("UPDATE `users` SET `beliked` = `beliked` + 1 WHERE `id` = '" . $tmp['user_id'] . "'");
			header('Location: ' . $set['homeurl'] .'/forum/index.php?id=' . $dir . '&page=' .$page);
		break;
		
	case 'bad':
		// Dislike
			$num = mysql_result(mysql_query("SELECT COUNT(*) FROM `forum_loving` WHERE `topic` = '$id' AND `user_id` = '$user_id'"),0);
			if ($num == 0) { header('Location: ' . $set['homeurl'] .'/forum/index.php?id=' . $dir . '&page=' .$page); exit;}
			mysql_query("DELETE FROM `forum_loving` WHERE `topic` = '$id' AND `user_id` = '$user_id' ");
			$tmp = mysql_fetch_assoc(mysql_query("SELECT `user_id` FROM `forum` WHERE `type` = 'm' AND `id` = '$id' AND `refid` = '$dir'"));
			header('Location: ' . $set['homeurl'] .'/forum/index.php?id=' . $dir . '&page=' .$page);
			mysql_query("UPDATE `users` SET `beliked` = `beliked` - 1 WHERE `id` = '" . $tmp['user_id'] . "'");
		break;
		
	case 'wholove':
	    // Liệt kê danh sách người like bài viết :3
			require('../incfiles/head.php');
			echo '<div class="phdr"><i class="fa fa-thumbs-o-up"></i> Thống kê lượt thích bài viết</div>';
			$req = mysql_query("SELECT * FROM `forum_loving` WHERE `topic` = '$id' ORDER BY `time`");
			echo '<div class="rmenu">';
			while ($res = mysql_fetch_assoc($req)) {
				$tmp = mysql_fetch_assoc(mysql_query("SELECT `rights` FROM `users` WHERE `id` = '" . $res['user_id'] . "'"));
				echo '<a href="' . $set['homeurl'] . '/users/profile.php?user=' . $res['user_id'] . '" title="' . $res['from'] . '"><span class="' . functions::color_user($tmp['rights']) . '">' . $res['from'] . '</span></a>, ';
			}
			echo '</div>';
			echo '<div class="phdr"><i class="fa fa-refresh fa-spin"></i> <a href="' . $set['homeurl'] . '/forum/index.php?id=' . $dir . '&page=' . $page . '" title="Quay lại chủ đề">Quay lại chủ đề</a></div>';
	    break;
	    
	default:
		header('Location: ' . $set['homeurl'] .'/forum/index.php?id=' . $dir . '&page=' .$page);
}