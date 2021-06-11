<?php

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