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

if (!$user_id) {
    echo '<div class="rmenu">Vui lòng <a href="' . $set['homeurl'] . '/login.php" title="Login"><b>Đăng nhập</b></a> hoặc <a href="' . $set['homeurl'] . '/registration.php" title="Đăng ký"><b>Đăng ký</b></a> để có thể xem nội dung này!</div>';
    require('../incfiles/end.php');
    exit;
}

if (!isset($_POST['photoimg']))
    die(header('location: ./?star=upload&er=1'));
if (!isset($_FILES['file']['name']) or $_FILES['file']['name'] == null)
    die(header('location: ./?star=upload&er=7'));
// Kiểm tra size
if ($_FILES['file']['size'] > 20000000)
    die(header('location: ./?star=upload&er=2'));
//Kiểm tra nếu là ảnh
if (!getimagesize($_FILES['file']['tmp_name']))
    die(header('location: ./?star=upload&er=3'));
// Kiểm tra phân giải
list($width, $height, $type, $attr) = getimagesize($_FILES['file']['tmp_name']);
if ($width > 30000 || $height > 30000)
    die(header('location: ./?star=upload&er=4'));
# Kiểm tra định dạng file
$access = array(".gif", ".png", ".jpg", ".jpeg");
$checkimage = false;
foreach ($access as $file) {
  if (preg_match("/$file\$/i", $_FILES['file']['name'])) {
    $checkimage = true;
    $mime = $file;
    break;
  }
}

if($checkimage == false){
    die(header('location: ./?star=upload&er=5'));
}

$client_id = "63abde42c477139";
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, 'https://api.imgur.com/3/image.json');
curl_setopt($ch, CURLOPT_POST, TRUE);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($ch, CURLOPT_HTTPHEADER, array('Authorization: Client-ID ' . $client_id));
curl_setopt($ch, CURLOPT_POSTFIELDS, array('image' => base64_encode(file_get_contents($_FILES['file']['tmp_name']))));
$reply = curl_exec($ch);
curl_close($ch);
$reply = json_decode($reply,true);
if ($reply['success'] == 1) {
    mysql_query("INSERT INTO `upload_img` SET
                `user_id` = '" . $user_id . "',
                `time` = '" . time() . "',
                `link` = '" . $reply['data']['link'] . "',
                `size` = '" . $_FILES['file']['size'] . "'
        ");
    die(header('location: ./?star=upload&link=' . base64_encode($reply['data']['link'])));
}
else {
    die(header('location: ./?star=upload&er=6'));
}

require('../incfiles/end.php');