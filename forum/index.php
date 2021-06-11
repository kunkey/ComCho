<?php
define('_IN_JOHNCMS', 1);

require('../incfiles/core.php');
$lng_forum = core::load_lng('forum');
if (isset($_SESSION['ref']))
    unset($_SESSION['ref']);

/*
-----------------------------------------------------------------
Настройки форума
-----------------------------------------------------------------
*/
$set_forum = $user_id && !empty($datauser['set_forum']) ? unserialize($datauser['set_forum']) : array(
    'farea'    => 0,
    'upfp'     => 0,
    'preview'  => 1,
    'postclip' => 1,
    'postcut'  => 2
);

/*
-----------------------------------------------------------------
Список расширений файлов, разрешенных к выгрузке
-----------------------------------------------------------------
*/
// Файлы архивов
$ext_arch = array(
    'zip',
    'rar',
    '7z',
    'tar',
    'gz',
    'apk'
);
// Звуковые файлы
$ext_audio = array(
    'mp3',
    'amr'
);
// Файлы документов и тексты
$ext_doc = array(
    'txt',
    'pdf',
    'doc',
    'docx',
    'rtf',
    'djvu',
    'xls',
    'xlsx'
);
// Файлы Java
$ext_java = array(
    'sis',
    'sisx',
    'apk'
);
// Файлы картинок
$ext_pic = array(
    'jpg',
    'jpeg',
    'gif',
    'png',
    'bmp'
);
// Файлы SIS
$ext_sis = array(
    'sis',
    'sisx'
);
// Файлы видео
$ext_video = array(
    '3gp',
    'avi',
    'flv',
    'mpeg',
    'mp4'
);
// Файлы Windows
$ext_win = array(
    'exe',
    'msi'
);
// Другие типы файлов (что не перечислены выше)
$ext_other = array('wmf');

// Ограничиваем доступ к Форуму
$error = '';
if (!$set['mod_forum'] && $rights < 7)
    $error = $lng_forum['forum_closed'];
elseif ($set['mod_forum'] == 1 && !$user_id)
    $error = $lng['access_guest_forbidden'];
if ($error) {
    require('../incfiles/head.php');
    echo '<div class="rmenu"><p>' . $error . '</p></div>';
    require('../incfiles/end.php');
    exit;
}

$headmod = $id ? 'forum,' . $id : 'forum';

// Заголовки страниц форума
if (empty($id)) {
    $textl = '' . $lng['forum'] . '';
} else {
    $req = mysql_query("SELECT `text` FROM `forum` WHERE `id`= '" . $id . "'");
    $res = mysql_fetch_assoc($req);
    $hdr = strtr($res['text'], array(
        '&laquo;' => '',
        '&raquo;' => '',
        '&quot;'  => '',
        '&amp;'   => '',
        '&lt;'    => '',
        '&gt;'    => '',
        '&#039;'  => ''
    ));
    $hdr = mb_substr($hdr, 0, 30);
    $hdr = functions::checkout($hdr);
    $textl = mb_strlen($res['text']) > 30 ? $hdr . '...' : $hdr;
}

