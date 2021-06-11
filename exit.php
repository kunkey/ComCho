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

define('_IN_JOHNCMS', 1);

require('incfiles/core.php');
$referer = isset($_SERVER['HTTP_REFERER']) ? htmlspecialchars($_SERVER['HTTP_REFERER']) : core::$system_set['homeurl'];

if (isset($_POST['submit'])) {
    setcookie('cuid', '');
    setcookie('cups', '');
    session_destroy();
    header('Location: index.php');
} else {
    require('incfiles/head.php');
    echo'<div class="rmenu">' .
        '<p>' . core::$lng['warning_exit'] . '</p>' .
        '<form action="exit.php" method="post"><p><input type="submit" name="submit" value="' . core::$lng['exit'] . '" /></p></form>' .
        '<p><a href="' . $referer . '">' . core::$lng['cancel'] . '</a></p>' .
        '</div>';
    require('incfiles/end.php');
}