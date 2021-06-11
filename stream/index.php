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

$headmod = 'stream';
require('../incfiles/core.php');
$textl = 'Thông tin - Sự kiện';
require('../incfiles/head.php');

if (!$user_id) {
    echo '<div class="rmenu">Vui lòng <a href="' . $set['homeurl'] . '/login.php" title="Login"><b>Đăng nhập</b></a> hoặc <a href="' . $set['homeurl'] . '/registration.php" title="Đăng ký"><b>Đăng ký</b></a> để có thể xem nội dung này!</div>';
    require('../incfiles/end.php');
    exit;
}

$star = functions::check($_GET['star']);

switch($star) {
    
    case 'clean':
        // Dọn dẹp hoạt động    
            if (isset($_POST['clean'])) {
                mysql_query("DELETE FROM `thongbao` WHERE `user_id` = '" . $user_id . "'");
                header('Location: ' . $set['homeurl'] . '/stream');
                exit;
            } else {
                echo '<div class="phdr"><i class="fa fa-bullhorn"></i> <a href="index.php" title="Thông tin - Sự kiện">Thông tin - Sự kiện</a> | Dọn dẹp hoạt động</div>';
                echo '<form action="index.php?star=clean" method="post"><div class="menu">';
                echo '<p><b style="color:red">Bạn có chắc muốn xóa mọi thông báo từ trước đến giờ?</b></p>' ,
                    '<p><input type="submit" name="clean" value="Dọn dẹp"/></p>';
                echo '</div></form>';
                echo '<div class="phdr"><i class="fa fa-backward" aria-hidden="true"></i> <a href="index.php" title="Back">Quay trở lại</a></div>';
            }
        break;
        
    default:
        // Index
        echo '<div class="phdr"><i class="fa fa-bullhorn"></i> Thông tin - Sự kiện</div>';
        $total = mysql_result(mysql_query("SELECT COUNT(*) FROM `thongbao` WHERE `user_id` = '" . $user_id . "'"),0);
        if ($total > 0) echo '<div class="rmenu"><a href="index.php?star=clean" title="Clean"><font color="blue">[Dọn dẹp tất cả các thông báo]</font></a></div>';
        $req = mysql_query("SELECT `thongbao`.*, `users`.`rights`, `users`.`name` FROM `thongbao` LEFT JOIN `users` ON `thongbao`.`from` = `users`.`id` WHERE `user_id` = '" . $user_id . "' ORDER BY `time` DESC LIMIT $start, $kmess");
        if (mysql_num_rows($req)) {
            while ($res = mysql_fetch_assoc($req)) {
                echo '<div' . ($res['xem'] == 0 ? ' class="topmenu" style="background-color: rgb(228, 244, 240);border-top:none;border-bottom: 1px solid #76e9e4;"' : ' class="list4" style="text-shadow: none;"') . '>';
                echo '<a href="' . $set['homeurl'] . '/users/profile.php?user=' . $res['from'] . '" title="' . $res['name'] . '">' ,
                    '<span style="font-weight:bold;" class="' . functions::color_user($res['rights']) . '">' ,
                    '' . $res['name'] . '' ,
                    '</span>' ,
                    '</a>' ,
                    ' ' . functions::checkout($res['text'],1,1) . '' ,
                    '<br/>' . functions::display_date($res['time']);
                echo '</div>';
            }
            if ($total > $kmess) {
                echo '<div class="topmenu">' . functions::display_pagination('index.php?', $start, $total, $kmess) . '</div>';
            }
            mysql_query("UPDATE `thongbao` SET `xem` = '1' WHERE `user_id` = '" . $user_id . "'");    
        } else {
            echo '<div class="menu">Hiện tại chưa có thông tin gì mới!</div>';
        }
        echo '<div class="phdr"><i class="fa fa-home" aria-hidden="true"></i> <a href="' . $set['homeurl'] . '" title="Home">Quay trở lại trang chủ</a></div>';

}

require('../incfiles/end.php');
// Mod bởi Trần Văn Hoài (Star)

?>