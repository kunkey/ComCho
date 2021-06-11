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

$headmod = 'game';
require('../incfiles/core.php');
$textl = 'Trò chơi giải trí';
require('../incfiles/head.php');

if (!$user_id) {
    echo '<div class="rmenu">Vui lòng <a href="' . $set['homeurl'] . '/login.php" title="Login"><b>Đăng nhập</b></a> hoặc <a href="' . $set['homeurl'] . '/registration.php" title="Đăng ký"><b>Đăng ký</b></a> để có thể xem nội dung này!</div>';
    require('../incfiles/end.php');
    exit;
}

$star = functions::check($_GET['star']);

$array = array(
	'ott',
	'taixiu'
);

if ($star && in_array($star, $array) && file_exists('includes/' . $star . '.php')) {
	require('includes/' . $star . '.php');
} else {
	echo '<div class="phdr"><i class="fa fa-gamepad" aria-hidden="true"></i> Trò chơi giải trí</div>';
	echo '<div class="list4">⚝ <a href="?star=ott">Oẳn tù tì</a></div>';
	echo '<div class="list4">⚝ <a href="?star=taixiu">Tài Xỉu</a></div>';
}

echo '<div class="phdr"><i class="fa fa-copyright" aria-hidden="true" style="font-size:13px"></i> Bản quyền thuộc Star</div>';
require('../incfiles/end.php');