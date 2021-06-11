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

echo '<div class="phdr"><i class="fa fa-shopping-cart" aria-hidden="true"></i> <a href="' . $set['homeurl'] . '/store" title="Cửa hàng"><b>Cửa hàng</b></a> | Vip User</div>';

if (isset($_POST['submit'])) {
    $subpass = functions::check(trim($_POST['subpass']));
    
    // Kiểm tra sấp mặt.
    if (md5(md5($subpass)) != $datauser['subpass']) {
        echo '<div class="rmenu">Lỗi! Mật khẩu cấp 2 không đúng</div>';
        echo '<div class="phdr"><i class="fa fa-backward" aria-hidden="true" style="font-size:12px;"></i> <a href="?star=vip" title="Vip User">Quay trở lại</a></div>';
        require('../incfiles/end.php');
        exit;
    }
    if ($datauser['vnd'] < 100000) {
        echo '<div class="rmenu">Lỗi! Bạn không đủ tiền để mua Vip. Bạn cần <b>' . (100000-$datauser['vnd']) . ' </b>VNĐ để có thể làm đẹp.</div>';
        echo '<div class="phdr"><i class="fa fa-backward" aria-hidden="true" style="font-size:12px;"></i> <a href="?star=vip" title="Vip User">Quay trở lại</a></div>';
        require('../incfiles/end.php');
        exit;
    }
    
    // ADD VIP + Dec money
    mysql_query("UPDATE `users` SET `vip` = '1', `vnd` = `vnd` - 100000 WHERE `id` = '" . $user_id . "'");
    echo '<div class="rmenu">Tậu <b style="color:red">Vip</b> thành công! Hãy đi khoe với bạn bè ngay nào ^^!</div>';
    echo '<div class="phdr"><i class="fa fa-home" aria-hidden="true"></i> <a href="' . $set['homeurl'] . '" title="Home">Quay lại trang chủ</a></div>';
    
} else {
    if ($datauser['vip'] == 1) {
        echo '<div class="rmenu">Bạn đã có <b style="color:red">Vip</b> rồi! Mua làm gì nữa để tốn tiền 😜</div>';
        require('../incfiles/end.php');
        exit;
    }
    echo '<div class="list4"><b style="color:red">Giá của <b style="color:red">Vip</b> là 100.000VNĐ</b></div>';
    if (empty($datauser['subpass'])) {
        echo '<div class="rmenu">Hãy <a href="' . $set['homeurl'] . '/users/subpass.php"><b style="color:red">Cập nhật mật khẩu cấp 2</b></a> trước khi vào cửa hàng bạn nhé!</div>';
    } else {
        echo '<form action="index.php?star=vip&mua" method="post">' ,
            '<div class="list2">Bạn có chắc muốn mua VIP chứ?</div>',
            '<div class="list2">Mật khẩu cấp 2:<br/><input type="password" name="subpass"/></div>' ,
            '<div class="list2"><input type="submit" name="submit" value="Mua"/></div>' ,
            '</form>';
    }
}