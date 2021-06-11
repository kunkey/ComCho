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
if ($rights == 3 || $rights >= 6) {
    /*
    -----------------------------------------------------------------
    Массовое удаление выбранных постов форума
    -----------------------------------------------------------------
    */
    require('../incfiles/head.php');
    if (isset($_GET['yes'])) {
        $dc = $_SESSION['dc'];
        $prd = $_SESSION['prd'];
        foreach ($dc as $delid) {
            mysql_query("UPDATE `forum` SET
                `close` = '1',
                `close_who` = '$login'
                WHERE `id` = '" . intval($delid) . "'
            ");
        }
        echo '<div class="list4">' . $lng_forum['mass_delete_confirm'] . '</div><div class="list4"><a href="' . $prd . '">' . $lng['back'] . '</a></div>';
    } else {
        if (empty($_POST['delch'])) {
            echo '<div class="rmenu">' . $lng_forum['error_mass_delete'] . '<br/><a href="' . htmlspecialchars(getenv("HTTP_REFERER")) . '">' . $lng['back'] . '</a></div>';
            require('../incfiles/end.php');
            exit;
        }
        foreach ($_POST['delch'] as $v) {
            $dc[] = intval($v);
        }
        $_SESSION['dc'] = $dc;
        $_SESSION['prd'] = htmlspecialchars(getenv("HTTP_REFERER"));
        echo '<div class="menu">' . $lng['delete_confirmation'] . '<br/><a href="index.php?act=massdel&amp;yes">' . $lng['delete'] . '</a> | ' .
            '<a href="' . htmlspecialchars(getenv("HTTP_REFERER")) . '">' . $lng['cancel'] . '</a></div>';
    }
}
