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

$headmod = 'newtopic';
require('../incfiles/core.php');
$textl = $lng_forum['search_forum'];
require('../incfiles/head.php');

echo '<div class="phdr"><i class="fa fa-book"></i> Chủ đề mới</div>';
echo '<div class="rmenu">Vui lòng chọn một trong số những chuyên mục cần đăng bài tương ứng với nội dung của bài đăng!</div>';

$req = mysql_query("SELECT `text`, `id` FROM `forum` WHERE `type` = 'f' ORDER BY `id`");

if (mysql_num_rows($req)) {
    while ($res = mysql_fetch_assoc($req)) {
        echo '<div class="phdr"><i class="fa fa-book"></i> ' . $res['text'] . '</div>';
        $num = mysql_query("SELECT * FROM `forum` WHERE `refid` = '" . $res['id'] . "' AND `type` = 'r' ORDER BY `id`");
        if (mysql_num_rows($num)) {
            while ($star = mysql_fetch_assoc($num)) {
                echo '<div class="list4"><i class="fa fa-star-o"></i> <a href="' . $set['homeurl'] . '/forum/index.php?act=nt&id=' . $star['id'] . '" title="' . $star['text'] . '">' . $star['text'] . '</a></div>';
            }
        }
        else {
            echo '<div class="rmenu">Chuyên mục trống!</div>';
        }
    }
}
else {
    echo '<div class="rmenu">Danh mục trống</div>';
}

require('../incfiles/end.php');
?>