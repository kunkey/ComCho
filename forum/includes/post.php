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

require('../incfiles/head.php');
if (empty($_GET['id'])) {
    echo functions::display_error($lng['error_wrong_data']);
    require('../incfiles/end.php');
    exit;
}



// Запрос сообщения
$req = mysql_query("SELECT `forum`.*, `users`.`sex`, `users`.`rights`, `users`.`lastdate`, `users`.`status`, `users`.`datereg`, `users`.`vip`
FROM `forum` LEFT JOIN `users` ON `forum`.`user_id` = `users`.`id`
WHERE `forum`.`type` = 'm' AND `forum`.`id` = '$id'" . ($rights >= 7 ? "" : " AND `forum`.`close` != '1'") . " LIMIT 1");
$res = mysql_fetch_array($req);

// Запрос темы
$them = mysql_fetch_assoc(mysql_query("SELECT * FROM `forum` WHERE `type` = 't' AND `id` = '" . $res['refid'] . "'"));
echo '<div class="phdr"><b>' . $lng_forum['topic'] . ':</b> ' . $them['text'] . '</div><div class="menu">';

// Данные пользователя
if ($set_user['avatar']) {
    echo '<table cellpadding="0" cellspacing="0"><tr><td>';
    if (file_exists(('../files/users/avatar/' . $res['user_id'] . '.png')))
        echo '<img src="../files/users/avatar/' . $res['user_id'] . '.png" width="32" height="32" alt="' . $res['from'] . '" />&#160;';
    else
        echo '<img src="../images/empty.png" width="32" height="32" alt="' . $res['from'] . '" />&#160;';
    echo '</td><td>';
}
if ($res['sex'])
    echo functions::image(($res['sex'] == 'm' ? 'm' : 'w') . ($res['datereg'] > time() - 86400 ? '_new' : '') . '.png', array('class' => 'icon-inline'));
else
    echo functions::image('del.png');
// Nick thành viên
if ($res['vip'])
    echo '<b style="color:red">[Vip]</b>';
echo '<a href="../users/profile.php?user=' . $res['user_id'] . '"><span class="' . functions::color_user($res['rights']) . '">' . $res['from'] . '</span></a> ';
    
// Метка должности
$user_rights = array(
    0 => '',
    1 => '(GMod)',
    2 => '(CMod)',
    3 => '(FMod)',
    4 => '(DMod)',
    5 => '(LMod)',
    6 => '(SMod)',
    7 => '(Admin)',
    9 => '(😭)'
);
echo @$user_rights[$res['rights']];
// Метка Онлайн / Офлайн
echo(time() > $res['lastdate'] + 300 ? '<span class="red"> [Off]</span> ' : '<span class="green"> [ON]</span> ');
echo '<a href="index.php?act=post&amp;id=' . $res['id'] . '" title="Link to post">[#]</a>';
// Ссылки на ответ и цитирование
if ($user_id && $user_id != $res['user_id']) {
    echo '&#160;<a href="index.php?act=say&amp;id=' . $res['id'] . '&amp;start=' . $start . '">' . $lng_forum['reply_btn'] . '</a>&#160;' .
        '<a href="index.php?act=say&amp;id=' . $res['id'] . '&amp;start=' . $start . '&amp;cyt">' . $lng_forum['cytate_btn'] . '</a> ';
}
// Время поста
echo ' <span class="gray">(' . functions::display_date($res['time']) . ')</span><br />';
// Статус юзера
if (!empty($res['status']))
    echo '<div class="status">' . functions::image('label.png', array('class' => 'icon-inline')) . $res['status'] . '</div>';
if ($set_user['avatar'])
    echo '</td></tr></table></div>';

// Вывод текста поста
$text = htmlentities($res['text'], ENT_QUOTES, 'UTF-8');
$text = nl2br($text);
$text = bbcode::tags($text);
if ($set_user['smileys'])
    $text = functions::smileys($text, ($res['rights'] >= 1) ? 1 : 0);
echo '<div class="rmenu">';
echo $text . '';

// Если есть прикрепленный файл, выводим его описание
$freq = mysql_query("SELECT * FROM `cms_forum_files` WHERE `post` = '" . $res['id'] . "'");
if (mysql_num_rows($freq) > 0) {
    $fres = mysql_fetch_assoc($freq);
    $fls = round(@filesize('../files/forum/attach/' . $fres['filename']) / 1024, 2);
    echo '<div class="gray" style="font-size: x-small; background-color: rgba(128, 128, 128, 0.1); padding: 2px 4px; margin-top: 4px">' . $lng_forum['attached_file'] . ':';
    // Предпросмотр изображений
    $att_ext = strtolower(functions::format('./files/forum/attach/' . $fres['filename']));
    $pic_ext = array(
        'gif',
        'jpg',
        'jpeg',
        'png'
    );
    if (in_array($att_ext, $pic_ext)) {
        echo '<div><a href="index.php?act=file&amp;id=' . $fres['id'] . '">';
        echo '<img src="thumbinal.php?file=' . (urlencode($fres['filename'])) . '" alt="' . $lng_forum['click_to_view'] . '" /></a></div>';
    } else {
        echo '<br /><a href="index.php?act=file&amp;id=' . $fres['id'] . '">' . $fres['filename'] . '</a>';
    }
    echo ' (' . $fls . ' кб.)<br/>';
    echo $lng_forum['downloads'] . ': ' . $fres['dlcount'] . ' ' . $lng_forum['time'] . '</div>';
    $file_id = $fres['id'];
}

echo '</div>';

// Вычисляем, на какой странице сообщение?
$page = ceil(mysql_result(mysql_query("SELECT COUNT(*) FROM `forum` WHERE `refid` = '" . $res['refid'] . "' AND `id` " . ($set_forum['upfp'] ? ">=" : "<=") . " '$id'"), 0) / $kmess);
echo '<div class="phdr"><a href="index.php?id=' . $res['refid'] . '&amp;page=' . $page . '">' . $lng_forum['back_to_topic'] . '</a></div>';
echo '<div class="list4"><a href="index.php">' . $lng['to_forum'] . '</a></div>';