// Переключаем режимы работы
$mods = array(
    'addfile',
    'addvote',
    'close',
    'deltema',
    'delvote',
    'editpost',
    'editvote',
    'file',
    'files',
    'filter',
    'loadtem',
    'massdel',
    'new',
    'nt',
    'per',
    'post',
    'ren',
    'restore',
    'say',
    'tema',
    'users',
    'vip',
    'vote',
    'who',
    'curators',
    'rating',
    'love'
);
if ($act && ($key = array_search($act, $mods)) !== false && file_exists('includes/' . $mods[$key] . '.php')) {
    require('includes/' . $mods[$key] . '.php');
} else {
    require('../incfiles/head.php');

    // Если форум закрыт, то для Админов выводим напоминание
    if (!$set['mod_forum']) echo '<div class="alarm">' . $lng_forum['forum_closed'] . '</div>';
    elseif ($set['mod_forum'] == 3) echo '<div class="rmenu">' . $lng['read_only'] . '</div>';
    if (!$user_id) {
        if (isset($_GET['newup']))
            $_SESSION['uppost'] = 1;
        if (isset($_GET['newdown']))
            $_SESSION['uppost'] = 0;
    }
    if ($id) {
        // Определяем тип запроса (каталог, или тема)
        $type = mysql_query("SELECT * FROM `forum` WHERE `id`= '$id'");
        if (!mysql_num_rows($type)) {
            // Если темы не существует, показываем ошибку
            echo functions::display_error($lng_forum['error_topic_deleted'], '<a href="index.php">' . $lng['to_forum'] . '</a>');
            require('../incfiles/end.php');
            exit;
        }
        $type1 = mysql_fetch_assoc($type);

        // Фиксация факта прочтения Топика
        if ($user_id && $type1['type'] == 't') {
            $req_r = mysql_query("SELECT * FROM `cms_forum_rdm` WHERE `topic_id` = '$id' AND `user_id` = '$user_id' LIMIT 1");
            if (mysql_num_rows($req_r)) {
                $res_r = mysql_fetch_assoc($req_r);
                if ($type1['time'] > $res_r['time'])
                    mysql_query("UPDATE `cms_forum_rdm` SET `time` = '" . time() . "' WHERE `topic_id` = '$id' AND `user_id` = '$user_id' LIMIT 1");
            } else {
                mysql_query("INSERT INTO `cms_forum_rdm` SET `topic_id` = '$id', `user_id` = '$user_id', `time` = '" . time() . "'");
            }
        }

        // Получаем структуру форума
        $res = true;
        $allow = 0;
        $parent = $type1['refid'];
        while ($parent != '0' && $res != false) {
            $req = mysql_query("SELECT * FROM `forum` WHERE `id` = '$parent' LIMIT 1");
            $res = mysql_fetch_assoc($req);
            if ($res['type'] == 'f' || $res['type'] == 'r') {
                $tree[] = '<a href="index.php?id=' . $parent . '">' . $res['text'] . '</a>';
                if ($res['type'] == 'r' && !empty($res['edit'])) {
                    $allow = intval($res['edit']);
                }
            }
            $parent = $res['refid'];
        }
        $tree[] = '<a href="index.php">' . $lng['forum'] . '</a>';
        krsort($tree);
        if ($type1['type'] != 't' && $type1['type'] != 'm')
            $tree[] = '<b>' . $type1['text'] . '</b>';

        // Счетчик файлов и ссылка на них
        $sql = ($rights == 9) ? "" : " AND `del` != '1'";
        if ($type1['type'] == 'f') {
            $count = mysql_result(mysql_query("SELECT COUNT(*) FROM `cms_forum_files` WHERE `cat` = '$id'" . $sql), 0);
            if ($count > 0)
                $filelink = '<a href="index.php?act=files&amp;c=' . $id . '">' . $lng_forum['files_category'] . '</a>';
        } elseif ($type1['type'] == 'r') {
            $count = mysql_result(mysql_query("SELECT COUNT(*) FROM `cms_forum_files` WHERE `subcat` = '$id'" . $sql), 0);
            if ($count > 0)
                $filelink = '<a href="index.php?act=files&amp;s=' . $id . '">' . $lng_forum['files_section'] . '</a>';
        } elseif ($type1['type'] == 't') {
            $count = mysql_result(mysql_query("SELECT COUNT(*) FROM `cms_forum_files` WHERE `topic` = '$id'" . $sql), 0);
            if ($count > 0)
                $filelink = '<a href="index.php?act=files&amp;t=' . $id . '">' . $lng_forum['files_topic'] . '</a>';
        }
        $filelink = isset($filelink) ? $filelink . '&#160;<span class="red">(' . $count . ')</span>' : false;

        // Счетчик "Кто в теме?"
        $wholink = false;
        if ($user_id && $type1['type'] == 't') {
            $online_u = mysql_result(mysql_query("SELECT COUNT(*) FROM `users` WHERE `lastdate` > " . (time() - 300) . " AND `place` = 'forum,$id'"), 0);
            $online_g = mysql_result(mysql_query("SELECT COUNT(*) FROM `cms_sessions` WHERE `lastdate` > " . (time() - 300) . " AND `place` = 'forum,$id'"), 0);
            $wholink = '<a href="index.php?act=who&amp;id=' . $id . '">' . $lng_forum['who_here'] . '?</a>&#160;<span class="red">(' . $online_u . '&#160;/&#160;' . $online_g . ')</span><br/>';
        }

        // Выводим верхнюю панель навигации
        echo '<a id="up"></a><p>' . counters::forum_new(1) . '</p>' .
            '<div class="phdr">' . functions::display_menu($tree) . '</div>';

        switch ($type1['type']) {
            case 'f':
                ////////////////////////////////////////////////////////////
                // Список разделов форума                                 //
                ////////////////////////////////////////////////////////////
                $req = mysql_query("SELECT `id`, `text`, `soft`, `edit` FROM `forum` WHERE `type`='r' AND `refid`='$id' ORDER BY `realid`");
                $total = mysql_num_rows($req);
                if ($total) {
                    $i = 0;
                    while (($res = mysql_fetch_assoc($req)) !== false) {
                        echo $i % 2 ? '<div class="list2">' : '<div class="list1">';
                        $coltem = mysql_result(mysql_query("SELECT COUNT(*) FROM `forum` WHERE `type` = 't' AND `refid` = '" . $res['id'] . "'"), 0);
                        echo '<a href="?id=' . $res['id'] . '">' . $res['text'] . '</a>';
                        if ($coltem)
                            echo " [$coltem]";
                        if (!empty($res['soft']))
                            echo '<div class="sub"><span class="gray">' . $res['soft'] . '</span></div>';
                        echo '</div>';
                        ++$i;
                    }
                    unset($_SESSION['fsort_id']);
                    unset($_SESSION['fsort_users']);
                } else {
                    echo '<div class="menu"><p>' . $lng_forum['section_list_empty'] . '</p></div>';
                }
                echo '<div class="phdr">' . $lng['total'] . ': ' . $total . '</div>';
                break;

            case 'r':
                ////////////////////////////////////////////////////////////
                // Список топиков                                         //
                ////////////////////////////////////////////////////////////
                $total = mysql_result(mysql_query("SELECT COUNT(*) FROM `forum` WHERE `type`='t' AND `refid`='$id'" . ($rights >= 7 ? '' : " AND `close`!='1'")), 0);
                if (($user_id && !isset($ban['1']) && !isset($ban['11']) && $set['mod_forum'] != 4) || core::$user_rights) {
                    // Кнопка создания новой темы
                    echo '<div class="gmenu"><form action="index.php?act=nt&amp;id=' . $id . '" method="post"><input type="submit" value="' . $lng_forum['new_topic'] . '" /></form></div>';
                }
                if ($total) {
                    $req = mysql_query("SELECT * FROM `forum` WHERE `type`='t'" . ($rights >= 7 ? '' : " AND `close`!='1'") . " AND `refid`='$id' ORDER BY `vip` DESC, `time` DESC LIMIT $start, $kmess");
                    $i = 0;
                    while (($res = mysql_fetch_assoc($req)) !== false) {
                        if ($res['close'])
                            echo '<div class="rmenu">';
                        else
                            echo $i % 2 ? '<div class="list4">' : '<div class="list4">';
                        $nikuser = mysql_query("SELECT `from` FROM `forum` WHERE `type` = 'm' AND `close` != '1' AND `refid` = '" . $res['id'] . "' ORDER BY `time` DESC LIMIT 1");
                        $nam = mysql_fetch_assoc($nikuser);
                        $colmes = mysql_query("SELECT COUNT(*) FROM `forum` WHERE `type`='m' AND `refid`='" . $res['id'] . "'" . ($rights >= 7 ? '' : " AND `close` != '1'"));
                        $colmes1 = mysql_result($colmes, 0);
                        $cpg = ceil($colmes1 / $kmess);
                        $np = mysql_result(mysql_query("SELECT COUNT(*) FROM `cms_forum_rdm` WHERE `time` >= '" . $res['time'] . "' AND `topic_id` = '" . $res['id'] . "' AND `user_id`='$user_id'"), 0);
                        // Значки
                        $icons = array(
                            ($np ? (!$res['vip'] ? functions::image('op.gif') : '') : functions::image('np.gif')),
                            ($res['vip'] ? functions::image('pt.gif') : ''),
                            ($res['realid'] ? functions::image('rate.gif') : ''),
                            ($res['edit'] ? functions::image('tz.gif') : '')
                        );
                        echo functions::display_menu($icons, '');
                        echo '<a href="index.php?id=' . $res['id'] . '">' . (empty($res['text']) ? '-----' : $res['text']) . '</a> [' . $colmes1 . ']';
                        if ($cpg > 1) {
                            echo '<a href="index.php?id=' . $res['id'] . '&amp;page=' . $cpg . '">&#160;&gt;&gt;</a>';
                        }
                        echo '<div class="sub">';
                        echo $res['from'];
                        if (!empty($nam['from'])) {
                            echo '&#160;/&#160;' . $nam['from'];
                        }
                        echo ' <span class="gray">(' . functions::display_date($res['time']) . ')</span></div></div>';
                        ++$i;
                    }
                    unset($_SESSION['fsort_id']);
                    unset($_SESSION['fsort_users']);
                } else {
                    echo '<div class="menu"><p>' . $lng_forum['topic_list_empty'] . '</p></div>';
                }
                echo '<div class="phdr">' . $lng['total'] . ': ' . $total . '</div>';
                if ($total > $kmess) {
                    echo '<div class="topmenu">' . functions::display_pagination('index.php?id=' . $id . '&amp;', $start, $total, $kmess) . '</div>' .
                        '<p><form action="index.php?id=' . $id . '" method="post">' .
                        '<input type="text" name="page" size="2"/>' .
                        '<input type="submit" value="' . $lng['to_page'] . ' &gt;&gt;"/>' .
                        '</form></p>';
                }
                break;

            case 't':
                ////////////////////////////////////////////////////////////
                // Показываем тему с постами                              //
                ////////////////////////////////////////////////////////////
                $filter = isset($_SESSION['fsort_id']) && $_SESSION['fsort_id'] == $id ? 1 : 0;
                $sql = '';
                if ($filter && !empty($_SESSION['fsort_users'])) {
                    // Подготавливаем запрос на фильтрацию юзеров
                    $sw = 0;
                    $sql = ' AND (';
                    $fsort_users = unserialize($_SESSION['fsort_users']);
                    foreach ($fsort_users as $val) {
                        if ($sw)
                            $sql .= ' OR ';
                        $sortid = intval($val);
                        $sql .= "`forum`.`user_id` = '$sortid'";
                        $sw = 1;
                    }
                    $sql .= ')';
                }

                // Если тема помечена для удаления, разрешаем доступ только администрации


                // Счетчик постов темы
                $colmes = mysql_result(mysql_query("SELECT COUNT(*) FROM `forum` WHERE `type`='m'$sql AND `refid`='$id'" . ($rights >= 7 ? '' : " AND `close` != '1'")), 0);
				mysql_query("UPDATE `forum` SET `view` = `view` + 1 WHERE `id` = '$id'");
				$view = $type1['view'];
                if ($start >= $colmes) {
                    // Исправляем запрос на несуществующую страницу
                    $start = max(0, $colmes - (($colmes % $kmess) == 0 ? $kmess : ($colmes % $kmess)));
                }
				
				// Tên bài viết - Trần Văn Hoài - Star
				echo '<div class="list1"><a href="#down"><img src="' . $set['homeurl'] . '/images/down.png" alt="Down" class=""></a> <b>' . $type1['text'] . '</b></div>';
				
                // Phân Trang
                if ($colmes > $kmess) {
                    echo '<div class="topmenu">' . functions::display_pagination('index.php?id=' . $id . '&amp;', $start, $colmes, $kmess) . '</div>';
                }
				
				// Đánh giá chủ đề - Trần Văn Hoài - Star
				$bad_rating = mysql_result(mysql_query("SELECT COUNT(*) FROM `forum_rating` WHERE `topic` = '$id' AND `type` = '2'"),0);
				$good_rating = mysql_result(mysql_query("SELECT COUNT(*) FROM `forum_rating` WHERE `topic` = '$id' AND `type` = '1'"),0);
				    
				if ($user_id) {
                    echo '<div class="list3"><center><b>Đánh Giá: </b> ';
					echo '(<font style="text-shadow: rgb(17, 17, 17) 0px 0px 0px, rgb(63, 255, 0) 0px 0px 0.5em, rgb(180, 255, 0) 0px 0px 0.4em;text-transform: uppercase;">' ,
					'<a href="index.php?act=rating&amp;id=' . $id . '&star=good"><i class="fa fa-thumbs-up"></i></a> ' ,
					'<b>' . $good_rating . '</b> ' ,
					'</font>' ,
					'- <font style="text-shadow:rgb(17, 17, 17) 0px 0px 0px, rgb(255, 118, 0) 0px 0px 0.5em, rgb(255, 0, 0) 0px 0px 0.4em;text-transform: uppercase;">' ,
					'<a href="index.php?act=rating&amp;id=' . $id . '&star=bad"><i class="fa fa-thumbs-down"></i></a> ' ,
					'<b>' . $bad_rating . '</b>' ,
					'</font>)</center>';
                    // Đếm Lượt Xem + Bình Luận - Trần Văn Hoài - Star
				    echo '<div class="list1"><center><b>Tổng <font color="red">' . $view . ' Lượt xem</font> và <font color="red">' . $colmes . ' Bình Luận </font></b></center></div>';

				}else {
                    // Đếm Lượt Xem + Bình Luận - Trần Văn Hoài - Star
                    echo '<div class="list1"><b>Tổng <font color="red">' . $view . ' Lượt xem</font> và <font color="red">' . $colmes . ' Bình Luận </font></b></div>';
                }
				

                // Метка удаления темы
                if ($type1['close']) {
                    echo '<div class="rmenu">' . $lng_forum['topic_delete_who'] . ': <b>' . $type1['close_who'] . '</b></div>';
                } elseif (!empty($type1['close_who']) && $rights >= 7) {
                    echo '<div class="gmenu"><small>' . $lng_forum['topic_delete_whocancel'] . ': <b>' . $type1['close_who'] . '</b></small></div>';
                }

                // Метка закрытия темы
                if ($type1['edit']) {
                    echo '<div class="rmenu" style="color:red;background:rgba(0, 255, 184, 0.91);text-align:center;font-weight:bold;font-size:18px">' . $lng_forum['topic_closed'] . '</div>';
                }

                // Блок голосований
                if ($type1['realid']) {
                    $clip_forum = isset($_GET['clip']) ? '&amp;clip' : '';
                    $vote_user = mysql_result(mysql_query("SELECT COUNT(*) FROM `cms_forum_vote_users` WHERE `user`='$user_id' AND `topic`='$id'"), 0);
                    $topic_vote = mysql_fetch_assoc(mysql_query("SELECT `name`, `time`, `count` FROM `cms_forum_vote` WHERE `type`='1' AND `topic`='$id' LIMIT 1"));
                    echo '<div  class="gmenu"><b>' . functions::checkout($topic_vote['name']) . '</b><br />';
                    $vote_result = mysql_query("SELECT `id`, `name`, `count` FROM `cms_forum_vote` WHERE `type`='2' AND `topic`='" . $id . "' ORDER BY `id` ASC");
                    if (!$type1['edit'] && !isset($_GET['vote_result']) && $user_id && $vote_user == 0) {
                        // Выводим форму с опросами
                        echo '<form action="index.php?act=vote&amp;id=' . $id . '" method="post">';
                        while (($vote = mysql_fetch_assoc($vote_result)) !== false) {
                            echo '<input type="radio" value="' . $vote['id'] . '" name="vote"/> ' . functions::checkout($vote['name'], 0, 1) . '<br />';
                        }
                        echo '<p><input type="submit" name="submit" value="' . $lng['vote'] . '"/><br /><a href="index.php?id=' . $id . '&amp;start=' . $start . '&amp;vote_result' . $clip_forum .
                            '">' . $lng_forum['results'] . '</a></p></form></div>';
                    } else {
                        // Выводим результаты голосования
                        echo '<small>';
                        while (($vote = mysql_fetch_assoc($vote_result)) !== false) {
                            $count_vote = $topic_vote['count'] ? round(100 / $topic_vote['count'] * $vote['count']) : 0;
                            echo functions::checkout($vote['name'], 0, 1) . ' [' . $vote['count'] . ']<br />';
                            echo '<img src="vote_img.php?img=' . $count_vote . '" alt="' . $lng_forum['rating'] . ': ' . $count_vote . '%" /><br />';
                        }
                        echo '</small></div><div class="bmenu">' . $lng_forum['total_votes'] . ': ';
                        if (core::$user_rights > 6)
                            echo '<a href="index.php?act=users&amp;id=' . $id . '">' . $topic_vote['count'] . '</a>';
                        else
                            echo $topic_vote['count'];
                        echo '</div>';
                        if ($user_id && $vote_user == 0)
                            echo '<div class="bmenu"><a href="index.php?id=' . $id . '&amp;start=' . $start . $clip_forum . '">' . $lng['vote'] . '</a></div>';
                    }
                }

                // Получаем данные о кураторах темы
                $curators = !empty($type1['curators']) ? unserialize($type1['curators']) : array();
                $curator = false;
                if ($rights < 6 && $rights != 3 && $user_id) {
                    if (array_key_exists($user_id, $curators)) $curator = true;
                }

                // Фиксация первого поста в теме
                if (($set_forum['postclip'] == 2 && ($set_forum['upfp'] ? $start < (ceil($colmes - $kmess)) : $start > 0)) || isset($_GET['clip'])) {
                    $postreq = mysql_query("SELECT `forum`.*, `users`.`sex`, `users`.`rights`, `users`.`lastdate`, `users`.`status`, `users`.`datereg`
                    FROM `forum` LEFT JOIN `users` ON `forum`.`user_id` = `users`.`id`
                    WHERE `forum`.`type` = 'm' AND `forum`.`refid` = '$id'" . ($rights >= 7 ? "" : " AND `forum`.`close` != '1'") . "
                    ORDER BY `forum`.`id` LIMIT 1");
                    $postres = mysql_fetch_assoc($postreq);
                    echo '<div class="topmenu"><p>';
                    if ($postres['sex'])
                        echo '<img src="../theme/' . $set_user['skin'] . '/images/' . ($postres['sex'] == 'm' ? 'm' : 'w') . ($postres['datereg'] > time() - 86400 ? '_new.png" width="14"' : '.png" width="10"') . ' height="10"/>&#160;';
                    else
                        echo '<img src="../images/del.png" width="10" height="10" alt=""/>&#160;';
                    if ($user_id && $user_id != $postres['user_id']) {
                        echo '<a href="../users/profile.php?user=' . $postres['user_id'] . '&amp;fid=' . $postres['id'] . '"><b>' . $postres['from'] . '</b></a> ' .
                            '<a href="index.php?act=say&amp;id=' . $postres['id'] . '&amp;start=' . $start . '"> ' . $lng_forum['reply_btn'] . '</a> ' .
                            '<a href="index.php?act=say&amp;id=' . $postres['id'] . '&amp;start=' . $start . '&amp;cyt"> ' . $lng_forum['cytate_btn'] . '</a> ';
                    } else {
                        echo '<b>' . $postres['from'] . '</b> ';
                    }
                    $user_rights = array(
                        3 => '(FMod)',
                        6 => '(Smd)',
                        7 => '(Adm)',
                        9 => '(SV!)'
                    );
                    echo @$user_rights[$postres['rights']];
                    echo(time() > $postres['lastdate'] + 300 ? '<span class="red"> [Off]</span>' : '<span class="green"> [ON]</span>');
                    echo ' <span class="gray">(' . functions::display_date($postres['time']) . ')</span><br/>';
                    if ($postres['close']) {
                        echo '<span class="red">' . $lng_forum['post_deleted'] . '</span><br/>';
                    }
                    echo functions::checkout(mb_substr($postres['text'], 0, 500), 0, 2);
                    if (mb_strlen($postres['text']) > 500)
                        echo '...<a href="index.php?act=post&amp;id=' . $postres['id'] . '">' . $lng_forum['read_all'] . '</a>';
                    echo '</p></div>';
                }

                // Памятка, что включен фильтр
                if ($filter) {
                    echo '<div class="rmenu">' . $lng_forum['filter_on'] . '</div>';
                }

                // Задаем правила сортировки (новые внизу / вверху)
                if ($user_id) {
                    $order = $set_forum['upfp'] ? 'DESC' : 'ASC';
                } else {
                    $order = ((empty($_SESSION['uppost'])) || ($_SESSION['uppost'] == 0)) ? 'ASC' : 'DESC';
                }

                ////////////////////////////////////////////////////////////
                // Основной запрос в базу, получаем список постов темы    //
                ////////////////////////////////////////////////////////////
                $req = mysql_query("
                  SELECT `forum`.*, `users`.`sex`, `users`.`rights`, `users`.`lastdate`, `users`.`status`, `users`.`datereg`, `users`.`beliked`, `users`.`danhhieu`
                  FROM `forum` LEFT JOIN `users` ON `forum`.`user_id` = `users`.`id`
                  WHERE `forum`.`type` = 'm' AND `forum`.`refid` = '$id'"
                    . ($rights >= 7 ? "" : " AND `forum`.`close` != '1'") . "$sql
                  ORDER BY `forum`.`id` $order LIMIT $start, $kmess
                ");

                // Верхнее поле "Написать"
                if (($user_id && !$type1['edit'] && $set_forum['upfp'] && $set['mod_forum'] != 3 && $allow != 4) || ($rights >= 7 && $set_forum['upfp'])) {
                    echo '<div class="gmenu"><form name="form1" action="index.php?act=say&amp;id=' . $id . '" method="post">';
                    if ($set_forum['farea']) {
                        $token = mt_rand(1000, 100000);
                        $_SESSION['token'] = $token;
                        echo '<p>' .
                            bbcode::auto_bb('form1', 'msg') .
                            '<textarea rows="' . $set_user['field_h'] . '" name="msg"></textarea></p>' .
                            '<p><input type="checkbox" name="addfiles" value="1" /> ' . $lng_forum['add_file'] .
                            ($set_user['translit'] ? '<br /><input type="checkbox" name="msgtrans" value="1" /> ' . $lng['translit'] : '') .
                            '</p><p><input type="submit" name="submit" value="' . $lng['write'] . '" style="width: 107px; cursor: pointer;"/> ' .
                            (isset($set_forum['preview']) && $set_forum['preview'] ? '<input type="submit" value="' . $lng['preview'] . '" style="width: 107px; cursor: pointer;"/>' : '') .
                            '<input type="hidden" name="token" value="' . $token . '"/>' .
                            '</p></form></div>';
                    } else {
                        echo '<p><input type="submit" name="submit" value="' . $lng['write'] . '"/></p></form></div>';
                    }
                }

                // Для администрации включаем форму массового удаления постов
                if ($rights == 3 || $rights >= 6)
                    echo '<form action="index.php?act=massdel" method="post">';
                $i = 1;

                ////////////////////////////////////////////////////////////
                // Основной список постов                                 //
                ////////////////////////////////////////////////////////////
                while (($res = mysql_fetch_assoc($req)) !== false) {
                    // Ngày Viết + #
                    
					echo '<div class="phdr"> <i class="fa fa-refresh fa-spin"></i>' ,
						' <b> Ngày Viết </b>: ' . functions::display_date($res['time']) . '' ,
						'<a id="' . $res['id'] . '" style="float:right" href="' . $set['homeurl'] . '/forum/index.php?act=post&id=' . $res['id'] . '">' . ($i == 1 ? '<b style="color:red">#' . $i . '</b>' : '#' . $i . '') . '</a>',
						'</div>';
						
                    if ($res['close']) {
                        echo '<div class="rmenu">';
                    } else {
                        echo '<div class="ibox-content-post" style="border-bottom:1px solid #e8e8e8">';
                    }

                    // Ảnh đại diện
                    if ($set_user['avatar']) {
                        echo '<div class="ibox-content-post" style="border-bottom:1px solid #e8e8e8"><table cellpadding="0" cellspacing="0"><tbody><tr><td>';
                        if (file_exists(('../files/users/avatar/' . $res['user_id'] . '.png')))
                            echo '<img src="../files/users/avatar/' . $res['user_id'] . '.png" class="avatar_head" width="50" height="50" alt="' . $res['from'] . '" />&#160;';
                        else
                            echo '<img src="../images/empty.png" class="avatar_head" width="50" height="50" alt="' . $res['from'] . '" />&#160;';
                        echo '</td><td>';
                    }

                    // Tên người dùng
                        $tmp = functions::get_user($res['user_id']);
                        if ($tmp['vip'] != 0) 
                            echo '<b style="color:red">[Vip]</b>';
                        echo '<a href="../users/profile.php?user=' . $res['user_id'] . '"><b class="' .functions::color_user($res['rights']) . '">' . $res['from'] . '</b></a> ';

                    // Chức vụ người dùng
                        echo '<b class="' .functions::color_user($res['rights']) . '">(' . functions::rank_user($res['rights']) . ')</b>';
                        
                    // Danh hiệu
                    if (!empty($res['danhhieu']))
                        echo '<br/> <img src="' . $set['homeurl'] . '/images/dh.gif" alt="Danh hiệu"/>' ,
                            ' <b><font color="green">Danh Hiệu:</font> <font color="darkviolet">[' . $res['danhhieu'] . ']</font></b>';

                    // Tâm trạng
                    if (!empty($res['status'])) {
                        echo '<br/><img src="' . $set['homeurl'] . '/images/stt.gif" alt="STT"/>' ,
                            ' <b style="color:red">Tâm Trạng:</b>' ,
                            ' <font color="orange">' . $res['status'] . '</font>';
                    }
                    
                    // Like + PM
                    echo '<br/><i class="fa fa-heart-o" aria-hidden="true"></i>' ,
                        ' <b style="color:#3366FF">' . $res['beliked'] . '</b>' ,
                        '' . ($user_id ? ' | <a href="/mail/index.php?act=write&id=' . $res['user_id'] . '" title="PM ' . $res['from'] . '"><b><i class="fa fa-inbox" aria-hidden="true"></i> nhắn tin</b></a>' : '');

                    // Закрываем таблицу с аватаром
                    if ($set_user['avatar']) {
                        echo '</td></tr></tbody></table></div>';
                    }

                    ////////////////////////////////////////////////////////////
                    // Вывод текста поста                                     //
                    ////////////////////////////////////////////////////////////
                    $text = $res['text'];
                    $text = functions::checkout($text, 1, 1);
                    if ($set_user['smileys']) {
                        $text = functions::smileys($text, $res['rights'] ? 1 : 0);
                    }
                    echo '<div class="list9" style="padding-bottom:15px">' . $text . '</div>';
                    
                    $total_like = mysql_result(mysql_query("SELECT COUNT(*) FROM `forum_loving` WHERE `topic` = '" . $res['id'] . "'"),0);
                    if ($total_like > 0) {
                        echo '<div class="listlike">';
                        $first_like = mysql_fetch_assoc(mysql_query("SELECT `forum_loving`.*, `users`.`rights` FROM `forum_loving` LEFT JOIN `users` ON `forum_loving`.`user_id` = `users`.`id`  ORDER BY `time` DESC LIMIT 1"));
                        echo '<a href="' . $set['homeurl'] . '/users/profile.php?user=' . $first_like['user_id'] . '" title="' . $first_like['from'] . '"><span class="' . functions::color_user($first_like['rights']) . '">' . $first_like['from'] . '</a>';
                        if ($total_like > 1) echo ' và <a href="index.php?act=love&id=' . $res['id'] . '&star=wholove&dir=' . $id . '&page=' . $page . '">' . ($total_like-1) . ' người khác</a>';
                        echo ' đã thích bài viết này.';
                        echo '</div>';
                    }
                    
                    if (($res['user_id'] != $user_id) && ($user_id)) {
                        echo '<div class="list2">';
                        // Like/Dislike bài viết.
                        $tmp = mysql_result(mysql_query("SELECT COUNT(*) FROM `forum_loving` WHERE `topic` = '" . $res['id'] . "' AND `user_id` = '" . $user_id . "'"),0);
                        echo '<a id="submit" href="index.php?act=love&id=' . $res['id'] . '&star=' . ($tmp == 0 ? 'good' : 'bad') . '&dir=' . $id . '&page=' . $page . '" title="Đánh giá bài viết">' . ($tmp == 0 ? '<i class="fa fa-thumbs-o-up"></i> Like' : '<i class="fa fa-thumbs-o-down"></i> DisLike') . '</a>';
                        
                        // Trích dẫn bài viết.
                        echo ' <a id="submit" href="index.php?act=say&amp;id=' . $res['id'] . '&amp;start=' . $start . '&amp;cyt" title="trích dẫn bài viết"><i class="fa fa fa-pencil-square-o"></i> Trích Dẫn</a>';
                        if ($res['kedit']) {
                            echo '<br /><span class="gray"><small>' . $lng_forum['edited'] . ' <b>' . $res['edit'] . '</b> (' . functions::display_date($res['tedit']) . ') <b>[' . $res['kedit'] . ']</b></small></span>';
                        }

                        echo '</div>';
                    }

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

                    // Ссылки на редактирование / удаление постов
                    if (
                        (($rights == 3 || $rights >= 6 || $curator) && $rights >= $res['rights'])
                        || ($res['user_id'] == $user_id && !$set_forum['upfp'] && ($start + $i) == $colmes && $res['time'] > time() - 300)
                        || ($res['user_id'] == $user_id && $set_forum['upfp'] && $start == 0 && $i == 1 && $res['time'] > time() - 300)
                        || ($i == 1 && $allow == 2 && $res['user_id'] == $user_id)
                    ) {
                        echo '<div class="sub">';

                        // Чекбокс массового удаления постов
                        if ($rights == 3 || $rights >= 6) {
                            echo '<input type="checkbox" name="delch[]" value="' . $res['id'] . '"/>&#160;';
                        }

                        // Служебное меню поста
                        $menu = array(
                            '<a class="btn btn-sm btn-dark" href="index.php?act=editpost&amp;id=' . $res['id'] . '">' . $lng['edit'] . '</a>',
                            ($rights >= 7 && $res['close'] == 1 ? '<a class="btn btn-sm btn-dark" href="index.php?act=editpost&amp;do=restore&amp;id=' . $res['id'] . '">' . $lng_forum['restore'] . '</a>' : ''),
                            ($res['close'] == 1 ? '' : '<a class="btn btn-sm btn-dark" href="index.php?act=editpost&amp;do=del&amp;id=' . $res['id'] . '">' . $lng['delete'] . '</a>')
                        );
                        echo functions::display_menu($menu);

                        // Показываем, кто удалил пост
                        if ($res['close']) {
                            echo '<div class="red">' . $lng_forum['who_delete_post'] . ': <b>' . $res['close_who'] . '</b></div>';
                        } elseif (!empty($res['close_who'])) {
                            echo '<div class="green">' . $lng_forum['who_restore_post'] . ': <b>' . $res['close_who'] . '</b></div>';
                        }

                        // Показываем IP и Useragent
						/***
						if ($rights == 3 || $rights >= 6) {
                            if ($res['ip_via_proxy']) {
                                echo '<div class="gray"><b class="red"><a href="' . $set['homeurl'] . '/' . $set['admp'] . '/index.php?act=search_ip&amp;ip=' . long2ip($res['ip']) . '">' . long2ip($res['ip']) . '</a></b> - ' .
                                    '<a href="' . $set['homeurl'] . '/' . $set['admp'] . '/index.php?act=search_ip&amp;ip=' . long2ip($res['ip_via_proxy']) . '">' . long2ip($res['ip_via_proxy']) . '</a>' .
                                    ' - ' . $res['soft'] . '</div>';
                            } else {
                                echo '<div class="gray"><a href="' . $set['homeurl'] . '/' . $set['admp'] . '/index.php?act=search_ip&amp;ip=' . long2ip($res['ip']) . '">' . long2ip($res['ip']) . '</a> - ' . $res['soft'] . '</div>';
                            }
                        }
						***/
                        echo '</div>';
                    }
                    echo '</div>';
                    ++$i;
                }

                // Кнопка массового удаления постов
                if ($rights == 3 || $rights >= 6) {
                    echo '<div class="rmenu"><input type="submit" value=" ' . $lng['delete'] . ' "/></div>';
                    echo '</form>';
                }

                // Нижнее поле "Написать"
                if (($user_id && !$type1['edit'] && !$set_forum['upfp'] && $set['mod_forum'] != 3 && $allow != 4) || ($rights >= 7 && !$set_forum['upfp'])) {
                    echo '<div class="phdr"><i class="fa fa-commenting" aria-hidden="true"></i> Bình luận</div>';
                    echo '<div class="gmenu"><form name="form2" action="index.php?act=say&amp;id=' . $id . '" method="post">';
                    if ($set_forum['farea']) {
                        $token = mt_rand(1000, 100000);
                        $_SESSION['token'] = $token;
                        echo '<p>';
                        echo bbcode::auto_bb('form2', 'msg');
                        echo '<textarea rows="' . $set_user['field_h'] . '" name="msg"  placeholder="Bạn muốn nói gì?"></textarea><br/></p>' .
                            '<p><input type="checkbox" name="addfiles" value="1" /> ' . $lng_forum['add_file'];
                        if ($set_user['translit'])
                            echo '<br /><input type="checkbox" name="msgtrans" value="1" /> ' . $lng['translit'];
                        echo '</p><p><input type="submit" name="submit" value="' . $lng['write'] . '" style="width: 107px; cursor: pointer;"/> ' .
                            (isset($set_forum['preview']) && $set_forum['preview'] ? '<input type="submit" value="' . $lng['preview'] . '" style="width: 107px; cursor: pointer;"/>' : '') .
                            '<input type="hidden" name="token" value="' . $token . '"/>' .
                            '</p></form></div>';
                    } else {
                        $token = mt_rand(1000, 100000);
                        $_SESSION['token'] = $token;
                        echo '<p>' .
                            bbcode::auto_bb('form2', 'msg') .
                            '<textarea rows="' . $set_user['field_h'] . '" name="msg" placeholder="Bạn muốn nói gì?"></textarea></p>' .
                            '<p><input type="checkbox" name="addfiles" value="1" /> ' . $lng_forum['add_file'] .
                            ($set_user['translit'] ? '<br /><input type="checkbox" name="msgtrans" value="1" /> ' . $lng['translit'] : '') .
                            '</p><p><input type="submit" name="submit" value="' . $lng['write'] . '" style="width: 107px; cursor: pointer;"/> ' .
                            (isset($set_forum['preview']) && $set_forum['preview'] ? '<input type="submit" value="' . $lng['preview'] . '" style="width: 107px; cursor: pointer;"/>' : '') .
                            '<input type="hidden" name="token" value="' . $token . '"/>' .
                            '</p></form></div>';
                    }
                }

                echo '<div class="phdr"><a id="down"></a><a href="#up">' . functions::image('up.png', array('class' => '')) . '</a>' .
                    '&#160;&#160;' . $lng['total'] . ': ' . $colmes . '</div>';

                // Постраничная навигация
                if ($colmes > $kmess) {
                    echo '<div class="topmenu">' . functions::display_pagination('index.php?id=' . $id . '&amp;', $start, $colmes, $kmess) . '</div>' .
                        '<p><form action="index.php?id=' . $id . '" method="post">' .
                        '<input type="text" name="page" size="2"/>' .
                        '<input type="submit" value="' . $lng['to_page'] . ' &gt;&gt;"/>' .
                        '</form></p>';
                } else {
                    echo '<br />';
                }
                
            

                // Giám sát chủ đề
                if ($curators) {

                    $array = array();
                    foreach ($curators as $key => $value) {
                        $array[] = '<a href="../users/profile.php?user=' . $key . '">' . $value . '</a>';
                    }
                    echo '<p><div class="rmenu">' . $lng_forum['curators'] . ': ' . implode(', ', $array) . '</div></p>';
                }

                echo '<div class="phdr">Quản lý bài viết</div>';


                // Ссылки на модерские функции управления темой
                if ($rights == 3 || $rights >= 6) {
                    if ($rights >= 7)
                        echo '<div class="list4"><a href="index.php?act=curators&amp;id=' . $id . '&amp;start=' . $start . '">' . $lng_forum['curators_of_the_topic'] . '</a></div>';
                    echo isset($topic_vote) && $topic_vote > 0
                        ? '<div class="list4"><a href="index.php?act=editvote&amp;id=' . $id . '">' . $lng_forum['edit_vote'] . '</a></div><div class="list4"><a href="index.php?act=delvote&amp;id=' . $id . '">' . $lng_forum['delete_vote'] . '</a></div>'
                        : '<div class="list4"><a href="index.php?act=addvote&amp;id=' . $id . '">' . $lng_forum['add_vote'] . '</a></div>';
                    echo '<div class="list4"><a href="index.php?act=ren&amp;id=' . $id . '">' . $lng_forum['topic_rename'] . '</a></div>';
                    // Закрыть - открыть тему
                    if ($type1['edit'] == 1)
                        echo '<div class="list4"><a href="index.php?act=close&amp;id=' . $id . '">' . $lng_forum['topic_open'] . '</a></div>';
                    else
                        echo '<div class="list4"><a href="index.php?act=close&amp;id=' . $id . '&amp;closed">' . $lng_forum['topic_close'] . '</a></div>';
                    // Удалить - восстановить тему
                    if ($type1['close'] == 1)
                        echo '<div class="list4"><a href="index.php?act=restore&amp;id=' . $id . '">' . $lng_forum['topic_restore'] . '</a></div>';
                    echo '<div class="list4"><a href="index.php?act=deltema&amp;id=' . $id . '">' . $lng_forum['topic_delete'] . '</a></div>';
                    if ($type1['vip'] == 1)
                        echo '<div class="list4"><a href="index.php?act=vip&amp;id=' . $id . '">' . $lng_forum['topic_unfix'] . '</a></div>';
                    else
                        echo '<div class="list4"><a href="index.php?act=vip&amp;id=' . $id . '&amp;vip">' . $lng_forum['topic_fix'] . '</a></div>';
                    echo '<div class="list4"><a href="index.php?act=per&amp;id=' . $id . '">' . $lng_forum['topic_move'] . '</a></div>';
                }

                // Ссылка на список "Кто в теме"
                if ($wholink) {
                    echo '<div class="list4">' . $wholink . '</div>';
                }

                // Ссылка на фильтр постов
                if ($filter) {
                    echo '<div class="list4"><a href="index.php?act=filter&amp;id=' . $id . '&amp;do=unset">' . $lng_forum['filter_cancel'] . '</a></div>';
                } else {
                    echo '<div class="list4"><a href="index.php?act=filter&amp;id=' . $id . '&amp;start=' . $start . '">' . $lng_forum['filter_on_author'] . '</a></div>';
                }

                // Ссылка на скачку темы
                echo '<div class="list4"><a href="index.php?act=tema&amp;id=' . $id . '">' . $lng_forum['download_topic'] . '</a></div>';
                break;

            default:
                // Если неверные данные, показываем ошибку
                echo functions::display_error($lng['error_wrong_data']);
                break;
        }
    } else {
        ////////////////////////////////////////////////////////////
        // Список Категорий форума                                //
        ////////////////////////////////////////////////////////////
        $count = mysql_result(mysql_query("SELECT COUNT(*) FROM `cms_forum_files`" . ($rights >= 7 ? '' : " WHERE `del` != '1'")), 0);
        echo '<p>' . counters::forum_new(1) . '</p>' .
            '<div class="phdr"><b>' . $lng['forum'] . '</b></div>' .
            '<div class="topmenu"><a href="search.php">' . $lng['search'] . '</a> | <a href="index.php?act=files">' . $lng_forum['files_forum'] . '</a> <span class="red">(' . $count . ')</span></div>';
        $req = mysql_query("SELECT `id`, `text`, `soft` FROM `forum` WHERE `type`='f' ORDER BY `realid`");
        $i = 0;
        while (($res = mysql_fetch_array($req)) !== false) {
            echo $i % 2 ? '<div class="list4">' : '<div class="list4">';
            $count = mysql_result(mysql_query("SELECT COUNT(*) FROM `forum` WHERE `type`='r' and `refid`='" . $res['id'] . "'"), 0);
            echo '<a href="index.php?id=' . $res['id'] . '">' . $res['text'] . '</a> [' . $count . ']';
            if (!empty($res['soft']))
                echo '<div class="sub"><span class="gray">' . $res['soft'] . '</span></div>';
            echo '</div>';
            ++$i;
        }
        $online_u = mysql_result(mysql_query("SELECT COUNT(*) FROM `users` WHERE `lastdate` > " . (time() - 300) . " AND `place` LIKE 'forum%'"), 0);
        $online_g = mysql_result(mysql_query("SELECT COUNT(*) FROM `cms_sessions` WHERE `lastdate` > " . (time() - 300) . " AND `place` LIKE 'forum%'"), 0);
        echo '<div class="phdr">' . ($user_id ? '<a href="index.php?act=who">' . $lng_forum['who_in_forum'] . '</a>' : $lng_forum['who_in_forum']) . '&#160;(' . $online_u . '&#160;/&#160;' . $online_g . ')</div>';
        unset($_SESSION['fsort_id']);
        unset($_SESSION['fsort_users']);
    }

    // Навигация внизу страницы
    echo '<p>' . ($id ? '<div class="list4"><a href="index.php">' . $lng['to_forum'] . '</a></div>' : '');
    if (!$id) {
        echo '<div class="rmenu"><a href="../pages/faq.php?act=forum">' . $lng_forum['forum_rules'] . '</a></div>';
    }
    echo '</p>';
    if (!$user_id) {
        if ((empty($_SESSION['uppost'])) || ($_SESSION['uppost'] == 0)) {
            echo '<a href="index.php?id=' . $id . '&amp;page=' . $page . '&amp;newup">' . $lng_forum['new_on_top'] . '</a>';
        } else {
            echo '<a href="index.php?id=' . $id . '&amp;page=' . $page . '&amp;newdown">' . $lng_forum['new_on_bottom'] . '</a>';
        }
    }
}

require_once('../incfiles/end.php');