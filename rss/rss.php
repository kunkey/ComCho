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

require_once ('../incfiles/core.php');
header('content-type: application/rss+xml');
echo '<?xml version="1.0" encoding="utf-8"?>' .
     '<rss version="2.0" xmlns:dc="http://purl.org/dc/elements/1.1/"><channel>' .
     '<title>' . htmlspecialchars($set['copyright']) . ' | News</title>' .
     '<link>' . $set['homeurl'] . '</link>' .
     '<description>News</description>' .
     '<language>ru-RU</language>';

// Новости
$req = mysql_query('SELECT * FROM `news` ORDER BY `time` DESC LIMIT 15;');
if (mysql_num_rows($req)) {
    while ($res = mysql_fetch_assoc($req)) {
        echo '<item>' .
             '<title>News: ' . $res['name'] . '</title>' .
             '<link>' . $set['homeurl'] . '/news/index.php</link>' .
             '<author>' . htmlspecialchars($res['avt']) . '</author>' .
             '<description>' . htmlspecialchars($res['text']) . '</description>' .
             '<pubDate>' . date('r', $res['time']) .
             '</pubDate>' .
             '</item>';
    }
}

// Библиотека
$req = mysql_query("select * from `lib` where `type`='bk' and `moder`='1' order by `time` desc LIMIT 15;");
if (mysql_num_rows($req)) {
    while ($res = mysql_fetch_array($req)) {
        echo '<item>' .
             '<title>Library: ' . htmlspecialchars($res['name']) . '</title>' .
             '<link>' . $set['homeurl'] . '/library/index.php?id=' . $res['id'] . '</link>' .
             '<author>' . htmlspecialchars($res['avtor']) . '</author>' .
             '<description>' . htmlspecialchars($res['announce']) .
             '</description>' .
             '<pubDate>' . date('r', $res['time']) . '</pubDate>' .
             '</item>';
    }
}
echo '</channel></rss>';