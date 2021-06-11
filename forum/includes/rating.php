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

$tmp = mysql_result(mysql_query("SELECT COUNT(*) FROM `forum` WHERE `type` = 't' AND `id` = '$id'"),0);

if ((!$user_id) || (!$id) || ($tmp <= 0))  {
    require('../incfiles/head.php');
    echo functions::display_error($lng['error_wrong_data']);
    require('../incfiles/end.php');
    exit;
}

$star = functions::check($_GET['star']);

switch ($star) {

	case 'good':
		// Đánh giá tốt :v
		// `type` = 1 -> good_rating;
		// `type` = 2 -> bad_rating;
			mysql_query("DELETE FROM `forum_rating` WHERE `topic` = '$id' AND `user_id` = '$user_id' AND `type` = '1'");
			mysql_query("DELETE FROM `forum_rating` WHERE `topic` = '$id' AND `user_id` = '$user_id' AND `type` = '2'");
			mysql_query("INSERT INTO `forum_rating` SET
					`topic` = '$id',
					`user_id` = '$user_id',
					`type` = '1'
				");
			header('Location: ' . $set['homeurl'] .'/forum/index.php?id=' . $id);
		break;
		
	case 'bad':
		// Đánh giá xấu :3
			mysql_query("DELETE FROM `forum_rating` WHERE `topic` = '$id' AND `user_id` = '$user_id' AND `type` = '1'");
			mysql_query("DELETE FROM `forum_rating` WHERE `topic` = '$id' AND `user_id` = '$user_id' AND `type` = '2'");
			mysql_query("INSERT INTO `forum_rating` SET
					`topic` = '$id',
					`user_id` = '$user_id',
					`type` = '2'
				");
			header('Location: ' . $set['homeurl'] .'/forum/index.php?id=' . $id);
		break;
	
	default:
		header('Location: ' . $set['homeurl'] .'/forum/index.php?id=' . $id);
}
