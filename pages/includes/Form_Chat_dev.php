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
    echo '<div id="datachat" style="overflow: scroll; height: 500px;">'.$p1.'</div>';
}
//--Kết thúc Phòng Chát//
?>



<script>
    var uid = <?php echo $datauser['id'];?>;
    var link = '/users/profile.php?user=<?php echo $datauser['id'];?>';
    var color = '<?php echo functions::color_user($datauser['rights']);?>';
    var name = '<?php echo $datauser['name'];?>';

    // Enable pusher logging - don't include this in production
    Pusher.logToConsole = false;
    var pusher = new Pusher('5590c5f02cb8403e5f17', {
        cluster: 'ap1'
    });
    var channel = pusher.subscribe('comcho');
    var loadad = '<audio style="display: none;" id=audioplayer autoplay=true><source src=ping.mp3 type=audio/mpeg></audio>';

    channel.bind('chatbox', function (json) {
        //console.log(json.data);
        if(json.data.status) {
            //alert(json.data.msg);
            $("#msg").val("");
            $("#datachat").prepend('<div class="list1"><i class="fa fa-circle" style="color:#5bc55a"></i> <a href="/users/profile.php?user='+ json.data.from +'"><b class="'+ json.data.color +'">'+ json.data.name +'</b></a>: '+ json.data.msg +'<br><span class="badge pull-right" style="font-size:10px;color:green;"><b>' + json.data.time + ' <i class="fa fa-history"></i></b></span><br><div class="sub" style="margin-top:-8px;"></div></div>');
            (json.data.from != uid) ? $('body').append(loadad) : '';
        }else {
 
        }
    });

$(document).ready(function() {
    $("#datachat").load("chat.php");

    $("#shoutbox").submit((e) => {

        e.preventDefault();        
        
        if($('input[name="msg"]').val()) {
            var token_msg = 'vlxx_'+ uid;
            //$("#datachat").prepend('s<div class="list1" id="'+ token_msg +'"><i class="fa fa-circle" style="color:#5bc55a"></i> <a href="'+ link +'"><b class="'+ color +'">'+ name +'</b></a>: đang gửi...<br><span class="badge pull-right" style="font-size:10px;color:green;"><b>vừa xong <i class="fa fa-history"></i></b></span><br><div class="sub" style="margin-top:-8px;"></div></div>');

            $.ajax({
                url : '/pages/ChatBox.php',
                type : "post",
                dataType:"text;charset=utf-8",
                data : {
                    msg : $('input[name="msg"]').val(),
                    token: $('input[name="token"]').val(),
                    msg_id : token_msg
                },
                success : function (chatoutput){}
            });
        }else {
            cuteToast({
                type: "error", // or 'info', 'error', 'warning'
                message: "Bạn chưa nhập tin nhắn",
                timer: 2000
            })
        }
    });
        
});
</script>