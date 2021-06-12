<?php
/**
 * Cấu hình cơ bản cho WordPress
 *
 * Trong quá trình cài đặt, file "wp-config.php" sẽ được tạo dựa trên nội dung 
 * mẫu của file này. Bạn không bắt buộc phải sử dụng giao diện web để cài đặt, 
 * chỉ cần lưu file này lại với tên "wp-config.php" và điền các thông tin cần thiết.
 *
 * File này chứa các thiết lập sau:
 *
 * * Thiết lập MySQL
 * * Các khóa bí mật
 * * Tiền tố cho các bảng database
 * * ABSPATH
 *
 * @link https://codex.wordpress.org/Editing_wp-config.php
 *
 * @package WordPress
 */

// ** Thiết lập MySQL - Bạn có thể lấy các thông tin này từ host/server ** //
/** Tên database MySQL */
define( 'DB_NAME', 'admin_wp' );

/** Username của database */
define( 'DB_USER', 'admin_wp' );

/** Mật khẩu của database */
define( 'DB_PASSWORD', '01635912116@Aa' );

/** Hostname của database */
define( 'DB_HOST', 'localhost' );

/** Database charset sử dụng để tạo bảng database. */
define( 'DB_CHARSET', 'utf8mb4' );

/** Kiểu database collate. Đừng thay đổi nếu không hiểu rõ. */
define('DB_COLLATE', '');

/**#@+
 * Khóa xác thực và salt.
 *
 * Thay đổi các giá trị dưới đây thành các khóa không trùng nhau!
 * Bạn có thể tạo ra các khóa này bằng công cụ
 * {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}
 * Bạn có thể thay đổi chúng bất cứ lúc nào để vô hiệu hóa tất cả
 * các cookie hiện có. Điều này sẽ buộc tất cả người dùng phải đăng nhập lại.
 *
 * @since 2.6.0
 */
define( 'AUTH_KEY',         'oa;7Y,QY]iwnOKeLgR6v* 9{GHRh.OOtwNyKLz8>#`JbdlSt043]8;1JY WxCgA ' );
define( 'SECURE_AUTH_KEY',  '20C*L)_zZ/WY!a/-w((5GJQ.o7y%-nKWCpxWcZZo9:%?R{[e,>r,UlF.P4gI(/7l' );
define( 'LOGGED_IN_KEY',    'byFC%G(<u|QeHocxAQ3t+h<S`)h1LU0E(pj*5pi#v:h)m@jJ%6343B_6=.Ki6my8' );
define( 'NONCE_KEY',        ':7klMV~AWhcO#F<m?k*6O4!Y{m1i<WHIHU$r/kpzBS]R/(].J2m%eRdzbW2rA`?A' );
define( 'AUTH_SALT',        '[L+rDz$P:F<Jo&BsF,?UG$&5ttppnx+s!_Fl#0+;`b:^A$r#m:]=d2s~bjD=+P8i' );
define( 'SECURE_AUTH_SALT', 'tCI8KdnuTB%%>#rRp]op4aN}MIN]_@O4m=y%>(eYj,rc0b 3(oBg+V~,>;%!f!Rk' );
define( 'LOGGED_IN_SALT',   'GH]`v;s3K_ZcrQrrK<=Q2Em0O:S6+o4Uj`I=O8w_54?Op^nC,Uz4%Od`#_QHYDoB' );
define( 'NONCE_SALT',       'QUJz!VW/2$]- hbxE.ygOh>?s+5Tt|g_G<pH=+H#T,_<$,|m*>#W(:/G8_VjZO*-' );

/**#@-*/

/**
 * Tiền tố cho bảng database.
 *
 * Đặt tiền tố cho bảng giúp bạn có thể cài nhiều site WordPress vào cùng một database.
 * Chỉ sử dụng số, ký tự và dấu gạch dưới!
 */
$table_prefix = 'wp_';

/**
 * Dành cho developer: Chế độ debug.
 *
 * Thay đổi hằng số này thành true sẽ làm hiện lên các thông báo trong quá trình phát triển.
 * Chúng tôi khuyến cáo các developer sử dụng WP_DEBUG trong quá trình phát triển plugin và theme.
 *
 * Để có thông tin về các hằng số khác có thể sử dụng khi debug, hãy xem tại Codex.
 *
 * @link https://codex.wordpress.org/Debugging_in_WordPress
 */
define('WP_DEBUG', false);

/* Đó là tất cả thiết lập, ngưng sửa từ phần này trở xuống. Chúc bạn viết blog vui vẻ. */

/** Đường dẫn tuyệt đối đến thư mục cài đặt WordPress. */
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');

/** Thiết lập biến và include file. */
require_once(ABSPATH . 'wp-settings.php');
