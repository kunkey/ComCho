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

$headmod = 'upload';
require('../incfiles/core.php');
$textl = 'Upload hình ảnh';
require('../incfiles/head.php');

echo '<div class="phdr"><i class="fa fa-upload" aria-hidden="true" style="font-size:13px"></i> <a href="index.php" title="Upload ảnh">Upload ảnh</a></div>';

if (!$user_id) {
    echo '<div class="rmenu">Vui lòng <a href="' . $set['homeurl'] . '/login.php" title="Login"><b>Đăng nhập</b></a> hoặc <a href="' . $set['homeurl'] . '/registration.php" title="Đăng ký"><b>Đăng ký</b></a> để có thể xem nội dung này!</div>';
    require('../incfiles/end.php');
    exit;
}

$star = functions::check($_GET['star']);
$id = (int)intval($_GET['id']);

switch($star) {
    
    case 'delete': 
        // Xóa ảnh
            if (isset($_GET['id'])) {
                
                // Khi đã xác nhận
                if (isset($_POST['del'])) {
                    mysql_query("DELETE FROM `upload_img` WHERE `id` = '" . $id . "' AND `user_id` = '" . $user_id . "'");
                    header('Location: ./index.php?star=file&id=' . $user_id);
                    exit;
                }
                
                // Kiểm tra xem ảnh có thuộc quyền sở hữu của cá nhân không?
                $kt = mysql_result(mysql_query("SELECT COUNT(*) FROM `upload_img` WHERE `id` = '" . $id . "' AND `user_id` = '" . $user_id ."'"),0);
                if ($kt == 0) {
                    echo '<div class="rmenu">Bạn không đủ quyền để làm điều này!</div>';
                    require('../incfiles/end.php');
                    exit;
                }
                
                // Form xóa :v
                echo '<form action="index.php?star=delete&id=' . $id . '" method="post">' ,
                    '<div class="rmenu"><b style="color:red">Bạn có chắc muốn xóa bức ảnh tuyệt vời đó?</b></div>' ,
                    '<div class="rmenu"><input type="submit" name="del" value="Chắc chắn"/> <a id="submit" style="padding: 4px 20px;" href="index.php?star=info&id=' . $id . '">Quay lại</a></div>' ,
                     '</form>';
            } else {
                echo '<div class="rmenu">Lỗi dữ liệu</div>';
            }
        break;
    
    case 'info':
        // Nội dung ảnh
            if (isset($_GET['id'])) {
                // Truy vấn
                $req = mysql_query("SELECT `upload_img`.*, `users`.`name`, `users`.`rights` FROM `upload_img` LEFT JOIN `users` ON `upload_img`.`user_id` = `users`.`id` WHERE `upload_img`.`id` = '" . $id . "'");
                if (!(mysql_num_rows($req))) {
                    echo '<div class="rmenu">Lỗi dữ liệu!</div>';
                    require('../incfiles/end.php');
                    exit;
                }
                
                $res = mysql_fetch_assoc($req);
                mysql_query("UPDATE `upload_img` SET `view` = `view` + 1 WHERE `id` = '" . $id . "'");
                // Thông tin upload
                echo '<div class="list4">Người Upload: <a href="' . $set['homeurl'] . '/users/profile.php?user=' . $res['user_id'] . '" title="' . $res['name'] . '"><b class="' . functions::color_user($res['rights']) . '">' . $res['name'] . '</b></a></div>';
                echo '<div class="list4">Tải lên lúc: ' . functions::display_date($res['time']) . '</div>';
                echo '<div class="list4">Kích thước: ' . functions::get_size($res['size']) . '</div>';
                echo '<div class="list4">Lượt xem: ' . $res['view'] . '</div>';
                echo ($res['user_id'] == $user_id ? '<div class="rmenu"><a href="index.php?star=delete&id=' . $res['id'] . '" title="Xóa ảnh"><b style="color:red">Xóa ảnh</b></a></div>' : '');
                
                // Phần hình ảnh và Download
                echo '<div class="phdr"><i class="fa fa-picture-o" aria-hidden="true" style="font-size:13px"></i> Nội dung hình ảnh</div>';
                echo '<div class="list4" style="text-align:center"><img style="max-width: 400px" src="' . $res['link'] . '" alt="IMG"/></div>';
                echo '<div class="list4" style="text-align:center"><a id="submit" href="' . $res['link'] . '" rel="nofollow">Tải về hình ảnh (' . functions::get_size($res['size']) . ')</a></div>';
                echo '<div class="list4" style="text-align:center">Link ảnh: <textarea rows="2" style="max-width:98%;font-size:12px;margin-top:3px;text-align:center">' . $res['link'] . '</textarea></div>';
                echo '<div class="list4" style="text-align:center">BBCODE: <textarea rows="2" style="max-width:98%;font-size:12px;margin-top:3px;text-align:center">[img]' . $res['link'] . '[/img]</textarea></div>';
                
            } else {
                echo '<div class="rmenu">Lỗi dữ liệu</div>';
            }
        break;
    
    case 'file':
        // Ảnh của cá nhân.
            if (isset($_GET['id'])) {
                // thông tin chủ nhân :v
                $user = functions::get_user($id); // Lấy thông tin từ data
                $last = mysql_fetch_assoc(mysql_query("SELECT `time` FROM `upload_img` WHERE `user_id` = '" . $id . "' ORDER BY `time` DESC"));
                echo '<div class="list4">Bộ ảnh của: <a href="' . $set['homeurl'] . '/users/profile.php?user=' . $user['id'] . '" title="' . $user['name'] . '"><b class="' . functions::color_user($user['rights']) . '">' . $user['name'] . '</b></a></div>';
                echo '<div class="list4">Last Updated: ' . functions::display_date($last['time']) . '</div>';
                
                // Phần ảnh của cá nhân :3
                echo '<div class="phdr"><i class="fa fa-folder-open" aria-hidden="true" style="font-size:13px"></i> Ảnh của ' . $user['name'] . '</div>';
                $req = mysql_query("SELECT `upload_img`.*, `users`.`name`, `users`.`rights` FROM `upload_img` LEFT JOIN `users` ON `upload_img`.`user_id` = `users`.`id` WHERE `upload_img`.`user_id` = '" . $id . "' ORDER BY `time` DESC LIMIT $start, $kmess");
                $total = mysql_result(mysql_query("SELECT COUNT(*) FROM `upload_img` WHERE `user_id` = '" . $id . "' ORDER BY `time`"),0);
                if (mysql_num_rows($req)) {
                    while ($res = mysql_fetch_assoc($req)) {
                        echo '<div class="list4" style="text-align:center">' ,
                            '<a href="index.php?star=info&id=' . $res['id'] . '" title="Show IMG"><img style="padding:2px;border:1px solid #D2D1D1;" src="' . $res['link'] . '" width="100" height="70"/></a>' ,
                            '<div class="listlike">' ,
                            '<a href="' . $set['homeurl'] . '/users/profile.php?user=' . $res['user_id'] . '" title="' . $res['name'] . '"><b class="' . functions::color_user($res['rights']) . '">' . $res['name'] . '</b></a>' ,
                            ' - ' . functions::display_date($res['time']) ,
                            ' - ' . functions::get_size($res['size']) ,
                            '</div>' ,
                            '</div>';
                    }
                    if ($total > $kmess) {
                        echo '<div class="topmenu" style="text-align:center">' . functions::display_pagination('index.php?star=file&id=' . $id . '&', $start, $total, $kmess) . '</div>';
                    }
                } else {
                    echo '<div class="rmenu">Hiện tại ' . $user['name'] . ' chưa có ảnh nào!</div>';
                }
            } else {
                echo '<div class="rmenu">Lỗi dữ liệu!</div>';
            }
        break;
    
    case 'upload':
        // Upload ảnh :v
            if (!isset($_GET['link'])) {
                echo '<form id="imageform" method="post" enctype="multipart/form-data" action="lib_uploadimage.php">' ,
                    '<div class="list4"><input type="file" name="file" /></div>' ,
                    '<div class="list4"><input type="submit" name="photoimg" value="Tải lên"/></div>';
            }
            
            function er($text)
            {
              return '<div class="rmenu" style="text-align:center"><b style="color: red">Lỗi:</b> ' . $text . '</div>';
            }
            if($_GET['er']==7) $error = er("Vui lòng chọn file để tải lên");
            if($_GET['er']==2) $error = er("File Quá Lớn, vui lòng chọn file < 20MB");
            if($_GET['er']==3) $error = er("Chỉ được upload file định dạng .gif, .png, .jpg, .jpeg");
            if($_GET['er']==4) $error = er("Chiều dài và rộng của ảnh quá lớn. Hãy upload ảnh nhỏ hơn 20000x20000 px");
            if($_GET['er']==5) $error = er("Định Dạng File Không Được Phép Upload");
            if($_GET['er']==6) $error = er("Không thể upload ảnh của bạn, hãy thử lại!");
            if($_GET['er']==1) $error = er("Có lỗi xảy ra, vui lòng thử lại");
            if(isset($error)){ echo $error;} else if(isset($_GET['link'])) {
                    echo '<div style="text-align:center">' ,
                        '<div class="rmenu"><b style="color:red">Upload thành công!</b></div>' ,
                        '<div class="list4">Link ảnh: <textarea rows="2" style="max-width:98%;font-size:12px;margin-top:3px;text-align:center">' . base64_decode($_GET['link']) . '</textarea></div>' ,
                        '<div class="list4">BBCODE: <textarea rows="2" style="max-width:98%;font-size:12px;margin-top:3px;text-align:center">[img]' . base64_decode($_GET['link']) . '[/img]</textarea></div>' ,
                        '<div class="list4">HTML Ảnh: <textarea rows="2" style="max-width:98%;font-size:12px;margin-top:3px;text-align:center"><img src="' . base64_decode($_GET['link']) . '" alt="IMG"/></textarea></div>' ,
                        '<div class="list4"><a href="' . base64_decode($_GET['link']) . '" rel="nofollow"><img style="max-width:400px" src="' . base64_decode($_GET['link']) . '"/></a></div>' ,
                        '<div class="list4"><a href="' . $set['homeurl'] . '/upload/?star=upload" title="Upload"><b style="color:green">Tiếp tục Upload</b></a></div>' ,
                        '</div>';
            } 
        break;
    default:
        // Index    
            $count = mysql_result(mysql_query("SELECT COUNT(*) FROM `upload_img` WHERE `user_id` = '" . $user_id . "'"),0);
            echo '<div class="list1" style="padding:10px;">' ,
                '<a href="index.php?star=file&id=' . $user_id . '" title="My Picture"><span class="google">Ảnh của tôi (<b>' . $count . '</b>)</span></a>' ,
                '<a href="index.php?star=upload" title="Upload hình ảnh"><span class="facebook"><i class="fa fa-cloud-upload" aria-hidden="true"></i> Upload Ảnh</span></a>' ,
                 '</div>';
            echo '<div class="phdr"><i class="fa fa-external-link-square" aria-hidden="true" style="text-align:13px"></i> Ảnh mới upload</div>';
            $req = mysql_query("SELECT `upload_img`.*, `users`.`name`, `users`.`rights` FROM `upload_img` LEFT JOIN `users` ON `upload_img`.`user_id` = `users`.`id` ORDER BY `time` DESC LIMIT $start, $kmess");
            $total = mysql_result(mysql_query("SELECT COUNT(*) FROM `upload_img` ORDER BY `time`"),0);
            if (mysql_num_rows($req)) {
                while ($res = mysql_fetch_assoc($req)) {
                    echo '<div class="list4" style="text-align:center">' ,
                        '<a href="index.php?star=info&id=' . $res['id'] . '" title="Show IMG"><img style="padding:2px;border:1px solid #D2D1D1;" src="' . $res['link'] . '" width="100" height="70"/></a>' ,
                        '<div class="listlike">' ,
                        '<a href="index.php?star=file&id=' . $res['user_id'] . '" title="' . $res['name'] . '"><b class="' . functions::color_user($res['rights']) . '">' . $res['name'] . '</b></a>' ,
                        ' - ' . functions::display_date($res['time']) ,
                        ' - ' . functions::get_size($res['size']) ,
                        '</div>' ,
                        '</div>';
                }
                if ($total > $kmess) {
                    echo '<div class="topmenu" style="text-align:center">' . functions::display_pagination('index.php?', $start, $total, $kmess) . '</div>';
                }
            }
}

echo '<div class="phdr"><i class="fa fa-home" aria-hidden="true" style="font-size:13px"></i> <a href="' . $set['homeurl'] . '" title="Home">Trang chủ</a></div>';
require('../incfiles/end.php');

?>