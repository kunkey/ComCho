<?php
defined('_IN_JOHNCMS') or die('Error: restricted access');

$mp = new mainpage();

/*
-----------------------------------------------------------------
Tin tức - ADS - Trần Văn Hoài - Star
-----------------------------------------------------------------
*/
echo '<div class="phdr"><i class="fa fa-bullhorn"></i> <a href="' . $set['homeurl'] . '/news" title="' . $lng['news'] . '"><b>' . $lng['news'] . ' - ' . $lng['ads'] . '</b></a></div>';
echo $mp->news;
echo '<div class="menu"><b>Lưu ý :</b> <font color="red">Không quảng cáo dưới mọi hình thức, nếu có quảng cáo phải liên hệ và được sự chấp thuận của Ban Quản Trị. Mọi hình thức vi phạm sẽ xử lý theo Nội Quy của Diễn đàn.</font></div>';

/*
-----------------------------------------------------------------
Chủ đề mới - Trần Văn Hoài - Star
-----------------------------------------------------------------
*/
echo '<div class="phdr"><i class="fa fa-book"></i> 10 ' . $lng['new_topic'] . '</div>';
if ($user_id) echo '<div class="list2"><a href="' . $set['homeurl'] . '/forum/newtopic.php" id="submit" style="border-radius:5px" title="Gửi Bài Viết Mới">Gửi Bài Viết Mới</a></div>';

// Phần bài viết mới
$req = mysql_query("SELECT * FROM `forum` WHERE `type` = 't' AND `close` != '1' ORDER BY `time` DESC LIMIT $start, $kmess");
if (mysql_num_rows($req)) {
    $total = mysql_result(mysql_query("SELECT COUNT(*) FROM `forum` WHERE `type` = 't' AND `close` != '1'"),0);
    while ($star = mysql_fetch_assoc($req)) {
        $count_like = mysql_result(mysql_query("SELECT COUNT(*) FROM `forum_loving` WHERE `topic` = '" . ($star['id']+1) . "'"),0);
        $cha = mysql_fetch_assoc(mysql_query("SELECT * FROM `forum` WHERE `id` = '" . $star['refid'] . "'"));
        $dem = mysql_result(mysql_query("SELECT COUNT(*) FROM `forum` WHERE `refid` = '" . $star['id'] . "' AND `type` = 'm'"),0);

        
        if (file_exists($_SERVER['DOCUMENT_ROOT'].'/files/users/avatar/' . $star['user_id'] . '.png')) {
            $avatar = $set['homeurl'] . '/files/users/avatar/' . $star['user_id'] . '.png';
        } else {
            $avatar = $set['homeurl'] . '/images/empty.png';
        }


        echo '<div class="list2">',
			'<table><tr>',
            '<td style="width: 17%;"><img src="' . $avatar . '" width="50" height="50" class="avatar_topic" alt="' . $star['from'] . '"></td>',
            '<td style="width: 63%;"> <a href="' . $set['homeurl'] . '/forum/index.php?id=' . $star['id'] . '" title="' . $star['text'] . '">' . $star['text'] . '</a><br><span class="label-' . rand(1,8) . '"><a style="color:white;" href="' . $set['homeurl'] . '/forum/index.php?id=' . $cha['id'] . '" title="' . $cha['text'] . '"><i class="fa fa-folder-open-o" aria-hidden="true"></i> ' . $cha['text'] . '</a></span>
			</td>',
            '<td> <span style="float:right;color:#fff;background:#777;border-radius:10px;padding:3px 7px;font-size:12px"><i class="fa fa-comments-o"></i> ' . ($dem) . '</span>',
            ' <span style="float:right;color:#fff;background:#e00;border-radius:10px;padding:3px 7px;font-size:12px"><i class="fa fa-heart-o"></i> ' . $count_like . '</span>',
            '</td>',
			'</tr></table>',
             ' </div>';

    }
    if ($total > $kmess) {
        echo '<div class="topmenu">' . functions::display_pagination('index.php?', $start, $total, $kmess) . '</div>';
    }
    
}
else {
    echo '<div class="rmenu">Hiện tại chưa có bài viết nào!</div>';
}

/*
-----------------------------------------------------------------
Trà chanh chém gió - Trần Văn Hoài - Star
-----------------------------------------------------------------
*/    
if($user_id){
    include('includes/Form_Chat_dev.php');
}
else {
    echo '<div class="rmenu">Bạn Cần <a href="' . $set['homeurl'] . '/login.php" title="Login"> Đăng nhập </a> để chém gió nhé</div>';
}

/*
-----------------------------------------------------------------
Danh mục diễn đàn - Trần Văn Hoài - Star
-----------------------------------------------------------------
*/    
echo '<div class="phdr"><i class="fa fa-list"></i> Danh Mục</div>';
echo '<div class="list4"><i class="fa fa-star-o"></i> <a href="' . $set['homeurl'] . '/upload" title="Upload"> Upload Hình Ảnh </a></div>';
echo '<div class="list4"><i class="fa fa-star-o"></i> <a href="' . $set['homeurl'] . '/forum" title="Diễn Đàn"> Diễn Đàn </a></div>';
echo '<div class="list4"><i class="fa fa-star-o"></i> <a href="' . $set['homeurl'] . '/users" title="Thành Viên"> Thành Viên </a></div>';
echo '<div class="list4"><i class="fa fa-star-o"></i> <a href="' . $set['homeurl'] . '/pages/faq.php" title="FAQ"> Thông Tin - FAQ </a></div>';
echo '<div class="list4"><i class="fa fa-star-o"></i> <a href="' . $set['homeurl'] . '/game" title="Trò chơi giải trí"> Trò Chơi Giải Trí </a></div>';

// Phần chuyên mục
echo '<div class="phdr"><i class="fa fa-list-alt"></i> Chuyên Mục</div>';
$req = mysql_query("SELECT * FROM `forum` WHERE `type` = 'f' ORDER BY `id`");
if (mysql_num_rows($req)) {
    while ($star = mysql_fetch_assoc($req)) {
        $text = $star['text'];
        echo '<div class="list4"><i class="fa fa-star-o"></i> <a href="' . $set['homeurl'] . '/forum/index.php?id=' . $star['id'] . '" title="' . $text . '">' . $text . '</a></div>';
    }
}
else {
    echo '<div class="rmenu">Danh mục trống!</div>';
}

?>
