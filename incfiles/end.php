<?php
defined('_IN_JOHNCMS') or die('Error: restricted access');

// Рекламный блок сайта
if (!empty($cms_ads[2])) {
    echo '<div class="gmenu">' . $cms_ads[2] . '</div>';
}

echo '<div class="fmenu">';
if (isset($_GET['err']) || $headmod != "mainpage" || ($headmod == 'mainpage' && $act)) {
    echo '<div><a href=\'' . $set['homeurl'] . '\'>' . functions::image('menu_home.png') . $lng['homepage'] . '</a></div>';
}
echo '<div>' . counters::online() . '</div>' .
    '</div>';

// Счетчики каталогов
functions::display_counters();

// Рекламный блок сайта
if (!empty($cms_ads[3])) {
    echo '<br />' . $cms_ads[3];
}

/*
-----------------------------------------------------------------
ВНИМАНИЕ!!!
Данный копирайт нельзя убирать в течение 90 дней с момента установки скриптов
-----------------------------------------------------------------
ATTENTION!!!
The copyright could not be removed within 90 days of installation scripts
-----------------------------------------------------------------
*/
echo '<div class="footer"><div style="text-align:center;padding:5px;"><small>2021 © Bản quyền đạo thuộc về <br>
<br><b><font color="red">Diễn Đàn T9</font></b><br>
</small></div></div>';
echo '</div></body></html>';