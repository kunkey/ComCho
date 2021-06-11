<?php

//--Phòng Chát--//

if($user_id){
$form = 'form';
$field = 'msg';

echo '<div class="phdr"><i class="fa fa-comments-o"></i> <a href="' . $set['homeurl'] . '/guestbook" title="' . $lng['chat_room'] . '">' . $lng['chat_room'] . '</a>' . ($rights >= 3? ' | <a href="' . $set['homeurl'] . '/guestbook/index.php?act=clean"><font color="red">[X]</font></a>' : '') . ' | <a href="' . $set['homeurl'] . '/files/comcho.apk"><font color="white">[Download App <i class="fa fa-download"></i>]</font></a></div><div class="gmenu">';
$refer = base64_encode($_SERVER['REQUEST_URI']);

$token = mt_rand(1000, 100000);

$_SESSION['token'] = $token;


echo '<form name="form" id="shoutbox" method="post">';
echo bbcode::auto_bb('form', 'msg');
echo '
<input class="form-control" placeholder="Nhập nội dung để chat" id="msg" name="msg" style="max-width:100%;font-size: 15px; margin-bottom:10px;" />
<input type="hidden" name="ref" value="'.$refer.'" />
<input type="hidden" name="token" value="'.$token.'" />
<input type="submit" name="submit" style="width:100px;margin-bottom:5px" value="Gửi"/> <a href="' . $set['homeurl'] . '/pages/faq.php?act=smileys" title="Biểu cảm">[Biểu Cảm]</a> </form>';

echo '</div>';
    echo '<div id="audio"></div>';
    echo '<div id="datachat" style="overflow: scroll; height: 400px;">'.$p1.'</div>';
}
//--Kết thúc Phòng Chát//

echo "<script>
var loadad = '<audio id=audioplayer autoplay=true><source src=ping.mp3 type=audio/mpeg></audio>';
$(document).ready(function(){
$(\"#datachat\").load(\"/chat.php\");
var refreshId = setInterval(function() {
$(\"#datachat\").load('/chat.php');
$(\"#datachat\").slideDown(\"slow\");
}, 5000);


$(\"#shoutbox\").validate({
debug: false,
submitHandler: function(form) {
$('#audio').fadeIn(100).html(loadad);
$.post('/chat.php', $(\"#shoutbox\").serialize(),function(chatoutput) {
$(\"#datachat\").html(chatoutput);
});
$(\"#msg\").val(\"\");
}
});

});
</script>";
?>