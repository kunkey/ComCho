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

echo '<div class="phdr"><i class="fa fa-shopping-cart" aria-hidden="true"></i> <a href="' . $set['homeurl'] . '/store" title="Cửa hàng"><b>Cửa hàng</b></a> | Danh hiệu</div>';

if (isset($_POST['submit'])) {
    $danhhieu = functions::check(trim($_POST['danhhieu']));
    $subpass = functions::check(trim($_POST['subpass']));
    
    // Kiểm tra sấp mặt.
    if (strlen($danhhieu) > 30) {
        echo '<div class="rmenu">Lỗi! Danh hiệu không được vượt quá 30 kí tự</div>';
        echo '<div class="phdr"><i class="fa fa-backward" aria-hidden="true" style="font-size:12px;"></i> <a href="?star=danhhieu" title="Danh hiệu">Quay trở lại</a></div>';
        require('../incfiles/end.php');
        exit;
    }
    if (strlen($danhhieu) < 3) {
        echo '<div class="rmenu">Lỗi! Danh hiệu không được ít hơn 3 kí tự</div>';
        echo '<div class="phdr"><i class="fa fa-backward" aria-hidden="true" style="font-size:12px;"></i> <a href="?star=danhhieu" title="Danh hiệu">Quay trở lại</a></div>';
        require('../incfiles/end.php');
        exit;
    }
    if (md5(md5($subpass)) != $datauser['subpass']) {
        echo '<div class="rmenu">Lỗi! Mật khẩu cấp 2 không đúng</div>';
        echo '<div class="phdr"><i class="fa fa-backward" aria-hidden="true" style="font-size:12px;"></i> <a href="?star=danhhieu" title="Danh hiệu">Quay trở lại</a></div>';
        require('../incfiles/end.php');
        exit;
    }
    if ($datauser['vnd'] < 100000) {
        echo '<div class="rmenu">Lỗi! Bạn không đủ tiền để mua danh hiệu. Bạn cần <b>' . (100000-$datauser['vnd']) . ' </b>VNĐ để có thể đánh bóng tên tuổi.</div>';
        echo '<div class="phdr"><i class="fa fa-backward" aria-hidden="true" style="font-size:12px;"></i> <a href="?star=danhhieu" title="Danh hiệu">Quay trở lại</a></div>';
        require('../incfiles/end.php');
        exit;
    }
    if ($datauser['danhhieu'] == $danhhieu) {
        echo '<div class="rmenu">Dừng lại! Bạn không nên lãng phí tiền của mình chỉ để mua lại danh hiệu đang có. Hãy dùng tiền vào việc gì đó phù hợp hơn.</div>';
        echo '<div class="phdr"><i class="fa fa-backward" aria-hidden="true" style="font-size:12px;"></i> <a href="?star=danhhieu" title="Danh hiệu">Quay trở lại</a></div>';
        require('../incfiles/end.php');
        exit;
    }
    
    // Cập nhật danh hiệu + Dec money
    mysql_query("UPDATE `users` SET `danhhieu` = '" . $danhhieu . "', `vnd` = `vnd` - 100000 WHERE `id` = '" . $user_id . "'");
    echo '<div class="rmenu">Cập nhật danh hiệu thành công! Hãy đi khoe với bạn bè ngay nào ^^!</div>';
    echo '<div class="phdr"><i class="fa fa-home" aria-hidden="true"></i> <a href="' . $set['homeurl'] . '" title="Home">Quay lại trang chủ</a></div>';
    
} else {
    echo '<div class="rmenu">' . (!empty($datauser['danhhieu']) ? 'Danh hiệu hiện tại của bạn là: <b style="color:darkviolet">' . $datauser['danhhieu'] . '</b>' : 'Bạn chưa có danh hiệu! Hãy làm mới nó nhé :))') . '</div>';
    echo '<div class="list4"><b style="color:red">Giá của mỗi lần mua là 100.000VNĐ</b></div>';
    if (empty($datauser['subpass'])) {
        echo '<div class="rmenu">Hãy <a href="' . $set['homeurl'] . '/users/subpass.php"><b style="color:red">Cập nhật mật khẩu cấp 2</b></a> trước khi vào cửa hàng bạn nhé!</div>';
    } else {
        echo '<form action="index.php?star=danhhieu&mua" method="post">' ,
            '<div class="list2">Danh hiệu bạn muốn đổi (Tối thiểu 3/Tối đa 30 kí tự):<br/><input type="text" name="danhhieu"/></div>' ,
            '<div class="list2">Mật khẩu cấp 2:<br/><input type="password" name="subpass"/></div>' ,
            '<div class="list2"><input type="submit" name="submit" value="Mua"/></div>' ,
            '</form>';
    }
}