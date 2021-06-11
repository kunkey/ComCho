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

//TODO: Поставить настраиваемый по вызову (через ссылку) размер изображений
function format($name) {
    $f1 = strrpos($name, ".");
    $f2 = substr($name, $f1 + 1, 999);
    $fname = strtolower($f2);
    return $fname;
}

$u = isset($_GET['u']) ? abs(intval($_GET['u'])) : NULL;
$file = isset($_GET['f']) ? htmlspecialchars(urldecode($_GET['f'])) : NULL;
if ($u && $file && file_exists('../files/users/album/' . $u . '/' . $file)) {
    $att_ext = strtolower(format('../files/users/album/' . $u . '/' . $file));
    $pic_ext = array (
        'gif',
        'jpg',
        'jpeg',
        'png'
    );
    if (in_array($att_ext, $pic_ext)) {
        $sizs = GetImageSize('../files/users/album/' . $u . '/' . $file);
        $razm = 230;
        $width = $sizs[0];
        $height = $sizs[1];
        $x_ratio = $razm / $width;
        $y_ratio = $razm / $height;
        if (($width <= $razm) && ($height <= $razm)) {
            $tn_width = $width;
            $tn_height = $height;
        } else if (($x_ratio * $height) < $razm) {
            $tn_height = ceil($x_ratio * $height);
            $tn_width = $razm;
        } else {
            $tn_width = ceil($y_ratio * $width);
            $tn_height = $razm;
        }
        switch ($att_ext) {
            case "gif":
                $im = ImageCreateFromGIF('../files/users/album/' . $u . '/' . $file);
                break;

            case "jpg":
                $im = ImageCreateFromJPEG('../files/users/album/' . $u . '/' . $file);
                break;

            case "jpeg":
                $im = ImageCreateFromJPEG('../files/users/album/' . $u . '/' . $file);
                break;

            case "png":
                $im = ImageCreateFromPNG('../files/users/album/' . $u . '/' . $file);
                break;
        }
        $im1 = imagecreatetruecolor($tn_width, $tn_height);
        imagecopyresized($im1, $im, 0, 0, 0, 0, $tn_width, $tn_height, $width, $height);
        // Передача изображения в Браузер
        ob_start();
        imageJpeg($im1, NULL, 60);
        ImageDestroy($im);
        imagedestroy($im1);
        header('Content-Type: image/jpeg');
        header('Content-Disposition: inline; filename=thumbinal.jpg');
        header('Content-Length: ' . ob_get_length());
        ob_end_flush();
    }
}

?>
