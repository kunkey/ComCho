<?php

/**
 * JohnCMS Version 6.2.2
 * Editor, Moder: Trần Văn Hoài (Star).
 * Facebook: http://facebook.com/VanHoai.308
 * Gmail: TranVanHoai.9a1.cpt@gmail.com
 * JohnCMS Vietnam.
 * Vui lòng không xóa những ghi chú này để tôn trọng tác giả.
 */

define('_IN_JOHNCMS', 1);

$headmod = 'subpass';
require('../incfiles/core.php');
$textl = 'Cập nhật mật khẩu cấp 2';
require('../incfiles/head.php');
$star = functions::check($_GET['star']);

echo '<div class="phdr"><i class="fa fa-key" aria-hidden="true"></i> Mật khẩu cấp 2</div>';

if (!$user_id) {
    echo '<div class="rmenu">Vui lòng <a href="' . $set['homeurl'] . '/login.php" title="Login"><b>Đăng nhập</b></a> hoặc <a href="' . $set['homeurl'] . '/registration.php" title="Đăng ký"><b>Đăng ký</b></a> để có thể xem nội dung này!</div>';
    require('../incfiles/end.php');
    exit;
}

if (!empty($datauser['subpass'])) {
    echo '<div class="rmenu">Bạn đã cập nhật mật khẩu cấp 2 của mình rồi mà? Nếu mất hãy liên hệ Admin để lấy lại nhé!</div>';
    require('../incfiles/end.php');
    exit;
}

switch($star) {
    
    case 'accept' :
            // Đồng ý :3
            if (isset($_POST['accepted'])) {
                mysql_query("UPDATE `users` SET `subpass` = '" . mysql_real_escape_string(md5(md5($_SESSION['subpass']))) . "' WHERE `id` = '" . $user_id . "'");
                echo '<div class="rmenu">Hế! Bạn đã cập nhật thành công của mình. Hãy ghi chép và nhớ nó để bảo mật tài khoản bạn nhé :)</div>';
            }
        break;
    
    case 'request':
            // Hỏi thêm lần nữa :3
            if (isset($_POST['update'])) {
                $subpass = functions::check(trim($_POST['subpass']));
                $resubpass = functions::check(trim($_POST['resubpass']));
                $pass = functions::check(trim($_POST['pass']));
                
                // Kiểm tra sấp mặt :3
                if (strlen($subpass) > 30) {
                    echo '<div class="rmenu">Lỗi! Mật khẩu cấp 2 không được vượt quá 30 kí tự</div>';
                    echo '<div class="phdr"><i class="fa fa-backward" aria-hidden="true" style="font-size:12px;"></i> <a href="subpass.php" title="Mật khẩu cấp 2">Quay trở lại</a></div>';
                    require('../incfiles/end.php');
                    exit;
                }
                if (strlen($subpass) < 6) {
                    echo '<div class="rmenu">Lỗi! Mật khẩu cấp 2 không đượt ít hơn 6 kí tự</div>';
                    echo '<div class="phdr"><i class="fa fa-backward" aria-hidden="true" style="font-size:12px;"></i> <a href="subpass.php" title="Mật khẩu cấp 2">Quay trở lại</a></div>';
                    require('../incfiles/end.php');
                    exit;
                }
                if ($subpass != $resubpass) {
                    echo '<div class="rmenu">Lỗi! Mật khẩu cấp 2 nhập lại không khớp</div>';
                    echo '<div class="phdr"><i class="fa fa-backward" aria-hidden="true" style="font-size:12px;"></i> <a href="subpass.php" title="Mật khẩu cấp 2">Quay trở lại</a></div>';
                    require('../incfiles/end.php');
                    exit;
                }
                if ($datauser['password'] != (md5(md5($pass)))) {
                    echo '<div class="rmenu">Lỗi! Mật khẩu đăng nhập không đúng</div>';
                    echo '<div class="phdr"><i class="fa fa-backward" aria-hidden="true" style="font-size:12px;"></i> <a href="subpass.php" title="Mật khẩu cấp 2">Quay trở lại</a></div>';
                    require('../incfiles/end.php');
                    exit;
                }
                
                // Lưu lại :)
                $_SESSION['subpass'] = $subpass;
                $_SESSION['resubpass'] = $resubpass;
                $_SESSION['pass'] = $pass;
                
                // Xác nhận thêm lần nữa.
                echo '<div class="rmenu"><b style="color:red">Bạn có chắc muốn đặt mật khẩu cấp 2 là <u>' . $subpass . '</u>?</b></div>';
                echo '<div class="list1"><form action="subpass.php?star=accept" method="post">' ,
                    '<input type="submit" name="accepted" value="Chấp nhận"/>' ,
                    '</form></div>';
            }
        break;
    
    default:
        // Index
        echo "<style> .list4 { text-shadow: none; } </style>";
        echo '<div class="rmenu">Cập nhật ngay cho mình mật khẩu cấp 2 để bảo vệ tài khoản một cách an toàn! <br/><b style="color:red">Chú ý: Hãy ghi nhớ mật khẩu cấp 2 của mình vì chỉ được cập nhật một lần duy nhất!</b></div>';
        echo '<form action="subpass.php?star=request" method="post">' ,
            '<div class="list4">Nhập mật khẩu cấp 2 (Tối thiểu 6/Tối đa 32 kí tự, không dấu): <br/> <input type="password" name="subpass"/></div>' ,
            '<div class="list4">Nhập lại mật khẩu cấp 2: <br/> <input type="password" name="resubpass"/></div>' ,
            '<div class="list4">Mật khẩu đăng nhập hiện tại của bạn: <br/> <input type="password" name="pass"/></div>' ,
            '<div class="list1"><input type="submit" name="update" value="Cập nhật"/></div>' ,
             '</form>';
}

echo '<div class="phdr"><i class="fa fa-home" aria-hidden="true"></i> <a href="' . $set['homeurl'] . '" title="Home">Quay trở lại trang chủ</a></div>';
    
require('../incfiles/end.php');

?>