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
$headmod = 'office';
$textl = $lng_profile['my_office'];
require('../incfiles/head.php');

/*
-----------------------------------------------------------------
Проверяем права доступа
-----------------------------------------------------------------
*/
if ($user['id'] != $user_id) {
    echo functions::display_error($lng['access_forbidden']);
    require('../incfiles/end.php');
    exit;
}

/*
-----------------------------------------------------------------
Личный кабинет пользователя
-----------------------------------------------------------------
*/

$total_photo = mysql_result(mysql_query("SELECT COUNT(*) FROM `cms_album_files` WHERE `user_id` = '$user_id'"), 0);
$total_friends = mysql_result(mysql_query("SELECT COUNT(*) FROM `cms_contact` WHERE `user_id`='$user_id' AND `type`='2' AND `friends`='1'"), 0);
$new_friends = mysql_result(mysql_query("SELECT COUNT(*) FROM `cms_contact` WHERE `from_id`='$user_id' AND `type`='2' AND `friends`='0';"), 0);
$online_friends = mysql_result(mysql_query("SELECT COUNT(*) FROM `cms_contact` LEFT JOIN `users` ON `cms_contact`.`from_id`=`users`.`id` WHERE `cms_contact`.`user_id`='$user_id' AND `cms_contact`.`type`='2' AND `cms_contact`.`friends`='1' AND `lastdate` > " . (time() - 300) . ""), 0);
echo '' .
    '<div class="gmenu"><p><h3>' . $lng_profile['my_actives'] . '</h3>' .
    '<div>' . functions::image('contacts.png') . '<a href="profile.php">' . $lng_profile['my_profile'] . '</a></div>' .
    '<div>' . functions::image('rate.gif') . '<a href="profile.php?act=stat">' . $lng['statistics'] . '</a></div>' .
    '<div>' . functions::image('photo.gif') . '<a href="album.php?act=list">' . $lng['photo_album'] . '</a>&#160;(' . $total_photo . ')</div>' .
    '<div>' . functions::image('guestbook.gif') . '<a href="profile.php?act=guestbook">' . $lng['guestbook'] . '</a>&#160;(' . $user['comm_count'] . ')</div>';
