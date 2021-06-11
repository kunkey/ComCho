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
    $topic_vote = mysql_result(mysql_query("SELECT COUNT(*) FROM `cms_forum_vote` WHERE `type`='1' AND `topic` = '$id'"), 0);
    require('../incfiles/head.php');
    if ($topic_vote == 0) {
        echo functions::display_error($lng['error_wrong_data']);
        require('../incfiles/end.php');
        exit;
    }
    if (isset($_GET['yes'])) {
        mysql_query("DELETE FROM `cms_forum_vote` WHERE `topic` = '$id'");
        mysql_query("DELETE FROM `cms_forum_vote_users` WHERE `topic` = '$id'");
        mysql_query("UPDATE `forum` SET  `realid` = '0'  WHERE `id` = '$id'");
        echo '<div class="rmenu">' . $lng_forum['voting_deleted'] . '</div><div class="phdr"><a href="' . $_SESSION['prd'] . '">' . $lng['continue'] . '</a></div>';
    } else {
        echo '<div class="rmenu">' . $lng_forum['voting_delete_warning'] . '</div>';
        echo '<div class="list1"><a id="submit" href="?act=delvote&amp;id=' . $id . '&amp;yes">' . $lng['delete'] . '</a></div>';
        echo '<div class="phdr"><a href="' . htmlspecialchars(getenv("HTTP_REFERER")) . '">' . $lng['cancel'] . '</a></div>';
        $_SESSION['prd'] = htmlspecialchars(getenv("HTTP_REFERER"));
    }
} else {
    header('location: ../index.php?err');
}
