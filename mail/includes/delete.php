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

$headmod = 'mail';
$textl = $lng['mail'];
require_once('../incfiles/head.php');

echo '<div class="phdr"><h3>' . $lng_mail['deleted_messages'] . '</h3></div>';
if ($id) {
    //Проверяем наличие сообщения
    $req = mysql_query("SELECT * FROM `cms_mail` WHERE (`user_id`='$user_id' OR `from_id`='$user_id') AND `id` = '$id' AND `delete`!='$user_id' LIMIT 1;");
    if (mysql_num_rows($req) == 0) {
        //Выводим ошибку
        echo functions::display_error($lng_mail['messages_does_not_exist']);
        require_once("../incfiles/end.php");
        exit;
    }
    $res = mysql_fetch_assoc($req);
    if (isset($_POST['submit'])) { //Если кнопка "Подвердить" нажата
        //Удаляем системное сообщение
        if ($res['sys']) {
            mysql_query("DELETE FROM `cms_mail` WHERE `from_id`='$user_id' AND `id` = '$id' AND `sys`='1' LIMIT 1");
            echo '<div class="gmenu">' . $lng_mail['messages_delete_ok'] . '</div>';
            echo '<div class="bmenu"><a href="index.php?act=systems">' . $lng['back'] . '</a></div>';
        } else {
            //Удаляем непрочитанное сообщение
            if ($res['read'] == 0 && $res['user_id'] == $user_id) {
                //Удаляем файл
                if ($res['file_name'])
                    @unlink('../files/mail/' . $res['file_name']);
                mysql_query("DELETE FROM `cms_mail` WHERE `user_id`='$user_id' AND `id` = '$id' LIMIT 1");
            } else {
                //Удаляем остальные сообщения
                if ($res['delete']) {
                    //Удаляем файл
                    if ($res['file_name'])
                        @unlink('../files/mail/' . $res['file_name']);
                    mysql_query("DELETE FROM `cms_mail` WHERE (`user_id`='$user_id' OR `from_id`='$user_id') AND `id` = '$id' LIMIT 1");
                } else {
                    mysql_query("UPDATE `cms_mail` SET `delete` = '$user_id' WHERE `id` = '$id' LIMIT 1");
                }
            }
            echo '<div class="gmenu">' . $lng_mail['messages_delete_ok'] . '</div>';
            echo '<div class="bmenu"><a href="index.php?act=write&amp;id=' . ($res['user_id'] == $user_id ? $res['from_id'] : $res['user_id']) . '">' . $lng['back'] . '</a></div>';
        }
    } else {
        echo '<div class="gmenu"><form action="index.php?act=delete&amp;id=' . $id . '" method="post"><div>
		' . $lng_mail['really_delete_message'] . '<br />
		<input type="submit" name="submit" value="' . $lng['delete'] . '"/>
		</div></form></div>';
    }
} else {
    echo '<div class="rmenu">' . $lng_mail['not_message_is_chose'] . '</div>';
}
echo '<div class="bmenu"><a href="../users/profile.php?act=office">' . $lng_mail['in_office'] . '</a></div>';