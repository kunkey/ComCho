<?php
/**
 * @package     JohnCMS
 * @link        http://johncms.com
 * @copyright   Copyright (C) 2008-2011 JohnCMS Community
 * @license     LICENSE.txt (see attached file)
 * @version     VERSION.txt (see attached file)
 * @author      http://johncms.com/about
 */

/**
 * Class CleanUser
 *
 * @package JohnCMS
 * @author  Oleg (AlkatraZ) Kasyanov <dev@mobicms.net>
 * @version v.1.0 2015-01-21
 * @since   build 1647
 */
class CleanUser
{
    public function removeUser($clean_id)
    {
        // Удаляем историю нарушений
        mysql_query("DELETE FROM `cms_ban_users` WHERE `user_id` = '" . $clean_id . "'");
        // Удаляем историю IP
        mysql_query("DELETE FROM `cms_users_iphistory` WHERE `user_id` = '" . $clean_id . "'");
        // Удаляем пользователя
        mysql_query("DELETE FROM `users` WHERE `id` = '" . $clean_id . "'");
    }

    /**
     * Удаляем пользовательские альбомы
     *
     * @param $clean_id
     */
    public function removeAlbum($clean_id)
    {
        // Удаляем папку с файлами картинок
        $dir = ROOTPATH . 'files/users/album/' . $clean_id;
        if (is_dir($dir)) {
            $this->removeDir($dir);
        }

        // Чистим таблицы
        $req = mysql_query("SELECT `id` FROM `cms_album_files` WHERE `user_id` = '" . $clean_id . "'");
        if (mysql_num_rows($req)) {
            while ($res = mysql_fetch_assoc($req)) {
                mysql_query("DELETE FROM `cms_album_comments` WHERE `sub_id` = '" . $res['id'] . "'");
                mysql_query("DELETE FROM `cms_album_downloads` WHERE `file_id` = '" . $res['id'] . "'");
                mysql_query("DELETE FROM `cms_album_views` WHERE `file_id` = '" . $res['id'] . "'");
                mysql_query("DELETE FROM `cms_album_votes` WHERE `file_id` = '" . $res['id'] . "'");
            }
        }

        mysql_query("DELETE FROM `cms_album_cat` WHERE `user_id` = '" . $clean_id . "'");
        mysql_query("DELETE FROM `cms_album_files` WHERE `user_id` = '" . $clean_id . "'");
        mysql_query("DELETE FROM `cms_album_downloads` WHERE `user_id` = '" . $clean_id . "'");
        mysql_query("DELETE FROM `cms_album_views` WHERE `user_id` = '" . $clean_id . "'");
        mysql_query("DELETE FROM `cms_album_votes` WHERE `user_id` = '" . $clean_id . "'");
    }

    /**
     * Удаляем почту и контакты
     *
     * @param $clean_id
     */
    public function removeMail($clean_id)
    {
        // Удаляем файлы юзера из почты
        $req = mysql_query("SELECT * FROM `cms_mail` WHERE (`user_id` OR `from_id` = '" . $clean_id . "') AND `file_name` != ''");

        if (mysql_num_rows($req)) {
            while ($res = mysql_fetch_assoc($req)) {
                // Удаляем файлы почты
                if (is_file(ROOTPATH . 'files/mail/' . $res['file_name'])) {
                    @unlink('../files/mail/' . $res['file_name']);
                }
            }
        }

        mysql_query("DELETE FROM `cms_mail` WHERE `user_id` = '" . $clean_id . "'");
        mysql_query("DELETE FROM `cms_mail` WHERE `from_id` = '" . $clean_id . "'");
        mysql_query("DELETE FROM `cms_contact` WHERE `user_id` = '" . $clean_id . "'");
        mysql_query("DELETE FROM `cms_contact` WHERE `from_id` = '" . $clean_id . "'");
    }

    /**
     * Удаляем Карму
     *
     * @param $clean_id
     */
    public function removeKarma($clean_id)
    {
        mysql_query("DELETE FROM `karma_users` WHERE `karma_user` = '" . $clean_id . "'");
    }

    public function cleanForum($clean_id)
    {
        // Скрываем темы на форуме
        mysql_query("UPDATE `forum` SET `close` = '1', `close_who` = 'SYSTEM' WHERE `type` = 't' AND `user_id` = '" . $clean_id . "'");
        // Скрываем посты на форуме
        mysql_query("UPDATE `forum` SET `close` = '1', `close_who` = 'SYSTEM' WHERE `type` = 'm' AND `user_id` = '" . $clean_id . "'");
        // Удаляем метки прочтения на Форуме
        mysql_query("DELETE FROM `cms_forum_rdm` WHERE `user_id` = '" . $clean_id . "'");
    }

    /**
     * Удаляем личную гостевую
     *
     * @param $clean_id
     */
    public function removeGuestbook($clean_id)
    {
        mysql_query("DELETE FROM `cms_users_guestbook` WHERE `sub_id` = '" . $clean_id . "'");
    }

    /**
     * Удаляем все комментарии пользователя
     *
     * @param $clean_id
     */
    public function cleanComments($clean_id)
    {
        $req = mysql_query("SELECT `name` FROM `users` WHERE `id` = " . $clean_id);
        if (mysql_num_rows($req)) {
            $res = mysql_fetch_assoc($req);

            // Удаляем из Галреи
            mysql_query("DELETE FROM `gallery` WHERE `avtor` = '" . $res['name'] . "' AND `type` = 'km'");
            // Удаляем из Библиотеки
            mysql_query("DELETE FROM `cms_library_comments` WHERE `user_id` = '" . $clean_id . "'");
            // Удаляем из Загрузок
            mysql_query("DELETE FROM `download` WHERE `avtor` = '" . $res['name'] . "' AND `type` = 'komm'");
            // Удаляем комментарии из личных гостевых
            mysql_query("DELETE FROM `cms_users_guestbook` WHERE `user_id` = '" . $clean_id . "'");
            // Удаляем комментарии из личных фотоальбомов
            mysql_query("DELETE FROM `cms_album_comments` WHERE `user_id` = '" . $clean_id . "'");
            // Удаляем посты из гостевой
            mysql_query("DELETE FROM `guest` WHERE `user_id` = '" . $clean_id . "'");
        }
    }

    /**
     * Рекурсивная функция удаления каталогов с файлами
     *
     * @param $dir
     */
    private function removeDir($dir)
    {
        if ($objs = glob($dir . "/*")) {
            foreach ($objs as $obj) {
                is_dir($obj) ? $this->removeDir($obj) : unlink($obj);
            }
        }
        rmdir($dir);
    }
}