if ($rights >= 1) {
    $guest = counters::guestbook(2);
    echo '<div>' . functions::image('forbidden.png') . '<a href="../guestbook/index.php?act=ga&amp;do=set">' . $lng['admin_club'] . '</a> (<span class="red">' . $guest . '</span>)</div>';
}
echo '</p></div>';
/*
-----------------------------------------------------------------
Блок почты
-----------------------------------------------------------------
*/
echo '<div class="list2"><p><h3>' . $lng_profile['my_mail'] . '</h3>';
//Входящие сообщения
$count_input = mysql_result(mysql_query("
	SELECT COUNT(*) 
	FROM `cms_mail` 
	LEFT JOIN `cms_contact` 
	ON `cms_mail`.`user_id`=`cms_contact`.`from_id` 
	AND `cms_contact`.`user_id`='$user_id' 
	WHERE `cms_mail`.`from_id`='$user_id' 
	AND `cms_mail`.`sys`='0' AND `cms_mail`.`delete`!='$user_id' 
	AND `cms_contact`.`ban`!='1' AND `spam`='0'"), 0);
echo '<div>' . functions::image('mail-inbox.png') . '<a href="../mail/index.php?act=input">' . $lng_profile['received'] . '</a>&nbsp;(' . $count_input . ($new_mail ? '/<span class="red">+' . $new_mail . '</span>' : '') . ')</div>';
//Исходящие сообщения
$count_output = mysql_result(mysql_query("SELECT COUNT(*) FROM `cms_mail` LEFT JOIN `cms_contact` ON `cms_mail`.`from_id`=`cms_contact`.`from_id` AND `cms_contact`.`user_id`='$user_id' 
WHERE `cms_mail`.`user_id`='$user_id' AND `cms_mail`.`delete`!='$user_id' AND `cms_mail`.`sys`='0' AND `cms_contact`.`ban`!='1'"), 0);
//Исходящие непрочитанные сообщения
$count_output_new = mysql_result(mysql_query("SELECT COUNT(*) FROM `cms_mail` LEFT JOIN `cms_contact` ON `cms_mail`.`from_id`=`cms_contact`.`from_id` AND `cms_contact`.`user_id`='$user_id' 
WHERE `cms_mail`.`user_id`='$user_id' AND `cms_mail`.`delete`!='$user_id' AND `cms_mail`.`read`='0' AND `cms_mail`.`sys`='0' AND `cms_contact`.`ban`!='1'"), 0);
echo '<div>' . functions::image('mail-send.png') . '<a href="../mail/index.php?act=output">' . $lng_profile['sent'] . '</a>&nbsp;(' . $count_output . ($count_output_new ? '/<span class="red">+' . $count_output_new . '</span>' : '') . ')</div>';
$count_systems = mysql_result(mysql_query("SELECT COUNT(*) FROM `cms_mail`
WHERE `from_id`='$user_id' AND `delete`!='$user_id' AND `sys`='1'"), 0);
//Системные сообщения
$count_systems_new = mysql_result(mysql_query("SELECT COUNT(*) FROM `cms_mail`
WHERE `from_id`='$user_id' AND `delete`!='$user_id' AND `sys`='1' AND `read`='0'"), 0);
echo '<div>' . functions::image('mail-info.png') . '<a href="../mail/index.php?act=systems">' . $lng_profile['systems'] . '</a>&nbsp;(' . $count_systems . ($count_systems_new ? '/<span class="red">+' . $count_systems_new . '</span>' : '') . ')</div>';
//Файлы
$count_file = mysql_result(mysql_query("SELECT COUNT(*) FROM `cms_mail`
WHERE (`user_id`='$user_id' OR `from_id`='$user_id') AND `delete`!='$user_id' AND `file_name`!='';"), 0);
echo '<div>' . functions::image('file.gif') . '<a href="../mail/index.php?act=files">' . $lng['files'] . '</a>&nbsp;(' . $count_file . ')</div>';
if (empty($ban['1']) && empty($ban['3'])) {
    echo '<p><form action="../mail/index.php?act=write" method="post"><input type="submit" value="' . $lng['write'] . '"/></form></p>';
}
// Блок контактов
echo '</p></div><div class="menu"><p><h3>' . $lng['contacts'] . '</h3>';
//Контакты
$count_contacts = mysql_result(mysql_query("SELECT COUNT(*) FROM `cms_contact`
WHERE `user_id`='" . $user_id . "' AND `ban`!='1';"), 0);
echo '<div>' . functions::image('user.png') . '<a href="../mail/">' . $lng['contacts'] . '</a>&nbsp;(' . $count_contacts . ')</div>';
//Заблокированные
$count_ignor = mysql_result(mysql_query("SELECT COUNT(*) FROM `cms_contact`
WHERE `user_id`='" . $user_id . "' AND `ban`='1';"), 0);
echo '<div>' . functions::image('user-ok.png') . '<a href="profile.php?act=friends">' . $lng_profile['friends'] . '</a>&#160;(' . $total_friends . ($new_friends ? '/<span class="red">+' . $new_friends . '</span>' : '') . ')&#160;<a href="profile.php?act=friends&amp;do=online">' . $lng['online'] . '</a> (' . $online_friends . ')</div>';
echo '<div>' . functions::image('user-block.png') . '<a href="../mail/index.php?act=ignor">' . $lng_profile['banned'] . '</a>&nbsp;(' . $count_ignor . ')</div>';
echo '</p></div>';

// Блок настроек
echo '<div class="bmenu"><p><h3 style="color:green">' . $lng['settings'] . '</h3>' .
    '<div>' . functions::image('settings.png') . '<a href="profile.php?act=settings">' . $lng['system_settings'] . '</a></div>' .
    '<div>' . functions::image('user-edit.png') . '<a href="profile.php?act=edit">' . $lng_profile['profile_edit'] . '</a></div>' .
    '<div>' . functions::image('lock.png') . '<a href="profile.php?act=password">' . $lng['change_password'] . '</a></div>';
if ($rights >= 1) {
    echo '<div>' . functions::image('forbidden.png') . '<span class="red"><a href="../' . $set['admp'] . '/index.php"><b>' . $lng['admin_panel'] . '</b></a></span></div>';
}
echo '</p></div>';

// Выход с сайта
echo '<div class="rmenu"><p><a href="' . $set['homeurl'] . '/exit.php">' . functions::image('del.png') . $lng['exit'] . '</a></p></div>';