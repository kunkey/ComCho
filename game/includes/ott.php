<?php
defined('_IN_JOHNCMS') or die('Error: restricted access');

echo '<div class="phdr"><i class="fa fa-shopping-cart" aria-hidden="true"></i> <a href="' . $set['homeurl'] . '/game" title="Trò chơi giải trí"><b>Trò chơi</b></a> | Oẳn tù tì</div>';

if (isset($_POST['submit'])) {
    $select = (int)intval($_POST['select']);
    
    // Kiểm tra 
    if ($datauser['vnd'] < 1000) {
        echo '<div class="rmenu">Lỗi! Bạn không đủ tiền để chơi. Bạn cần <b>' . (1000-$datauser['vnd']) . ' </b>VNĐ để có thể thử vận may.</div>';
        echo '<div class="phdr"><i class="fa fa-backward" aria-hidden="true" style="font-size:12px;"></i> <a href="?star=ott" title="Oẳn tù tì">Quay trở lại</a></div>';
        require('../incfiles/end.php');
        exit;
    }
    if ($datauser['time_play'] > time()) {
        $time_wait = $datauser['time_play'] - time();
        echo '<div class="rmenu">Lỗi! Bạn cần đợi thêm ' . $time_wait . ' giây nữa để có thể chiến tiếp.</div>';
        echo '<div class="phdr"><i class="fa fa-backward" aria-hidden="true" style="font-size:12px;"></i> <a href="?star=ott" title="Oẳn tù tì">Quay trở lại</a></div>';
        require('../incfiles/end.php');
        exit;
    }
    if (($select < 1) || ($select > 3)) {
        echo '<div class="rmenu">Lỗi dữ liệu, vui lòng chọn lại :)</div>';
        echo '<div class="phdr"><i class="fa fa-backward" aria-hidden="true" style="font-size:12px;"></i> <a href="?star=ott" title="Oẳn tù tì">Quay trở lại</a></div>';
        require('../incfiles/end.php');
        exit;        
    }
    
    // Kết quả
    $ran = rand(1,3);
    $win = array(
            1 => 3,
            2 => 1,
            3 => 2
        );
    $lose = array(
            1 => 2,
            2 => 3,
            3 => 1
        );
    if ($win[$select] == $ran) $vnd = 3000;
    if ($lose[$select] == $ran) $vnd = -1000;
    if ($ran == $select) $vnd = 0;
    
    $ketqua = array(
            3000 => 'Bạn đã chiến thắng và giành được 3000VNĐ từ tay Robot. Hãy tự hào!',
            -1000 => 'Bạn đã thua cuộc, vừa để tuột mất 3000VNĐ, lại còn bị trừ 1000VNĐ. Đừng từ bỏ! Hãy tiếp tục thử vận may nhé!',
            0 => 'Bạn và Robot đã hòa nhau, thật là một cuộc đấu ngang tài ngang sức. Tiếp tục chiến tiếp nào.'
        );
    
    // Cộng - trừ tiền :)
    $time = time() + 5;
    mysql_query("UPDATE `users` SET `vnd` = `vnd` + $vnd, `time_play` = '" . $time . "' WHERE `id` = '" . $user_id . "'");
    echo '<div class="list4">Máy chọn: <img src="../images/game/ott/' . $ran . '.png" width="35" height="40"/></div>' ,
        '<div class="list4">Bạn chọn: <img src="../images/game/ott/' . $select . '.png" width="35" height="40"/></div>' ,
        '<div class="list4">Kết quả: <b style="color:green">' . $ketqua[$vnd] . '</b></div>' ,
        '<div class="rmenu"><a href="index.php?star=ott" title="Oẳn tù tì" id="submit">Tiếp tục chiến</a></div>';
    
} else {
        echo "<style>.list4 { text-shadow: none; }</style>";
        echo '<div class="list4">Bạn đang có <b style="color:red">' . $datauser['vnd'] . '</b>VNĐ.</div>';
        echo '<div class="list4"><b style="color:green">Thắng bạn sẽ nhận được 3000VNĐ - Thua bạn sẽ bị trừ 1000VNĐ - Hòa sẽ không mất mát gì cả!</b></div>';
        echo '<div class="phdr"><i class="fa fa-play-circle-o" aria-hidden="true"></i> Chơi game</div>';
        echo '<div class="rmenu">Bạn muốn chọn Kéo, Búa hay Bao?</div>';
        echo '<form action="index.php?star=ott" method="post">' ,
            '<div class="list4"><input type="radio" name="select" value="1"/> <img src="../images/game/ott/1.png" width="35" height="40" alt="Kéo"/></div>' ,
            '<div class="list4"><input type="radio" name="select" value="2"/> <img src="../images/game/ott/2.png" width="35" height="40" alt="Búa"/></div>' ,
            '<div class="list4"><input type="radio" name="select" value="3"/> <img src="../images/game/ott/3.png" width="35" height="40" alt="Bao"/></div>' ,
            '<div class="list4"><input type="submit" name="submit" value="Thử vận may!"/></div>' ,
            '</form>';
}