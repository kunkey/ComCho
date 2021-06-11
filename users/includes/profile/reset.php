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

if ($rights >= 7 && $rights > $user['rights']) {
    /*
    -----------------------------------------------------------------
    Сброс настроек пользователя
    -----------------------------------------------------------------
    */
    $textl = htmlspecialchars($user['name']) . ': ' . $lng_profile['profile_edit'];
    require('../incfiles/head.php');
    mysql_query("UPDATE `users` SET `set_user` = '', `set_forum` = '', `set_chat` = '' WHERE `id` = '" . $user['id'] . "'");
    echo '<div class="gmenu"><p>' . $lng_profile['reset1'] . ' <b>' . $user['name'] . '</b> ' . $lng_profile['reset2'] . '<br />' .
    '<a href="profile.php?user=' . $user['id'] . '">' . $lng['profile'] . '</a></p></div>';
    require_once ('../incfiles/end.php');
    exit;
}
  
?>