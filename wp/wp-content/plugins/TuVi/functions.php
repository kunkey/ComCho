<?php
//Khởi tạo function cho shortcode
function tuvi_init() {
    echo '<script src="/tuvi.js"></script>';
    echo '<div class="main_tuvi" id="tuvi"><div>';
}

add_shortcode('tuvi', 'tuvi_init');
?>
