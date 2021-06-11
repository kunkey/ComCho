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

defined('_IN_JOHNADM') or die('Error: restricted access');

echo '<div class="phdr"><a href="index.php"><b>' . $lng['admin_panel'] . '</b></a> | ' . $lng['smileys'] . '</div>';

$ext = array('gif', 'jpg', 'jpeg', 'png'); // Список разрешенных расширений
$smileys = array();

// Обрабатываем простые смайлы
foreach (glob(ROOTPATH . 'images' . DIRECTORY_SEPARATOR . 'smileys' . DIRECTORY_SEPARATOR . 'simply' . DIRECTORY_SEPARATOR . '*') as $var) {
    $file = basename($var);
    $name = explode(".", $file);
    if (in_array($name[1], $ext)) {
        $smileys['usr'][':' . $name[0]] = '<img src="' . $set['homeurl'] . '/images/smileys/simply/' . $file . '" alt="" />';
    }
}

// Обрабатываем Админские смайлы
foreach (glob(ROOTPATH . 'images' . DIRECTORY_SEPARATOR . 'smileys' . DIRECTORY_SEPARATOR . 'admin' . DIRECTORY_SEPARATOR . '*') as $var) {
    $file = basename($var);
    $name = explode(".", $file);
    if (in_array($name[1], $ext)) {
        $smileys['adm'][':' . functions::trans($name[0]) . ':'] = '<img src="' . $set['homeurl'] . '/images/smileys/admin/' . $file . '" alt="" />';
        $smileys['adm'][':' . $name[0] . ':'] = '<img src="' . $set['homeurl'] . '/images/smileys/admin/' . $file . '" alt="" />';
    }
}

// Обрабатываем смайлы каталога
foreach (glob(ROOTPATH . 'images' . DIRECTORY_SEPARATOR . 'smileys' . DIRECTORY_SEPARATOR . 'user' . DIRECTORY_SEPARATOR . '*' . DIRECTORY_SEPARATOR . '*') as $var) {
    $file = basename($var);
    $name = explode(".", $file);
    if (in_array($name[1], $ext)) {
        $path = $set['homeurl'] . '/images/smileys/user/' . basename(dirname($var));
        $smileys['usr'][':' . functions::trans($name[0]) . ':'] = '<img src="' . $path . '/' . $file . '" alt="" />';
        $smileys['usr'][':' . $name[0] . ':'] = '<img src="' . $path . '/' . $file . '" alt="" />';
    }
}

// Записываем в файл Кэша
if (file_put_contents(ROOTPATH . 'files/cache/smileys.dat', serialize($smileys))) {
    echo '<div class="gmenu"><p>' . $lng['smileys_updated'] . '</p></div>';
} else {
    echo '<div class="rmenu"><p>' . $lng['smileys_error'] . '</p></div>';
}
$total = count($smileys['adm']) + count($smileys['usr']);
echo '<div class="phdr">' . $lng['total'] . ': ' . $total . '</div>' .
    '<p><a href="index.php">' . $lng['admin_panel'] . '</a></p>';